# 14 - DOCUMENTATION INDEX

## Daftar Dokumen

| No | File | Deskripsi | Status |
|----|------|-----------|--------|
| 00 | [PROJECT_OVERVIEW.md](00_PROJECT_OVERVIEW.md) | Visi, misi, target, scope proyek | ✅ Selesai |
| 01 | [PRD.md](01_PRD.md) | Product Requirements Document | ✅ Selesai |
| 02 | [RULES.md](02_RULES.md) | Coding standard, database rule, git flow, security rule, UI rule | ✅ Selesai |
| 03 | [ROADMAP.md](03_ROADMAP.md) | Roadmap pengembangan 7 phase | ✅ Selesai |
| 04 | [TASKS.md](04_TASKS.md) | Daftar 500+ task | ✅ Selesai |
| 05 | [SYSTEM_ARCHITECTURE.md](05_SYSTEM_ARCHITECTURE.md) | Arsitektur sistem, folder structure | ✅ Selesai |
| 06 | [DATABASE.md](06_DATABASE.md) | ERD, 88 tabel, schema detail | ✅ Selesai |
| 07 | [API.md](07_API.md) | REST API documentation | ✅ Selesai |
| 08 | [UI_GUIDE.md](08_UI_GUIDE.md) | Panduan UI/UX, design system | ✅ Selesai |
| 09 | [FEATURES.md](09_FEATURES.md) | Dokumentasi fitur lengkap | ✅ Selesai |
| 10 | [SECURITY.md](10_SECURITY.md) | RBAC, OWASP, enkripsi, audit | ✅ Selesai |
| 11 | [TESTING.md](11_TESTING.md) | Strategi testing, contoh test | ✅ Selesai |
| 12 | [DEPLOYMENT.md](12_DEPLOYMENT.md) | Docker, Nginx, backup, monitoring | ✅ Selesai |
| 13 | [CHANGELOG.md](13_CHANGELOG.md) | Riwayat perubahan | ✅ Selesai |
| 14 | [DOCUMENTATION.md](14_DOCUMENTATION.md) | Index dokumentasi (file ini) | ✅ Selesai |

---

## Agents Documentation

| No | File | Role | Status |
|----|------|------|--------|
| 01 | [chief.md](../agents/chief.md) | Chief AI / Project Manager | ✅ Selesai |
| 02 | [analyst.md](../agents/analyst.md) | Business Analyst | ✅ Selesai |
| 03 | [architect.md](../agents/architect.md) | Software Architect | ✅ Selesai |
| 04 | [database.md](../agents/database.md) | Database Engineer | ✅ Selesai |
| 05 | [backend.md](../agents/backend.md) | Backend Engineer | ✅ Selesai |
| 06 | [frontend.md](../agents/frontend.md) | Frontend Engineer | ✅ Selesai |
| 07 | [uiux.md](../agents/uiux.md) | UI/UX Designer | ✅ Selesai |
| 08 | [qa.md](../agents/qa.md) | QA Engineer | ✅ Selesai |
| 09 | [security.md](../agents/security.md) | Security Engineer | ✅ Selesai |
| 10 | [documentation.md](../agents/documentation.md) | Documentation Engineer | ✅ Selesai |
| 11 | [devops.md](../agents/devops.md) | DevOps Engineer | ✅ Selesai |

---

## Document Relationship

```
PROJECT_OVERVIEW (00)
    ├── PRD (01) - Requirements dari overview
    ├── RULES (02) - Standar yang berlaku
    ├── ROADMAP (03) - Timeline dari scope
    │
    ├── ARCHITECTURE (05) - Desain teknis
    │   ├── DATABASE (06) - Schema database
    │   ├── API (07) - Endpoint REST
    │   └── UI_GUIDE (08) - Panduan UI
    │
    ├── FEATURES (09) - Detail fitur
    │   └── TASKS (04) - Task dari fitur
    │
    ├── SECURITY (10) - Keamanan
    ├── TESTING (11) - Testing
    ├── DEPLOYMENT (12) - Deployment
    │
    ├── CHANGELOG (13) - Riwayat
    └── DOCUMENTATION (14) - Index
```

---

## Cara Membaca Dokumentasi

1. **Mulai dari PROJECT_OVERVIEW** untuk memahami proyek secara keseluruhan
2. **Baca PRD** untuk memahami kebutuhan fungsional dan non-fungsional
3. **Pelajari RULES** untuk memahami standar yang berlaku
4. **Lihat ROADMAP** untuk memahami timeline pengembangan
5. **Gunakan TASKS** sebagai checklist pengerjaan
6. **Rujuk ARCHITECTURE, DATABASE, API** untuk implementasi teknis
7. **Ikuti UI_GUIDE** untuk konsistensi tampilan
8. **Baca FEATURES** untuk detail setiap fitur
9. **Terapkan SECURITY** untuk keamanan
10. **Ikuti TESTING** untuk quality assurance
11. **Gunakan DEPLOYMENT** untuk production

---

## Kontribusi

### Update Dokumentasi

1. Buat branch `docs/nama-update`
2. Edit file dokumentasi terkait
3. Update CHANGELOG
4. Submit PR untuk review
5. Merge setelah approval

### Aturan Penulisan

- Gunakan Bahasa Indonesia
- Format Markdown standar
- Konsisten dengan struktur yang ada
- Sertakan contoh kode jika relevan
- Update index jika menambah dokumen baru
