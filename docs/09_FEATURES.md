# 09 - FEATURES DOCUMENTATION

## Modul Sistem

### 1. Landing Page

**Deskripsi:** Halaman publik yang menampilkan informasi rumah sakit.

**Fitur:**
- Hero section dengan CTA
- Daftar layanan rumah sakit
- Profil dokter (carousel/grid)
- Statistik (jumlah dokter, pasien, layanan)
- Form kontak
- Peta lokasi (Google Maps embed)
- Responsive & mobile friendly
- Dark mode toggle

**Access:** Public

---

### 2. Authentication

**Deskripsi:** Sistem autentikasi untuk mengakses sistem.

**Fitur:**
- Login (email + password)
- Registrasi akun baru
- Verifikasi email (token-based)
- Forgot password (email link)
- Reset password
- Session management
- Remember me
- Rate limiting (5 attempt / 15 menit)
- Auto logout (30 menit idle)
- Multi-factor authentication (opsional)

**Access:** Public (login/register), Protected (logout/profile)

---

### 3. Dashboard

**Deskripsi:** Halaman utama setelah login, menampilkan statistik dan ringkasan.

**Dashboard per Role:**

| Role | Komponen |
|------|----------|
| Admin | Total user, kunjungan hari ini, revenue, notifikasi |
| Dokter | Jadwal hari ini, pasien waiting, resep pending |
| Perawat | Tugas hari ini, pasien di rawat, asesmen pending |
| Kasir | Tagihan pending, pembayaran hari ini, revenue |
| Farmasi | Resep pending, stok rendah, obat kadaluarsa |
| Lab | Order pending, hasil belum input |
| Manajemen | KPI charts, revenue trend, kunjungan trend |

**Fitur Umum:**
- Widget konfigurable
- Notifikasi real-time
- Quick actions
- Chart interaktif (Chart.js / ApexCharts)
- Dark mode support

---

### 4. User Management

**Deskripsi:** Mengelola akun pengguna sistem.

**Fitur:**
- CRUD user
- Upload foto profil
- Assign role (multi-role)
- Aktif/nonaktifkan user
- Filter & search
- Export data (CSV/Excel)
- Riwayat aktivitas

**Data:**
- Nama, email, phone, avatar
- Status aktif/nonaktif
- Role assignment
- Last login info

---

### 5. Role & Permission (RBAC)

**Deskripsi:** Sistem kontrol akses berbasis role.

**Fitur:**
- CRUD role
- Granular permission (CRUD per modul)
- Assign permission ke role
- Role hierarchy (opsional)
- Default system roles
- Custom role

**Default Roles:**
- Admin Sistem
- Dokter
- Perawat
- Kasir
- Farmasi
- Laboran
- Radiologi
- Gudang
- Pasien
- Manajemen

---

### 6. Audit Log

**Deskripsi:** Mencatat semua aktivitas dalam sistem.

**Fitur:**
- Log create, update, delete
- Log login/logout
- Log perubahan data sensitif
- Filter & search
- Export log
- Retensi configurable
- Detail perubahan (old/new values)

**Data yang Di-log:**
- User yang melakukan aksi
- Jenis aksi
- Modul dan record
- Nilai lama dan baru
- IP address
- User agent
- Timestamp

---

### 7. Notifikasi

**Deskripsi:** Sistem notifikasi in-app.

**Fitur:**
- Notifikasi in-app
- Email notification
- Notifikasi untuk event tertentu:
  - Resep baru (farmasi)
  - Order lab baru (lab)
  - Tagihan baru (kasir)
  - Jadwal baru (dokter)
  - Stok minimum (gudang)
- Mark as read / read all
- Unread count badge
- Notifikasi preference

---

### 8. Pasien

**Deskripsi:** Mengelola data pasien rumah sakit.

**Fitur:**
- Registrasi pasien baru
- Generate MRN otomatis (format: RM-YYYYMMDD-XXXX)
- Data demografis lengkap
- Data identitas (NIK, KTP)
- Riwayat kunjungan
- Riwayat alergi
- Riwayat penyakit kronis
- Data asuransi
- Upload dokumen pendukung
- Foto pasien
- Search & filter (nama, MRN, NIK)
- Export data

