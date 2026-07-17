# 01 - PRODUCT REQUIREMENTS DOCUMENT (PRD)

## Sistem Informasi Rumah Sakit (SIRS)

### 1. Functional Requirements

#### 1.1 Landing Page

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-LP-001 | Menampilkan informasi rumah sakit | High |
| FR-LP-002 | Menampilkan daftar layanan | High |
| FR-LP-003 | Menampilkan profil dokter | Medium |
| FR-LP-004 | Form kontak dan lokasi | Medium |
| FR-LP-005 | Responsive design | High |
| FR-LP-006 | Dark mode toggle | Low |

#### 1.2 Authentication

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-AUTH-001 | Login dengan email dan password | Critical |
| FR-AUTH-002 | Registrasi akun baru | High |
| FR-AUTH-003 | Verifikasi email | High |
| FR-AUTH-004 | Forgot password via email | High |
| FR-AUTH-005 | Reset password | High |
| FR-AUTH-006 | Logout dan invalidate session | Critical |
| FR-AUTH-007 | Remember me | Low |
| FR-AUTH-008 | Rate limiting login attempt | High |
| FR-AUTH-009 | Session timeout otomatis | High |
| FR-AUTH-010 | Multi-factor authentication | Medium |

#### 1.3 Dashboard

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-DASH-001 | Dashboard Admin (statistik lengkap) | High |
| FR-DASH-002 | Dashboard Dokter (jadwal, pasien hari ini) | High |
| FR-DASH-003 | Dashboard Perawat (tugas, monitoring) | High |
| FR-DASH-004 | Dashboard Kasir (billing pending) | High |
| FR-DASH-005 | Dashboard Farmasi (resep pending) | High |
| FR-DASH-006 | Dashboard Lab (order pending) | High |
| FR-DASH-007 | Dashboard Manajemen (KPI, laporan) | High |
| FR-DASH-008 | Widget konfigurable | Medium |
| FR-DASH-009 | Notifikasi real-time | High |
| FR-DASH-010 | Quick actions | Medium |

#### 1.4 User Management

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-USER-001 | CRUD user | Critical |
| FR-USER-002 | Assign role ke user | Critical |
| FR-USER-003 | Aktif/nonaktifkan user | High |
| FR-USER-004 | Profil user | High |
| FR-USER-005 | Upload foto profil | Low |
| FR-USER-006 | Riwayat aktivitas user | Medium |
| FR-USER-007 | Filter dan search user | High |
| FR-USER-008 | Export data user | Medium |

#### 1.5 Role & Permission (RBAC)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-RBAC-001 | CRUD role | Critical |
| FR-RBAC-002 | Assign permission ke role | Critical |
| FR-RBAC-003 | Permission granularity (CRUD per modul) | Critical |
| FR-RBAC-004 | Role hierarchy | Medium |
| FR-RBAC-005 | Default roles (Admin, Dokter, Perawat, Kasir, Farmasi, Lab, Radiologi, Gudang, Pasien) | Critical |
| FR-RBAC-006 | Custom role | High |

#### 1.6 Audit Log

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-AUDIT-001 | Log semua create, update, delete | Critical |
| FR-AUDIT-002 | Log login/logout | Critical |
| FR-AUDIT-003 | Log perubahan data sensitif | Critical |
| FR-AUDIT-004 | Filter dan search log | High |
| FR-AUDIT-005 | Export log | Medium |
| FR-AUDIT-006 | Retensi log (configurable) | Medium |

#### 1.7 Notifikasi

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-NOTIF-001 | Notifikasi in-app | High |
| FR-NOTIF-002 | Email notification | Medium |
| FR-NOTIF-003 | Notifikasi untuk event tertentu | High |
| FR-NOTIF-004 | Mark as read | High |
| FR-NOTIF-005 | Notifikasi preference | Low |

#### 1.8 Pasien

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-PAS-001 | Registrasi pasien baru | Critical |
| FR-PAS-002 | CRUD data pasien | Critical |
| FR-PAS-003 | Nomor Rekam Medis (MRN) otomatis | Critical |
| FR-PAS-004 | Data demografis lengkap | High |
| FR-PAS-005 | Riwayat kunjungan | High |
| FR-PAS-006 | Riwayat alergi | Critical |
| FR-PAS-007 | Riwayat penyakit kronis | High |
| FR-PAS-008 | Data asuransi | Medium |
| FR-PAS-009 | Upload dokumen pendukung | Medium |
| FR-PAS-010 | Search dan filter | High |
| FR-PAS-011 | Export data pasien | Medium |

#### 1.9 Dokter

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-DOK-001 | CRUD data dokter | Critical |
| FR-DOK-002 | Spesialisasi | High |
| FR-DOK-003 | Jadwal praktik | High |
| FR-DOK-004 | Daftar pasien | High |
| FR-DOK-005 | Kuota per jadwal | Medium |

