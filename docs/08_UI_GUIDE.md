# 08 - UI/UX GUIDE

## Design System

### Framework Stack

| Layer | Technology |
|-------|-----------|
| CSS Framework | Tailwind CSS 3.x |
| Component Library | DaisyUI 4.x |
| JavaScript | Alpine.js 3.x |
| Build Tool | Vite 5.x |
| Icons | Heroicons / Lucide |

### Theme

DaisyUI themes yang digunakan:

| Theme | Usage |
|-------|-------|
| `light` | Default (siang) |
| `dark` | Dark mode (malam) |

Toggle via `data-theme` attribute pada `<html>`.

---

## Color System

### Primary Colors

```css
--p: 259 94% 51%;      /* Primary - Ungu */
--pf: 259 94% 41%;     /* Primary Focus */
--pc: 0 0% 100%;       /* Primary Content */
```

### Secondary Colors

```css
--s: 198 93% 60%;      /* Secondary - Biru */
--sf: 198 93% 50%;     /* Secondary Focus */
--sc: 0 0% 100%;       /* Secondary Content */
```

### Accent Colors

```css
--a: 174 60% 51%;      /* Accent - Teal */
--af: 174 60% 41%;     /* Accent Focus */
--ac: 0 0% 100%;       /* Accent Content */
```

### Neutral Colors

```css
--n: 218 18% 12%;      /* Neutral */
--nf: 218 18% 8%;      /* Neutral Focus */
--nc: 0 0% 100%;       /* Neutral Content */
```

### Status Colors

| Status | DaisyUI Class | Usage |
|--------|---------------|-------|
| Info | `badge-info`, `alert-info` | Informasi |
| Success | `badge-success`, `alert-success` | Berhasil |
| Warning | `badge-warning`, `alert-warning` | Peringatan |
| Error | `badge-error`, `alert-error` | Error/gagal |

---

## Typography

### Font Stack

```css
font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
```

### Scale

| Element | Class | Size |
|---------|-------|------|
| H1 | `text-3xl font-bold` | 30px |
| H2 | `text-2xl font-bold` | 24px |
| H3 | `text-xl font-semibold` | 20px |
| H4 | `text-lg font-semibold` | 18px |
| Body | `text-base` | 16px |
| Small | `text-sm` | 14px |
| Caption | `text-xs` | 12px |

---

## Layout Structure

### Main Layout

```
┌─────────────────────────────────────────────┐
│ Header (sticky)                              │
├──────────┬──────────────────────────────────┤
│          │                                  │
│ Sidebar  │        Content Area              │
│ (fixed)  │                                  │
│          │   ┌──────────────────────────┐   │
│          │   │ Page Header (breadcrumb) │   │
│          │   ├──────────────────────────┤   │
│          │   │                          │   │
│          │   │      Page Content        │   │
│          │   │                          │   │
│          │   └──────────────────────────┘   │
│          │                                  │
└──────────┴──────────────────────────────────┘
```

### Sidebar

- **Width:** `w-64` (256px), collapsible ke `w-16` (64px)
- **Position:** Fixed left
- **Background:** `bg-base-200`
- **Content:** Logo, menu items, user info
- **Collapse:** Toggle button, simpan state di localStorage
- **Mobile:** Overlay/drawer

### Header

- **Height:** `h-16` (64px)
- **Position:** Sticky top
- **Background:** `bg-base-100`
- **Content:** Sidebar toggle, search, notifications, user dropdown, dark mode toggle

### Content Area

- **Padding:** `p-6`
- **Max width:** Full width within sidebar
- **Background:** `bg-base-100`

---

## Component Standards

### Buttons

```html
<!-- Primary -->
<button class="btn btn-primary">Simpan</button>

<!-- Secondary -->
<button class="btn btn-secondary">Batal</button>

<!-- Ghost -->
<button class="btn btn-ghost">Kembali</button>

<!-- Icon -->
<button class="btn btn-square btn-ghost">
  <svg>...</svg>
</button>

<!-- With Icon -->
<button class="btn btn-primary">
  <svg>...</svg> Tambah
</button>

<!-- Sizes -->
<button class="btn btn-sm btn-primary">Small</button>
<button class="btn btn-md btn-primary">Medium</button>
<button class="btn btn-lg btn-primary">Large</button>
```

### Cards

```html
<div class="card bg-base-100 shadow-lg">
  <div class="card-body">
    <h2 class="card-title">Judul Card</h2>
    <p>Konten card...</p>
    <div class="card-actions justify-end">
      <button class="btn btn-primary">Aksi</button>
    </div>
  </div>
</div>
```

### Tables

```html
<div class="overflow-x-auto">
  <table class="table table-zebra">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>John Doe</td>
        <td>
          <div class="flex gap-2">
            <button class="btn btn-sm btn-ghost">Edit</button>
            <button class="btn btn-sm btn-error btn-ghost">Hapus</button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</div>
```

### Forms

```html
<div class="form-control">
  <label class="label">
    <span class="label-text">Nama Lengkap</span>
    <span class="label-text-alt text-error">*</span>
  </label>
  <input type="text" class="input input-bordered" placeholder="Masukkan nama" />
  <label class="label">
    <span class="label-text-alt text-error">Error message</span>
  </label>
</div>
```

### Modals

```html>
<dialog id="my_modal" class="modal">
  <div class="modal-box">
    <h3 class="font-bold text-lg">Konfirmasi</h3>
    <p class="py-4">Apakah Anda yakin?</p>
    <div class="modal-action">
      <button class="btn">Batal</button>
      <button class="btn btn-primary">Ya</button>
    </div>
  </div>
  <form method="dialog" class="modal-backdrop">
    <button>close</button>
  </form>
</dialog>
```

### Alerts

