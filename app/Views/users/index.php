<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Manajemen User</h1>
        <div class="flex gap-2">
            <a href="/users/export" class="btn btn-outline btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export
            </a>
            <a href="/users/create" class="btn btn-primary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah User
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <form method="GET" action="/users" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="form-control">
                    <input type="text" name="search" placeholder="Cari nama, email, telepon..." class="input input-bordered input-sm w-full" value="<?= $filters['search'] ?? '' ?>" />
                </div>
                <div class="form-control">
                    <select name="role_id" class="select select-bordered select-sm w-full">
                        <option value="">Semua Role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role->id ?>" <?= ($filters['role_id'] ?? '') == $role->id ? 'selected' : '' ?>><?= esc($role->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-control">
                    <select name="is_active" class="select select-bordered select-sm w-full">
                        <option value="">Semua Status</option>
                        <option value="1" <?= ($filters['is_active'] ?? '') === '1' ? 'selected' : '' ?>>Aktif</option>
                        <option value="0" <?= ($filters['is_active'] ?? '') === '0' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-1">Filter</button>
                    <a href="/users" class="btn btn-ghost btn-sm">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card bg-base-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Roles</th>
                        <th>Status</th>
                        <th>Terakhir Login</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-8 text-base-content/50">Tidak ada data user</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <a href="/users/<?= $user->uuid ?>" class="font-medium link link-primary"><?= esc($user->name) ?></a>
                                </td>
                                <td><?= esc($user->email) ?></td>
                                <td><?= esc($user->phone ?? '-') ?></td>
                                <td>
                                    <?php foreach ($user->roles as $role): ?>
                                        <span class="badge badge-sm badge-primary"><?= esc($role->role_name) ?></span>
                                    <?php endforeach; ?>
                                </td>
                                <td>
                                    <?php if ($user->is_active): ?>
                                        <span class="badge badge-sm badge-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-sm badge-error">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-sm"><?= $user->last_login_at ? date('d M Y H:i', strtotime($user->last_login_at)) : '-' ?></td>
                                <td>
                                    <div class="flex gap-1">
                                        <a href="/users/<?= $user->uuid ?>" class="btn btn-ghost btn-xs" title="Detail">Detail</a>
                                        <a href="/users/<?= $user->uuid ?>/edit" class="btn btn-ghost btn-xs" title="Edit">Edit</a>
                                        <?php if ($user->is_active): ?>
                                            <form method="POST" action="/users/<?= $user->uuid ?>/deactivate" class="inline" onsubmit="return confirm('Nonaktifkan user ini?')">
                                                <button type="submit" class="btn btn-warning btn-xs" title="Nonaktifkan">Nonaktif</button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" action="/users/<?= $user->uuid ?>/activate" class="inline">
                                                <button type="submit" class="btn btn-success btn-xs" title="Aktifkan">Aktif</button>
                                            </form>
                                        <?php endif; ?>
                                        <form method="POST" action="/users/<?= $user->uuid ?>" class="inline" onsubmit="return confirm('Hapus user ini?')">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-error btn-xs" title="Hapus">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($meta['total_pages'] > 1): ?>
            <div class="card-body">
                <div class="flex justify-center">
                    <div class="join">
                        <?php for ($i = 1; $i <= $meta['total_pages']; $i++): ?>
                            <a href="?page=<?= $i ?>&search=<?= urlencode($filters['search'] ?? '') ?>&role_id=<?= urlencode($filters['role_id'] ?? '') ?>&is_active=<?= urlencode($filters['is_active'] ?? '') ?>"
                               class="join-item btn btn-sm <?= $meta['page'] == $i ? 'btn-active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
