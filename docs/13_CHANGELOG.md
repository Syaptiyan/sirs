# Changelog

> Semua perubahan penting pada proyek SIRS akan dicatat di sini.
> Format berdasarkan [Keep a Changelog](https://keepachangelog.com/) dan [Semantic Versioning](https://semver.org/).

---

## [Unreleased]

### Added — 2026-07-17

#### 🏗️ Foundation
- Initial project setup: CodeIgniter 4.7, Tailwind CSS, Alpine.js, DaisyUI, Vite
- Docker development environment (Nginx 1.27, PHP-FPM 8.3, Redis 7, PostgreSQL 16)
- PHP 8.3.32, Composer 2.10

#### 🔐 Authentication & Authorization
- Login, Register, Forgot Password, Reset Password
- Email verification flow
- RBAC system (10 role, 48 permission) dengan permission editor
- Middleware otorisasi per route

#### 📊 Dashboard
- Dashboard per role: Admin, Dokter, Perawat, Kasir, Farmasi, Lab, Manajemen
- Landing page dengan glassmorphism design
- Sidebar navigasi, Header, Footer, Dark mode toggle

#### 👥 Manajemen Pasien
- Registrasi pasien dengan auto-generasi MRN
- Data alergi, penyakit kronis, upload dokumen
- Pencarian & filter multi-kriteria, ekspor data

#### 👨‍⚕️ Manajemen Dokter & Perawat
- CRUD dokter dengan spesialisasi
- Mapping dokter ke user
- CRUD perawat dengan assignment management

#### 🏥 Poliklinik & Ruangan
- CRUD poli, mapping dokter-poli
- CRUD ruangan, tipe ruangan, management bed

#### 📋 Antrean & Registrasi
- Generate nomor antrean, status real-time, display system, prioritas
- Registrasi Rawat Jalan, Rawat Inap, IGD
- Triage IGD dengan klasifikasi prioritas

#### 📝 Rekam Medis
- SOAP Format (Subjective, Objective, Assessment, Plan)
- Template rekam medis, riwayat pasien, cetak rekam medis

#### 🩺 Diagnosa & Tindakan
- ICD-10 integration (50+ kode)
- Diagnosa primer & sekunder, kode tindakan medis

#### 💊 Farmasi
- Manajemen obat, stok, penerimaan, distribusi
- Monitor kadaluwarsa, alert stok minimum, stok opname
- Resep digital dengan detail obat

#### 🔬 Laboratorium & Radiologi
- Pemeriksaan lab dengan template & range normal, cetak hasil
- Pemeriksaan radiologi dengan upload gambar

#### 💰 Billing & Pembayaran
- Generate invoice otomatis, diskon, klaim asuransi
- 5 metode pembayaran, cetak kwitansi

#### 📦 Gudang & Inventaris
- Manajemen stok, penerimaan, distribusi, stok opname
- Supplier CRUD, purchase orders
- Asset management, maintenance tracking, depresiasi

#### 📈 Laporan & Statistik
- Laporan kunjungan, pendapatan, farmasi, lab, inventaris
- Ekspor PDF & Excel
- Statistik interaktif dengan Chart.js

#### 🧪 Testing
- Unit test, feature test, security test
- PHPUnit 10 dengan SQLite3 in-memory

#### 🔧 DevOps
- Docker Compose (Nginx + PHP-FPM + Redis + PostgreSQL)
- Backup database & file, health check monitoring, deploy script

---

### Security — 2026-07-18

- **Force Global Secure Requests** — CSP, redirect HTTPS
- **Honeypot Protection** — diaktifkan secara global
- **CSRF Protection** — diaktifkan secara global (session-based)
- **Security Headers** — X-Frame-Options, X-Content-Type-Options, dll.
- **InvalidChars Filter** — memblokir input berbahaya
- **Rate Limiting** — route auth: login (10/900s), register/forgot/reset (5/3600s)
- **Sensitive Data Filtering** — exception traces dibersihkan
- **Cookie Security** — Secure, HttpOnly, SameSite=Strict
- **Redis Cache** — handler Redis dengan fallback ke file
- **Encryption** — auto-generate AES-256 encryption key
- **UUID Service** — menggunakan `random_bytes(16)`

### Fixed — 2026-07-18

- **Security Config** — tipe nullable untuk properti `$tokenRandomize`, `$regenerate`
- **Cache Config** — handler Redis yang benar + fallback file
- **Session Config** — menambahkan properti `?string $savePath = null`
- **Filters Config** — CSRF dipindah ke global `before` filter, semua filter keamanan diaktifkan
- **Exceptions Config** — diisi `$sensitiveDataInTrace`
- **Services Config** — mendaftarkan layanan `uuid`
- **UserModel** — cast `$allowedFields` ke string untuk mencegah error tipe SQL
- **Migrasi CreateUsersTable** — menghapus tabel `users` milik Shield terlebih dahulu (FK-safe) sebelum membuat skema aplikasi
- **Migrasi CreateSettingsTable** — menggunakan `ifNotExists` untuk menghindari konflik dengan library Settings; `down()` no-op
- **Database Migration Conflict** — konflik dengan library CodeIgniter Shield & Settings diselesaikan
- **Test Bootstrap** — menjalankan semua migrasi sekali sebelum suite PHPUnit (in-memory SQLite3)
- **Test Classes** — semua kelas tes menggunakan `$refresh = false` untuk mencegah kegagalan cascade regress

---

## [1.0.0] — Target 2024

### Phase 1: Foundation (Minggu 1–2)

#### Added
- Project setup: CodeIgniter 4, Tailwind CSS, Alpine.js, DaisyUI, Vite
- Docker development environment
- PostgreSQL database connection
- Database migration system
- Authentication system (Login, Register, Email verification, Forgot/Reset password)
- RBAC system (Role management, Permission management, Middleware)
- Dashboard per role (Admin, Dokter, Perawat, Kasir, Farmasi, Lab, Manajemen)
- Landing page, Audit log, Notification system
- Base layout (Sidebar, Header, Footer, Dark mode toggle)

### Phase 2: Master Data (Minggu 3–4)

#### Added
- **Patient** — Registrasi, MRN auto-generation, CRUD, search & filter, alergi, penyakit kronis, dokumen, ekspor
- **Doctor** — CRUD, spesialisasi, mapping ke user
- **Nurse** — CRUD, assignment management
- **Polyclinic** — CRUD, dokter-poli mapping
- **Room** — CRUD, tipe ruangan, bed management
- **Settings** — Profil rumah sakit, parameter sistem

### Phase 3: Registration & Queue (Minggu 5–6)

#### Added
- **Doctor Schedule** — CRUD, conflict detection, holiday management
- **Queue** — Nomor antrean, status real-time, display system, prioritas
- **Registration** — Rawat Jalan, Rawat Inap, IGD
- **Triage** — IGD triage, klasifikasi prioritas

### Phase 4: Medical Records (Minggu 7–8)

#### Added
- **Medical Records** — SOAP format, template, riwayat, cetak
- **Diagnosis** — ICD-10 integration, diagnosa primer & sekunder
- **Action** — Tindakan medis, kode tindakan
- **Prescription** — Resep digital, detail obat, cetak resep

### Phase 5: Pharmacy, Lab & Radiology (Minggu 9–10)

#### Added
- **Pharmacy** — Obat, stok, penerimaan, distribusi, expiry monitoring, minimum stock alert, stok opname
- **Laboratory** — Pemeriksaan, hasil, template, range normal, cetak
- **Radiology** — Pemeriksaan, hasil, upload gambar, template

### Phase 6: Billing & Inventory (Minggu 11–12)

#### Added
- **Billing** — Auto invoice, item details, diskon, klaim asuransi
- **Payment** — Multi metode, processing, cetak kwitansi
- **Warehouse** — Stok, penerimaan, distribusi, stok opname
- **Supplier** — CRUD, purchase orders
- **Inventory** — Asset management, maintenance, depresiasi

### Phase 7: Reports & Optimization (Minggu 13–14)

#### Added
- **Reports** — Kunjungan, pendapatan, farmasi, lab, inventaris — PDF/Excel export
- **Statistics** — Kunjungan, penyakit, pendapatan — Chart.js interactive charts
- Performance optimization, Security audit, Production deployment, Final documentation

---

## Riwayat Versi

| Versi | Tanggal | Deskripsi |
|-------|---------|-----------|
| 0.2.0 | 2026-07-18 | Production hardening: security, rate limiting, cache, migration conflict fixes, test bootstrap |
| 0.1.0 | 2026-07-17 | Initial development — seluruh modul inti |
