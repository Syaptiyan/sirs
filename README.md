<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.3.32-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/CodeIgniter_4.7-EF4223?style=for-the-badge&logo=codeigniter&logoColor=white" alt="CodeIgniter">
  <img src="https://img.shields.io/badge/PostgreSQL-4169E1?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL">
  <img src="https://img.shields.io/badge/Redis-DC382D?style=for-the-badge&logo=redis&logoColor=white" alt="Redis">
  <img src="https://img.shields.io/badge/Tailwind_CSS-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker">
</p>

<div align="center">
  <h1>SIRS</h1>
  <h3>Sistem Informasi Rumah Sakit</h3>
  <p><em>Built with CodeIgniter 4 — Modern, Modular, Production-Ready</em></p>
</div>

---

## Fitur Unggulan

### Manajemen Pasien
- Registrasi pasien dengan auto-generasi MRN (Medical Record Number)
- Data demografis, alergi, penyakit kronis, dokumen
- Pencarian & filter multi-kriteria

### Antrean & Registrasi
- Antrean real-time dengan display system
- Prioritas pasien (IGD, Rawat Jalan, Rawat Inap)
- Deteksi jadwal dokter & konflik

### Rekam Medis
- SOAP Format (Subjective, Objective, Assessment, Plan)
- Template rekam medis
- Riwayat pasien terintegrasi

### Diagnosa & Tindakan
- ICD-10 Integration (50+ kode diagnosa)
- Kode tindakan medis
- Riwayat diagnosa per pasien

### Farmasi & Gudang
- Manajemen stok obat
- Peringatan kadaluwarsa & stok minimum
- Resep digital & distribusi

### Laboratorium & Radiologi
- Pemeriksaan lab dengan template & range normal
- Pemeriksaan radiologi dengan upload gambar
- Cetak hasil

### Keuangan & Pembayaran
- Generate invoice otomatis
- Multi metode pembayaran (5 metode)
- Cetak kwitansi
- Klaim asuransi

### Keamanan
- RBAC (10 Role, 48 Permission)
- Proteksi CSRF, XSS, SQL Injection
- Rate Limiting & Brute Force Protection
- Enkripsi AES-256
- Audit logging

### Laporan & Statistik
- Laporan kunjungan, pendapatan, farmasi, lab, inventaris
- Ekspor PDF & Excel
- Statistik interaktif dengan Chart.js

---

## Tech Stack

| Lapisan | Teknologi |
|---------|-----------|
| **Backend** | PHP 8.3, CodeIgniter 4.7 |
| **Database** | PostgreSQL 16 |
| **Cache & Session** | Redis 7 |
| **Frontend** | Tailwind CSS, Alpine.js, DaisyUI, Vite |
| **Web Server** | Nginx 1.27 |
| **Container** | Docker & Docker Compose |
| **Testing** | PHPUnit 10, SQLite3 (in-memory) |
| **Library** | CodeIgniter Shield (Auth), MPDF, PHPMailer |

---

## Arsitektur

```
sirs/
├── app/
│   ├── Config/          # Konfigurasi aplikasi
│   ├── Controllers/     # Controller (25+)
│   ├── Database/
│   │   └── Migrations/  # Migrasi database (50+)
│   ├── Entities/        # Entity models
│   ├── Filters/         # Security filters
│   ├── Helpers/         # Helper functions
│   ├── Libraries/       # Custom libraries (UUID, dll)
│   ├── Models/          # Models (60+)
│   ├── Services/        # Business logic (30+)
│   └── Views/           # Views (80+)
├── docker/
│   ├── nginx/           # Nginx config
│   └── php/             # PHP-FPM Dockerfile
├── tests/
│   ├── feature/         # Feature tests
│   ├── security/        # Security tests
│   └── unit/            # Unit tests
└── docs/                # Documentation
```

---

## Persyaratan

- Docker & Docker Compose
- Git

## Instalasi

### 1. Clone & Masuk

```bash
git clone https://github.com/Syaptiyan/sirs.git
cd sirs
```

### 2. Setup Environment

```bash
cp env .env
```

Edit `.env`:

```env
app.baseURL = 'http://localhost/'

database.default.hostname = postgres
database.default.database = sirs
database.default.username = postgres
database.default.password = secret
database.default.DBDriver = Postgre
database.default.port = 5432
```

### 3. Jalankan Container

```bash
docker compose up -d
```

### 4. Install Dependencies

```bash
docker compose exec app composer install
```

### 5. Migrasi Database

```bash
docker compose exec app php spark migrate --all
```

### 6. Buka Aplikasi

Akses [http://localhost](http://localhost)

---

## Docker Services

| Service | Port | Deskripsi |
|---------|------|-----------|
| `nginx` | `80` | Web server |
| `app` | `9000` | PHP-FPM 8.3 |
| `postgres` | `5432` | Database PostgreSQL |
| `redis` | `6379` | Cache & Session |

---

## Pengembangan

### Spark Serve (Lokal)

```bash
composer install
php spark serve
```

### Menjalankan Test

```bash
# Semua test
php spark test

# Test spesifik
vendor/bin/phpunit tests/feature/AuthControllerTest.php

# Coverage (wajib XDebug)
vendor/bin/phpunit --coverage-html writable/cache/coverage
```

> **Catatan:** Test menggunakan SQLite3 in-memory, data di-reset setiap sesi test.

### Build Frontend

```bash
npm install
npm run build    # Production
npm run dev      # Development (Vite HMR)
```

---

## Scripts

| Script | Fungsi |
|--------|--------|
| `scripts/backup-db.sh` | Backup database PostgreSQL |
| `scripts/backup-files.sh` | Backup file aplikasi |
| `scripts/deploy.sh` | Deploy ke production |
| `scripts/health-check.sh` | Cek status aplikasi |

---

## Monitoring

Endpoint health check: `GET /health`

```bash
curl http://localhost/health
```

Response:
```json
{
    "status": "ok",
    "timestamp": "2026-07-18T00:00:00+07:00",
    "version": "1.0.0",
    "database": {"status": "ok"},
    "cache": {"status": "ok"}
}
```

---

## Dokumentasi

- [Kode Etik](docs/01_KODE_ETIK.md)
- [Struktur Proyek](docs/02_STRUKTUR_PROYEK.md)
- [Panduan Gaya Kode](docs/03_GAYA_KODE.md)
- [Panduan Git](docs/04_GIT.md)
- [Panduan Database](docs/05_DATABASE.md)
- [Panduan API](docs/06_API.md)
- [Panduan Testing](docs/07_TESTING.md)
- [Panduan Deployment](docs/08_DEPLOYMENT.md)
- [Panduan Keamanan](docs/10_KEAMANAN.md)
- [Changelog](docs/13_CHANGELOG.md)

---

## Lisensi

[MIT](LICENSE) — Dibuat dengan ❤️ oleh [Syaptiyan](https://github.com/Syaptiyan)
