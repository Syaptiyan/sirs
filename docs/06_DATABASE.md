# 06 - DATABASE DESIGN

## PostgreSQL (Supabase)

### Conventions

| Item | Convention |
|------|------------|
| Engine | PostgreSQL 15+ |
| Charset | UTF-8 |
| PK | `id` BIGSERIAL |
| FK | `{table_singular}_id` BIGINT |
| Timestamps | `created_at`, `updated_at`, `deleted_at` (soft delete) |
| UUID | UUID type untuk public-facing identifier |
| Decimal | DECIMAL(15,2) untuk monetary |
| Enum | VARCHAR + CHECK constraint |

---

## ERD Overview

### Core System Tables (1-10)

| # | Table | Description |
|---|-------|-------------|
| 1 | users | Akun pengguna sistem |
| 2 | roles | Role/level pengguna |
| 3 | permissions | Permission/hak akses |
| 4 | role_permissions | Relasi role ↔ permission |
| 5 | user_roles | Relasi user ↔ role |
| 6 | sessions | Session aktif |
| 7 | password_resets | Token reset password |
| 8 | email_verifications | Token verifikasi email |
| 9 | audit_logs | Log aktivitas |
| 10 | notifications | Notifikasi in-app |

### Master Data Tables (11-25)

| # | Table | Description |
|---|-------|-------------|
| 11 | patients | Data pasien |
| 12 | patient_allergies | Alergi pasien |
| 13 | patient_chronic_diseases | Penyakit kronis pasien |
| 14 | patient_insurance | Asuransi pasien |
| 15 | patient_documents | Dokumen pasien |
| 16 | doctors | Data dokter |
| 17 | doctor_specializations | Spesialisasi dokter |
| 18 | nurses | Data perawat |
| 19 | polyclinics | Data poli |
| 20 | doctor_polyclinics | Relasi dokter ↔ poli |
| 21 | rooms | Data ruangan |
| 22 | room_types | Tipe ruangan |
| 23 | wards | Data bangsal |
| 24 | beds | Data tempat tidur |
| 25 | bed_assignments | Penugasan tempat tidur |

### Scheduling Tables (26-32)

| # | Table | Description |
|---|-------|-------------|
| 26 | doctor_schedules | Jadwal dokter |
| 27 | schedule_exceptions | Libur/cuti dokter |
| 28 | queues | Antrian |
| 29 | queue_displays | Display antrian |
| 30 | appointments | Janji temu |
| 31 | appointment_reminders | Pengingat janji temu |
| 32 | shifts | Shift kerja |

### Clinical Tables (33-50)

| # | Table | Description |
|---|-------|-------------|
| 33 | visits | Kunjungan |
| 34 | visit_types | Tipe kunjungan (RJ, RI, IGD) |
| 35 | medical_records | Rekam medis |
| 36 | medical_record_templates | Template rekam medis |
| 37 | diagnoses | Diagnosa |
| 38 | icd10_codes | Kode ICD-10 |
| 39 | actions | Tindakan medis |
| 40 | action_types | Jenis tindakan |
| 41 | prescriptions | Resep |
| 42 | prescription_details | Detail resep |
| 43 | vital_signs | Tanda vital |
| 44 | assessments | Asesmen |
| 45 | nursing_notes | Catatan perawat |
| 46 | doctor_rounds | Visite dokter |
| 47 | consent_forms | Form persetujuan |
| 48 | discharge_summaries | Resume pulang |
| 49 | referrals | Rujukan |
| 50 | triages | Triase IGD |

### Pharmacy Tables (51-58)

| # | Table | Description |
|---|-------|-------------|
| 51 | drugs | Data obat |
| 52 | drug_categories | Kategori obat |
| 53 | drug_batches | Batch obat |
| 54 | drug_stocks | Stok obat |
| 55 | drug_receipts | Penerimaan obat |
| 56 | drug_distributions | Distribusi obat |
| 57 | drug_returns | Retur obat |
| 58 | drug_stock_opnames | Stok opname obat |