#### 1.10 Perawat

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-NRS-001 | CRUD data perawat | High |
| FR-NRS-002 | Penugasan ke poli/ruangan | High |
| FR-NRS-003 | Shift management | Medium |

#### 1.11 Poli

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-POL-001 | CRUD poli | High |
| FR-POL-002 | Mapping dokter ke poli | High |
| FR-POL-003 | Kuota harian poli | Medium |

#### 1.12 Ruangan

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-RMN-001 | CRUD ruangan | High |
| FR-RMN-002 | Tipe ruangan (VIP, Kelas 1-3, ICU, ICCU, HCU) | High |
| FR-RMN-003 | Status ketersediaan | High |
| FR-RMN-004 | Kapasitas dan occupancy | Medium |

#### 1.13 Jadwal Dokter

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-JDW-001 | Buat jadwal dokter | Critical |
| FR-JDW-002 | Edit/hapus jadwal | High |
| FR-JDW-003 | Konflik jadwal detection | High |
| FR-JDW-004 | Jadwal berulang | Medium |
| FR-JDW-005 | Libur dan cuti | Medium |

#### 1.14 Antrian

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-ANTR-001 | Generate nomor antrian | Critical |
| FR-ANTR-002 | Status antrian real-time | High |
| FR-ANTR-003 | Display antrian (TV/monitor) | Medium |
| FR-ANTR-004 | Prioritas antrian | Medium |
| FR-ANTR-005 | Skip dan recall | Medium |

#### 1.15 Pendaftaran

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-PEND-001 | Pendaftaran rawat jalan | Critical |
| FR-PEND-002 | Pendaftaran rawat inap | Critical |
| FR-PEND-003 | Pendaftaran IGD | Critical |
| FR-PEND-004 | Pilih dokter dan jadwal | High |
| FR-PEND-005 | Validasi data pasien | High |
| FR-PEND-006 | Cetak tiket antrian | Medium |

#### 1.16 Rawat Jalan

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-RJ-001 | Kunjungan rawat jalan | Critical |
| FR-RJ-002 | Status kunjungan | High |
| FR-RJ-003 | Riwayat kunjungan | High |

#### 1.17 Rawat Inap

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-RI-001 | Admisi rawat inap | Critical |
| FR-RI-002 | Penugasan ruangan | Critical |
| FR-RI-003 | Monitoring harian | High |
| FR-RI-004 | Transfer ruangan | Medium |
| FR-RI-005 | Discharge planning | High |

#### 1.18 IGD

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-IGD-001 | Registrasi IGD | Critical |
| FR-IGD-002 | Triase | Critical |
| FR-IGD-003 | Status pasien IGD | High |
| FR-IGD-004 | Konversi ke rawat inap | High |

#### 1.19 Rekam Medis

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-RM-001 | Create rekam medis | Critical |
| FR-RM-002 | SOAP format | Critical |
| FR-RM-003 | Riwayat rekam medis | Critical |
| FR-RM-004 | Template rekam medis | Medium |
| FR-RM-005 | Attach file/lampiran | Medium |
| FR-RM-006 | Print rekam medis | High |

#### 1.20 Diagnosa

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-DIAG-001 | Input diagnosa (ICD-10) | Critical |
| FR-DIAG-002 | Diagnosa utama dan sekunder | High |
| FR-DIAG-003 | Autocomplete ICD-10 | Medium |

#### 1.21 Tindakan

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-TDK-001 | Input tindakan medis | Critical |
| FR-TDK-002 | Kode tindakan | High |
| FR-TDK-003 | Biaya tindakan | High |

#### 1.22 Resep

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-RSP-001 | Buat resep digital | Critical |
| FR-RSP-002 | Detail obat, dosis, frekuensi | Critical |
| FR-RSP-003 | Cetak resep | High |
| FR-RSP-004 | Riwayat resep | High |

#### 1.23 Farmasi

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-FARM-001 | Kelola stok obat | Critical |
| FR-FARM-002 | Penerimaan obat | High |
| FR-FARM-003 | Distribusi obat | High |
| FR-FARM-004 | Monitoring expired date | High |
| FR-FARM-005 | Minimum stok alert | High |
| FR-FARM-006 | Retur obat | Medium |

#### 1.24 Laboratorium

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-LAB-001 | Order laboratorium | Critical |
| FR-LAB-002 | Input hasil lab | Critical |
| FR-LAB-003 | Cetak hasil lab | High |
| FR-LAB-004 | Template pemeriksaan | Medium |
| FR-LAB-005 | Normal range | Medium |