```html
<div class="alert alert-success">
  <svg>...</svg>
  <span>Data berhasil disimpan!</span>
</div>

<div class="alert alert-error">
  <svg>...</svg>
  <span>Terjadi kesalahan!</span>
</div>
```

### Badges

```html
<span class="badge badge-success">Aktif</span>
<span class="badge badge-error">Nonaktif</span>
<span class="badge badge-warning">Pending</span>
<span class="badge badge-info">Proses</span>
```

---

## Page Templates

### List Page

```
┌─────────────────────────────────────────────┐
│ Page Title                    [+ Tambah Baru]│
├─────────────────────────────────────────────┤
│ [Search............] [Filter▼] [Export]      │
├─────────────────────────────────────────────┤
│ ┌─────────────────────────────────────────┐ │
│ │ Table                                   │ │
│ │ ...                                     │ │
│ └─────────────────────────────────────────┘ │
│                                             │
│ Showing 1-20 of 100   [< 1 2 3 ... 5 >]    │
└─────────────────────────────────────────────┘
```

### Form Page

```
┌─────────────────────────────────────────────┐
│ [< Kembali]  Page Title                     │
├─────────────────────────────────────────────┤
│ ┌─────────────────────────────────────────┐ │
│ │ Card                                    │ │
│ │ ┌─────────────────────────────────────┐ │ │
│ │ │ Section 1: Data Utama               │ │ │
│ │ │ [Form fields in 2-column grid]      │ │ │
│ │ └─────────────────────────────────────┘ │ │
│ │ ┌─────────────────────────────────────┐ │ │
│ │ │ Section 2: Data Tambahan            │ │ │
│ │ │ [Form fields in 2-column grid]      │ │ │
│ │ └─────────────────────────────────────┘ │ │
│ └─────────────────────────────────────────┘ │
│                                             │
│ [Batal]                          [Simpan]   │
└─────────────────────────────────────────────┘
```

### Detail Page

```
┌─────────────────────────────────────────────┐
│ [< Kembali]  Page Title   [Edit] [Hapus]    │
├─────────────────────────────────────────────┤
│ ┌─────────────────────────────────────────┐ │
│ │ Card: Informasi Utama                   │ │
│ │ [Label: Value] in 2-column grid         │ │
│ └─────────────────────────────────────────┘ │
│ ┌─────────────────────────────────────────┐ │
│ │ Tabs: [Tab 1] [Tab 2] [Tab 3]          │ │
│ │ ┌─────────────────────────────────────┐ │ │
│ │ │ Tab Content                         │ │ │
│ │ └─────────────────────────────────────┘ │ │
│ └─────────────────────────────────────────┘ │
└─────────────────────────────────────────────┘
```

---

## Dark Mode Implementation

### Toggle Component

```html
<label class="swap swap-rotate">
  <input type="checkbox" class="theme-controller" value="dark" />
  <!-- Sun icon -->
  <svg class="swap-off fill-current w-6 h-6">...</svg>
  <!-- Moon icon -->
  <svg class="swap-on fill-current w-6 h-6">...</svg>
</label>
```

### JavaScript (Alpine.js)

```javascript
// resources/js/app.js
document.addEventListener('alpine:init', () => {
  Alpine.store('theme', {
    current: localStorage.getItem('theme') || 'light',
    toggle() {
      this.current = this.current === 'light' ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', this.current);
      localStorage.setItem('theme', this.current);
    }
  });
});
```

---

## Responsive Breakpoints

| Breakpoint | Width | Usage |
|------------|-------|-------|
| `sm:` | 640px | Mobile landscape |
| `md:` | 768px | Tablet |
| `lg:` | 1024px | Desktop |
| `xl:` | 1280px | Large desktop |
| `2xl:` | 1536px | Extra large |

### Mobile Strategy

- Sidebar: Drawer/overlay di mobile
- Table: Horizontal scroll atau card view
- Form: Single column di mobile
- Cards: Full width di mobile
- Navigation: Bottom nav atau hamburger menu

---

## Glassmorphism Accent

Untuk elemen tertentu (card hero, sidebar, modal):

```css
.glass {
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 16px;
}

/* Dark mode */
[data-theme="dark"] .glass {
  background: rgba(0, 0, 0, 0.25);
  border: 1px solid rgba(255, 255, 255, 0.1);
}
```

---

## Loading States

### Skeleton Loading

```html
<div class="animate-pulse">
  <div class="h-4 bg-base-300 rounded w-3/4 mb-2"></div>
  <div class="h-4 bg-base-300 rounded w-1/2"></div>
</div>
```

### Spinner

```html
<span class="loading loading-spinner loading-md"></span>
```

### Page Loading

```html
<div class="flex items-center justify-center min-h-screen">
  <span class="loading loading-lg"></span>
</div>
```

---

## Empty States

```html
<div class="flex flex-col items-center justify-center py-12">
  <svg class="w-16 h-16 text-base-300 mb-4">...</svg>
  <h3 class="text-lg font-semibold mb-2">Belum ada data</h3>
  <p class="text-base-content/60 mb-4">Mulai dengan menambahkan data baru</p>
  <button class="btn btn-primary">+ Tambah</button>
</div>
```

---

## Landing Page Design

### Sections

1. **Hero** - Judul utama, CTA, ilustrasi
2. **Layanan** - Grid icon + deskripsi layanan
3. **Tentang** - Profil rumah sakit
4. **Dokter** - Carousel/grid profil dokter
5. **Statistik** - Counter animasi
6. **Kontak** - Form + info kontak + maps
7. **Footer** - Links, sosmed, copyright

### Style

- Full-width hero dengan gradient/glassmorphism
- Card dengan shadow-lg dan hover effect
- Animasi scroll dengan Alpine.js
- Responsive grid layout
