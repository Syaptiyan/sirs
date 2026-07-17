# 02 - RULES & STANDARDS

## 1. Coding Standard

### PHP (CodeIgniter 4)

- Ikuti PSR-12 coding style
- Gunakan namespace sesuai struktur CI4
- PascalCase untuk class, camelCase untuk method dan variable
- Prefix private/protected property dengan underscore (_)
- Type hinting wajib untuk parameter dan return type
- DocBlock wajib untuk setiap class dan method public
- Hindari God Class (max 300 baris per class)
- Single Responsibility Principle

```php
<?php

namespace App\Controllers;

use App\Services\PatientService;

class PatientController extends BaseController
{
    private PatientService $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    public function index(): string
    {
        $patients = $this->patientService->getAll();
        return view('patients/index', ['patients' => $patients]);
    }
}
```

### JavaScript (Alpine.js)

- Gunakan Alpine.js untuk interaksi ringan
- Hindari jQuery
- camelCase untuk variable dan function
- Pisahkan logic ke file terpisah jika kompleks
- Gunakan Vite untuk bundling

### CSS (Tailwind + DaisyUI)

- Utility-first dengan Tailwind
- Gunakan DaisyUI component class untuk konsistensi
- Hindari custom CSS kecuali diperlukan
- Dark mode dengan class strategy

---

## 2. Database Rule

### Naming Convention

| Object | Convention | Contoh |
|--------|------------|--------|
| Table | snake_case, plural | `patients`, `medical_records` |
| Column | snake_case | `created_at`, `patient_name` |
| Primary Key | `id` | `id` |
| Foreign Key | `{singular_table}_id` | `patient_id`, `doctor_id` |
| Index | `idx_{table}_{column}` | `idx_patients_mrn` |
| Unique | `uq_{table}_{column}` | `uq_users_email` |
| Foreign Key Constraint | `fk_{table}_{ref_table}` | `fk_visits_patients` |

### Rules

- Semua tabel wajib punya `id`, `created_at`, `updated_at`
- Soft delete dengan `deleted_at` (nullable)
- Gunakan UUID untuk public-facing ID
- Integer ID untuk internal (performance)
- Wajib index untuk kolom yang sering di-query
- Foreign key dengan ON DELETE RESTRICT (default)
- Enum gunakan VARCHAR + CHECK constraint
- Decimal untuk monetary (15,2)

---

## 3. Git Flow

### Branch Strategy

```
main (production)
├── develop (staging)
│   ├── feature/feature-name
│   ├── bugfix/bug-description
│   └── hotfix/issue-description
└── release/v1.0.0
```

### Branch Naming

| Prefix | Contoh |
|--------|--------|
| feature/ | feature/patient-registration |
| bugfix/ | bugfix/login-redirect |
| hotfix/ | hotfix/security-patch |
| release/ | release/v1.0.0 |

### Commit Message

```
<type>(<scope>): <description>

[optional body]

[optional footer]
```

**Type:**
- `feat`: Fitur baru
- `fix`: Bug fix
- `docs`: Dokumentasi
- `style`: Formatting (tidak mengubah logic)
- `refactor`: Refactoring
- `test`: Testing
- `chore`: Maintenance

**Contoh:**
```
feat(patient): add patient registration form
fix(auth): fix login redirect loop
docs(api): update endpoint documentation
```

### Pull Request

- Wajib 1 reviewer sebelum merge
- CI/CD harus pass
- Branch up-to-date dengan target
- Description lengkap dengan context

---

## 4. Security Rule

### Authentication

- Password minimum 8 karakter, kombinasi huruf, angka, simbol
- Bcrypt dengan cost factor 12
- Session timeout 30 menit idle
- Rate limiting: 5 attempt per 15 menit per IP
- HTTPS wajib di production
- Secure cookie flags (HttpOnly, Secure, SameSite=Strict)

### Authorization

- RBAC dengan granularity per action (CRUD)
- Default deny, explicit allow
- Principle of least privilege
- Validasi permission di setiap endpoint

### Data Protection

- Encrypt sensitive data at rest (AES-256)
- TLS 1.3 untuk data in transit
- SQL injection prevention (parameterized query / query builder)
- XSS prevention (output encoding)
- CSRF token di setiap form

### Audit

- Log semua create, update, delete
- Log login/logout
- Log akses data sensitif
- Retensi log minimal 1 tahun

---

## 5. UI Rule

### Design System

- **Framework:** Tailwind CSS + DaisyUI
- **Theme:** Light + Dark mode
- **Style:** Glassmorphism accent
- **Typography:** System font stack
- **Spacing:** 4px grid system
- **Border Radius:** `rounded-box` (DaisyUI)

### Color Palette

| Token | Light | Dark |
|-------|-------|------|
| Primary | `primary` | `primary` |
| Secondary | `secondary` | `secondary` |
| Accent | `accent` | `accent` |
| Neutral | `neutral` | `neutral` |
| Base | `base-100` | `base-100` |
| Info | `info` | `info` |
| Success | `success` | `success` |
| Warning | `warning` | `warning` |
| Error | `error` | `error` |

### Component Standard

- Gunakan DaisyUI component (`btn`, `card`, `modal`, `table`, dll)
- Konsisten padding: `p-4` untuk card, `p-6` untuk section
- Gap antar elemen: `gap-4` atau `gap-6`
- Shadow: `shadow-lg` untuk card utama
- Responsive breakpoints: `sm:`, `md:`, `lg:`, `xl:`

### Dark Mode

- Toggle di header/sidebar
- Simpan preference di localStorage
- Default mengikuti system preference
- Gunakan `data-theme` attribute (DaisyUI)

### Accessibility

- Semantic HTML
- ARIA labels untuk interactive elements
- Color contrast ratio minimal 4.5:1
- Keyboard navigation support
- Focus visible indicators

### Responsive

- Mobile-first approach
- Sidebar collapse di mobile
- Table horizontal scroll di mobile
- Form stack di mobile
- Touch-friendly target (min 44x44px)
