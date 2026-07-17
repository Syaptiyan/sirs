# 13 - CHANGELOG

## Format

```
## [version] - YYYY-MM-DD

### Added
- Fitur baru

### Changed
- Perubahan fitur

### Deprecated
- Fitur yang akan dihapus

### Removed
- Fitur yang dihapus

### Fixed
- Bug fix

### Security
- Patch keamanan
```

---

## [Unreleased]

### Added - 2026-07-17
- Initial project setup (CodeIgniter 4, Tailwind CSS, Alpine.js, DaisyUI, Vite)
- PHP 8.3.32 and Composer 2.10.2 installed
- Docker development environment (Nginx, PHP-FPM, Redis, PostgreSQL)
- 50+ database migrations for all modules
- 60+ models with soft deletes, UUID, timestamps
- 30+ services with business logic
- 25+ controllers with CRUD operations
- 80+ views with responsive design
- Authentication system (login, register, forgot password, reset password)
- RBAC system with 10 roles and 48 permissions
- Dashboard for 8 different roles
- Audit logging system
- Notification system
- Landing page with glassmorphism design
- Patient module with MRN generation
- Doctor module with specialization
- Nurse module
- Polyclinic module
- Room module with bed management
- Settings module
- Doctor schedule module with conflict detection
- Queue module with real-time display
- Registration module (Rawat Jalan, Rawat Inap, IGD)
- Triage module with vital signs
- Medical records module with SOAP format
- ICD-10 integration with 50 codes
- Prescription module with digital workflow
- Pharmacy module with stock management
- Laboratory module with templates
- Radiology module with image upload
- Billing module with auto invoice generation
- Payment module with 5 payment methods
- Warehouse module with supplier management
- Inventory module for assets
- Reports module with PDF/Excel export
- Statistics module with Chart.js visualization
- User management with activity log
- Role management with permission editor
- Security (CSRF, XSS, SQL injection prevention, rate limiting, AES-256 encryption)
- Testing (unit, feature, security tests)
- DevOps (Docker, backup scripts, health check monitoring)

### Added
- Initial project setup
- Documentation framework

### Security - 2026-07-18
- Paksa semua request aman (CSP, redirect HTTPS)
- Proteksi Honeypot diaktifkan secara global
- Proteksi CSRF diaktifkan secara global (berbasis session)
- Filter security headers (X-Frame-Options, X-Content-Type-Options, dll.)
- Filter InvalidChars untuk memblokir input berbahaya
- Rate limiting pada route auth (login 10/900s, register/forgot/reset 5/3600s)
- Filter data sensitif pada exception traces
- Keamanan cookie: Secure, HttpOnly, SameSite=Strict
- Handler cache Redis (fallback ke file)
- Pembuatan kunci enkripsi AES-256 otomatis
- Layanan UUID generation menggunakan random_bytes(16)

### Fixed - 2026-07-18
- Konfigurasi `Security` — tipe nullable untuk properti `$tokenRandomize`, `$regenerate`
- Konfigurasi `Cache` — handler Redis yang benar + fallback file
- Konfigurasi `Session` — menambahkan properti `?string $savePath = null`
- Konfigurasi `Filters` — CSRF dipindah ke global `before` filter, semua filter keamanan diaktifkan
- Konfigurasi `Exceptions` — diisi `$sensitiveDataInTrace`
- Konfigurasi `Services` — mendaftarkan layanan `uuid`
- `UserModel` — cast `$allowedFields` ke string untuk mencegah error tipe SQL
- Migrasi `CreateUsersTable` — menghapus tabel `users` milik Shield terlebih dahulu (FK-safe) sebelum membuat skema aplikasi
- Migrasi `CreateSettingsTable` — menggunakan `ifNotExists` untuk menghindari konflik dengan library Settings; `down()` no-op
- Konflik migrasi database dengan library CodeIgniter Shield & Settings diselesaikan
- Bootstrap database tes — menjalankan semua migrasi sekali sebelum suite PHPUnit (in-memory SQLite3)
- Semua kelas tes menggunakan `$refresh = false` untuk mencegah kegagalan cascade regress

