<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold">Statistik Pendapatan</h1>
    <p class="text-base-content/70">Data trend pendapatan rumah sakit</p>
</div>

<div class="card bg-base-100 shadow-md mb-6">
    <div class="card-body">
        <form method="get" action="/stats/revenue" class="flex flex-wrap gap-4 items-end">
            <div class="form-control">
                <label class="label"><span class="label-text">Periode</span></label>
                <select name="period" class="select select-bordered select-sm">
                    <option value="daily" <?= $period === 'daily' ? 'selected' : '' ?>>Harian</option>
                    <option value="weekly" <?= $period === 'weekly' ? 'selected' : '' ?>>Mingguan</option>
                    <option value="monthly" <?= $period === 'monthly' ? 'selected' : '' ?>>Bulanan</option>
                </select>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Rentang (bulan)</span></label>
                <select name="months" class="select select-bordered select-sm">
                    <option value="3" <?= $months === 3 ? 'selected' : '' ?>>3 Bulan</option>
                    <option value="6" <?= $months === 6 ? 'selected' : '' ?>>6 Bulan</option>
                    <option value="12" <?= $months === 12 ? 'selected' : '' ?>>12 Bulan</option>
                    <option value="24" <?= $months === 24 ? 'selected' : '' ?>>24 Bulan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Total Pendapatan</div>
        <div class="stat-value text-success">Rp <?= number_format($stats['total'], 0, ',', '.') ?></div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Rata-rata per Periode</div>
        <div class="stat-value text-primary">Rp <?= number_format($stats['average'], 0, ',', '.') ?></div>
    </div>
</div>

<div class="card bg-base-100 shadow-md mb-6">
    <div class="card-body">
        <h2 class="card-title mb-4">Trend Pendapatan</h2>
        <canvas id="revenueChart" height="100"></canvas>
    </div>
</div>

<div class="card bg-base-100 shadow-md">
    <div class="card-body">
        <h2 class="card-title mb-4">Detail Data</h2>
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Pendapatan</th>
                        <th>% dari Total</th>
                        <th>Visualisasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($stats['labels'])): ?>
                        <?php foreach ($stats['labels'] as $i => $label): ?>
                            <tr>
                                <td><?= esc($label) ?></td>
                                <td>Rp <?= number_format($stats['values'][$i], 0, ',', '.') ?></td>
                                <td><?= $stats['total'] > 0 ? round(($stats['values'][$i] / $stats['total']) * 100, 1) : 0 ?>%</td>
                                <td>
                                    <div class="w-full bg-base-200 rounded-full h-2.5">
                                        <div class="bg-success h-2.5 rounded-full" style="width: <?= max($stats['values']) > 0 ? round(($stats['values'][$i] / max($stats['values'])) * 100) : 0 ?>%"></div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const revenueData = <?= json_encode($stats) ?>;

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: revenueData.labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: revenueData.values,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.3,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000000) {
                                return 'Rp ' + (value / 1000000000).toFixed(1) + 'M';
                            } else if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                            }
                            return 'Rp ' + value;
                        }
                    }
                }
            }
        }
    });
});
</script>

<?= $this->endSection() ?>
