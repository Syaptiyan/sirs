<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Peringatan Stok</h1>
        <p class="text-base-content/70">Monitor stok rendah dan obat yang akan kadaluarsa</p>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Low Stock -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                    Stok Rendah
                    <?php if (!empty($lowStock)): ?>
                        <span class="badge badge-warning"><?= count($lowStock) ?></span>
                    <?php endif; ?>
                </h2>

                <?php if (empty($lowStock)): ?>
                    <div class="text-center py-8 text-base-content/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Semua stok dalam kondisi aman.
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Obat</th>
                                    <th>Stok</th>
                                    <th>Min</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lowStock as $drug): ?>
                                    <tr class="hover">
                                        <td>
                                            <a href="<?= base_url('pharmacy/drugs/' . $drug->uuid) ?>" class="font-mono text-sm text-primary hover:underline">
                                                <?= esc($drug->code) ?>
                                            </a>
                                        </td>
                                        <td><?= esc($drug->name) ?></td>
                                        <td>
                                            <span class="font-bold text-error"><?= $drug->total_stock ?></span>
                                        </td>
                                        <td><?= $drug->min_stock ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Expiring Soon -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Akan Kadaluarsa (90 hari)
                    <?php if (!empty($expiringSoon)): ?>
                        <span class="badge badge-error"><?= count($expiringSoon) ?></span>
                    <?php endif; ?>
                </h2>

                <?php if (empty($expiringSoon)): ?>
                    <div class="text-center py-8 text-base-content/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Tidak ada obat yang akan kadaluarsa.
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Obat</th>
                                    <th>Batch</th>
                                    <th>Kadaluarsa</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($expiringSoon as $batch): ?>
                                    <tr class="hover">
                                        <td class="font-mono text-sm"><?= esc($batch->drug_code ?? '-') ?></td>
                                        <td><?= esc($batch->drug_name ?? '-') ?></td>
                                        <td class="font-mono text-sm"><?= esc($batch->batch_number) ?></td>
                                        <td>
                                            <?php
                                            $daysLeft = (strtotime($batch->expiry_date) - time()) / 86400;
                                            $badgeClass = $daysLeft <= 30 ? 'badge-error' : ($daysLeft <= 60 ? 'badge-warning' : 'badge-info');
                                            ?>
                                            <span class="badge badge-sm <?= $badgeClass ?>"><?= esc($batch->expiry_date) ?></span>
                                        </td>
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
</div>
<?= $this->endSection() ?>
