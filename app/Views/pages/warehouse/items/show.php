<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="<?= base_url('warehouse/items') ?>" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Kembali
        </a>
        <div>
            <h1 class="text-2xl font-bold">Detail Barang</h1>
            <p class="text-base-content/70"><?= esc($item->code) ?> - <?= esc($item->name) ?></p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Info Barang -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg">Informasi Barang</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-base-content/70">Kode</span>
                        <span class="font-mono font-medium"><?= esc($item->code) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-base-content/70">Nama</span>
                        <span class="font-medium"><?= esc($item->name) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-base-content/70">Kategori</span>
                        <span><?= esc($item->category_name ?? '-') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-base-content/70">Satuan</span>
                        <span><?= esc($item->unit) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-base-content/70">Harga Beli</span>
                        <span>Rp <?= number_format($item->buy_price, 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-base-content/70">Stok Minimum</span>
                        <span><?= $item->min_stock ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-base-content/70">Total Stok</span>
                        <span class="font-bold text-lg <?= $item->total_stock <= $item->min_stock ? 'text-error' : 'text-success' ?>">
                            <?= $item->total_stock ?> <?= esc($item->unit) ?>
                        </span>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="flex gap-2">
                    <a href="<?= base_url('warehouse/items/' . $item->uuid . '/edit') ?>" class="btn btn-outline btn-sm">Edit</a>
                </div>
            </div>
        </div>

        <!-- Stok Per Gudang -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg">Stok Per Gudang</h2>
                <?php if (empty($item->stocks)): ?>
                    <p class="text-base-content/50 text-center py-4">Belum ada stok.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="table table-sm w-full">
                            <thead>
                                <tr>
                                    <th>Gudang</th>
                                    <th class="text-right">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($item->stocks as $s): ?>
                                    <tr>
                                        <td><?= esc($s->warehouse_name ?? '-') ?></td>
                                        <td class="text-right font-bold"><?= $s->quantity ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