### Laboratory Tables (59-64)

| # | Table | Description |
|---|-------|-------------|
| 59 | lab_orders | Order laboratorium |
| 60 | lab_order_items | Item order lab |
| 61 | lab_results | Hasil lab |
| 62 | lab_templates | Template pemeriksaan |
| 63 | lab_normal_ranges | Nilai normal |
| 64 | lab_specimens | Spesimen |

### Radiology Tables (65-68)

| # | Table | Description |
|---|-------|-------------|
| 65 | radiology_orders | Order radiologi |
| 66 | radiology_results | Hasil radiologi |
| 67 | radiology_images | Gambar radiologi |
| 68 | radiology_templates | Template radiologi |

### Billing Tables (69-75)

| # | Table | Description |
|---|-------|-------------|
| 69 | invoices | Tagihan |
| 70 | invoice_items | Item tagihan |
| 71 | payments | Pembayaran |
| 72 | payment_methods | Metode pembayaran |
| 73 | payment_receipts | Bukti pembayaran |
| 74 | discounts | Diskon |
| 75 | insurance_claims | Klaim asuransi |

### Inventory Tables (76-82)

| # | Table | Description |
|---|-------|-------------|
| 76 | warehouses | Gudang |
| 77 | suppliers | Supplier |
| 78 | items | Barang |
| 79 | item_categories | Kategori barang |
| 80 | item_stocks | Stok barang |
| 81 | item_receipts | Penerimaan barang |
| 82 | item_distributions | Distribusi barang |

### System Tables (83-88)

| # | Table | Description |
|---|-------|-------------|
| 83 | settings | Pengaturan sistem |
| 84 | provinces | Provinsi |
| 85 | cities | Kota/Kabupaten |
| 86 | districts | Kecamatan |
| 87 | villages | Kelurahan/Desa |
| 88 | blood_types | Golongan darah |

---

## Table Details

### 1. users

```sql
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    uuid UUID NOT NULL DEFAULT gen_random_uuid() UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    avatar VARCHAR(500) NULL,
    is_active BOOLEAN NOT NULL DEFAULT true,
    last_login_at TIMESTAMP NULL,
    last_login_ip VARCHAR(45) NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_uuid ON users(uuid);
CREATE INDEX idx_users_is_active ON users(is_active);
```

### 2. roles

```sql
CREATE TABLE roles (
    id BIGSERIAL PRIMARY KEY,
    uuid UUID NOT NULL DEFAULT gen_random_uuid() UNIQUE,
    name VARCHAR(50) NOT NULL UNIQUE,
    slug VARCHAR(50) NOT NULL UNIQUE,
    description TEXT NULL,
    is_system BOOLEAN NOT NULL DEFAULT false,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
```

### 3. permissions

```sql
CREATE TABLE permissions (
    id BIGSERIAL PRIMARY KEY,
    uuid UUID NOT NULL DEFAULT gen_random_uuid() UNIQUE,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    module VARCHAR(50) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_permissions_module ON permissions(module);
```

### 4. role_permissions

```sql
CREATE TABLE role_permissions (
    id BIGSERIAL PRIMARY KEY,
    role_id BIGINT NOT NULL REFERENCES roles(id) ON DELETE CASCADE,
    permission_id BIGINT NOT NULL REFERENCES permissions(id) ON DELETE CASCADE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(role_id, permission_id)
);

CREATE INDEX idx_role_permissions_role ON role_permissions(role_id);
CREATE INDEX idx_role_permissions_permission ON role_permissions(permission_id);
```

### 5. user_roles

```sql
CREATE TABLE user_roles (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    role_id BIGINT NOT NULL REFERENCES roles(id) ON DELETE CASCADE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, role_id)
);

CREATE INDEX idx_user_roles_user ON user_roles(user_id);
CREATE INDEX idx_user_roles_role ON user_roles(role_id);
```

