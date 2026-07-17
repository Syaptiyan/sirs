<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Laporan Inventaris</h1>
            <p class="text-base-content/70">Data stok barang inventaris</p>
        </div>
        <div class="flex gap-2">
            <form method="post" action="/reports/export-pdf" class="inline">
                <input type="hidden" name="type" value="inventory">
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
                <input type="hidden" name="type" value="inventory">
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
        <form method="get" action="/reports/inventory" class="flex flex-wrap gap-4 items-end">
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
        <div class="stat-title">Total Item</div>
        <div class="stat-value text-primary"><?= $report['summary']['total_items'] ?></div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Stok Rendah</div>
        <div class="stat-value text-warning"><?= $report['summary']['low_stock'] ?></div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Habis</div>
        <div class="stat-value text-error"><?= $report['summary']['out_of_stock'] ?></div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Total Nilai</div>
        <div class="stat-value text-success">Rp <?= number_format($report['summary']['total_value'], 0, ',', '.') ?></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title">Berdasarkan Kategori</h2>
            <canvas id="categoryChart" height="200"></canvas>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title">Status Stok</h2>
            <canvas id="stockChart" height="200"></canvas>
        </div>
    </div>
</div>

<div class="card bg-base-100 shadow-md">
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Item</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Minimum</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($report['items'])): ?>
                        <?php foreach ($report['items'] as $i => $item): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($item->name ?? '-') ?></td>
                                <td><?= esc($item->category ?? '-') ?></td>
                                <td><?= $item->stock ?? 0 ?></td>
                                <td><?= $item->minimum_stock ?? 0 ?></td>
                                <td>Rp <?= number_format($item->price ?? 0, 0, ',', '.') ?></td>
                                <td>Rp <?= number_format(($item->stock ?? 0) * ($item->price ?? 0), 0, ',', '.') ?></td>
                                <td>
                                    <?php
                                    $stock = $item->stock ?? 0;
                                    $minStock = $item->minimum_stock ?? 0;
                                    if ($stock <= 0) {
                                        echo '<span class="badge badge-error">Habis</span>';
                                    } elseif ($stock <= $minStock) {
                                        echo '<span class="badge badge-warning">Rendah</span>';
                                    } else {
                                        echo '<span class="badge badge-success">Aman</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center">Tidak ada data</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryData = <?= json_encode($report['summary']['by_category'] ?? []) ?>;
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(categoryData),
            datasets: [{
                data: Object.values(categoryData),
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(34, 197, 94)',
                    'rgb(251, 191, 36)',
                    'rgb(239, 68, 68)',
                    'rgb(168, 85, 247)',
                    'rgb(236, 72, 153)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    const totalItems = <?= $report['summary']['total_items'] ?>;
    const lowStock = <?= $report['summary']['low_stock'] ?>;
    const outOfStock = <?= $report['summary']['out_of_stock'] ?>;
    const normal = totalItems - lowStock - outOfStock;

    new Chart(document.getElementById('stockChart'), {
        type: 'pie',
        data: {
            labels: ['Normal', 'Stok Rendah', 'Habis'],
            datasets: [{
                data: [normal, lowStock, outOfStock],
                backgroundColor: [
                    'rgb(34, 197, 94)',
                    'rgb(251, 191, 36)',
                    'rgb(239, 68, 68)'
                ]
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
