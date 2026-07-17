<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold">Data Poliklinik</h1>
            <p class="text-base-content/70">Kelola data poliklinik rumah sakit</p>
        </div>
        <a href="<?= base_url('polyclinics/create') ?>" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Poliklinik
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

    <!-- Filter & Search -->
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body p-4">
            <form action="<?= base_url('polyclinics') ?>" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="form-control flex-1">
                    <input type="text" name="search" value="<?= esc($filters['search'] ?? '') ?>" placeholder="Cari nama poliklinik, kode, atau lokasi..." class="input input-bordered w-full" />
                </div>
                <div class="form-control sm:w-48">
                    <select name="is_active" class="select select-bordered w-full">
                        <option value="">Semua Status</option>
                        <option value="1" <?= ($filters['is_active'] ?? '') === '1' ? 'selected' : '' ?>>Aktif</option>
                        <option value="0" <?= ($filters['is_active'] ?? '') === '0' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Cari
                    </button>
                    <a href="<?= base_url('polyclinics') ?>" class="btn btn-ghost">Reset</a>
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
                        <th>Kode</th>
                        <th>Nama Poliklinik</th>
                        <th>Lokasi</th>
                        <th>Kuota Harian</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($polyclinics)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-8 text-base-content/50">
                                Tidak ada data poliklinik ditemukan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($polyclinics as $i => $polyclinic): ?>
                            <tr>
                                <td><?= ($meta['page'] ?? 1) * ($meta['per_page'] ?? 20) - ($meta['per_page'] ?? 20) + $i + 1 ?></td>
                                <td>
                                    <span class="badge badge-ghost font-mono"><?= esc($polyclinic->code) ?></span>
                                </td>
                                <td>
                                    <div class="font-medium"><?= esc($polyclinic->name) ?></div>
                                    <?php if ($polyclinic->description): ?>
                                        <div class="text-xs text-base-content/50 truncate max-w-xs"><?= esc($polyclinic->description) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-sm"><?= esc($polyclinic->location ?? '-') ?></td>
                                <td class="text-center">
                                    <span class="badge badge-outline"><?= $polyclinic->daily_quota ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($polyclinic->is_active): ?>
                                        <span class="badge badge-success badge-sm">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-error badge-sm">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <a href="<?= base_url('polyclinics/' . $polyclinic->uuid) ?>" class="btn btn-sm btn-ghost btn-square" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                        <a href="<?= base_url('polyclinics/' . $polyclinic->uuid . '/edit') ?>" class="btn btn-sm btn-ghost btn-square" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        <form action="<?= base_url('polyclinics/' . $polyclinic->uuid) ?>" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus poliklinik ini?')">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-ghost btn-square text-error" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (isset($meta) && $meta['total_pages'] > 1): ?>
            <div class="card-body pt-2">
                <!-- Pagination placeholder -->
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>