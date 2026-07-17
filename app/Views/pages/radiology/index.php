<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold">Order Radiologi</h1>
            <p class="text-base-content/70">Kelola order pemeriksaan radiologi</p>
        </div>
        <a href="<?= base_url('radiology/create') ?>" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Order
        </a>
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
            <form action="<?= base_url('radiology') ?>" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="form-control sm:w-48">
                    <select name="status" class="select select-bordered w-full">
                        <option value="">Semua Status</option>
                        <option value="pending" <?= ($status ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="in_progress" <?= ($status ?? '') === 'in_progress' ? 'selected' : '' ?>>Diproses</option>
                        <option value="completed" <?= ($status ?? '') === 'completed' ? 'selected' : '' ?>>Selesai</option>
                        <option value="cancelled" <?= ($status ?? '') === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Filter
                    </button>
                    <a href="<?= base_url('radiology') ?>" class="btn btn-ghost">Reset</a>
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
                        <th>No. Order</th>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($result['orders'])): ?>
                        <tr>
                            <td colspan="7" class="text-center py-8 text-base-content/50">
                                Tidak ada order radiologi ditemukan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($result['orders'] as $i => $order): ?>
                            <tr>
                                <td><?= ($result['page'] - 1) * $result['perPage'] + $i + 1 ?></td>
                                <td>
                                    <div class="font-bold text-primary"><?= esc($order->order_number) ?></div>
                                </td>
                                <td>
                                    <div class="font-medium"><?= esc($order->patient_name ?? '-') ?></div>
                                    <div class="text-xs text-base-content/50"><?= esc($order->mrn ?? '') ?></div>
                                </td>
                                <td>dr. <?= esc($order->doctor_name ?? '-') ?></td>
                                <td><?= esc($order->order_date) ?></td>
                                <td>
                                    <?php
                                    $statusLabels = [
                                        'pending'    => 'Pending',
                                        'in_progress'=> 'Diproses',
                                        'completed'  => 'Selesai',
                                        'cancelled'  => 'Dibatalkan',
                                    ];
                                    $statusClasses = [
                                        'pending'    => 'badge-warning',
                                        'in_progress'=> 'badge-info',
                                        'completed'  => 'badge-success',
                                        'cancelled'  => 'badge-ghost',
                                    ];
                                    ?>
                                    <span class="badge badge-sm <?= $statusClasses[$order->status] ?? 'badge-ghost' ?>">
                                        <?= $statusLabels[$order->status] ?? $order->status ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <a href="<?= base_url('radiology/' . $order->uuid) ?>" class="btn btn-sm btn-ghost btn-square" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($result['totalPages'] > 1): ?>
        <div class="flex justify-center mt-6">
            <div class="join">
                <?php for ($p = 1; $p <= $result['totalPages']; $p++): ?>
                    <a href="<?= base_url('radiology?page=' . $p . '&status=' . urlencode($status ?? '')) ?>"
                       class="join-item btn btn-sm <?= $p === $result['page'] ? 'btn-active' : '' ?>">
                        <?= $p ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