### 11. patients

```sql
CREATE TABLE patients (
    id BIGSERIAL PRIMARY KEY,
    uuid UUID NOT NULL DEFAULT gen_random_uuid() UNIQUE,
    mrn VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    nik VARCHAR(20) NULL UNIQUE,
    birth_place VARCHAR(100) NULL,
    birth_date DATE NOT NULL,
    gender VARCHAR(10) NOT NULL CHECK (gender IN ('L', 'P')),
    blood_type VARCHAR(5) NULL CHECK (blood_type IN ('A', 'B', 'AB', 'O', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-')),
    religion VARCHAR(30) NULL,
    education VARCHAR(50) NULL,
    occupation VARCHAR(100) NULL,
    marital_status VARCHAR(20) NULL CHECK (marital_status IN ('belum_kawin', 'kawin', 'cerai_hidup', 'cerai_mati')),
    phone VARCHAR(20) NULL,
    email VARCHAR(150) NULL,
    address TEXT NULL,
    province_id BIGINT NULL REFERENCES provinces(id),
    city_id BIGINT NULL REFERENCES cities(id),
    district_id BIGINT NULL REFERENCES districts(id),
    village_id BIGINT NULL REFERENCES villages(id),
    postal_code VARCHAR(10) NULL,
    emergency_contact_name VARCHAR(150) NULL,
    emergency_contact_phone VARCHAR(20) NULL,
    emergency_contact_relation VARCHAR(50) NULL,
    photo VARCHAR(500) NULL,
    notes TEXT NULL,
    is_active BOOLEAN NOT NULL DEFAULT true,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

CREATE INDEX idx_patients_mrn ON patients(mrn);
CREATE INDEX idx_patients_nik ON patients(nik);
CREATE INDEX idx_patients_name ON patients(name);
CREATE INDEX idx_patients_birth_date ON patients(birth_date);
CREATE INDEX idx_patients_gender ON patients(gender);
CREATE INDEX idx_patients_is_active ON patients(is_active);
```

### 16. doctors

```sql
CREATE TABLE doctors (
    id BIGSERIAL PRIMARY KEY,
    uuid UUID NOT NULL DEFAULT gen_random_uuid() UNIQUE,
    user_id BIGINT NULL REFERENCES users(id),
    employee_id VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    sip VARCHAR(50) NOT NULL UNIQUE,
    specialization_id BIGINT NOT NULL REFERENCES doctor_specializations(id),
    phone VARCHAR(20) NULL,
    email VARCHAR(150) NULL,
    photo VARCHAR(500) NULL,
    bio TEXT NULL,
    consultation_fee DECIMAL(15,2) NOT NULL DEFAULT 0,
    is_active BOOLEAN NOT NULL DEFAULT true,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

CREATE INDEX idx_doctors_user ON doctors(user_id);
CREATE INDEX idx_doctors_specialization ON doctors(specialization_id);
CREATE INDEX idx_doctors_employee_id ON doctors(employee_id);
```

### 26. doctor_schedules

```sql
CREATE TABLE doctor_schedules (
    id BIGSERIAL PRIMARY KEY,
    uuid UUID NOT NULL DEFAULT gen_random_uuid() UNIQUE,
    doctor_id BIGINT NOT NULL REFERENCES doctors(id),
    polyclinic_id BIGINT NOT NULL REFERENCES polyclinics(id),
    day_of_week SMALLINT NOT NULL CHECK (day_of_week BETWEEN 0 AND 6),
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    quota INTEGER NOT NULL DEFAULT 20,
    is_active BOOLEAN NOT NULL DEFAULT true,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

CREATE INDEX idx_doctor_schedules_doctor ON doctor_schedules(doctor_id);
CREATE INDEX idx_doctor_schedules_polyclinic ON doctor_schedules(polyclinic_id);
CREATE INDEX idx_doctor_schedules_day ON doctor_schedules(day_of_week);
```

