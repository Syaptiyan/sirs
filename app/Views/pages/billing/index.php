<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold">Tagihan</h1>
            <p class="text-base-content/70">Kelola data tagihan pasien</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Filter -->
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body p-4">
            <form action="<?= base_url('billing') ?>" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="form-control sm:w-48">
                    <select name="status" class="select select-bordered w-full">
                        <option value="">Semua Status</option>
                        <option value="unpaid" <?= ($status ?? '') === 'unpaid' ? 'selected' : '' ?>>Belum Dibayar</option>
                        <option value="partial" <?= ($status ?? '') === 'partial' ? 'selected' : '' ?>>Sebagian</option>
                        <option value="paid" <?= ($status ?? '') === 'paid' ? 'selected' : '' ?>>Lunas</option>
                        <option value="cancelled" <?= ($status ?? '') === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Filter
                    </button>
                    <a href="<?= base_url('billing') ?>" class="btn btn-ghost">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card bg-base-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Tagihan</th>
                        <th>Pasien</th>
                        <th>No. Kunjungan</th>
                        <th>Tanggal</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Dibayar</th>
                        <th class="text-right">Sisa</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($invoices)): ?>
                        <tr>
                            <td colspan="10" class="text-center py-8 text-base-content/50">
                                Tidak ada data tagihan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($invoices as $i => $invoice): ?>
                            <tr>
                                <td><?= ($page - 1) * $perPage + $i + 1 ?></td>
                                <td>
                                    <a href="<?= base_url('billing/' . $invoice->uuid) ?>" class="font-bold text-primary hover:underline">
                                        <?= esc($invoice->invoice_number) ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="font-medium"><?= esc($invoice->patient_name ?? '-') ?></div>
                                    <div class="text-xs text-base-content/50"><?= esc($invoice->mrn ?? '') ?></div>
                                </td>
                                <td>
                                    <span class="font-mono text-sm"><?= esc($invoice->visit_number ?? '-') ?></span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($invoice->invoice_date)) ?></td>
                                <td class="text-right font-mono">Rp <?= number_format($invoice->total_amount, 0, ',', '.') ?></td>
                                <td class="text-right font-mono">Rp <?= number_format($invoice->paid_amount, 0, ',', '.') ?></td>
                                <td class="text-right font-mono">Rp <?= number_format($invoice->remaining_amount, 0, ',', '.') ?></td>
                                <td>
                                    <?php
                                    $statusLabels = [
                                        'unpaid'    => 'Belum Dibayar',
                                        'partial'   => 'Sebagian',
                                        'paid'      => 'Lunas',
                                        'cancelled' => 'Dibatalkan',
                                    ];
                                    $statusClasses = [
                                        'unpaid'    => 'badge-error',
                                        'partial'   => 'badge-warning',
                                        'paid'      => 'badge-success',
                                        'cancelled' => 'badge-ghost',
                                    ];
                                    ?>
                                    <span class="badge badge-sm <?= $statusClasses[$invoice->status] ?? 'badge-ghost' ?>">
                                        <?= $statusLabels[$invoice->status] ?? $invoice->status ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <a href="<?= base_url('billing/' . $invoice->uuid) ?>" class="btn btn-sm btn-ghost btn-square" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                        <?php if (in_array($invoice->status, ['unpaid', 'partial'])): ?>
                                            <a href="<?= base_url('payments/process?invoice_uuid=' . $invoice->uuid) ?>" class="btn btn-sm btn-success btn-square" title="Bayar">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="card-body p-4">
                <div class="flex justify-center">
                    <div class="join">
                        <?php if ($page > 1): ?>
                            <a href="<?= base_url('billing?' . http_build_query(array_merge($_GET, ['page' => $page - 1]))) ?>" class="join-item btn btn-sm">&laquo;</a>
                        <?php endif; ?>

                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                            <?php if ($p === $page): ?>
                                <span class="join-item btn btn-sm btn-active"><?= $p ?></span>
                            <?php else: ?>
                                <a href="<?= base_url('billing?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>" class="join-item btn btn-sm"><?= $p ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="<?= base_url('billing?' . http_build_query(array_merge($_GET, ['page' => $page + 1]))) ?>" class="join-item btn btn-sm">&raquo;</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
