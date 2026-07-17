<?php

namespace App\Controllers;

class LandingController extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'SIRS - Sistem Informasi Rumah Sakit',
            'services' => [
                ['icon' => 'heart-pulse', 'name' => 'Rawat Jalan', 'desc' => 'Pelayanan konsultasi dan perawatan tanpa rawat inap oleh dokter spesialis.'],
                ['icon' => 'bed', 'name' => 'Rawat Inap', 'desc' => 'Fasilitas kamar nyaman dengan perawatan 24 jam oleh tenaga medis profesional.'],
                ['icon' => 'ambulance', 'name' => 'IGD 24 Jam', 'desc' => 'Instalasi Gawat Darurat siaga penuh untuk penanganan emergensi kapan saja.'],
                ['icon' => 'flask-conical', 'name' => 'Laboratorium', 'desc' => 'Pemeriksaan lab lengkap dengan hasil akurat dan cepat.'],
                ['icon' => 'scan', 'name' => 'Radiologi', 'desc' => 'Layanan USG, Rontgen, CT Scan dengan teknologi terkini.'],
                ['icon' => 'baby', 'name' => 'KIA & KB', 'desc' => 'Pelayanan kesehatan ibu dan anak serta program keluarga berencana.'],
            ],
            'doctors' => [
                ['name' => 'dr. Andi Wijaya, Sp.PD', 'specialty' => 'Penyakit Dalam', 'photo' => 'doctor-1.jpg'],
                ['name' => 'dr. Siti Rahma, Sp.OG', 'specialty' => 'Kebidanan & Kandungan', 'photo' => 'doctor-2.jpg'],
                ['name' => 'dr. Budi Santoso, Sp.JP', 'specialty' => 'Jantung & Pembuluh Darah', 'photo' => 'doctor-3.jpg'],
            ],
            'stats' => [
                ['label' => 'Pasien Dilayani', 'value' => 15000, 'suffix' => '+'],
                ['label' => 'Dokter Spesialis', 'value' => 45, 'suffix' => '+'],
                ['label' => 'Tahun Pengalaman', 'value' => 25, 'suffix' => ''],
                ['label' => 'Ruang Perawatan', 'value' => 120, 'suffix' => '+'],
            ],
            'contact' => [
                'address' => 'Jl. Kesehatan No. 123, Kota Sehat, Indonesia 12345',
                'phone' => '(021) 555-0123',
                'email' => 'info@sirs-hospital.co.id',
                'hours' => 'Senin - Sabtu: 08.00 - 20.00 WIB | IGD: 24 Jam',
            ],
        ];

        return view('layouts/landing', $data);
    }
}
