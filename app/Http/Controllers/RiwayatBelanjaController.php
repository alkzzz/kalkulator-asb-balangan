<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiwayatBelanja;
use App\Models\Skpd;
use App\Models\StrukturAsb;
use App\Models\ObjekBelanja;
use Illuminate\Support\Facades\DB;

class RiwayatBelanjaController extends Controller
{
    public function index()
    {
        $data = RiwayatBelanja::select(
            'asb_id',
            'tahun',
            DB::raw('SUM(persentase) as total_persen'),
            DB::raw('SUM(nilai_rupiah) as total_nilai')
        )
            ->with('asb')
            ->where('skpd_id', auth()->user()->skpd_id)
            ->groupBy('asb_id', 'tahun')
            ->orderByDesc('tahun')
            ->paginate(15);

        return view('riwayat_belanja.index', compact('data'));
    }

    public function create()
    {
        return view('riwayat_belanja.form', [
            'title'        => 'Tambah Riwayat Belanja',
            'action'       => route('riwayat-belanja.store'),
            'method'       => 'POST',
            'asbList'      => StrukturAsb::orderBy('kode')->get(),
            'objekBelanja' => ObjekBelanja::orderBy('nama_objek')->get(),
            'riwayat'      => new RiwayatBelanja()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'asb_id'           => 'required|exists:struktur_asb,id',
            'objek_belanja_id' => 'required|exists:objek_belanja,id',
            'tahun'            => 'required|integer|between:2020,' . (now()->year - 1),
            'persentase'       => 'required|numeric|min:0|max:100',
            'nilai_rupiah'     => 'required|numeric|min:0',
            'keterangan'       => 'nullable|string|max:255',
        ]);

        RiwayatBelanja::create([
            'skpd_id'          => auth()->user()->skpd_id,
            'asb_id'           => $request->asb_id,
            'objek_belanja_id' => $request->objek_belanja_id,
            'tahun'            => $request->tahun,
            'persentase'       => $request->persentase,
            'nilai_rupiah'     => $request->nilai_rupiah,
            'keterangan'       => $request->keterangan,
        ]);

        return redirect()->route('riwayat-belanja.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show($asbId, $tahun)
    {
        $riwayat = RiwayatBelanja::with('objekBelanja')
            ->where('skpd_id', auth()->user()->skpd_id)
            ->where('asb_id', $asbId)
            ->where('tahun', $tahun)
            ->orderBy('objek_belanja_id')
            ->get();

        $asb = StrukturAsb::findOrFail($asbId);

        return view('riwayat_belanja.show', compact('riwayat', 'asb', 'tahun'));
    }


    public function edit(RiwayatBelanja $riwayat)
    {
        $this->authorizeAccess($riwayat);

        return view('riwayat_belanja.form', [
            'title'        => 'Edit Riwayat Belanja',
            'action'       => route('riwayat_belanja.update', $riwayat->id),
            'method'       => 'PUT',
            'asbList'      => StrukturAsb::orderBy('kode')->get(),
            'objekBelanja' => ObjekBelanja::orderBy('nama_objek')->get(),
            'riwayat'      => $riwayat
        ]);
    }

    public function update(Request $request, RiwayatBelanja $riwayat)
    {
        $this->authorizeAccess($riwayat);

        $request->validate([
            'asb_id'           => 'required|exists:struktur_asb,id',
            'objek_belanja_id' => 'required|exists:objek_belanja,id',
            'tahun'            => 'required|integer|between:2020,' . (now()->year - 1),
            'persentase'       => 'required|numeric|min:0|max:100',
            'nilai_rupiah'     => 'required|numeric|min:0',
            'keterangan'       => 'nullable|string|max:255',
        ]);

        $riwayat->update([
            'asb_id'           => $request->asb_id,
            'objek_belanja_id' => $request->objek_belanja_id,
            'tahun'            => $request->tahun,
            'persentase'       => $request->persentase,
            'nilai_rupiah'     => $request->nilai_rupiah,
            'keterangan'       => $request->keterangan,
        ]);

        return redirect()->route('riwayat-belanja.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(RiwayatBelanja $riwayat)
    {
        $this->authorizeAccess($riwayat);
        $riwayat->delete();

        return redirect()->route('riwayat-belanja.index')->with('success', 'Data berhasil dihapus.');
    }

    private function authorizeAccess(RiwayatBelanja $riwayat)
    {
        if ($riwayat->skpd_id !== auth()->user()->skpd_id) {
            abort(403, 'Akses ditolak.');
        }
    }
}
