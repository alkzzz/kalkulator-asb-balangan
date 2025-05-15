<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skpd;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SkpdUserSeeder extends Seeder
{
    public function run()
    {
        $skpdNames = [
            ['nama' => 'Badan Pengelolaan Keuangan dan Aset Daerah', 'singkatan' => 'BPKPAD', 'role' => 'admin'],
            ['nama' => 'Dinas Pendidikan dan Kebudayaan', 'singkatan' => 'DISDIKBUD', 'role' => 'user'],
            ['nama' => 'Dinas Kesehatan', 'singkatan' => 'DINKES', 'role' => 'user'],
            ['nama' => 'Dinas Pekerjaan Umum dan Penataan Ruang', 'singkatan' => 'DISPUPR', 'role' => 'user'],
            ['nama' => 'Badan Penanggulangan Bencana Daerah', 'singkatan' => 'BPBD', 'role' => 'user'],
            ['nama' => 'Satuan Polisi Pamong Praja', 'singkatan' => 'SATPOL PP', 'role' => 'user'],
            ['nama' => 'Badan Kesatuan Bangsa dan Politik', 'singkatan' => 'KESBANGPOL', 'role' => 'user'],
            ['nama' => 'Dinas Sosial, Pemberdayaan Perempuan dan Perlindungan Anak serta Pemberdayaan Masyarakat dan Desa', 'singkatan' => 'DINSOS P3A PMD', 'role' => 'user'],
            ['nama' => 'Dinas Ketahanan Pangan, Pertanian dan Perikanan', 'singkatan' => 'DKP3', 'role' => 'user'],
            ['nama' => 'Dinas Perlindungan Lingkungan Hidup', 'singkatan' => 'DPLH', 'role' => 'user'],
            ['nama' => 'Dinas Kependudukan dan Pencatatan Sipil', 'singkatan' => 'DISDUKCAPIL', 'role' => 'user'],
            ['nama' => 'Dinas Perhubungan', 'singkatan' => 'DISHUB', 'role' => 'user'],
            ['nama' => 'Dinas Komunikasi, Informatika, Statistik dan Persandian', 'singkatan' => 'DISKOMINFO', 'role' => 'user'],
            ['nama' => 'Dinas Koperasi, UKM, dan Tenaga Kerja', 'singkatan' => 'DISKOP UKM NAKER', 'role' => 'user'],
            ['nama' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu, Transmigrasi dan Tenaga Kerja', 'singkatan' => 'DPMPTSP', 'role' => 'user'],
            ['nama' => 'Dinas Kepemudaan, Olahraga dan Pariwisata', 'singkatan' => 'DISPORAPAR', 'role' => 'user'],
            ['nama' => 'Dinas Perpustakaan dan Kearsipan', 'singkatan' => 'DISPERSIP', 'role' => 'user'],
            ['nama' => 'Dinas Perindustrian dan Perdagangan', 'singkatan' => 'DISPERINDAG', 'role' => 'user'],
            ['nama' => 'Sekretariat Daerah', 'singkatan' => 'SETDA', 'role' => 'user'],
            ['nama' => 'Sekretariat Dewan Perwakilan Rakyat Daerah', 'singkatan' => 'SETWAN', 'role' => 'user'],
            ['nama' => 'Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan', 'singkatan' => 'BAPPEDA', 'role' => 'user'],
            ['nama' => 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia', 'singkatan' => 'BKPSDM', 'role' => 'user'],
            ['nama' => 'Inspektorat', 'singkatan' => 'INSPEKTORAT', 'role' => 'user'],
            ['nama' => 'Kecamatan Lampihong', 'singkatan' => 'Kecamatan Lampihong', 'role' => 'user'],
            ['nama' => 'Kecamatan Awayan', 'singkatan' => 'Kecamatan Awayan', 'role' => 'user'],
            ['nama' => 'Kecamatan Paringin', 'singkatan' => 'Kecamatan Paringin', 'role' => 'user'],
            ['nama' => 'Kecamatan Paringin Selatan', 'singkatan' => 'Kecamatan Paringin Selatan', 'role' => 'user'],
            ['nama' => 'Kecamatan Juai', 'singkatan' => 'Kecamatan Juai', 'role' => 'user'],
            ['nama' => 'Kecamatan Halong', 'singkatan' => 'Kecamatan Halong', 'role' => 'user'],
            ['nama' => 'Kecamatan Tebing Tinggi', 'singkatan' => 'Kecamatan Tebing Tinggi', 'role' => 'user'],
            ['nama' => 'Kecamatan Batu Mandi', 'singkatan' => 'Kecamatan Batu Mandi', 'role' => 'user']
        ];

        foreach ($skpdNames as $skpdData) {
            // Create SKPD
            $skpd = Skpd::create([
                'nama' => $skpdData['nama'],
                'singkatan' => $skpdData['singkatan'],
            ]);

            // Create a User for each SKPD
            User::create([
                'name' => $skpdData['nama'],
                'username' => strtolower(str_replace(' ', '-', $skpdData['singkatan'])),
                'email' => strtolower(str_replace(' ', '-', $skpdData['singkatan'])) . '@balangan.go.id',
                'password' => Hash::make('12345678'), // Default password
                'role' => $skpdData['role'], // Role (admin or user)
                'skpd_id' => $skpd->id,
            ]);
        }
    }
}
