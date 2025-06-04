<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StrukturASB;
use App\Models\RiwayatBelanja;
use App\Models\ObjekBelanja;

class KalkulatorASBController extends Controller
{
    public function index()
    {
        $asbList = StrukturASB::with('costDrivers')->orderByRaw('CAST(kode AS UNSIGNED)')->get();
        return view('asb.kalkulator', compact('asbList'));
    }

    public function hitung(Request $request)
    {
        $asb = StrukturASB::with('costDrivers')->findOrFail($request->asb_id);

        $fixedCost     = $asb->belanja_tetap ?? 0;
        $variableTotal = 0;
        $hasilDrivers  = [];

        foreach ($asb->costDrivers as $driver) {
            $m = 1;
            for ($i = 1; $i <= $driver->jumlah_input; $i++) {
                $m *= (float) $request->input("input_{$driver->id}_{$i}", 1);
            }
            $sub = $driver->koefisien * $m;
            $variableTotal += $sub;
            $hasilDrivers[] = ['label' => $driver->label, 'subtotal' => $sub];
        }

        $total    = $fixedCost + $variableTotal;
        $summary  = $this->ringkasanPersen($asb->id);

        $breakdown = $summary->map(fn($r) => [
            'objek'        => $r['objek'],
            'avg_pct'      => $r['avg_pct'],
            'limit_pct'    => $r['limit_pct'],
            'avg_amount'   => round($total * $r['avg_pct']   / 100),
            'limit_amount' => round($total * $r['limit_pct'] / 100),
        ]);

        return view('asb.kalkulator_hasil', compact(
            'asb',
            'fixedCost',
            'variableTotal',
            'total',
            'hasilDrivers',
            'breakdown'
        ));
    }

    public function breakdown($asbId)
    {
        return response()->json(
            $this->ringkasanPersen($asbId)->map(fn($r) => [
                'objek'     => $r['objek'],
                'avg_pct'   => $r['avg_pct'],
                'limit_pct' => $r['limit_pct'],
            ])
        );
    }

    private function ringkasanPersen(int $asbId): \Illuminate\Support\Collection
    {
        $allRiwayatForAsb = RiwayatBelanja::where('asb_id', $asbId)->get();

        if ($allRiwayatForAsb->isEmpty()) {
            return collect();
        }

        $riwayatPerInstance = $allRiwayatForAsb->groupBy(function ($item) {
            return $item->skpd_id . '-' . $item->tahun;
        });

        $percentagesByObjek = [];

        foreach ($riwayatPerInstance as $instanceItems) {
            $totalNilaiRupiahInInstance = $instanceItems->sum('nilai_rupiah');

            if ($totalNilaiRupiahInInstance == 0) {
                continue;
            }

            $itemsGroupedByObjekInInstance = $instanceItems->groupBy('objek_belanja_id');

            foreach ($itemsGroupedByObjekInInstance as $objekId => $itemDetails) {
                $totalObjekInInstance = $itemDetails->sum('nilai_rupiah');
                $percentForThisInstance = ($totalObjekInInstance / $totalNilaiRupiahInInstance) * 100;
                $percentagesByObjek[$objekId][] = $percentForThisInstance;
            }
        }

        $objekBelanjaIds = array_keys($percentagesByObjek);
        $objeks = ObjekBelanja::whereIn('id', $objekBelanjaIds)->get()->keyBy('id');

        $result = collect();

        foreach ($percentagesByObjek as $objekId => $values) {
            $n = count($values);

            if ($n == 0) continue;

            $avg = array_sum($values) / $n;

            $sd = 0;
            if ($n > 1) {
                $sumOfSquares = 0;
                foreach ($values as $value) {
                    $sumOfSquares += pow($value - $avg, 2);
                }
                $sd = sqrt($sumOfSquares / ($n - 1));
            }

            $namaObjek = 'Tidak ditemukan';
            if (isset($objeks[$objekId])) {
                $namaObjek = $objeks[$objekId]->nama_objek;
            }

            $result->push([
                'objek'     => $namaObjek,
                'avg_pct'   => round($avg, 2),
                'limit_pct' => round($avg + $sd, 2),
            ]);
        }

        return $result;
    }
}
