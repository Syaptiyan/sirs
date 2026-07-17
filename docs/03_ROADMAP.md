# 03 - ROADMAP

## Sistem Informasi Rumah Sakit (SIRS)

### Overview

| Phase | Durasi | Fokus | Status |
|-------|--------|-------|--------|
| Phase 1 | Minggu 1-2 | Foundation & Core Infrastructure | Pending |
| Phase 2 | Minggu 3-4 | Master Data Management | Pending |
| Phase 3 | Minggu 5-6 | Pendaftaran & Antrian | Pending |
| Phase 4 | Minggu 7-8 | Rekam Medis & Klinis | Pending |
| Phase 5 | Minggu 9-10 | Farmasi, Lab & Radiologi | Pending |
| Phase 6 | Minggu 11-12 | Billing & Inventaris | Pending |
| Phase 7 | Minggu 13-14 | Laporan, Optimasi & Deployment | Pending |

---

### Phase 1: Foundation & Core Infrastructure (Minggu 1-2)

**Goal:** Sistem berjalan dengan autentikasi, dashboard, dan infrastruktur dasar.

**Deliverables:**
- Project setup (CI4, Tailwind, Alpine, DaisyUI, Vite, PostgreSQL)
- Docker development environment
- Database migration system
- Authentication system (login, register, verify email, forgot password)
- RBAC system (role, permission, middleware)
- Dashboard per role
- Landing page
- Audit log system
- Notifikasi system
- Base layout (sidebar, header, footer)
- Dark mode toggle

**Exit Criteria:**
- User bisa register, login, dan logout
- Dashboard berbeda per role
- Audit log mencatat aktivitas
- Dark mode berfungsi

---

### Phase 2: Master Data Management (Minggu 3-4)

Goal:** Master data tersedia dan bisa dikelola.

**Deliverables:**
- Modul Pasien (CRUD, MRN, search, filter, export)
- Modul Dokter (CRUD, spesialisasi, mapping ke user)
- Modul Perawat (CRUD, penugasan)
- Modul Poli (CRUD, mapping dokter)
- Modul Ruangan (CRUD, tipe, status, kapasitas)
- Modul Pengaturan Sistem (profil RS, parameter)
- Upload file support (Supabase Storage)

**Exit Criteria:**
- Semua master data bisa CRUD
- Relasi antar data terbentuk
- Upload/download file berfungsi

---

### Phase 3: Pendaftaran & Antrian (Minggu 5-6)

**Goal:** Pasien bisa didaftarkan dan mendapat antrian.

**Deliverables:**
- Modul Jadwal Dokter (CRUD, konflik, libur)
- Modul Antrian (generate nomor, status, display)
- Modul Pendaftaran Rawat Jalan
- Modul Pendaftaran Rawat Inap
- Modul Pendaftaran IGD
- Triase IGD
- Konversi IGD ke Rawat Inap
- Cetak tiket antrian

**Exit Criteria:**
- Pendaftaran berjalan untuk RJ, RI, IGD
- Antrian berfungsi real-time
- Jadwal dokter tidak konflik

---

### Phase 4: Rekam Medis & Klinis (Minggu 7-8)

**Goal:** Dokter bisa menulis rekam medis digital.

**Deliverables:**
- Modul Rekam Medis (SOAP format)
- Modul Diagnosa (ICD-10 integration)
- Modul Tindakan
- Modul Resep (digital)
- Template rekam medis
- Riwayat kunjungan pasien
- Print rekam medis
- Cetak resep

**Exit Criteria:**
- Dokter bisa menulis SOAP
- Diagnosa ICD-10 berfungsi
- Resep terkirim ke farmasi
- Riwayat pasien lengkap

---

### Phase 5: Farmasi, Lab & Radiologi (Minggu 9-10)

**Goal:** Modul pendukung klinis berfungsi.

**Deliverables:**
- Modul Farmasi (stok, penerimaan, distribusi)
- Monitoring expired date obat
- Minimum stok alert
- Retur obat
- Modul Laboratorium (order, input hasil, cetak)
- Template pemeriksaan lab
- Normal range
- Modul Radiologi (order, input hasil, upload gambar)

**Exit Criteria:**
- Farmasi bisa kelola stok dan distribusi
- Lab bisa order dan input hasil
- Radiologi bisa order dan input hasil
- Alert stok dan expired berfungsi

---

### Phase 6: Billing & Inventaris (Minggu 11-12)

**Goal:** Sistem keuangan dan inventaris berfungsi.

**Deliverables:**
- Modul Billing (generate tagihan, rincian)
- Diskon dan adjustment
- Modul Pembayaran (tunai, transfer, QRIS)
- Bukti pembayaran
- Modul Gudang (stok, penerimaan, distribusi)
- Modul Supplier (CRUD, PO)
- Modul Inventaris (CRUD, kategori, maintenance)
- Stok opname

**Exit Criteria:**
- Billing tergenerate otomatis
- Pembayaran tercatat dengan benar
- Inventaris terkelola
- Stok opname berfungsi

---

### Phase 7: Laporan, Optimasi & Deployment (Minggu 13-14)

**Goal:** Sistem siap production.

**Deliverables:**
- Modul Laporan (kunjungan, pendapatan, farmasi, lab, inventaris)
- Modul Statistik (chart, grafik interaktif)
- Export PDF/Excel
- Filter tanggal
- Performance optimization
- Security audit
- Docker production setup
- Nginx configuration
- Backup system
- Monitoring setup
- Dokumentasi final

**Exit Criteria:**
- Semua laporan berfungsi
- Performance target tercapai (< 2s P95)
- Security audit pass
- Docker production ready
- Backup & monitoring aktif
- Dokumentasi lengkap

---

### Milestone

| Milestone | Target | Deliverable |
|-----------|--------|-------------|
| M1 | Akhir Minggu 2 | Auth + Dashboard live |
| M2 | Akhir Minggu 4 | Master data lengkap |
| M3 | Akhir Minggu 6 | Pendaftaran + Antrian live |
| M4 | Akhir Minggu 8 | Rekam medis digital |
| M5 | Akhir Minggu 10 | Farmasi + Lab + Radiologi |
| M6 | Akhir Minggu 12 | Billing + Inventaris |
| M7 | Akhir Minggu 14 | Production ready |

### Risk Mitigation

| Risk | Phase | Mitigasi |
|------|-------|----------|
| Scope creep | All | Dokumentasi ketat, change management |
| Technical debt | All | Code review, refactoring tiap phase |
| Integration issue | Phase 4-6 | API contract awal, mock testing |
| Performance | Phase 7 | Indexing, caching, query optimization |
| Security | All | Security audit tiap phase |
