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

    private function ringkasanPersen(int $asbId)
    {
        $riwayat = RiwayatBelanja::where('asb_id', $asbId)->get();

        $grouped = $riwayat->groupBy('objek_belanja_id');
        $objeks = ObjekBelanja::whereIn('id', $grouped->keys())->get()->keyBy('id');

        $result = collect();

        foreach ($grouped as $objekId => $items) {
            $values = $items->pluck('persentase')->all();
            $n = count($values);
            $avg = array_sum($values) / $n;
            $sd = $n > 1 ? sqrt(array_sum(array_map(fn($v) => pow($v - $avg, 2), $values)) / ($n - 1)) : 0;

            $result->push([
                'objek'      => $objeks[$objekId]->nama_objek ?? 'Tidak ditemukan',
                'avg_pct'    => round($avg, 2),
                'limit_pct'  => round($avg + $sd, 2),
            ]);
        }

        return $result;
    }
}
