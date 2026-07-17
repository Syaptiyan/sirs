<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Laporan Kunjungan</h1>
            <p class="text-base-content/70">Data kunjungan pasien</p>
        </div>
        <div class="flex gap-2">
            <form method="post" action="/reports/export-pdf" class="inline">
                <input type="hidden" name="type" value="visits">
                <input type="hidden" name="date_from" value="<?= $report['date_from'] ?>">
                <input type="hidden" name="date_to" value="<?= $report['date_to'] ?>">
                <input type="hidden" name="polyclinic_id" value="<?= $report['polyclinic_id'] ?? '' ?>">
                <button type="submit" class="btn btn-error btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    PDF
                </button>
            </form>
            <form method="post" action="/reports/export-excel" class="inline">
                <input type="hidden" name="type" value="visits">
                <input type="hidden" name="date_from" value="<?= $report['date_from'] ?>">
                <input type="hidden" name="date_to" value="<?= $report['date_to'] ?>">
                <input type="hidden" name="polyclinic_id" value="<?= $report['polyclinic_id'] ?? '' ?>">
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
        <form method="get" action="/reports/visits" class="flex flex-wrap gap-4 items-end">
            <div class="form-control">
                <label class="label"><span class="label-text">Dari Tanggal</span></label>
                <input type="date" name="date_from" value="<?= $report['date_from'] ?>" class="input input-bordered input-sm">
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Sampai Tanggal</span></label>
                <input type="date" name="date_to" value="<?= $report['date_to'] ?>" class="input input-bordered input-sm">
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Poli</span></label>
                <select name="polyclinic_id" class="select select-bordered select-sm">
                    <option value="">Semua Poli</option>
                    <?php foreach ($polyclinics as $poly): ?>
                        <option value="<?= $poly->id ?>" <?= ($report['polyclinic_id'] ?? '') == $poly->id ? 'selected' : '' ?>>
                            <?= esc($poly->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Total Kunjungan</div>
        <div class="stat-value text-primary"><?= $report['summary']['total_visits'] ?></div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Selesai</div>
        <div class="stat-value text-success"><?= $report['summary']['completed'] ?? 0 ?></div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Dalam Proses</div>
        <div class="stat-value text-warning"><?= $report['summary']['in_progress'] ?? 0 ?></div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Dibatalkan</div>
        <div class="stat-value text-error"><?= $report['summary']['cancelled'] ?? 0 ?></div>
    </div>
</div>

<div class="card bg-base-100 shadow-md">
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>No. MR</th>
                        <th>Nama Pasien</th>
                        <th>Dokter</th>
                        <th>Poli</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($report['visits'])): ?>
                        <?php foreach ($report['visits'] as $i => $visit): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= date('d/m/Y', strtotime($visit->visit_date)) ?></td>
                                <td><?= esc($visit->mr_number ?? '-') ?></td>
                                <td><?= esc($visit->patient_name ?? '-') ?></td>
                                <td><?= esc($visit->doctor_name ?? '-') ?></td>
                                <td><?= esc($visit->polyclinic_name ?? '-') ?></td>
                                <td>
                                    <?php
                                    $statusClass = match($visit->status) {
                                        'completed' => 'badge-success',
                                        'in_progress' => 'badge-warning',
                                        'waiting' => 'badge-info',
                                        'cancelled' => 'badge-error',
                                        default => 'badge-ghost'
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= ucfirst($visit->status) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">Tidak ada data</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
