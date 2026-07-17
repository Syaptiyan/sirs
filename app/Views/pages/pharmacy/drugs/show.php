<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="<?= base_url('pharmacy/drugs') ?>" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Kembali
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold"><?= esc($drug->name) ?></h1>
            <p class="text-base-content/70">Kode: <?= esc($drug->code) ?></p>
        </div>
        <a href="<?= base_url('pharmacy/drugs/' . $drug->uuid . '/edit') ?>" class="btn btn-outline btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            Edit
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Drug Info -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg">Informasi Obat</h2>
                <div class="overflow-x-auto">
                    <table class="table">
                        <tr><td class="font-medium w-40">Kode</td><td><?= esc($drug->code) ?></td></tr>
                        <tr><td class="font-medium">Nama</td><td><?= esc($drug->name) ?></td></tr>
                        <tr><td class="font-medium">Nama Generik</td><td><?= esc($drug->generic_name ?? '-') ?></td></tr>
                        <tr><td class="font-medium">Kategori</td><td><?= esc($drug->category_name ?? '-') ?></td></tr>
                        <tr><td class="font-medium">Bentuk</td><td><span class="badge badge-sm"><?= esc($drug->form) ?></span></td></tr>
                        <tr><td class="font-medium">Kekuatan</td><td><?= esc($drug->strength ?? '-') ?></td></tr>
                        <tr><td class="font-medium">Satuan</td><td><?= esc($drug->unit) ?></td></tr>
                        <tr><td class="font-medium">Pabrikan</td><td><?= esc($drug->manufacturer ?? '-') ?></td></tr>
                        <tr><td class="font-medium">Harga Beli</td><td>Rp <?= number_format($drug->buy_price, 0, ',', '.') ?></td></tr>
                        <tr><td class="font-medium">Harga Jual</td><td>Rp <?= number_format($drug->sell_price, 0, ',', '.') ?></td></tr>
                        <tr><td class="font-medium">Stok Minimum</td><td><?= $drug->min_stock ?></td></tr>
                        <tr><td class="font-medium">Total Stok</td><td><span class="font-bold text-lg"><?= $drug->total_stock ?></span> <?= esc($drug->unit) ?></td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Batches -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg">Batch</h2>
                <?php if (empty($drug->batches)): ?>
                    <p class="text-base-content/50 py-4">Belum ada batch.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>No. Batch</th>
                                    <th>Kadaluarsa</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($drug->batches as $batch): ?>
                                    <tr>
                                        <td class="font-mono text-sm"><?= esc($batch->batch_number) ?></td>
                                        <td><?= esc($batch->expiry_date) ?></td>
                                        <td><?= $batch->quantity ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Stock Details -->
    <div class="card bg-base-100 shadow-sm mt-6">
        <div class="card-body">
            <h2 class="card-title text-lg">Detail Stok per Lokasi</h2>
            <?php if (empty($drug->stocks)): ?>
                <p class="text-base-content/50 py-4">Belum ada stok.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Batch</th>
                                <th>Kadaluarsa</th>
                                <th>Lokasi</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($drug->stocks as $stock): ?>
                                <tr>
                                    <td class="font-mono text-sm"><?= esc($stock->batch_number ?? '-') ?></td>
                                    <td><?= esc($stock->expiry_date ?? '-') ?></td>
                                    <td><?= esc($stock->location ?? '-') ?></td>
                                    <td><?= $stock->quantity ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
