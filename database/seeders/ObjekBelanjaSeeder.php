<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObjekBelanjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $namaList = [
            'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Tulis Kantor',
            'Belanja Alat/Bahan untuk Kegiatan Kantor-Kertas dan Cover',
            'Belanja Alat/Bahan untuk Kegiatan Kantor-Suvenir/Cendera Mata',
            'Belanja Alat/Bahan untuk Kegiatan Kantor-Bahan Komputer',
            'Belanja Alat/Bahan untuk Kegiatan Kantor-Persediaan Dokumen/Administrasi Tender',
            'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat/Bahan untuk Kegiatan Kantor Lainnya',
            'Belanja Makanan dan Minuman Rapat',
            'Belanja Jasa Iklan/Reklame, Film, dan Pemotretan',
            'Belanja Kursus Singkat/Pelatihan',
            'Belanja Sewa Bangunan Gedung Tempat Pertemuan',
            'Belanja Perjalanan Dinas Biasa',
            'Belanja Perjalanan Dinas Dalam Kota',
            'Belanja Perjalanan Dinas Paket Meeting Dalam Kota',
            'Belanja Perjalanan Dinas Paket Meeting Luar Kota',
            'Belanja Obat-Obatan-Obat',
            'Honorarium Penyelenggaraan Kegiatan Pendidikan dan Pelatihan',
            'Honorarium Narasumber atau Pembahas, Moderator, Pembawa Acara, dan Panitia',
            'Belanja Honorarium Penanggungjawaban Pengelola Keuangan',
            'Belanja Honorarium Pengadaan Barang/Jasa',
            // Item baru dari data gambar ditambahkan di bawah:
            'Belanja Alat/Bahan untuk Kegiatan Kantor-Bahan Cetak',
            'Belanja Alat/Bahan untuk Kegiatan Kantor-Benda Pos',
            'Belanja Jasa Tenaga Administrasi',
            'Belanja Jasa Tenaga Ahli',
            'Belanja Jasa Konversi Aplikasi/Sistem Informasi',
            'Belanja Pemeliharaan Aset Tidak Berwujud-Software',
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('objek_belanja')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $rows = array_map(fn($nama) => ['nama_objek' => $nama], $namaList);

        DB::table('objek_belanja')->insert($rows);
    }
}
