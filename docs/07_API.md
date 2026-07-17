# 07 - REST API DOCUMENTATION

## Base URL

```
Development: http://localhost:8080/api/v1
Production: https://api.sirs.example.com/api/v1
```

## Authentication

Semua endpoint (kecuali public) memerlukan Bearer Token:

```
Authorization: Bearer {session_token}
```

## Response Format

### Success

```json
{
  "success": true,
  "message": "Data retrieved successfully",
  "data": {},
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 100,
    "total_pages": 5
  }
}
```

### Error

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "field": ["Error message"]
  }
}
```

---

## Public Endpoints

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/auth/register` | Registrasi akun |
| POST | `/auth/login` | Login |
| POST | `/auth/logout` | Logout |
| POST | `/auth/forgot-password` | Request reset password |
| POST | `/auth/reset-password` | Reset password |
| POST | `/auth/verify-email` | Verifikasi email |
| POST | `/auth/resend-verification` | Kirim ulang verifikasi |

### Landing Page

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/public/services` | Daftar layanan |
| GET | `/public/doctors` | Profil dokter publik |
| GET | `/public/contact` | Info kontak |
| POST | `/public/contact` | Kirim pesan |

---

## Protected Endpoints

### Dashboard

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/dashboard/admin` | Statistik admin |
| GET | `/dashboard/doctor` | Statistik dokter |
| GET | `/dashboard/nurse` | Statistik perawat |
| GET | `/dashboard/cashier` | Statistik kasir |
| GET | `/dashboard/pharmacy` | Statistik farmasi |
| GET | `/dashboard/lab` | Statistik lab |
| GET | `/dashboard/management` | Statistik manajemen |

### Users

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/users` | List semua user |
| POST | `/users` | Buat user baru |
| GET | `/users/{uuid}` | Detail user |
| PUT | `/users/{uuid}` | Update user |
| DELETE | `/users/{uuid}` | Hapus user (soft delete) |
| PUT | `/users/{uuid}/activate` | Aktifkan user |
| PUT | `/users/{uuid}/deactivate` | Nonaktifkan user |
| GET | `/users/{uuid}/activity` | Riwayat aktivitas |

### Roles

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/roles` | List semua role |
| POST | `/roles` | Buat role baru |
| GET | `/roles/{uuid}` | Detail role |
| PUT | `/roles/{uuid}` | Update role |
| DELETE | `/roles/{uuid}` | Hapus role |
| GET | `/roles/{uuid}/permissions` | List permission role |
| PUT | `/roles/{uuid}/permissions` | Update permission role |

### Permissions

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/permissions` | List semua permission |
| GET | `/permissions/modules` | List modul |

### Patients

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/patients` | List pasien (search, filter, paginate) |
| POST | `/patients` | Registrasi pasien baru |
| GET | `/patients/{uuid}` | Detail pasien |
| PUT | `/patients/{uuid}` | Update pasien |
| DELETE | `/patients/{uuid}` | Hapus pasien |
| GET | `/patients/{uuid}/visits` | Riwayat kunjungan |
| GET | `/patients/{uuid}/medical-records` | Riwayat rekam medis |
| GET | `/patients/{uuid}/allergies` | Daftar alergi |
| POST | `/patients/{uuid}/allergies` | Tambah alergi |
| DELETE | `/patients/{uuid}/allergies/{id}` | Hapus alergi |
| GET | `/patients/{uuid}/chronic-diseases` | Penyakit kronis |
| POST | `/patients/{uuid}/chronic-diseases` | Tambah penyakit kronis |
| GET | `/patients/{uuid}/documents` | Daftar dokumen |
| POST | `/patients/{uuid}/documents` | Upload dokumen |
| GET | `/patients/export` | Export data (CSV/Excel) |

### Doctors

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/doctors` | List dokter |
| POST | `/doctors` | Tambah dokter |
| GET | `/doctors/{uuid}` | Detail dokter |
| PUT | `/doctors/{uuid}` | Update dokter |
| DELETE | `/doctors/{uuid}` | Hapus dokter |
| GET | `/doctors/{uuid}/schedules` | Jadwal dokter |
| POST | `/doctors/{uuid}/schedules` | Buat jadwal |
| GET | `/doctors/{uuid}/patients` | Daftar pasien |

### Nurses

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/nurses` | List perawat |
| POST | `/nurses` | Tambah perawat |
| GET | `/nurses/{uuid}` | Detail perawat |
| PUT | `/nurses/{uuid}` | Update perawat |
| DELETE | `/nurses/{uuid}` | Hapus perawat |

