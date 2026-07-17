<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Data Pasien</h1>
            <p class="text-base-content/70">Kelola data pasien rumah sakit</p>
        </div>
        <a href="<?= site_url('patients/create') ?>" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Pasien
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <form method="GET" action="<?= current_url() ?>" class="mb-4">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="form-control flex-1">
                        <div class="input-group">
                            <input type="text" name="search" placeholder="Cari nama, No. RM, NIK, telepon..."
                                   class="input input-bordered w-full"
                                   value="<?= esc($filters['search'] ?? '') ?>" />
                            <button type="submit" class="btn btn-square btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </button>
                        </div>
                    </div>
                    <select name="gender" class="select select-bordered">
                        <option value="">Semua Gender</option>
                        <option value="L" <?= ($filters['gender'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= ($filters['gender'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                    <select name="status" class="select select-bordered">
                        <option value="">Semua Status</option>
                        <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Aktif</option>
                        <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Non-aktif</option>
                    </select>
                    <?php if (!empty($filters['search']) || !empty($filters['gender']) || !empty($filters['status'])): ?>
                        <a href="<?= site_url('patients') ?>" class="btn btn-ghost">Reset</a>
                    <?php endif; ?>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. RM</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Gender</th>
                            <th>Tanggal Lahir</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($patients)): ?>
                            <tr>
                                <td colspan="9" class="text-center py-8 text-base-content/50">
                                    Tidak ada data pasien ditemukan.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($patients as $i => $patient): ?>
                                <tr>
                                    <td><?= ($pagination['currentPage'] - 1) * $pagination['perPage'] + $i + 1 ?></td>
                                    <td><span class="badge badge-ghost font-mono"><?= esc($patient['mrn']) ?></span></td>
                                    <td class="font-medium"><?= esc($patient['name']) ?></td>
                                    <td class="font-mono text-sm"><?= esc($patient['nik']) ?></td>
                                    <td><?= $patient['gender'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                                    <td><?= date('d/m/Y', strtotime($patient['birth_date'])) ?></td>
                                    <td><?= esc($patient['phone']) ?></td>
                                    <td>
                                        <?php if ($patient['status'] === 'active'): ?>
                                            <span class="badge badge-success badge-sm">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge badge-ghost badge-sm">Non-aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="flex gap-1">
                                            <a href="<?= site_url('patients/' . $patient['id']) ?>" class="btn btn-ghost btn-xs" title="Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            </a>
                                            <a href="<?= site_url('patients/' . $patient['id'] . '/edit') ?>" class="btn btn-ghost btn-xs" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($pagination['totalPages'] > 1): ?>
                <div class="flex justify-center mt-4">
                    <div class="join">
                        <?php if ($pagination['currentPage'] > 1): ?>
                            <a href="<?= site_url('patients?' . http_build_query(array_merge($filters ?? [], ['page' => $pagination['currentPage'] - 1]))) ?>" class="join-item btn btn-sm">«</a>
                        <?php endif; ?>

                        <?php
                        $start = max(1, $pagination['currentPage'] - 2);
                        $end = min($pagination['totalPages'], $pagination['currentPage'] + 2);
                        ?>
                        <?php if ($start > 1): ?>
                            <a href="<?= site_url('patients?' . http_build_query(array_merge($filters ?? [], ['page' => 1]))) ?>" class="join-item btn btn-sm">1</a>
                            <?php if ($start > 2): ?><span class="join-item btn btn-sm btn-disabled">...</span><?php endif; ?>
                        <?php endif; ?>

                        <?php for ($p = $start; $p <= $end; $p++): ?>
                            <a href="<?= site_url('patients?' . http_build_query(array_merge($filters ?? [], ['page' => $p]))) ?>"
                               class="join-item btn btn-sm <?= $p === $pagination['currentPage'] ? 'btn-active' : '' ?>"><?= $p ?></a>
                        <?php endfor; ?>

                        <?php if ($end < $pagination['totalPages']): ?>
                            <?php if ($end < $pagination['totalPages'] - 1): ?><span class="join-item btn btn-sm btn-disabled">...</span><?php endif; ?>
                            <a href="<?= site_url('patients?' . http_build_query(array_merge($filters ?? [], ['page' => $pagination['totalPages']]))) ?>" class="join-item btn btn-sm"><?= $pagination['totalPages'] ?></a>
                        <?php endif; ?>

                        <?php if ($pagination['currentPage'] < $pagination['totalPages']): ?>
                            <a href="<?= site_url('patients?' . http_build_query(array_merge($filters ?? [], ['page' => $pagination['currentPage'] + 1]))) ?>" class="join-item btn btn-sm">»</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
