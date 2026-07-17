<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Laporan Laboratorium</h1>
            <p class="text-base-content/70">Data pemeriksaan laboratorium</p>
        </div>
        <div class="flex gap-2">
            <form method="post" action="/reports/export-pdf" class="inline">
                <input type="hidden" name="type" value="lab">
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
                <input type="hidden" name="type" value="lab">
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
        <form method="get" action="/reports/lab" class="flex flex-wrap gap-4 items-end">
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

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Total Order</div>
        <div class="stat-value text-primary"><?= $report['summary']['total_orders'] ?></div>
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
        <div class="stat-title">Menunggu</div>
        <div class="stat-value text-info"><?= $report['summary']['pending'] ?? 0 ?></div>
    </div>
</div>

<div class="card bg-base-100 shadow-md mb-6">
    <div class="card-body">
        <h2 class="card-title">Trend Harian</h2>
        <canvas id="labChart" height="100"></canvas>
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
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($report['lab_orders'])): ?>
                        <?php foreach ($report['lab_orders'] as $i => $order): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= date('d/m/Y', strtotime($order->created_at)) ?></td>
                                <td><?= esc($order->mr_number ?? '-') ?></td>
                                <td><?= esc($order->patient_name ?? '-') ?></td>
                                <td>
                                    <?php
                                    $statusClass = match($order->status ?? 'pending') {
                                        'completed' => 'badge-success',
                                        'in_progress' => 'badge-warning',
                                        'pending' => 'badge-info',
                                        default => 'badge-ghost'
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= ucfirst($order->status ?? 'pending') ?></span>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dailyData = <?= json_encode($report['summary']['by_day'] ?? []) ?>;
    const labels = Object.keys(dailyData);
    const values = Object.values(dailyData);

    new Chart(document.getElementById('labChart'), {
        type: 'bar',
        data: {
            labels: labels.map(d => new Date(d).toLocaleDateString('id-ID', {day: '2-digit', month: 'short'})),
            datasets: [{
                label: 'Jumlah Order',
                data: values,
                backgroundColor: 'rgba(168, 85, 247, 0.5)',
                borderColor: 'rgb(168, 85, 247)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>

<?= $this->endSection() ?>