---

### 9. Dokter

**Deskripsi:** Mengelola data dokter.

**Fitur:**
- CRUD data dokter
- Spesialisasi
- SIP (Surat Izin Praktik)
- Mapping ke user account
- Jadwal praktik per poli
- Kuota per jadwal
- Bio dan foto
- Consultation fee

---

### 10. Perawat

**Deskripsi:** Mengelola data perawat.

**Fitur:**
- CRUD data perawat
- Penugasan ke poli/ruangan
- Shift management
- Mapping ke user account

---

### 11. Poli

**Deskripsi:** Mengelola poliklinik.

**Fitur:**
- CRUD poli
- Mapping dokter ke poli
- Kuota harian
- Lokasi poli
- Status aktif/nonaktif

---

### 12. Ruangan

**Deskripsi:** Mengelola ruangan rumah sakit.

**Fitur:**
- CRUD ruangan
- Tipe ruangan:
  - VIP
  - Kelas 1
  - Kelas 2
  - Kelas 3
  - ICU
  - ICCU
  - HCU
  - Isolasi
  - Operasi
- Status ketersediaan
- Kapasitas dan occupancy
- Penugasan bed

---

### 13. Jadwal Dokter

**Deskripsi:** Mengelola jadwal praktik dokter.

**Fitur:**
- Buat jadwal (dokter, poli, hari, jam, kuota)
- Edit/hapus jadwal
- Konflik jadwal detection
- Jadwal berulang (mingguan)
- Libur dan cuti
- Override jadwal (tanggal tertentu)
- Available slot check

---

### 14. Antrian

**Deskripsi:** Sistem antrian untuk pasien.

**Fitur:**
- Generate nomor antrian otomatis
- Status antrian real-time:
  - Waiting
  - Called
  - In Progress
  - Completed
  - Skipped
- Display antrian (TV/monitor)
- Prioritas (emergency, VIP)
- Skip dan recall
- Sound notification
- Estimated wait time

---

### 15. Pendaftaran

**Deskripsi:** Pendaftaran pasien untuk layanan.

**Tipe Pendaftaran:**

| Tipe | Deskripsi |
|------|-----------|
| Rawat Jalan | Kunjungan poliklinik |
| Rawat Inap | Admisi inap |
| IGD | Emergency |

**Fitur:**
- Cari pasien (MRN, nama, NIK)
- Pilih poli dan dokter
- Pilih jadwal
- Validasi data
- Generate nomor antrian
- Cetak tiket antrian
- Cetak label pasien

---

### 16. Rawat Jalan

**Deskripsi:** Mengelola kunjungan rawat jalan.

**Fitur:**
- Kunjungan rawat jalan
- Status kunjungan (waiting, in_progress, completed, cancelled)
- Riwayat kunjungan
- Link ke rekam medis

---

### 17. Rawat Inap

**Deskripsi:** Mengelola rawat inap pasien.

**Fitur:**
- Admisi rawat inap
- Penugasan ruangan dan bed
- Monitoring harian
- Transfer ruangan
- Discharge planning
- Resume pulang

---

### 18. IGD

**Deskripsi:** Mengelola pasien IGD.

**Fitur:**
- Registrasi IGD
- Triase (Emergency, Urgent, Non-Urgent)
- Stabilisasi
- Status pasien IGD
- Konversi ke rawat inap
- Konversi ke rawat jalan

---

### 19. Rekam Medis

**Deskripsi:** Rekam medis digital pasien.

**Fitur:**
- SOAP format:
  - Subjective (keluhan pasien)
  - Objective (pemeriksaan fisik)
  - Assessment (diagnosa)
  - Planning (rencana tindakan)
- Template rekam medis
- Riwayat rekam medis
- Attach file/lampiran
- Print rekam medis
- Digital signature (opsional)

---

### 20. Diagnosa

**Deskripsi:** Input diagnosa berbasis ICD-10.

**Fitur:**
- Search kode ICD-10
- Diagnosa utama
- Diagnosa sekunder (multiple)
- Autocomplete ICD-10
- Keterangan diagnosa

