<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkpdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $skpdList = [
            ['id' => 1, 'nama' => 'Sekretariat Daerah', 'singkatan' => 'Setda'],
            ['id' => 2, 'nama' => 'Sekretariat DPRD', 'singkatan' => 'Setwan'],
            ['id' => 3, 'nama' => 'Inspektorat', 'singkatan' => 'Inspektorat'],
            ['id' => 4, 'nama' => 'Dinas Pendidikan dan Kebudayaan', 'singkatan' => 'Disdikbud'],
            ['id' => 5, 'nama' => 'Dinas Kesehatan', 'singkatan' => 'Dinkes'],
            ['id' => 6, 'nama' => 'Dinas Pekerjaan Umum dan Penataan Ruang', 'singkatan' => 'DPUPR'],
            ['id' => 7, 'nama' => 'Dinas Perumahan Rakyat, Kawasan Permukiman dan Lingkungan Hidup', 'singkatan' => 'DPKPLH'],
            ['id' => 8, 'nama' => 'Dinas Sosial', 'singkatan' => 'Dinsos'],
            ['id' => 9, 'nama' => 'Dinas Tenaga Kerja, Transmigrasi, Koperasi dan UKM', 'singkatan' => 'DisnakertranskopUKM'],
            ['id' => 10, 'nama' => 'Dinas Perindustrian dan Perdagangan', 'singkatan' => 'Disperindag'],
            ['id' => 11, 'nama' => 'Dinas Pertanian', 'singkatan' => 'Distan'],
            ['id' => 12, 'nama' => 'Dinas Ketahanan Pangan, Perikanan dan Peternakan', 'singkatan' => 'DKPPP'],
            ['id' => 13, 'nama' => 'Dinas Kependudukan dan Pencatatan Sipil', 'singkatan' => 'Disdukcapil'],
            ['id' => 14, 'nama' => 'Dinas Komunikasi, Informatika, Statistik dan Persandian', 'singkatan' => 'Diskominfo'],
            ['id' => 15, 'nama' => 'Dinas Perhubungan', 'singkatan' => 'Dishub'],
            ['id' => 16, 'nama' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu', 'singkatan' => 'DPMPTSP'],
            ['id' => 17, 'nama' => 'Dinas Pemuda, Olahraga dan Pariwisata', 'singkatan' => 'Disporapar'],
            ['id' => 18, 'nama' => 'Dinas Perpustakaan dan Kearsipan', 'singkatan' => 'Dispusip'],
            ['id' => 19, 'nama' => 'Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan', 'singkatan' => 'Bappeda Litbang'],
            ['id' => 20, 'nama' => 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia', 'singkatan' => 'BKPSDM'],
            ['id' => 21, 'nama' => 'Badan Pengelolaan Keuangan, Pendapatan dan Aset Daerah', 'singkatan' => 'BPKPAD'],
            ['id' => 22, 'nama' => 'Badan Penanggulangan Bencana Daerah', 'singkatan' => 'BPBD'],
            ['id' => 23, 'nama' => 'Satuan Polisi Pamong Praja dan Pemadam Kebakaran', 'singkatan' => 'Satpol PP & Damkar'],
            ['id' => 24, 'nama' => 'Badan Kesatuan Bangsa dan Politik', 'singkatan' => 'Bakesbangpol'],
            ['id' => 25, 'nama' => 'Kecamatan Paringin', 'singkatan' => 'Kec. Paringin'],
            ['id' => 26, 'nama' => 'Kecamatan Paringin Selatan', 'singkatan' => 'Kec. Paringin Selatan'],
            ['id' => 27, 'nama' => 'Kecamatan Lampihong', 'singkatan' => 'Kec. Lampihong'],
            ['id' => 28, 'nama' => 'Kecamatan Batumandi', 'singkatan' => 'Kec. Batumandi'],
            ['id' => 29, 'nama' => 'Kecamatan Juai', 'singkatan' => 'Kec. Juai'],
            ['id' => 30, 'nama' => 'Kecamatan Awayan', 'singkatan' => 'Kec. Awayan'],
            ['id' => 31, 'nama' => 'Kecamatan Tebing Tinggi', 'singkatan' => 'Kec. Tebing Tinggi'],
            ['id' => 32, 'nama' => 'Kecamatan Halong', 'singkatan' => 'Kec. Halong'],
        ];

        DB::table('skpd')->insert($skpdList);
    }
}
