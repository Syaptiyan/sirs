<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold">Statistik Penyakit</h1>
    <p class="text-base-content/70">Data frekuensi penyakit berdasarkan diagnosis</p>
</div>

<div class="card bg-base-100 shadow-md mb-6">
    <div class="card-body">
        <form method="get" action="/stats/diseases" class="flex flex-wrap gap-4 items-end">
            <div class="form-control">
                <label class="label"><span class="label-text">Limit</span></label>
                <select name="limit" class="select select-bordered select-sm">
                    <option value="5" <?= $limit === 5 ? 'selected' : '' ?>>Top 5</option>
                    <option value="10" <?= $limit === 10 ? 'selected' : '' ?>>Top 10</option>
                    <option value="15" <?= $limit === 15 ? 'selected' : '' ?>>Top 15</option>
                    <option value="20" <?= $limit === 20 ? 'selected' : '' ?>>Top 20</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        </form>
    </div>
</div>

<div class="stat bg-base-100 shadow rounded-box mb-6">
    <div class="stat-title">Total Records</div>
    <div class="stat-value text-primary"><?= $stats['total_records'] ?></div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title mb-4">Top Penyakit (Bar Chart)</h2>
            <canvas id="diseaseBarChart" height="300"></canvas>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title mb-4">Distribusi Penyakit</h2>
            <canvas id="diseasePieChart" height="300"></canvas>
        </div>
    </div>
</div>

<div class="card bg-base-100 shadow-md">
    <div class="card-body">
        <h2 class="card-title mb-4">Detail Data</h2>
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Diagnosis</th>
                        <th>Jumlah Kasus</th>
                        <th>Persentase</th>
                        <th>Visualisasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($stats['labels'])): ?>
                        <?php foreach ($stats['labels'] as $i => $label): ?>
                            <tr>
                                <td><span class="badge badge-primary"><?= $i + 1 ?></span></td>
                                <td><?= esc($label) ?></td>
                                <td><?= $stats['values'][$i] ?></td>
                                <td><?= $stats['total_records'] > 0 ? round(($stats['values'][$i] / $stats['total_records']) * 100, 1) : 0 ?>%</td>
                                <td>
                                    <div class="w-full bg-base-200 rounded-full h-2.5">
                                        <div class="bg-primary h-2.5 rounded-full" style="width: <?= $stats['total_records'] > 0 ? round(($stats['values'][$i] / max($stats['values'])) * 100) : 0 ?>%"></div>
                                    </div>
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
    const diseaseData = <?= json_encode($stats) ?>;

    const colors = [
        'rgba(239, 68, 68, 0.7)',
        'rgba(251, 191, 36, 0.7)',
        'rgba(34, 197, 94, 0.7)',
        'rgba(59, 130, 246, 0.7)',
        'rgba(168, 85, 247, 0.7)',
        'rgba(236, 72, 153, 0.7)',
        'rgba(20, 184, 166, 0.7)',
        'rgba(245, 158, 11, 0.7)',
        'rgba(99, 102, 241, 0.7)',
        'rgba(156, 163, 175, 0.7)'
    ];

    new Chart(document.getElementById('diseaseBarChart'), {
        type: 'bar',
        data: {
            labels: diseaseData.labels,
            datasets: [{
                label: 'Jumlah Kasus',
                data: diseaseData.values,
                backgroundColor: colors.slice(0, diseaseData.labels.length),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('diseasePieChart'), {
        type: 'doughnut',
        data: {
            labels: diseaseData.labels,
            datasets: [{
                data: diseaseData.values,
                backgroundColor: colors.slice(0, diseaseData.labels.length)
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 8
                    }
                }
            }
        }
    });
});
</script>

<?= $this->endSection() ?>
