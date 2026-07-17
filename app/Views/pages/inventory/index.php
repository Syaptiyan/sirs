<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold">Inventaris</h1>
            <p class="text-base-content/70">Kelola data aset/inventaris</p>
        </div>
        <a href="<?= base_url('inventory/create') ?>" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Inventaris
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
            <form action="<?= base_url('inventory') ?>" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="form-control flex-1">
                    <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari kode atau nama inventaris..." class="input input-bordered w-full" />
                </div>
                <div class="form-control sm:w-48">
                    <select name="category" class="select select-bordered w-full">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= esc($cat) ?>" <?= ($category ?? '') === $cat ? 'selected' : '' ?>>
                                <?= esc($cat) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-control sm:w-40">
                    <select name="status" class="select select-bordered w-full">
                        <option value="">Semua Status</option>
                        <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Aktif</option>
                        <option value="maintenance" <?= ($status ?? '') === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        <option value="disposed" <?= ($status ?? '') === 'disposed' ? 'selected' : '' ?>>Disposed</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Cari
                    </button>
                    <a href="<?= base_url('inventory') ?>" class="btn btn-ghost">Reset</a>
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
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-8 text-base-content/50">
                                Tidak ada data inventaris ditemukan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($items as $i => $item): ?>
                            <tr>
                                <td><?= ($page ?? 1) * ($perPage ?? 20) - ($perPage ?? 20) + $i + 1 ?></td>
                                <td><span class="font-mono text-sm"><?= esc($item->code) ?></span></td>
                                <td class="font-medium"><?= esc($item->name) ?></td>
                                <td><span class="badge badge-outline"><?= esc($item->category) ?></span></td>
                                <td class="text-sm"><?= esc($item->location ?? '-') ?></td>
                                <td>
                                    <?php
                                    $conditionClass = match($item->condition) {
                                        'good' => 'badge-success',
                                        'fair' => 'badge-warning',
                                        'poor' => 'badge-error',
                                        'disposed' => 'badge-ghost',
                                        default => 'badge-ghost',
                                    };
                                    ?>
                                    <span class="badge <?= $conditionClass ?> badge-sm"><?= ucfirst(esc($item->condition)) ?></span>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = match($item->status) {
                                        'active' => 'badge-success',
                                        'maintenance' => 'badge-warning',
                                        'disposed' => 'badge-error',
                                        default => 'badge-ghost',
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass ?> badge-sm"><?= ucfirst(esc($item->status)) ?></span>
                                </td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <a href="<?= base_url('inventory/' . $item->uuid) ?>" class="btn btn-sm btn-ghost btn-square" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                        <a href="<?= base_url('inventory/' . $item->uuid . '/edit') ?>" class="btn btn-sm btn-ghost btn-square" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        <form action="<?= base_url('inventory/' . $item->uuid . '/delete') ?>" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus inventaris ini?')">
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

        <?php if ($totalPages > 1): ?>
            <div class="card-body pt-2">
                <div class="flex justify-center">
                    <div class="join">
                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                            <a href="<?= base_url('inventory?' . http_build_query(array_filter(['search' => $search, 'category' => $category, 'status' => $status, 'page' => $p]))) ?>" class="join-item btn btn-sm <?= $p == $page ? 'btn-active' : '' ?>"><?= $p ?></a>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
