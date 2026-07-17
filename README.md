# SIRS - Sistem Informasi Rawat Jalan

Sistem informasi rawat jalan berbasis CodeIgniter 4.

## Persyaratan

- Docker & Docker Compose
- Git

## Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd sirs
```

### 2. Setup Environment

```bash
cp env .env
```

Edit `.env` dan sesuaikan konfigurasi:

```env
app.baseURL = 'http://localhost/'

database.default.hostname = postgres
database.default.database = sirs
database.default.username = postgres
database.default.password = secret
database.default.DBDriver = Postgre
database.default.port = 5432
```

### 3. Jalankan dengan Docker

```bash
docker compose up -d
```

### 4. Install Dependencies

```bash
docker compose exec app composer install
```

### 5. Jalankan Migrasi

```bash
docker compose exec app php spark migrate --all
```

### 6. Akses Aplikasi

Buka browser dan akses: `http://localhost`

## Struktur Docker

```
docker/
├── php/
│   ├── Dockerfile
│   └── local.ini
└── nginx/
    └── conf.d/
        └── app.conf
```

### Services

| Service  | Port  | Keterangan          |
|----------|-------|---------------------|
| nginx    | 80    | Web server          |
| postgres | 5432  | Database            |
| redis    | 6379  | Cache & Session     |
| app      | 9000  | PHP-FPM             |

## Scripts

### Backup Database

```bash
bash scripts/backup-db.sh
```

### Backup Files

```bash
bash scripts/backup-files.sh
```

### Deploy

```bash
bash scripts/deploy.sh
```

### Health Check

```bash
bash scripts/health-check.sh
```

## Monitoring

Health check endpoint tersedia di `/health`:

```bash
curl http://localhost/health
```

Response:

```json
{
    "status": "ok",
    "timestamp": "2026-07-17T22:00:00+07:00",
    "version": "1.0.0",
    "database": {"status": "ok"},
    "cache": {"status": "ok"}
}
```

## Pengembangan

### Menjalankan secara lokal

```bash
composer install
php spark serve
```

### Menjalankan Test

```bash
php spark test
```

## Dokumentasi

- [CodeIgniter 4 User Guide](https://codeigniter.com/user_guide/)
- [Docker Documentation](https://docs.docker.com/)

## Lisensi

MIT License
