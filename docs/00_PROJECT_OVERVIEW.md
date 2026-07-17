# 00 - PROJECT OVERVIEW

## Sistem Informasi Rumah Sakit (SIRS)

### Visi

Menjadi sistem informasi rumah sakit enterprise terpadu yang mengoptimalkan operasional, meningkatkan kualitas pelayanan kesehatan, dan memberikan pengalaman digital terbaik bagi seluruh pemangku kepentingan.

### Misi

1. Mengintegrasikan seluruh proses bisnis rumah sakit dalam satu platform digital
2. Menyediakan data real-time untuk pengambilan keputusan klinis dan manajerial
3. Meningkatkan efisiensi operasional dan mengurangi biaya administrasi
4. Memastikan keamanan dan privasi data pasien sesuai standar nasional dan internasional
5. Mendukung interoperabilitas dengan sistem kesehatan lainnya

### Target Pengguna

| Role | Deskripsi |
|------|-----------|
| Admin Sistem | Mengelola konfigurasi, user, dan hak akses |
| Manajemen | Monitoring KPI, laporan, dan statistik |
| Dokter | Kelola jadwal, rekam medis, diagnosa, resep |
| Perawat | Kelola asesmen, tindakan, monitoring pasien |
| Kasir | Kelola billing, pembayaran, dan tagihan |
| Farmasi | Kelola stok obat, resep, dan distribusi |
| Laboratorium | Kelola order lab, hasil, dan pelaporan |
| Radiologi | Kelola order radiologi, hasil, dan pelaporan |
| Gudang/Logistik | Kelola inventaris, supplier, dan procurement |
| Pasien | Akses rekam medis, janji temu, dan riwayat |

### Scope Proyek

#### In Scope

- Landing Page publik
- Autentikasi dan manajemen user (RBAC)
- Dashboard per role
- Modul Manajemen Pasien
- Modul Manajemen Dokter dan Perawat
- Modul Poli dan Ruangan
- Modul Jadwal Dokter dan Antrian
- Modul Pendaftaran (Rawat Jalan, Rawat Inap, IGD)
- Modul Rekam Medis (Diagnosa, Tindakan, Resep)
- Modul Farmasi
- Modul Laboratorium
- Modul Radiologi
- Modul Billing dan Pembayaran
- Modul Gudang, Supplier, Inventaris
- Modul Laporan dan Statistik
- Modul Notifikasi
- Modul Audit Log
- Modul Pengaturan Sistem
- REST API untuk integrasi eksternal
- PWA Support
- Dark Mode
- Responsive Design

#### Out of Scope

- Integrasi BPJS (fase selanjutnya)
- Integrasi asuransi pihak ketiga
- Sistem payroll
- Manajemen aset tetap
- Video call telemedicine

### Stakeholder

| Stakeholder | Kepentingan |
|-------------|-------------|
| Direktur RS | Visibilitas operasional, laporan strategis |
| Manajer IT | Maintenance, security, integrasi |
| Kepala Klinik | Monitoring pelayanan, kualitas klinis |
| Tim Medis | Kemudahan input data, akses rekam medis |
| Tim Administrasi | Efisiensi proses registrasi dan billing |
| Pasien | Akses informasi kesehatan, kemudahan layanan |

### Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | CodeIgniter 4, PHP 8.3+, Composer |
| Frontend | Tailwind CSS, Alpine.js, DaisyUI, Vite |
| Database | PostgreSQL (Supabase) |
| Storage | Supabase Storage |
| Authentication | Session-based, RBAC |
| API | REST API |
| Testing | PHPUnit |
| Deployment | Docker, Nginx, PHP-FPM |

### Timeline

| Phase | Durasi | Fokus |
|-------|--------|-------|
| Phase 1 | Minggu 1-2 | Foundation, Auth, Dashboard |
| Phase 2 | Minggu 3-4 | Master Data (Pasien, Dokter, Poli) |
| Phase 3 | Minggu 5-6 | Pendaftaran, Antrian, Jadwal |
| Phase 4 | Minggu 7-8 | Rekam Medis, Diagnosa, Tindakan |
| Phase 5 | Minggu 9-10 | Farmasi, Lab, Radiologi |
| Phase 6 | Minggu 11-12 | Billing, Pembayaran, Inventaris |
| Phase 7 | Minggu 13-14 | Laporan, Statistik, Optimasi |

### Risk

| Risk | Impact | Mitigasi |
|------|--------|----------|
| Perubahan scope | High | Dokumentasi ketat, change management |
| Data breach | Critical | Enkripsi, audit log, RBAC ketat |
| Performance | High | Indexing, caching, query optimization |
| Downtime | High | Docker, health check, monitoring |
| Compliance | Medium | Audit trail, logging lengkap |

### Success Metrics

- 99.9% uptime
- < 2s response time (P95)
- 100% audit coverage untuk data kritis
- Zero data breach
- 80% pengurangan waktu administrasi