### Polyclinics

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/polyclinics` | List poli |
| POST | `/polyclinics` | Tambah poli |
| GET | `/polyclinics/{uuid}` | Detail poli |
| PUT | `/polyclinics/{uuid}` | Update poli |
| DELETE | `/polyclinics/{uuid}` | Hapus poli |

### Rooms

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/rooms` | List ruangan |
| POST | `/rooms` | Tambah ruangan |
| GET | `/rooms/{uuid}` | Detail ruangan |
| PUT | `/rooms/{uuid}` | Update ruangan |
| DELETE | `/rooms/{uuid}` | Hapus ruangan |
| GET | `/rooms/{uuid}/beds` | List bed |

### Doctor Schedules

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/schedules` | List jadwal (filter: doctor, polyclinic, day) |
| POST | `/schedules` | Buat jadwal |
| GET | `/schedules/{uuid}` | Detail jadwal |
| PUT | `/schedules/{uuid}` | Update jadwal |
| DELETE | `/schedules/{uuid}` | Hapus jadwal |
| GET | `/schedules/available` | Cek ketersediaan |

### Queues

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/queues` | List antrian hari ini |
| POST | `/queues` | Generate nomor antrian |
| PUT | `/queues/{uuid}/call` | Panggil antrian |
| PUT | `/queues/{uuid}/skip` | Skip antrian |
| PUT | `/queues/{uuid}/recall` | Recall antrian |
| PUT | `/queues/{uuid}/complete` | Selesai |
| GET | `/queues/display` | Data display antrian |

### Visits

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/visits` | List kunjungan |
| POST | `/visits` | Buat kunjungan baru |
| GET | `/visits/{uuid}` | Detail kunjungan |
| PUT | `/visits/{uuid}` | Update kunjungan |
| PUT | `/visits/{uuid}/status` | Update status |
| PUT | `/visits/{uuid}/admit` | Admisi rawat inap |
| PUT | `/visits/{uuid}/discharge` | Discharge |

### Medical Records

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/medical-records` | List rekam medis |
| POST | `/medical-records` | Buat rekam medis |
| GET | `/medical-records/{uuid}` | Detail rekam medis |
| PUT | `/medical-records/{uuid}` | Update rekam medis |
| GET | `/medical-records/templates` | List template |
| POST | `/medical-records/templates` | Buat template |
| GET | `/medical-records/{uuid}/print` | Cetak rekam medis |

### Diagnoses

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/diagnoses` | List diagnosa |
| POST | `/diagnoses` | Tambah diagnosa |
| GET | `/icd10/search` | Search kode ICD-10 |

### Prescriptions

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/prescriptions` | List resep |
| POST | `/prescriptions` | Buat resep |
| GET | `/prescriptions/{uuid}` | Detail resep |
| PUT | `/prescriptions/{uuid}` | Update resep |
| PUT | `/prescriptions/{uuid}/dispense` | Dispense resep |
| GET | `/prescriptions/{uuid}/print` | Cetak resep |

### Drugs (Pharmacy)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/drugs` | List obat |
| POST | `/drugs` | Tambah obat |
| GET | `/drugs/{uuid}` | Detail obat |
| PUT | `/drugs/{uuid}` | Update obat |
| DELETE | `/drugs/{uuid}` | Hapus obat |
| GET | `/drugs/stocks` | Stok obat |
| GET | `/drugs/expiring` | Obat akan kadaluarsa |
| GET | `/drugs/low-stock` | Stok minimum |
| POST | `/drugs/receipts` | Penerimaan obat |
| POST | `/drugs/distributions` | Distribusi obat |
| POST | `/drugs/returns` | Retur obat |
| POST | `/drugs/stock-opnames` | Stok opname |