---

## [1.0.0] - 2024-XX-XX (Target)

### Phase 1: Foundation (Minggu 1-2)

#### Added
- Project setup (CodeIgniter 4, Tailwind CSS, Alpine.js, DaisyUI, Vite)
- Docker development environment
- PostgreSQL database connection
- Database migration system
- Authentication system:
  - Login
  - Register
  - Email verification
  - Forgot password
  - Reset password
- RBAC system:
  - Role management
  - Permission management
  - Middleware
- Dashboard per role:
  - Admin dashboard
  - Doctor dashboard
  - Nurse dashboard
  - Cashier dashboard
  - Pharmacy dashboard
  - Lab dashboard
  - Management dashboard
- Landing page
- Audit log system
- Notification system
- Base layout:
  - Sidebar navigation
  - Header
  - Footer
  - Dark mode toggle

### Phase 2: Master Data (Minggu 3-4)

#### Added
- Patient module:
  - Patient registration
  - MRN auto-generation
  - Patient CRUD
  - Patient search & filter
  - Allergies management
  - Chronic diseases management
  - Document upload
  - Export data
- Doctor module:
  - Doctor CRUD
  - Specialization management
  - Doctor mapping to user
- Nurse module:
  - Nurse CRUD
  - Assignment management
- Polyclinic module:
  - Poli CRUD
  - Doctor-poli mapping
- Room module:
  - Room CRUD
  - Room types
  - Bed management
- System settings:
  - Hospital profile
  - System parameters

### Phase 3: Registration & Queue (Minggu 5-6)

#### Added
- Doctor schedule module:
  - Schedule CRUD
  - Conflict detection
  - Holiday management
- Queue module:
  - Queue number generation
  - Real-time status
  - Display system
  - Priority queue
- Registration module:
  - Outpatient registration
  - Inpatient admission
  - Emergency registration
- Triage module:
  - Emergency triage
  - Priority classification

### Phase 4: Medical Records (Minggu 7-8)

#### Added
- Medical records module:
  - SOAP format
  - Template system
  - History view
  - Print records
- Diagnosis module:
  - ICD-10 integration
  - Primary & secondary diagnosis
- Action module:
  - Medical actions
  - Action codes
- Prescription module:
  - Digital prescription
  - Drug details
  - Print prescription

### Phase 5: Pharmacy, Lab & Radiology (Minggu 9-10)

#### Added
- Pharmacy module:
  - Drug management
  - Stock management
  - Drug receipt
  - Drug distribution
  - Expiry monitoring
  - Minimum stock alert
  - Stock opname
- Laboratory module:
  - Lab orders
  - Result input
  - Templates
  - Normal ranges
  - Print results
- Radiology module:
  - Radiology orders
  - Result input
  - Image upload
  - Templates

### Phase 6: Billing & Inventory (Minggu 11-12)

#### Added
- Billing module:
  - Auto invoice generation
  - Item details
  - Discount management
  - Insurance claims
- Payment module:
  - Multiple payment methods
  - Payment processing
  - Receipt printing
- Warehouse module:
  - Stock management
  - Receipt & distribution
  - Stock opname
- Supplier module:
  - Supplier CRUD
  - Purchase orders
- Inventory module:
  - Asset management
  - Maintenance tracking
  - Depreciation

### Phase 7: Reports & Optimization (Minggu 13-14)

#### Added
- Reports module:
  - Visit reports
  - Revenue reports
  - Pharmacy reports
  - Lab reports
  - Inventory reports
  - PDF/Excel export
- Statistics module:
  - Visit statistics
  - Disease statistics
  - Revenue statistics
  - Interactive charts
- Performance optimization
- Security audit
- Production deployment setup
- Final documentation

---

## Version History

| Version | Date | Description |
|---------|------|-------------|
| 1.0.0 | TBD | Initial release |
| 0.1.0 | - | Development started |
