<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StrukturAsbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            "Penyusunan Dokumen Perencanaan Perangkat Daerah",
            "Penyusunan Dokumen RKA-SKPD",
            "Penyusunan Dokumen Perubahan RKA-SKPD",
            "Penyusunan DPA-SKPD",
            "Penyusunan Perubahan DPA-SKPD",
            "Evaluasi Kinerja Perangkat Daerah",
            "Penyusunan Laporan Capaian Kinerja dan IkhtisarRealisasi Kinerja SKPD",
            "Pelaksanaan Penatausahaan dan Pengujian/Verifikasi KeuanganSKPD",
            "Penyusunan Pelaporan dan Analisis Prognosis Realisasi Anggaran",
            "Penyusunan Laporan Keuangan Bulanan/Triwulanan/Semesteran SKPD",
            "Penyusunan Laporan Keuangan Akhir Tahun SKPD",
            "Pelatihan Pegawai di Dalam Kantor-Dengan Narasumber Dalam Daerah-Tanpa Praktik Lapangan-Setengah Hari",
            "Pelatihan Pegawai di Hotel-Dengan Narasumber Dalam Daerah-Tanpa Praktik Lapangan-Setengah Hari",
            "Pelatihan Pegawai di Dalam Kantor-Dengan Narasumber Dalam Daerah-Tanpa Praktik Lapangan-Penuh Hari",
            "Pelatihan Pegawai di Hotel-Dengan Narasumber Dalam Daerah-Tanpa Praktik Lapangan-Penuh Hari",
            "Pelatihan Pegawai Di Hotel-Dengan Narasumber Dalam Daerah-Tanpa Praktik Lapangan- Menginap di Dalam Kota",
            "Pelatihan Pegawai di Hotel-Dengan Melibatkan Narasumber Luar Daerah-Tanpa Praktik Lapangan-Menginap Di Dalam Kota",
            "Pelatihan Pegawai di Hotel-Dengan Melibatkan Narasumber Luar Daerah-Tanpa Praktik Lapangan-Menginap Di Luar Kota",
            "Sosialisasi Tatap Muka Pegawai di Dalam Kantor - Fullday - Narasumber Internal",
            "Sosialisasi Tatap Muka Pegawai di Dalam Kantor - Halfday - Narasumber Internal",
            "Sosialisasi Tatap Muka Pegawai - Fullboard - Narasumber Internal",
            "Sosialisasi Tatap Muka Pegawai - Fullboard - Narasumber Eksternal",
            "Sosialisasi Tatap Muka - Pegawai - Mengundang Kabupaten/Kota Di Hotel - Halfday -  Narasumber Internal",
            "Sosialisasi Tatap Muka - Pegawai - Mendatangi Kabupaten/Kota Di Hotel - Halfday -  Narasumber Internal",
            "Sosialisasi Tatap Muka Pegawai - Mendatangi Kabupaten/Kota - Narasumber Internal",
            "Sosialisasi Tatap Muka Masyarakat di Dalam Kantor - Halfday - Narasumber Internal",
            "Sosialisasi Tatap Muka Masyarakat di Hotel - Fullday - Narasumber Internal",
            "Sosialisasi Tatap Muka Masyarakat di Hotel - Halfday - Narasumber Internal",
            "Sosialisasi Tatap Muka Masyarakat - Fullboard - Narasumber Internal",
            "Penyelenggaraan Forum atau Rapat Koordinasi di Dalam Kantor",
            "Penyelenggaraan Forum atau Rapat Koordinasi di Dalam Kantor Dengan Narasumber",
            "Penyelenggaraan Forum atau Rapat Koordinasi di Hotel -  Half Day",
            "Penyelenggaraan Forum atau Rapat Koordinasi di Hotel - Full Day",
            "Penyelenggaraan Forum atau Rapat Koordinasi di Hotel - Full Board",
            "Pendidikan dan Pelatihan Pegawai di Dalam Kantor-Dengan Narasumber Internal-Fullday",
            "Pendidikan dan Pelatihan Pegawai di Dalam Kantor-Dengan Melibatkan Narasumber Eksternal-Fullday",
            "Pendidikan dan Pelatihan Pegawai di Hotel-Dengan Melibatkan Narasumber Eksternal-Fullboard",
            "Pendidikan dan Pelatihan Pegawai di Luar Daerah-Dengan Melibatkan Narasumber Eksternal-Fullboard"
        ];

        foreach ($data as $index => $nama) {
            DB::table('struktur_asb')->insert([
                'id' => $index + 1,
                'kode' => $index + 1,
                'nama' => $nama,
            ]);
        }
    }
}