### Lab Orders

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/lab/orders` | List order lab |
| POST | `/lab/orders` | Buat order lab |
| GET | `/lab/orders/{uuid}` | Detail order |
| PUT | `/lab/orders/{uuid}` | Update order |
| GET | `/lab/orders/{uuid}/results` | Hasil lab |
| POST | `/lab/orders/{uuid}/results` | Input hasil lab |
| GET | `/lab/orders/{uuid}/print` | Cetak hasil |
| GET | `/lab/templates` | Template pemeriksaan |

### Radiology Orders

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/radiology/orders` | List order radiologi |
| POST | `/radiology/orders` | Buat order |
| GET | `/radiology/orders/{uuid}` | Detail order |
| GET | `/radiology/orders/{uuid}/results` | Hasil radiologi |
| POST | `/radiology/orders/{uuid}/results` | Input hasil |
| POST | `/radiology/orders/{uuid}/images` | Upload gambar |

### Invoices (Billing)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/invoices` | List tagihan |
| POST | `/invoices` | Buat tagihan |
| GET | `/invoices/{uuid}` | Detail tagihan |
| PUT | `/invoices/{uuid}` | Update tagihan |
| GET | `/invoices/{uuid}/items` | Rincian item |
| POST | `/invoices/{uuid}/items` | Tambah item |
| DELETE | `/invoices/{uuid}/items/{id}` | Hapus item |
| GET | `/invoices/{uuid}/print` | Cetak tagihan |

### Payments

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/payments` | List pembayaran |
| POST | `/payments` | Proses pembayaran |
| GET | `/payments/{uuid}` | Detail pembayaran |
| GET | `/payments/{uuid}/receipt` | Cetak receipt |

### Inventory

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/items` | List barang |
| POST | `/items` | Tambah barang |
| GET | `/items/{uuid}` | Detail barang |
| PUT | `/items/{uuid}` | Update barang |
| DELETE | `/items/{uuid}` | Hapus barang |
| GET | `/items/stocks` | Stok barang |
| POST | `/items/receipts` | Penerimaan barang |
| POST | `/items/distributions` | Distribusi barang |

### Suppliers

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/suppliers` | List supplier |
| POST | `/suppliers` | Tambah supplier |
| GET | `/suppliers/{uuid}` | Detail supplier |
| PUT | `/suppliers/{uuid}` | Update supplier |
| DELETE | `/suppliers/{uuid}` | Hapus supplier |

### Reports

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/reports/visits` | Laporan kunjungan |
| GET | `/reports/revenue` | Laporan pendapatan |
| GET | `/reports/pharmacy` | Laporan farmasi |
| GET | `/reports/lab` | Laporan lab |
| GET | `/reports/radiology` | Laporan radiologi |
| GET | `/reports/inventory` | Laporan inventaris |
| GET | `/reports/export/pdf` | Export PDF |
| GET | `/reports/export/excel` | Export Excel |

### Statistics

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/stats/visits` | Statistik kunjungan |
| GET | `/stats/diseases` | Statistik penyakit |
| GET | `/stats/revenue` | Statistik pendapatan |
| GET | `/stats/dashboard` | Statistik dashboard |

### Audit Logs

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/audit-logs` | List audit log |
| GET | `/audit-logs/{uuid}` | Detail log |
| GET | `/audit-logs/export` | Export log |

### Notifications

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/notifications` | List notifikasi |
| PUT | `/notifications/{uuid}/read` | Tandai dibaca |
| PUT | `/notifications/read-all` | Tandai semua dibaca |
| GET | `/notifications/unread-count` | Jumlah belum dibaca |

### Settings

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/settings` | List pengaturan |
| GET | `/settings/{group}` | Pengaturan per grup |
| PUT | `/settings/{group}` | Update pengaturan |

---

## Pagination

```
GET /patients?page=1&per_page=20&search=john&sort=name&order=asc
```

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| page | int | 1 | Halaman |
| per_page | int | 20 | Item per halaman (max 100) |
| search | string | - | Keyword search |
| sort | string | created_at | Kolom sorting |
| order | string | desc | asc/desc |

---

## Filtering

```
GET /visits?date_from=2024-01-01&date_to=2024-01-31&status=completed&polyclinic_id=1
```

---

## Error Codes

| Code | Status | Description |
|------|--------|-------------|
| 200 | OK | Success |
| 201 | Created | Created successfully |
| 400 | Bad Request | Invalid request |
| 401 | Unauthorized | Not authenticated |
| 403 | Forbidden | Not authorized |
| 404 | Not Found | Resource not found |
| 422 | Unprocessable | Validation error |
| 429 | Too Many Requests | Rate limited |
| 500 | Internal Error | Server error |