### 33. visits

```sql
CREATE TABLE visits (
    id BIGSERIAL PRIMARY KEY,
    uuid UUID NOT NULL DEFAULT gen_random_uuid() UNIQUE,
    visit_number VARCHAR(30) NOT NULL UNIQUE,
    patient_id BIGINT NOT NULL REFERENCES patients(id),
    doctor_id BIGINT NOT NULL REFERENCES doctors(id),
    polyclinic_id BIGINT NOT NULL REFERENCES polyclinics(id),
    visit_type_id BIGINT NOT NULL REFERENCES visit_types(id),
    room_id BIGINT NULL REFERENCES rooms(id),
    bed_id BIGINT NULL REFERENCES beds(id),
    schedule_id BIGINT NULL REFERENCES doctor_schedules(id),
    queue_id BIGINT NULL REFERENCES queues(id),
    visit_date DATE NOT NULL,
    visit_time TIME NOT NULL,
    complaint TEXT NULL,
    anamnesis TEXT NULL,
    physical_examination TEXT NULL,
    diagnosis_primary BIGINT NULL REFERENCES icd10_codes(id),
    diagnosis_secondary BIGINT NULL REFERENCES icd10_codes(id),
    diagnosis_text TEXT NULL,
    treatment_plan TEXT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'waiting' CHECK (status IN ('waiting', 'in_progress', 'completed', 'cancelled', 'no_show')),
    admission_date TIMESTAMP NULL,
    discharge_date TIMESTAMP NULL,
    discharge_type VARCHAR(20) NULL CHECK (discharge_type IN ('sembuh', 'membaik', 'pulang_paksa', 'dirujuk', 'meninggal', 'DO')),
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

CREATE INDEX idx_visits_patient ON visits(patient_id);
CREATE INDEX idx_visits_doctor ON visits(doctor_id);
CREATE INDEX idx_visits_polyclinic ON visits(polyclinic_id);
CREATE INDEX idx_visits_date ON visits(visit_date);
CREATE INDEX idx_visits_status ON visits(status);
CREATE INDEX idx_visits_number ON visits(visit_number);
```

### 35. medical_records

```sql
CREATE TABLE medical_records (
    id BIGSERIAL PRIMARY KEY,
    uuid UUID NOT NULL DEFAULT gen_random_uuid() UNIQUE,
    record_number VARCHAR(30) NOT NULL UNIQUE,
    visit_id BIGINT NOT NULL REFERENCES visits(id),
    patient_id BIGINT NOT NULL REFERENCES patients(id),
    doctor_id BIGINT NOT NULL REFERENCES doctors(id),
    subjective TEXT NULL,
    objective TEXT NULL,
    assessment TEXT NULL,
    plan TEXT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

CREATE INDEX idx_medical_records_visit ON medical_records(visit_id);
CREATE INDEX idx_medical_records_patient ON medical_records(patient_id);
CREATE INDEX idx_medical_records_doctor ON medical_records(doctor_id);
```

### 41. prescriptions

```sql
CREATE TABLE prescriptions (
    id BIGSERIAL PRIMARY KEY,
    uuid UUID NOT NULL DEFAULT gen_random_uuid() UNIQUE,
    prescription_number VARCHAR(30) NOT NULL UNIQUE,
    visit_id BIGINT NOT NULL REFERENCES visits(id),
    patient_id BIGINT NOT NULL REFERENCES patients(id),
    doctor_id BIGINT NOT NULL REFERENCES doctors(id),
    prescription_date DATE NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'dispensed', 'partial', 'cancelled')),
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

CREATE INDEX idx_prescriptions_visit ON prescriptions(visit_id);
CREATE INDEX idx_prescriptions_patient ON prescriptions(patient_id);
CREATE INDEX idx_prescriptions_doctor ON prescriptions(doctor_id);
CREATE INDEX idx_prescriptions_date ON prescriptions(prescription_date);
```