#### 1.25 Radiologi

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-RAD-001 | Order radiologi | Critical |
| FR-RAD-002 | Input hasil radiologi | Critical |
| FR-RAD-003 | Upload gambar | High |
| FR-RAD-004 | Cetak hasil | High |

#### 1.26 Billing

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-BILL-001 | Generate tagihan otomatis | Critical |
| FR-BILL-002 | Rincian biaya | Critical |
| FR-BILL-003 | Diskon dan adjustment | High |
| FR-BILL-004 | Tagihan asuransi | Medium |
| FR-BILL-005 | Cetak tagihan | High |

#### 1.27 Pembayaran

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-BAYR-001 | Proses pembayaran | Critical |
| FR-BAYR-002 | Metode pembayaran (tunai, transfer, QRIS) | High |
| FR-BAYR-003 | Bukti pembayaran | High |
| FR-BAYR-004 | Riwayat pembayaran | High |

#### 1.28 Gudang

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-GDG-001 | Kelola stok barang | High |
| FR-GDG-002 | Penerimaan barang | High |
| FR-GDG-003 | Distribusi ke unit | High |
| FR-GDG-004 | Stok opname | Medium |

#### 1.29 Supplier

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-SUP-001 | CRUD supplier | High |
| FR-SUP-002 | Purchase order | Medium |
| FR-SUP-003 | Riwayat transaksi | Medium |

#### 1.30 Inventaris

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-INV-001 | CRUD inventaris | High |
| FR-INV-002 | Kategori inventaris | Medium |
| FR-INV-003 | Maintenance tracking | Medium |
| FR-INV-004 | Depresiasi | Low |

#### 1.31 Laporan

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-LAP-001 | Laporan kunjungan | High |
| FR-LAP-002 | Laporan pendapatan | High |
| FR-LAP-003 | Laporan farmasi | High |
| FR-LAP-004 | Laporan lab & radiologi | Medium |
| FR-LAP-005 | Laporan inventaris | Medium |
| FR-LAP-006 | Export PDF/Excel | High |
| FR-LAP-007 | Filter tanggal | High |

#### 1.32 Statistik

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-STAT-001 | Statistik kunjungan harian/bulanan | High |
| FR-STAT-002 | Statistik penyakit | Medium |
| FR-STAT-003 | Statistik pendapatan | Medium |
| FR-STAT-004 | Chart dan grafik interaktif | Medium |

#### 1.33 Pengaturan Sistem

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-SET-001 | Profil rumah sakit | High |
| FR-SET-002 | Parameter sistem | High |
| FR-SET-003 | Template dokumen | Medium |
| FR-SET-004 | Backup & restore | High |
| FR-SET-005 | Maintenance mode | Medium |

---

### 2. Non-Functional Requirements

| ID | Category | Requirement | Target |
|----|----------|-------------|--------|
| NFR-001 | Performance | Response time API | < 2s (P95) |
| NFR-002 | Performance | Page load time | < 3s |
| NFR-003 | Performance | Concurrent users | 500+ |
| NFR-004 | Availability | Uptime | 99.9% |
| NFR-005 | Security | Data encryption | AES-256 at rest, TLS 1.3 in transit |
| NFR-006 | Security | Authentication | Session-based + MFA |
| NFR-007 | Security | Authorization | RBAC |
| NFR-008 | Security | Audit logging | 100% coverage data kritis |
| NFR-009 | Scalability | Horizontal scaling | Docker + load balancer |
| NFR-010 | Usability | Mobile responsive | All pages |
| NFR-011 | Usability | Accessibility | WCAG 2.1 AA |
| NFR-012 | Maintainability | Code coverage | > 80% |
| NFR-013 | Compliance | Audit trail | Semua transaksi kritis |
| NFR-014 | Backup | Backup otomatis | Harian |
| NFR-015 | Recovery | RTO | < 4 jam |
| NFR-016 | Recovery | RPO | < 1 jam |

---

### 3. User Story

#### 3.1 Admin Sistem

```
Sebagai admin sistem,
Saya ingin mengelola user dan role,
Sehingga hak akses sistem terkontrol dengan baik.
```

```
Sebagai admin sistem,
Saya ingin melihat audit log,
Sehingga semua aktivitas termonitor dan teraudit.
```

#### 3.2 Dokter

```
Sebagai dokter,
Saya ingin melihat jadwal dan daftar pasien hari ini,
Sehingga saya bisa mempersiapkan pemeriksaan dengan baik.
```

```
Sebagai dokter,
Saya ingin menulis rekam medis digital,
Sehingga dokumentasi klinis lebih akurat dan tersimpan aman.
```

```
Sebagai dokter,
Saya ingin membuat resep digital,
Sehingga resep langsung diterima farmasi tanpa kertas.
```

#### 3.3 Perawat

```
Sebagai perawat,
Saya ingin mencatat tanda vital pasien,
Sehingga monitoring pasien terdokumentasi dengan baik.
```

