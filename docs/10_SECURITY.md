# 10 - SECURITY

## 1. Authentication Security

### Password Policy

| Rule | Value |
|------|-------|
| Minimum length | 8 karakter |
| Complexity | Huruf besar, huruf kecil, angka, simbol |
| Hashing | Bcrypt (cost factor: 12) |
| History | Tidak boleh sama dengan 5 password terakhir |
| Expiry | 90 hari (configurable) |

### Session Security

| Rule | Value |
|------|-------|
| Session driver | Database |
| Session timeout | 30 menit idle |
| Session regeneration | Setiap login |
| Cookie flags | HttpOnly, Secure, SameSite=Strict |
| Session invalidation | Setelah logout |

### Rate Limiting

| Action | Limit | Window |
|--------|-------|--------|
| Login attempt | 5 | 15 menit |
| Password reset | 3 | 1 jam |
| API request | 100 | 1 menit |
| Registration | 10 | 1 jam |

### Brute Force Protection

- Lock account setelah 5 gagal login
- CAPTCHA setelah 3 gagal login
- Notifikasi email setelah lock
- Admin unlock manual

---

## 2. RBAC (Role-Based Access Control)

### Permission Model

```
User → Role → Permission → Module → Action
```

### Permission Granularity

| Module | View | Create | Update | Delete | Export | Print |
|--------|------|--------|--------|--------|--------|-------|
| Users | ✓ | ✓ | ✓ | ✓ | ✓ | - |
| Patients | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| Medical Records | ✓ | ✓ | ✓ | - | - | ✓ |
| Prescriptions | ✓ | ✓ | ✓ | - | - | ✓ |
| Billing | ✓ | ✓ | ✓ | - | - | ✓ |
| Drugs | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| Lab | ✓ | ✓ | ✓ | - | - | ✓ |
| Radiology | ✓ | ✓ | ✓ | - | - | ✓ |
| Inventory | ✓ | ✓ | ✓ | ✓ | ✓ | - |
| Reports | ✓ | - | - | - | ✓ | ✓ |
| Audit Log | ✓ | - | - | - | ✓ | - |
| Settings | ✓ | - | ✓ | - | - | - |

### Default Role Permissions

**Admin:** Full access semua modul
**Dokter:** View/Edit patients, medical records, prescriptions, schedules
**Perawat:** View/Edit patients, vital signs, nursing notes
**Kasir:** View/Edit billing, payments
**Farmasi:** View/Edit drugs, prescriptions (dispense)
**Laboran:** View/Edit lab orders/results
**Radiologi:** View/Edit radiology orders/results
**Gudang:** View/Edit inventory, items, suppliers
**Pasien:** View own records, appointments
**Manajemen:** View reports, statistics

---

## 3. OWASP Top 10 Prevention

### A01:2021 - Broken Access Control

**Prevention:**
- RBAC enforcement di setiap endpoint
- Server-side authorization check
- Principle of least privilege
- CORS policy ketat
- Disable directory listing
- File access control

### A02:2021 - Cryptographic Failures

**Prevention:**
- TLS 1.3 untuk semua koneksi
- AES-256 untuk data at rest
- Bcrypt untuk password
- Secure random untuk token
- Encrypt sensitive field (NIK, phone)

### A03:2021 - Injection

**Prevention:**
- Parameterized query (CI4 Query Builder)
- Prepared statements
- Input validation dan sanitization
- ORM untuk database access
- Stored procedure untuk operasi kompleks

### A04:2021 - Insecure Design

**Prevention:**
- Threat modeling
- Secure design patterns
- Defense in depth
- Fail secure
- Least privilege principle

### A05:2021 - Security Misconfiguration

**Prevention:**
- Hardened default configuration
- Remove unnecessary features
- Error handling tanpa detail
- Security headers
- Regular security patching

### A06:2021 - Vulnerable Components

**Prevention:**
- Regular dependency audit
- Automated vulnerability scanning
- Pin dependency versions
- Monitor security advisories

### A07:2021 - Identification Failures

**Prevention:**
- Strong authentication
- MFA support
- Session management ketat
- Account lockout policy

