<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StrukturASB;
use App\Models\AsbObjekTahun;
use App\Models\ObjekBelanja;

class KalkulatorASBController extends Controller
{
    public function index()
    {
        $asbList = StrukturASB::with('costDrivers')->orderBy('kode')->get();
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
        $years = AsbObjekTahun::where('asb_id', $asbId)
            ->pluck('tahun')->unique()->sort()->values();
        $Y = max($years->count(), 1);

        $rows = AsbObjekTahun::where('asb_id', $asbId)->get();
        $objeks = ObjekBelanja::whereIn('id', $rows->pluck('objek_belanja_id')->unique())
            ->get()->keyBy('id');

        $out = collect();
        foreach ($objeks as $id => $obj) {
            $data = $years->map(
                fn($th) =>
                $rows->first(fn($r) => $r->objek_belanja_id == $id && $r->tahun == $th)->persentase ?? 0
            )->all();
            $avg = array_sum($data) / $Y;
            $sd  = $Y > 1
                ? sqrt(array_sum(array_map(fn($v) => ($v - $avg) ** 2, $data)) / ($Y - 1))
                : 0;
            $out->push([
                'objek'     => $obj->nama_objek,
                'avg_pct'   => round($avg, 2),
                'limit_pct' => round(min($avg + $sd, 100), 2),
            ]);
        }
        return $out;
    }
}
