<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold">Statistik Kunjungan</h1>
    <p class="text-base-content/70">Data trend kunjungan pasien</p>
</div>

<div class="card bg-base-100 shadow-md mb-6">
    <div class="card-body">
        <form method="get" action="/stats/visits" class="flex flex-wrap gap-4 items-end">
            <div class="form-control">
                <label class="label"><span class="label-text">Periode</span></label>
                <select name="period" class="select select-bordered select-sm">
                    <option value="daily" <?= $period === 'daily' ? 'selected' : '' ?>>Harian</option>
                    <option value="weekly" <?= $period === 'weekly' ? 'selected' : '' ?>>Mingguan</option>
                    <option value="monthly" <?= $period === 'monthly' ? 'selected' : '' ?>>Bulanan</option>
                </select>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Rentang (hari)</span></label>
                <select name="days" class="select select-bordered select-sm">
                    <option value="7" <?= $days === 7 ? 'selected' : '' ?>>7 Hari</option>
                    <option value="14" <?= $days === 14 ? 'selected' : '' ?>>14 Hari</option>
                    <option value="30" <?= $days === 30 ? 'selected' : '' ?>>30 Hari</option>
                    <option value="60" <?= $days === 60 ? 'selected' : '' ?>>60 Hari</option>
                    <option value="90" <?= $days === 90 ? 'selected' : '' ?>>90 Hari</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Total Kunjungan</div>
        <div class="stat-value text-primary"><?= $stats['total'] ?></div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Rata-rata per Hari</div>
        <div class="stat-value text-secondary"><?= $days > 0 ? round($stats['total'] / $days, 1) : 0 ?></div>
    </div>
</div>

<div class="card bg-base-100 shadow-md mb-6">
    <div class="card-body">
        <h2 class="card-title mb-4">Trend Kunjungan</h2>
        <canvas id="visitChart" height="100"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title mb-4">Berdasarkan Status</h2>
            <canvas id="statusChart" height="200"></canvas>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title mb-4">Detail Status</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Jumlah</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats['by_status'] as $status => $count): ?>
                            <tr>
                                <td>
                                    <?php
                                    $statusClass = match($status) {
                                        'completed' => 'badge-success',
                                        'in_progress' => 'badge-warning',
                                        'waiting' => 'badge-info',
                                        'cancelled' => 'badge-error',
                                        default => 'badge-ghost'
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= ucfirst($status) ?></span>
                                </td>
                                <td><?= $count ?></td>
                                <td><?= $stats['total'] > 0 ? round(($count / $stats['total']) * 100, 1) : 0 ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const visitData = <?= json_encode($stats) ?>;

    new Chart(document.getElementById('visitChart'), {
        type: 'line',
        data: {
            labels: visitData.labels,
            datasets: [{
                label: 'Kunjungan',
                data: visitData.values,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
                            return context.parsed.y + ' kunjungan';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    const statusData = <?= json_encode($stats['by_status']) ?>;
    const statusColors = {
        'completed': 'rgb(34, 197, 94)',
        'in_progress': 'rgb(251, 191, 36)',
        'waiting': 'rgb(59, 130, 246)',
        'cancelled': 'rgb(239, 68, 68)',
        'no_show': 'rgb(107, 114, 128)'
    };

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: Object.keys(statusData).map(s => statusColors[s] || 'rgb(156, 163, 175)')
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});
</script>

<?= $this->endSection() ?>