### 42. prescription_details

```sql
CREATE TABLE prescription_details (
    id BIGSERIAL PRIMARY KEY,
    prescription_id BIGINT NOT NULL REFERENCES prescriptions(id) ON DELETE CASCADE,
    drug_id BIGINT NOT NULL REFERENCES drugs(id),
    quantity DECIMAL(10,2) NOT NULL,
    unit VARCHAR(20) NOT NULL,
    dosage VARCHAR(100) NOT NULL,
    frequency VARCHAR(100) NOT NULL,
    duration VARCHAR(50) NULL,
    instructions TEXT NULL,
    dispensed_quantity DECIMAL(10,2) NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_prescription_details_prescription ON prescription_details(prescription_id);
CREATE INDEX idx_prescription_details_drug ON prescription_details(drug_id);
```

### 51. drugs

```sql
CREATE TABLE drugs (
    id BIGSERIAL PRIMARY KEY,
    uuid UUID NOT NULL DEFAULT gen_random_uuid() UNIQUE,
    code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(200) NOT NULL,
    generic_name VARCHAR(200) NULL,
    category_id BIGINT NOT NULL REFERENCES drug_categories(id),
    form VARCHAR(50) NOT NULL,
    strength VARCHAR(100) NULL,
    unit VARCHAR(20) NOT NULL,
    manufacturer VARCHAR(200) NULL,
    buy_price DECIMAL(15,2) NOT NULL DEFAULT 0,
    sell_price DECIMAL(15,2) NOT NULL DEFAULT 0,
    min_stock INTEGER NOT NULL DEFAULT 0,
    is_active BOOLEAN NOT NULL DEFAULT true,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

CREATE INDEX idx_drugs_code ON drugs(code);
CREATE INDEX idx_drugs_name ON drugs(name);
CREATE INDEX idx_drugs_category ON drugs(category_id);
```

### 69. invoices

```sql
CREATE TABLE invoices (
    id BIGSERIAL PRIMARY KEY,
    uuid UUID NOT NULL DEFAULT gen_random_uuid() UNIQUE,
    invoice_number VARCHAR(30) NOT NULL UNIQUE,
    visit_id BIGINT NOT NULL REFERENCES visits(id),
    patient_id BIGINT NOT NULL REFERENCES patients(id),
    invoice_date DATE NOT NULL,
    subtotal DECIMAL(15,2) NOT NULL DEFAULT 0,
    discount_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
    tax_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
    total_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
    paid_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
    remaining_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'unpaid' CHECK (status IN ('unpaid', 'partial', 'paid', 'cancelled')),
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

CREATE INDEX idx_invoices_visit ON invoices(visit_id);
CREATE INDEX idx_invoices_patient ON invoices(patient_id);
CREATE INDEX idx_invoices_date ON invoices(invoice_date);
CREATE INDEX idx_invoices_status ON invoices(status);
CREATE INDEX idx_invoices_number ON invoices(invoice_number);
```

### 9. audit_logs

```sql
CREATE TABLE audit_logs (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NULL REFERENCES users(id),
    action VARCHAR(20) NOT NULL CHECK (action IN ('create', 'read', 'update', 'delete', 'login', 'logout', 'export', 'import')),
    module VARCHAR(50) NOT NULL,
    record_id BIGINT NULL,
    old_values JSONB NULL,
    new_values JSONB NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_audit_logs_user ON audit_logs(user_id);
CREATE INDEX idx_audit_logs_action ON audit_logs(action);
CREATE INDEX idx_audit_logs_module ON audit_logs(module);
CREATE INDEX idx_audit_logs_created ON audit_logs(created_at);
```

---

## Index Strategy

### Composite Index

