# 05 - SYSTEM ARCHITECTURE

## High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                      CLIENT LAYER                           │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐ │
│  │ Landing Page │  │  Dashboard  │  │   Mobile Browser    │ │
│  │  (Public)    │  │  (Auth)     │  │   (Responsive)      │ │
│  └──────┬───────┘  └──────┬──────┘  └──────────┬──────────┘ │
│         │                 │                     │            │
│         └─────────────────┼─────────────────────┘            │
│                           │                                  │
│                    ┌──────▼──────┐                           │
│                    │   Vite +    │                           │
│                    │   Alpine.js │                           │
│                    │   + DaisyUI │                           │
│                    └──────┬──────┘                           │
└───────────────────────────┼──────────────────────────────────┘
                            │ HTTP/HTTPS
┌───────────────────────────┼──────────────────────────────────┐
│                      WEB SERVER                              │
│                    ┌──────▼──────┐                           │
│                    │    Nginx    │                           │
│                    │  (Reverse   │                           │
│                    │   Proxy)    │                           │
│                    └──────┬──────┘                           │
└───────────────────────────┼──────────────────────────────────┘
                            │ FastCGI
┌───────────────────────────┼──────────────────────────────────┐
│                   APPLICATION LAYER                          │
│                    ┌──────▼──────┐                           │
│                    │   PHP-FPM   │                           │
│                    │  (PHP 8.3+) │                           │
│                    └──────┬──────┘                           │
│                           │                                  │
│                    ┌──────▼──────┐                           │
│                    │ CodeIgniter │                           │
│                    │     4       │                           │
│                    └──────┬──────┘                           │
│                           │                                  │
│         ┌─────────────────┼─────────────────┐               │
│         │                 │                 │               │
│  ┌──────▼──────┐  ┌──────▼──────┐  ┌──────▼──────┐        │
│  │ Controllers │  │  Services   │  │  Libraries  │        │
│  │             │  │             │  │             │        │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘        │
│         │                │                 │               │
│         └────────────────┼─────────────────┘               │
│                          │                                  │
│                   ┌──────▼──────┐                           │
│                   │   Models    │                           │
│                   │  (Entities) │                           │
│                   └──────┬──────┘                           │
└──────────────────────────┼──────────────────────────────────┘
                           │
┌──────────────────────────┼──────────────────────────────────┐
│                    DATA LAYER                               │
│              ┌────────────┼────────────┐                    │
│              │            │            │                    │
│       ┌──────▼──────┐ ┌──▼──────┐ ┌──▼──────────┐         │
│       │ PostgreSQL  │ │Supabase │ │   Redis     │         │
│       │ (Supabase)  │ │Storage  │ │  (Cache)    │         │
│       │             │ │         │ │             │         │
│       └─────────────┘ └─────────┘ └─────────────┘         │
└─────────────────────────────────────────────────────────────┘
```

---

## Folder Structure

```
sirs/
├── app/
│   ├── Commands/
│   ├── Config/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── v1/
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── PatientController.php
│   │   │   │   └── ...
│   │   ├── Admin/
│   │   ├── Doctor/
│   │   ├── Nurse/
│   │   ├── Cashier/
│   │   ├── Pharmacist/
│   │   ├── Lab/
│   │   ├── Radiology/
│   │   ├── Warehouse/
│   │   └── Patient/
│   ├── Database/
│   │   ├── Migrations/
│   │   ├── Seeds/
│   │   └── Factories/
│   ├── Entities/
│   ├── Filters/
│   ├── Helpers/
│   ├── Language/
│   ├── Libraries/
│   ├── Models/
│   ├── Services/
│   │   ├── Auth/
│   │   ├── Patient/
│   │   ├── Medical/
│   │   ├── Pharmacy/
│   │   ├── Lab/
│   │   ├── Billing/
│   │   └── ...
│   ├── ThirdParty/
│   └── Views/
│       ├── layouts/
│       │   ├── main.php
│       │   ├── auth.php
│       │   └── landing.php
│       ├── components/
│       │   ├── sidebar.php
│       │   ├── header.php
│       │   ├── footer.php
│       │   ├── modal.php
│       │   └── ...
│       ├── pages/
│       │   ├── auth/
│       │   ├── dashboard/
│       │   ├── patients/
│       │   ├── doctors/
│       │   └── ...
│       ├── errors/
│       └── landing/
├── public/
│   ├── assets/
│   ├── build/ (Vite output)
│   ├── index.php
│   └── .htaccess
├── resources/
│   ├── css/
│   │   └── app.css
│   └── js/
│       ├── app.js
│       ├── alpine/
│       └── components/
├── tests/
│   ├── unit/
│   ├── integration/
│   └── feature/
├── docker/
│   ├── nginx/
│   ├── php/
│   └── docker-compose.yml
├── docs/
├── agents/
├── writable/
├── composer.json
├── package.json
├── vite.config.js
├── tailwind.config.js
├── .env
├── .env.example
└── README.md
```

---

## Architecture Pattern

### MVC + Service Layer

```
Controller → Service → Model → Database
    ↓           ↓
  View      Library
