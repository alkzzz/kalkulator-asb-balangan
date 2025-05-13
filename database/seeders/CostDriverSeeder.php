<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CostDriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ASB ID 1: Jumlah Subkegiatan
        DB::table('cost_drivers')->insert([
            'struktur_asb_id' => 1,
            'label' => 'Jumlah Sub Kegiatan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ASB ID 2: Jumlah Peserta dan Hari Pelaksanaan
        DB::table('cost_drivers')->insert([
            'struktur_asb_id' => 2,
            'label' => 'Jumlah Peserta',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cost_drivers')->insert([
            'struktur_asb_id' => 2,
            'label' => 'Hari Pelaksanaan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ASB ID 3: Jumlah Peserta, Hari, Materi
        DB::table('cost_drivers')->insert([
            'struktur_asb_id' => 3,
            'label' => 'Jumlah Peserta',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cost_drivers')->insert([
            'struktur_asb_id' => 3,
            'label' => 'Hari Pelaksanaan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cost_drivers')->insert([
            'struktur_asb_id' => 3,
            'label' => 'Jumlah Materi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
