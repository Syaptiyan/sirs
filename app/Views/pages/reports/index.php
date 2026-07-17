<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold">Laporan</h1>
    <p class="text-base-content/70">Halaman utama untuk mengakses berbagai laporan</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <a href="/reports/visits" class="card bg-base-100 shadow-md hover:shadow-lg transition-shadow">
        <div class="card-body">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="card-title">Kunjungan</h2>
                    <p class="text-sm text-base-content/70">Laporan jumlah kunjungan pasien</p>
                </div>
            </div>
        </div>
    </a>

    <a href="/reports/revenue" class="card bg-base-100 shadow-md hover:shadow-lg transition-shadow">
        <div class="card-body">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-success/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="card-title">Pendapatan</h2>
                    <p class="text-sm text-base-content/70">Laporan pendapatan rumah sakit</p>
                </div>
            </div>
        </div>
    </a>

    <a href="/reports/pharmacy" class="card bg-base-100 shadow-md hover:shadow-lg transition-shadow">
        <div class="card-body">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-secondary/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <div>
                    <h2 class="card-title">Farmasi</h2>
                    <p class="text-sm text-base-content/70">Laporan resep dan obat</p>
                </div>
            </div>
        </div>
    </a>

    <a href="/reports/lab" class="card bg-base-100 shadow-md hover:shadow-lg transition-shadow">
        <div class="card-body">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-accent/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <div>
                    <h2 class="card-title">Laboratorium</h2>
                    <p class="text-sm text-base-content/70">Laporan pemeriksaan lab</p>
                </div>
            </div>
        </div>
    </a>

    <a href="/reports/inventory" class="card bg-base-100 shadow-md hover:shadow-lg transition-shadow">
        <div class="card-body">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-warning/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <h2 class="card-title">Inventaris</h2>
                    <p class="text-sm text-base-content/70">Laporan stok barang</p>
                </div>
            </div>
        </div>
    </a>
</div>

<?= $this->endSection() ?>