```

### Layer Responsibilities

| Layer | Responsibility |
|-------|---------------|
| Controller | HTTP request/response, validation, routing |
| Service | Business logic, orchestration |
| Model | Data access, query builder |
| Entity | Data structure, casting |
| Library | Reusable utilities |
| View | Presentation (Blade-like syntax) |

### Dependency Injection

Gunakan CI4's built-in DI container:

```php
// Services/Auth/AuthService.php
namespace App\Services\Auth;

use App\Models\UserModel;
use App\Models\SessionModel;

class AuthService
{
    public function __construct(
        private UserModel $userModel,
        private SessionModel $sessionModel
    ) {}

    public function login(string $email, string $password): ?array
    {
        // Business logic
    }
}
```

---

## Authentication Architecture

```
┌─────────────┐     ┌──────────────┐     ┌─────────────┐
│   Login     │────▶│   Auth       │────▶│  Session    │
│   Form      │     │   Service    │     │  Database   │
└─────────────┘     └──────┬───────┘     └─────────────┘
                           │
                    ┌──────▼───────┐
                    │  Middleware   │
                    │  - Auth       │
                    │  - RBAC       │
                    │  - CSRF       │
                    └──────────────┘
```

### RBAC Flow

```
Request → Middleware → Check Permission → Allow/Deny
           ↓
    Route → Controller → Service → Response
```

---

## API Architecture

### REST API Versioning

```
/api/v1/patients
/api/v1/doctors
/api/v1/appointments
```

### Response Format

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

### Error Response

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["Email is required"],
    "password": ["Password must be at least 8 characters"]
  }
}
```

---

## Database Architecture

### Connection

```php
// .env
database.default.hostname = aws-0-ap-southeast-1.pooler.supabase.com
database.default.database = postgres
database.default.username = postgres.xxxx
database.default.password = *****
database.default.DBDriver = Postgre
database.default.port = 5432
database.default.DBDebug = true
```

### Migration Strategy

- Semua perubahan schema via migration
- Rollback support
- Seeder untuk data awal
- Factory untuk testing

---

## Caching Strategy

| Cache | TTL | Purpose |
|-------|-----|---------|
| User session | 30 min | Authentication |
| Permission | 1 hour | RBAC lookup |
| Master data | 5 min | Dropdown data |
| Config | 1 hour | System settings |
| Dashboard stats | 2 min | Performance |

---

## File Storage

### Supabase Storage

```
supabase/
├── avatars/          # Foto profil
├── documents/        # Dokumen pasien
├── lab-results/      # Hasil lab
├── radiology/        # Gambar radiologi
└── reports/          # Laporan export
```

### Upload Flow

```
Client → Validation → Temp Upload → Supabase Storage → Save URL to DB
```

---

## Queue & Background Job

### Use Cases

- Email sending
- Report generation
- Data export
- Backup
- Notification

### Implementation

Gunakan CI4's built-in queue atau cron job untuk task ringan.

---

## Monitoring & Logging

### Log Strategy

| Log | Level | Retention |
|-----|-------|-----------|
| Application | INFO | 30 days |
| Error | ERROR | 90 days |
| Security | WARN | 1 year |
| Audit | INFO | 1 year |
| Access | INFO | 30 days |

### Health Check

```
GET /health
{
  "status": "ok",
  "database": "connected",
  "storage": "connected",
  "cache": "connected",
  "version": "1.0.0"
}
```
