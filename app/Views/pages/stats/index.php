<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold">Dashboard Statistik</h1>
    <p class="text-base-content/70">Ringkasan data statistik rumah sakit</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Kunjungan Hari Ini</div>
        <div class="stat-value text-primary"><?= $stats['today']['visits'] ?></div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Pendapatan Hari Ini</div>
        <div class="stat-value text-success">Rp <?= number_format($stats['today']['revenue'], 0, ',', '.') ?></div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Kunjungan Bulan Ini</div>
        <div class="stat-value text-secondary"><?= $stats['month']['visits'] ?></div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Pendapatan Bulan Ini</div>
        <div class="stat-value text-accent">Rp <?= number_format($stats['month']['revenue'], 0, ',', '.') ?></div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Total Pasien</div>
        <div class="stat-value"><?= $stats['total_patients'] ?></div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Invoice Belum Bayar</div>
        <div class="stat-value text-warning"><?= $stats['pending_invoices'] ?></div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Obat Stok Rendah</div>
        <div class="stat-value text-error"><?= $stats['low_stock_drugs'] ?></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <h2 class="card-title">Trend Kunjungan (7 Hari)</h2>
                <a href="/stats/visits" class="btn btn-ghost btn-sm">Detail</a>
            </div>
            <canvas id="visitTrendChart" height="200"></canvas>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <h2 class="card-title">Trend Pendapatan (6 Bulan)</h2>
                <a href="/stats/revenue" class="btn btn-ghost btn-sm">Detail</a>
            </div>
            <canvas id="revenueTrendChart" height="200"></canvas>
        </div>
    </div>
</div>

<div class="card bg-base-100 shadow-md">
    <div class="card-body">
        <div class="flex items-center justify-between mb-4">
            <h2 class="card-title">Top 5 Penyakit</h2>
            <a href="/stats/diseases" class="btn btn-ghost btn-sm">Detail</a>
        </div>
        <canvas id="diseaseChart" height="150"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const visitTrend = <?= json_encode($stats['visit_trend']) ?>;
    new Chart(document.getElementById('visitTrendChart'), {
        type: 'line',
        data: {
            labels: visitTrend.labels,
            datasets: [{
                label: 'Kunjungan',
                data: visitTrend.values,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.3,
                fill: true
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

    const revenueTrend = <?= json_encode($stats['revenue_trend']) ?>;
    new Chart(document.getElementById('revenueTrendChart'), {
        type: 'line',
        data: {
            labels: revenueTrend.labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: revenueTrend.values,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                        }
                    }
                }
            }
        }
    });

    const diseaseData = <?= json_encode($stats['top_diseases']) ?>;
    new Chart(document.getElementById('diseaseChart'), {
        type: 'bar',
        data: {
            labels: diseaseData.labels,
            datasets: [{
                label: 'Jumlah Kasus',
                data: diseaseData.values,
                backgroundColor: [
                    'rgba(239, 68, 68, 0.7)',
                    'rgba(251, 191, 36, 0.7)',
                    'rgba(34, 197, 94, 0.7)',
                    'rgba(59, 130, 246, 0.7)',
                    'rgba(168, 85, 247, 0.7)'
                ],
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