```sql
-- Kunjungan per hari per poli
CREATE INDEX idx_visits_date_polyclinic ON visits(visit_date, polyclinic_id);

-- Stok obat per batch
CREATE INDEX idx_drug_stocks_drug_batch ON drug_stocks(drug_id, batch_id);

-- Jadwal dokter per hari
CREATE INDEX idx_doctor_schedules_doctor_day ON doctor_schedules(doctor_id, day_of_week);
```

### Partial Index

```sql
-- Hanya user aktif
CREATE INDEX idx_users_active ON users(id) WHERE is_active = true AND deleted_at IS NULL;

-- Hanya tagihan belum lunas
CREATE INDEX idx_invoices_unpaid ON invoices(id) WHERE status IN ('unpaid', 'partial');
```

---

## Seed Data

### Default Roles

```sql
INSERT INTO roles (name, slug, description, is_system) VALUES
('Admin Sistem', 'admin', 'Administrator sistem dengan akses penuh', true),
('Dokter', 'doctor', 'Dokter dengan akses klinis', true),
('Perawat', 'nurse', 'Perawat dengan akses keperawatan', true),
('Kasir', 'cashier', 'Kasir dengan akses billing', true),
('Farmasi', 'pharmacist', 'Farmasi dengan akses obat', true),
('Laboran', 'lab', 'Laboran dengan akses laboratorium', true),
('Radiologi', 'radiology', 'Radiologi dengan akses radiologi', true),
('Gudang', 'warehouse', 'Gudang/logistik dengan akses inventaris', true),
('Pasien', 'patient', 'Pasien dengan akses terbatas', true),
('Manajemen', 'management', 'Manajemen dengan akses laporan', true);
```

### Default Permissions

```sql
INSERT INTO permissions (name, slug, module) VALUES
-- Users
('Lihat User', 'users.view', 'users'),
('Tambah User', 'users.create', 'users'),
('Edit User', 'users.update', 'users'),
('Hapus User', 'users.delete', 'users'),
-- Roles
('Lihat Role', 'roles.view', 'roles'),
('Tambah Role', 'roles.create', 'roles'),
('Edit Role', 'roles.update', 'roles'),
('Hapus Role', 'roles.delete', 'roles'),
-- Patients
('Lihat Pasien', 'patients.view', 'patients'),
('Tambah Pasien', 'patients.create', 'patients'),
('Edit Pasien', 'patients.update', 'patients'),
('Hapus Pasien', 'patients.delete', 'patients'),
('Export Pasien', 'patients.export', 'patients'),
-- Doctors
('Lihat Dokter', 'doctors.view', 'doctors'),
('Tambah Dokter', 'doctors.create', 'doctors'),
('Edit Dokter', 'doctors.update', 'doctors'),
('Hapus Dokter', 'doctors.delete', 'doctors'),
-- Medical Records
('Lihat Rekam Medis', 'medical_records.view', 'medical_records'),
('Tambah Rekam Medis', 'medical_records.create', 'medical_records'),
('Edit Rekam Medis', 'medical_records.update', 'medical_records'),
('Cetak Rekam Medis', 'medical_records.print', 'medical_records'),
-- Prescriptions
('Lihat Resep', 'prescriptions.view', 'prescriptions'),
('Tambah Resep', 'prescriptions.create', 'prescriptions'),
('Edit Resep', 'prescriptions.update', 'prescriptions'),
('Dispense Resep', 'prescriptions.dispense', 'prescriptions'),
-- Billing
('Lihat Tagihan', 'billing.view', 'billing'),
('Buat Tagihan', 'billing.create', 'billing'),
('Edit Tagihan', 'billing.update', 'billing'),
('Proses Pembayaran', 'billing.payment', 'billing'),
-- Reports
('Lihat Laporan', 'reports.view', 'reports'),
('Export Laporan', 'reports.export', 'reports'),
-- Audit
('Lihat Audit Log', 'audit.view', 'audit'),
-- Settings
('Lihat Pengaturan', 'settings.view', 'settings'),
('Edit Pengaturan', 'settings.update', 'settings');
```
