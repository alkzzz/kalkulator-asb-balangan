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
    public function index(Request $request)
    {
        $asbList = StrukturAsb::orderByRaw('CAST(kode AS UNSIGNED)')->get();
        $selectedAsb = $request->get('asb_id', 1);

        $riwayatTahunan = collect();

        if ($selectedAsb) {
            $riwayatTahunan = RiwayatBelanja::select(
                'tahun',
                DB::raw('SUM(persentase) as total_persen'),
                DB::raw('SUM(nilai_rupiah) as total_nilai')
            )
                ->where('skpd_id', auth()->user()->skpd_id)
                ->where('asb_id', $selectedAsb)
                ->groupBy('tahun')
                ->orderByDesc('tahun')
                ->get();
        }

        return view('riwayat_belanja.index', compact('asbList', 'selectedAsb', 'riwayatTahunan'));
    }

    public function create(Request $request)
    {
        $asbId = $request->get('asb_id');

        // Tahun yang sudah pernah diisi oleh user untuk ASB ini
        $tahunSudahAda = RiwayatBelanja::where('skpd_id', auth()->user()->skpd_id)
            ->where('asb_id', $asbId)
            ->pluck('tahun')
            ->unique()
            ->toArray();

        // Tahun yang tersedia = 5 tahun terakhir kecuali yang sudah terisi
        $tahunTersedia = collect(range(now()->year - 5, now()->year))
            ->reject(fn($tahun) => in_array($tahun, $tahunSudahAda))
            ->values();

        return view('riwayat_belanja.form', [
            'title'         => 'Tambah Riwayat Belanja',
            'action'        => route('riwayat-belanja.store'),
            'method'        => 'POST',
            'asbList'       => StrukturAsb::orderByRaw('CAST(kode AS UNSIGNED)')->get(),
            'objekBelanja'  => ObjekBelanja::orderBy('nama_objek')->get(),
            'riwayat'       => new RiwayatBelanja(['asb_id' => $asbId]),
            'tahunTersedia' => $tahunTersedia,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'objek_belanja_id' => 'required|array|min:1',
            'objek_belanja_id.*' => 'exists:objek_belanja,id',
            'persentase' => 'required|array|min:1',
            'persentase.*' => 'numeric|min:0|max:100',
            'nilai_rupiah' => 'required|array|min:1',
            'nilai_rupiah.*' => 'numeric|min:0',
        ]);

        foreach ($request->objek_belanja_id as $i => $objekId) {
            RiwayatBelanja::create([
                'skpd_id' => auth()->user()->skpd_id,
                'asb_id' => $request->asb_id,
                'tahun' => $request->tahun,
                'objek_belanja_id' => $objekId,
                'persentase' => $request->persentase[$i],
                'nilai_rupiah' => $request->nilai_rupiah[$i],
            ]);
        }

        return redirect()->route('riwayat-belanja.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show($asbId, $tahun)
    {
        $asb = StrukturAsb::findOrFail($asbId);

        $riwayatList = RiwayatBelanja::with('objekBelanja')
            ->where('asb_id', $asbId)
            ->where('skpd_id', auth()->user()->skpd_id)
            ->where('tahun', $tahun)
            ->get();

        return view('riwayat_belanja.show', compact('asb', 'tahun', 'riwayatList'));
    }

    public function edit(RiwayatBelanja $riwayat)
    {
        $this->authorizeAccess($riwayat);

        return view('riwayat_belanja.form', [
            'title'        => 'Edit Riwayat Belanja',
            'action'       => route('riwayat_belanja.update', $riwayat->id),
            'method'       => 'PUT',
            'asbList'      => StrukturAsb::orderByRaw('CAST(kode AS UNSIGNED)')->get(),
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
