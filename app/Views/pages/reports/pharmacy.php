<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Laporan Farmasi</h1>
            <p class="text-base-content/70">Data resep dan obat</p>
        </div>
        <div class="flex gap-2">
            <form method="post" action="/reports/export-pdf" class="inline">
                <input type="hidden" name="type" value="pharmacy">
                <input type="hidden" name="date_from" value="<?= $report['date_from'] ?>">
                <input type="hidden" name="date_to" value="<?= $report['date_to'] ?>">
                <button type="submit" class="btn btn-error btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    PDF
                </button>
            </form>
            <form method="post" action="/reports/export-excel" class="inline">
                <input type="hidden" name="type" value="pharmacy">
                <input type="hidden" name="date_from" value="<?= $report['date_from'] ?>">
                <input type="hidden" name="date_to" value="<?= $report['date_to'] ?>">
                <button type="submit" class="btn btn-success btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Excel
                </button>
            </form>
        </div>
    </div>
</div>

<div class="card bg-base-100 shadow-md mb-6">
    <div class="card-body">
        <form method="get" action="/reports/pharmacy" class="flex flex-wrap gap-4 items-end">
            <div class="form-control">
                <label class="label"><span class="label-text">Dari Tanggal</span></label>
                <input type="date" name="date_from" value="<?= $report['date_from'] ?>" class="input input-bordered input-sm">
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Sampai Tanggal</span></label>
                <input type="date" name="date_to" value="<?= $report['date_to'] ?>" class="input input-bordered input-sm">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Total Resep</div>
        <div class="stat-value text-primary"><?= $report['summary']['total_prescriptions'] ?></div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Sudah Diserahkan</div>
        <div class="stat-value text-success"><?= $report['summary']['dispensed'] ?? 0 ?></div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Menunggu</div>
        <div class="stat-value text-warning"><?= $report['summary']['pending'] ?? 0 ?></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title text-warning">Stok Rendah</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra table-sm">
                    <thead>
                        <tr>
                            <th>Nama Obat</th>
                            <th>Stok</th>
                            <th>Minimum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($report['low_stock'])): ?>
                            <?php foreach ($report['low_stock'] as $drug): ?>
                                <tr>
                                    <td><?= esc($drug->name ?? '-') ?></td>
                                    <td><span class="badge badge-warning"><?= $drug->stock ?? 0 ?></span></td>
                                    <td><?= $drug->minimum_stock ?? 0 ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center">Semua stok aman</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title text-error">Akan Kedaluwarsa</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra table-sm">
                    <thead>
                        <tr>
                            <th>Nama Obat</th>
                            <th>Tgl Kedaluwarsa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($report['expiring'])): ?>
                            <?php foreach ($report['expiring'] as $drug): ?>
                                <tr>
                                    <td><?= esc($drug->name ?? '-') ?></td>
                                    <td><span class="badge badge-error"><?= date('d/m/Y', strtotime($drug->expiry_date)) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="2" class="text-center">Tidak ada obat yang akan kedaluwarsa</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card bg-base-100 shadow-md">
    <div class="card-body">
        <h2 class="card-title">Daftar Resep</h2>
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>No. MR</th>
                        <th>Nama Pasien</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($report['prescriptions'])): ?>
                        <?php foreach ($report['prescriptions'] as $i => $prescription): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= date('d/m/Y', strtotime($prescription->created_at)) ?></td>
                                <td><?= esc($prescription->mr_number ?? '-') ?></td>
                                <td><?= esc($prescription->patient_name ?? '-') ?></td>
                                <td>
                                    <?php
                                    $statusClass = match($prescription->status ?? 'pending') {
                                        'dispensed' => 'badge-success',
                                        'pending' => 'badge-warning',
                                        default => 'badge-ghost'
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= ucfirst($prescription->status ?? 'pending') ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