---

### 21. Tindakan

**Deskripsi:** Mencatat tindakan medis.

**Fitur:**
- Input tindakan medis
- Kode tindakan
- Biaya tindakan
- Keterangan
- Riwayat tindakan

---

### 22. Resep

**Deskripsi:** Resep digital dari dokter ke farmasi.

**Fitur:**
- Buat resep digital
- Detail obat (nama, dosis, frekuensi, durasi)
- Instruksi penggunaan
- Cetak resep
- Riwayat resep
- Status: pending, dispensed, partial, cancelled

---

### 23. Farmasi

**Deskripsi:** Mengelola obat dan farmasi.

**Fitur:**
- Kelola master data obat
- Kategori obat
- Penerimaan obat dari supplier
- Distribusi obat ke pasien
- Monitoring expired date
- Minimum stok alert
- Retur obat
- Stok opname
- Batch tracking

---

### 24. Laboratorium

**Deskripsi:** Mengelola pemeriksaan laboratorium.

**Fitur:**
- Order laboratorium dari dokter
- Input hasil lab
- Template pemeriksaan
- Normal range
- Cetak hasil lab
- Spesimen tracking
- Status order

---

### 25. Radiologi

**Deskripsi:** Mengelola pemeriksaan radiologi.

**Fitur:**
- Order radiologi dari dokter
- Input hasil radiologi
- Upload gambar (DICOM support)
- Template radiologi
- Cetak hasil
- Status order

---

### 26. Billing

**Deskripsi:** Mengelola tagihan pasien.

**Fitur:**
- Generate tagihan otomatis dari kunjungan
- Rincian biaya (konsultasi, tindakan, obat, lab, radiologi)
- Diskon dan adjustment
- Tagihan asuransi
- Cetak tagihan
- Status: unpaid, partial, paid, cancelled

---

### 27. Pembayaran

**Deskripsi:** Memproses pembayaran tagihan.

**Fitur:**
- Proses pembayaran
- Metode pembayaran:
  - Tunai
  - Transfer bank
  - QRIS
  - Kartu debit/kredit
- Bukti pembayaran
- Cetak receipt
- Riwayat pembayaran
- Reconciliation

---

### 28. Gudang

**Deskripsi:** Mengelola gudang dan stok barang.

**Fitur:**
- Kelola stok barang
- Penerimaan barang
- Distribusi ke unit
- Stok opname
- Minimum stok alert
- Mutasi antar gudang

---

### 29. Supplier

**Deskripsi:** Mengelola data supplier.

**Fitur:**
- CRUD supplier
- Purchase order
- Riwayat transaksi
- Evaluasi supplier

---

### 30. Inventaris

**Deskripsi:** Mengelola aset/inventaris rumah sakit.

**Fitur:**
- CRUD inventaris
- Kategori inventaris
- Maintenance tracking
- Jadwal maintenance
- Depresiasi
- Status aset (active, maintenance, disposed)

---

### 31. Laporan

**Deskripsi:** Modul laporan dan analitik.

**Jenis Laporan:**
- Laporan kunjungan (harian, bulanan, tahunan)
- Laporan pendapatan
- Laporan farmasi (penggunaan obat, stok)
- Laporan lab & radiologi
- Laporan inventaris
- Laporan keuangan

**Fitur:**
- Filter tanggal
- Filter poli/dokter/layanan
- Export PDF
- Export Excel
- Print
- Chart dan grafik

---

### 32. Statistik

**Deskripsi:** Dashboard statistik dan analitik.

**Fitur:**
- Statistik kunjungan (harian, mingguan, bulanan)
- Statistik penyakit (top 10)
- Statistik pendapatan
- Statistik poli
- Chart interaktif
- Comparison period
- Export data

---

### 33. Pengaturan Sistem

**Deskripsi:** Konfigurasi sistem rumah sakit.

**Fitur:**
- Profil rumah sakit (nama, alamat, logo, kontak)
- Parameter sistem
- Template dokumen
- Backup & restore
- Maintenance mode
- Konfigurasi email
- Konfigurasi storage
