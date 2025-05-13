<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StrukturASB;
use App\Models\AsbObjekTahun;
use App\Models\ObjekBelanja;

class ASBController extends Controller
{

    // Tampilkan daftar semua ASB
    public function index()
    {
        $asb = DB::table('struktur_asb')->orderBy('kode')->get();

        return view('asb.index', compact('asb'));
    }

    private function ringkasanPersen(int $asbId)
    {
        // 1. Ambil daftar tahun unik, urutkan
        $years = AsbObjekTahun::where('asb_id', $asbId)
            ->pluck('tahun')
            ->unique()
            ->sort()
            ->values()
            ->all();

        $Y = count($years) ?: 1;

        // 2. Ambil semua baris untuk ASB ini
        $rows = AsbObjekTahun::where('asb_id', $asbId)
            ->get(['objek_belanja_id', 'tahun', 'persentase']);

        // 3. Ambil data objek belanja
        $objeks = ObjekBelanja::whereIn('id', $rows->pluck('objek_belanja_id')->unique())
            ->get()
            ->keyBy('id');

        $result = collect();

        foreach ($objeks as $objekId => $obj) {
            // bangun array persentase per tahun; 0 jika tidak ada
            $data = [];
            foreach ($years as $th) {
                $hit = $rows->first(
                    fn($r) =>
                    $r->objek_belanja_id == $objekId && $r->tahun == $th
                );
                $data[] = $hit->persentase ?? 0;
            }

            // 4. hitung rata-rata & sd
            $avg = array_sum($data) / $Y;
            // standar deviasi sampel
            $sd  = $Y > 1
                ? sqrt(array_sum(array_map(fn($v) => ($v - $avg) ** 2, $data)) / ($Y - 1))
                : 0;

            $result->push([
                'objek'     => $obj->nama_objek,
                'avg_pct'   => round($avg, 2),
                'limit_pct' => round(min($avg + $sd, 100), 2),
            ]);
        }

        return $result;
    }


    public function riwayatStore(Request $r, $asbId)
    {
        $tahunMax = now()->year - 1;
        $r->validate([
            'tahun'               => "required|integer|between:2020,$tahunMax",
            'objek_belanja_id'    => 'required|array|min:1',
            'objek_belanja_id.*'  => 'exists:objek_belanja,id',
            'persentase'          => 'required|array|min:1',
            'persentase.*'        => 'numeric|min:0|max:100',
        ]);

        // ** cek total 100% **
        $total = collect($r->persentase)->map(fn($p) => (float)$p)->sum();
        $total = round($total, 2);
        if ($total !== 100.00) {
            $sisa = round(100 - $total, 2);
            $pesan = "Total persentase harus 100%. Saat ini {$total}%. ";
            if ($sisa > 0) {
                $pesan .= "Tambahkan {$sisa}% lagi.";
            } else {
                $pesan .= "Kurangi " . abs($sisa) . "%.";
            }
            return back()
                ->withErrors(['persentase' => $pesan])
                ->withInput();
        }

        // simpan semua baris
        foreach ($r->objek_belanja_id as $i => $objekId) {
            AsbObjekTahun::updateOrCreate(
                [
                    'asb_id'           => $asbId,
                    'tahun'            => $r->tahun,
                    'objek_belanja_id' => $objekId,
                ],
                ['persentase' => (float)$r->persentase[$i]]
            );
        }

        return back()->with('success', 'Riwayat belanja tersimpan.');
    }

    public function riwayatDestroyTahun($asbId, $tahun)
    {
        AsbObjekTahun::where('asb_id', $asbId)
            ->where('tahun', $tahun)
            ->delete();

        return back()->with('success', "Riwayat tahun $tahun berhasil dihapus.");
    }

    // Tampilkan form tambah ASB
    public function create()
    {
        $objekBelanja = ObjekBelanja::all();

        return view('asb.form', [
            'title' => 'Tambah Struktur ASB',
            'action' => route('asb.store'),
            'method' => 'POST',
            'asb' => null,
            'summary' => collect(),
            'objekBelanja' => $objekBelanja,
        ]);
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:struktur_asb,kode',
            'nama' => 'required',
            'fixed_cost' => 'nullable|numeric|min:0',
            'variable_cost' => 'nullable|numeric|min:0',
        ]);

        DB::table('struktur_asb')->insert([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'definisi' => $request->definisi,
            'fixed_cost' => $request->fixed_cost ?? 0,
            'variable_cost' => $request->variable_cost ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('asb.index')->with('success', 'ASB berhasil ditambahkan.');
    }

    // Tampilkan form edit ASB
    public function edit($id)
    {
        $asb = StrukturASB::with('costDrivers')->findOrFail($id);

        return view('asb.form', [
            'title'        => 'Edit Struktur ASB',
            'action'       => route('asb.update', $id),
            'method'       => 'PUT',
            'asb'          => $asb,
            'summary'      => $this->ringkasanPersen($id),      // ← kirim summary
            'objekBelanja' => ObjekBelanja::orderBy('nama_objek')->get(),
            'riwayat'      => $asb->objekTahunan()->with('objekBelanja')->get()->groupBy('tahun'),
        ]);
    }

    // Update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|unique:struktur_asb,kode,' . $id,
            'nama' => 'required',
            'fixed_cost' => 'nullable|numeric|min:0',
            'variable_cost' => 'nullable|numeric|min:0',
        ]);

        DB::table('struktur_asb')->where('id', $id)->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'definisi' => $request->definisi,
            'fixed_cost' => $request->fixed_cost ?? 0,
            'variable_cost' => $request->variable_cost ?? 0,
            'updated_at' => now(),
        ]);

        return redirect()->route('asb.index')->with('success', 'ASB berhasil diperbarui.');
    }

    // Hapus ASB
    public function destroy($id)
    {
        DB::table('struktur_asb')->where('id', $id)->delete();
        return redirect()->route('asb.index')->with('success', 'ASB berhasil dihapus.');
    }

    // For Select2
    public function getOptions(Request $request)
    {
        $search = $request->input('q');

        $data = DB::table('struktur_asb')
            ->select('kode', 'nama')
            ->when($search, function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            })
            ->get();

        return response()->json($data->map(fn($i) => [
            'id'   => $i->kode,
            'text' => "{$i->kode} - {$i->nama}",
        ]));
    }
}