### A08:2021 - Software Integrity Failures

**Prevention:**
- Digital signature untuk release
- Integrity check untuk dependencies
- Secure CI/CD pipeline
- Code signing

### A09:2021 - Logging Failures

**Prevention:**
- Comprehensive audit logging
- Log monitoring dan alerting
- Tamper-proof log storage
- Log retention policy

### A10:2021 - SSRF

**Prevention:**
- Input validation untuk URL
- Whitelist allowed domains
- Network segmentation
- Disable unnecessary protocols

---

## 4. CSRF Protection

### Implementation

- CSRF token di setiap form
- Token regeneration per request
- Header validation untuk AJAX
- SameSite cookie attribute

```php
// CI4 built-in CSRF
<form>
    <?= csrf_field() ?>
    <!-- form fields -->
</form>

// AJAX
fetch('/api/endpoint', {
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
});
```

---

## 5. XSS Prevention

### Strategy

- Output encoding di semua template
- Content Security Policy (CSP) header
- Sanitize user input
- HttpOnly cookies
- X-Content-Type-Options: nosniff

### Implementation

```php
// CI4 auto-escapes
<?= esc($userInput) ?>

// CSP Header
Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-{random}'
```

---

## 6. SQL Injection Prevention

### Rules

- SELALU gunakan Query Builder atau Model
- JANGAN pernah concatenate user input ke query
- Parameterized query wajib
- Stored procedure untuk operasi kompleks
- Input validation sebelum query

```php
// CORRECT - Query Builder
$builder->where('email', $email);

// WRONG - Raw query
$db->query("SELECT * FROM users WHERE email = '$email'");
```

---

## 7. Audit Log

### Events Logged

| Event | Module | Detail |
|-------|--------|--------|
| create | Semua | Data baru dibuat |
| update | Semua | Data diubah (old/new values) |
| delete | Semua | Data dihapus |
| login | Auth | User login |
| logout | Auth | User logout |
| failed_login | Auth | Login gagal |
| export | Reports | Data di-export |
| print | Medical | Data di-print |
| access | Medical | Data sensitif diakses |

### Log Structure

```json
{
    "user_id": 1,
    "action": "update",
    "module": "patients",
    "record_id": 123,
    "old_values": {"name": "John"},
    "new_values": {"name": "John Doe"},
    "ip_address": "192.168.1.1",
    "user_agent": "Mozilla/5.0...",
    "description": "Update patient data"
}
```

---

## 8. Encryption

### At Rest

- Database: PostgreSQL TDE (Transparent Data Encryption)
- File: AES-256 encryption untuk file sensitif
- Backup: Encrypted backup

### In Transit

- TLS 1.3 wajib
- HSTS header
- Certificate pinning (mobile)

### Sensitive Fields

| Field | Encryption |
|-------|------------|
| Password | Bcrypt (one-way) |
| NIK | AES-256 (reversible) |
| Phone | AES-256 (reversible) |
| Email | Plain (hashed for search) |
| Medical records | AES-256 (reversible) |

---

## 9. Security Headers

```
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000; includeSubDomains
Content-Security-Policy: default-src 'self'
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: camera=(), microphone=(), geolocation=()
```

---

## 10. Data Privacy

### PII (Personally Identifiable Information)

| Data | Classification | Protection |
|------|---------------|------------|
| NIK | Highly Sensitive | Encrypted |
| Medical Records | Highly Sensitive | Encrypted + Access Log |
| Name | Sensitive | Access Control |
| Phone | Sensitive | Encrypted |
| Email | Sensitive | Access Control |
| Address | Sensitive | Access Control |

### Data Retention

| Data | Retention | Action |
|------|-----------|--------|
| Patient data | 10 tahun | Archive |
| Medical records | 10 tahun | Archive |
| Audit logs | 1 tahun | Archive |
| Sessions | 30 hari | Delete |
| Backups | 1 tahun | Delete |

### Patient Rights

- Right to access own data
- Right to request correction
- Right to request deletion (with legal constraints)
- Right to data portability
- Right to withdraw consent