```
Sebagai perawat,
Saya ingin melihat daftar tugas hari ini,
Sehingga tidak ada tugas yang terlewat.
```

#### 3.4 Kasir

```
Sebagai kasir,
Saya ingin melihat tagihan yang belum dibayar,
Sehingga proses penagihan lebih efisien.
```

```
Sebagai kasir,
Saya ingin memproses pembayaran dan mencetak receipt,
Sehingga transaksi tercatat dengan benar.
```

#### 3.5 Farmasi

```
Sebagai farmasi,
Saya ingin menerima resep digital dari dokter,
Sehingga proses pengambilan obat lebih cepat.
```

```
Sebagai farmasi,
Saya ingin memonitor stok obat dan expired date,
Sehingga tidak ada kehabisan stok atau obat kadaluarsa.
```

#### 3.6 Pasien

```
Sebagai pasien,
Saya ingin melihat riwayat kunjungan dan rekam medis,
Sehingga saya bisa memantau kesehatan saya.
```

```
Sebagai pasien,
Saya ingin melihat tagihan dan riwayat pembayaran,
Sehingga saya tahu kewajiban saya.
```

---

### 4. Use Case

#### UC-001: Login

| Field | Detail |
|-------|--------|
| Actor | Semua user |
| Precondition | User sudah terdaftar |
| Main Flow | 1. User buka halaman login → 2. Input email & password → 3. Klik Login → 4. Sistem validasi → 5. Redirect ke dashboard sesuai role |
| Alternative | 3a. Email belum terverifikasi → tampilkan pesan |
| Postcondition | User terautentikasi, session aktif |

#### UC-002: Registrasi Pasien Baru

| Field | Detail |
|-------|--------|
| Actor | Admin / Petugas Registrasi |
| Precondition | User sudah login |
| Main Flow | 1. Buka form registrasi pasien → 2. Input data pasien → 3. Sistem generate MRN → 4. Simpan data → 5. Cetak kartu pasien |
| Postcondition | Pasien terdaftar dengan MRN unik |

#### UC-003: Pendaftaran Rawat Jalan

| Field | Detail |
|-------|--------|
| Actor | Admin / Petugas |
| Precondition | Pasien sudah terdaftar |
| Main Flow | 1. Cari pasien → 2. Pilih poli → 3. Pilih dokter & jadwal → 4. Validasi → 5. Generate nomor antrian → 6. Cetak tiket |
| Postcondition | Pasien terdaftar untuk kunjungan, antrian aktif |

#### UC-004: Penulisan Rekam Medis

| Field | Detail |
|-------|--------|
| Actor | Dokter |
| Precondition | Pasien sudah dipanggil |
| Main Flow | 1. Buka rekam medis pasien → 2. Isi SOAP → 3. Input diagnosa (ICD-10) → 4. Input tindakan → 5. Buat resep → 6. Simpan |
| Postcondition | Rekam medis tersimpan, resep terkirim ke farmasi |

#### UC-005: Proses Pembayaran

| Field | Detail |
|-------|--------|
| Actor | Kasir |
| Precondition | Tagihan sudah generated |
| Main Flow | 1. Cari tagihan → 2. Review rincian → 3. Pilih metode bayar → 4. Proses pembayaran → 5. Cetak receipt |
| Postcondition | Pembayaran tercatat, status tagihan lunas |

---

### 5. User Flow

#### 5.1 Flow Pasien Rawat Jalan

```
Pasien Datang
    ↓
Registrasi / Cek Data
    ↓
Ambil Nomor Antrian
    ↓
Tunggu Panggilan
    ↓
Pemeriksaan Dokter
    ↓
Rekam Medis + Diagnosa
    ↓
Resep (jika ada)
    ↓
Farmasi → Ambil Obat
    ↓
Kasir → Pembayaran
    ↓
Selesai
```

#### 5.2 Flow Pasien Rawat Inap

```
Pasien Datang (IGD/Rujukan)
    ↓
Registrasi Rawat Inap
    ↓
Admisi + Penugasan Ruangan
    ↓
Pemeriksaan Dokter
    ↓
Perawatan Harian
    ↓
Monitoring + Asesmen
    ↓
Discharge Planning
    ↓
Billing Akhir
    ↓
Pembayaran
    ↓
Discharge
```

#### 5.3 Flow IGD

```
Pasien IGD Datang
    ↓
Triase (Emergency/Urgent/Non-Urgent)
    ↓
Registrasi IGD
    ↓
Stabilisasi
    ↓
Pemeriksaan Dokter IGD
    ↓
Decision:
    ├→ Rawat Inap → Admisi
    ├→ Rawat Jalan → Pendaftaran RJ
    └→ Pulang → Resep + Billing
```
