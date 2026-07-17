<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold">Antrian Hari Ini</h1>
            <p class="text-base-content/70">Kelola antrian pasien</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= base_url('queues/display') ?>" class="btn btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                Display
            </a>
            <a href="<?= base_url('queues/create') ?>" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Antrian
            </a>
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
            <form action="<?= base_url('queues') ?>" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="form-control sm:w-56">
                    <select name="polyclinic_id" class="select select-bordered w-full">
                        <option value="">Semua Poliklinik</option>
                        <?php foreach ($polyclinics as $poly): ?>
                            <option value="<?= $poly->id ?>" <?= ($polyclinicId ?? '') == $poly->id ? 'selected' : '' ?>>
                                <?= esc($poly->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-control sm:w-48">
                    <select name="status" class="select select-bordered w-full">
                        <option value="">Semua Status</option>
                        <option value="waiting" <?= ($status ?? '') === 'waiting' ? 'selected' : '' ?>>Menunggu</option>
                        <option value="called" <?= ($status ?? '') === 'called' ? 'selected' : '' ?>>Dipanggil</option>
                        <option value="in_progress" <?= ($status ?? '') === 'in_progress' ? 'selected' : '' ?>>Sedang Dilayani</option>
                        <option value="completed" <?= ($status ?? '') === 'completed' ? 'selected' : '' ?>>Selesai</option>
                        <option value="skipped" <?= ($status ?? '') === 'skipped' ? 'selected' : '' ?>>Dilewati</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Filter
                    </button>
                    <a href="<?= base_url('queues') ?>" class="btn btn-ghost">Reset</a>
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
                        <th>No. Antrian</th>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Poliklinik</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($queues)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-8 text-base-content/50">
                                Tidak ada antrian hari ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($queues as $i => $queue): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <div class="font-bold text-primary"><?= esc($queue->queue_number) ?></div>
                                </td>
                                <td>
                                    <div class="font-medium"><?= esc($queue->patient_name ?? '-') ?></div>
                                    <div class="text-xs text-base-content/50"><?= esc($queue->mrn ?? '') ?></div>
                                </td>
                                <td>dr. <?= esc($queue->doctor_name ?? '-') ?></td>
                                <td>
                                    <span class="badge badge-primary badge-outline"><?= esc($queue->polyclinic_name ?? '-') ?></span>
                                </td>
                                <td>
                                    <?php
                                    $priorityLabels = [0 => 'Normal', 1 => 'Tinggi', 2 => 'Darurat'];
                                    $priorityClasses = [0 => 'badge-ghost', 1 => 'badge-warning', 2 => 'badge-error'];
                                    ?>
                                    <span class="badge badge-sm <?= $priorityClasses[$queue->priority] ?? 'badge-ghost' ?>">
                                        <?= $priorityLabels[$queue->priority] ?? 'Normal' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $statusLabels = [
                                        'waiting' => 'Menunggu',
                                        'called' => 'Dipanggil',
                                        'in_progress' => 'Sedang Dilayani',
                                        'completed' => 'Selesai',
                                        'skipped' => 'Dilewati',
                                    ];
                                    $statusClasses = [
                                        'waiting' => 'badge-info',
                                        'called' => 'badge-warning',
                                        'in_progress' => 'badge-primary',
                                        'completed' => 'badge-success',
                                        'skipped' => 'badge-ghost',
                                    ];
                                    ?>
                                    <span class="badge badge-sm <?= $statusClasses[$queue->status] ?? 'badge-ghost' ?>">
                                        <?= $statusLabels[$queue->status] ?? $queue->status ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <?php if ($queue->status === 'waiting'): ?>
                                            <form action="<?= base_url('queues/' . $queue->uuid . '/call') ?>" method="POST" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-warning btn-square" title="Panggil">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                                </button>
                                            </form>
                                            <form action="<?= base_url('queues/' . $queue->uuid . '/skip') ?>" method="POST" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-ghost btn-square" title="Lewati">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if ($queue->status === 'called'): ?>
                                            <form action="<?= base_url('queues/' . $queue->uuid . '/complete') ?>" method="POST" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-success btn-square" title="Selesai">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if ($queue->status === 'skipped'): ?>
                                            <form action="<?= base_url('queues/' . $queue->uuid . '/recall') ?>" method="POST" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-warning btn-square" title="Panggil Ulang">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
