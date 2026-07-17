<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <a href="/roles" class="btn btn-ghost btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold"><?= esc($role->name) ?></h1>
            <?php if ($role->is_active): ?>
                <span class="badge badge-success">Aktif</span>
            <?php else: ?>
                <span class="badge badge-error">Nonaktif</span>
            <?php endif; ?>
        </div>
        <div class="flex gap-2">
            <a href="/roles/<?= $role->uuid ?>/edit" class="btn btn-outline btn-sm">Edit</a>
            <a href="/roles/<?= $role->uuid ?>/permissions" class="btn btn-primary btn-sm">Kelola Permission</a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-lg">Info Role</h3>
                    <div class="space-y-4 mt-2">
                        <div>
                            <label class="text-sm text-base-content/60">Nama</label>
                            <p class="font-medium"><?= esc($role->name) ?></p>
                        </div>
                        <div>
                            <label class="text-sm text-base-content/60">Slug</label>
                            <p class="font-medium"><?= esc($role->slug) ?></p>
                        </div>
                        <div>
                            <label class="text-sm text-base-content/60">Deskripsi</label>
                            <p class="font-medium"><?= esc($role->description ?? '-') ?></p>
                        </div>
                        <div>
                            <label class="text-sm text-base-content/60">Jumlah User</label>
                            <p class="font-medium"><?= count($role->users) ?></p>
                        </div>
                        <div>
                            <label class="text-sm text-base-content/60">Jumlah Permission</label>
                            <p class="font-medium"><?= count($role->permissions) ?></p>
                        </div>
                        <div>
                            <label class="text-sm text-base-content/60">Tanggal Dibuat</label>
                            <p class="font-medium"><?= date('d M Y H:i', strtotime($role->created_at)) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-lg">Permissions</h3>
                    <?php if (empty($role->permissions)): ?>
                        <p class="text-base-content/50 text-center py-4">Belum ada permission yang diassign</p>
                    <?php else: ?>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <?php foreach ($role->permissions as $perm): ?>
                                <div class="badge badge-lg badge-outline gap-1" title="<?= esc($perm->permission_description ?? '') ?>">
                                    <?= esc($perm->permission_name) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-lg">Users dengan Role Ini</h3>
                    <?php if (empty($role->users)): ?>
                        <p class="text-base-content/50 text-center py-4">Tidak ada user dengan role ini</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="table table-zebra table-sm">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($role->users as $userRole): ?>
                                        <tr>
                                            <td>
                                                <a href="/users/<?= $userRole->user_id ?>" class="link link-primary"><?= esc($userRole->user_name) ?></a>
                                            </td>
                                            <td><?= esc($userRole->user_email) ?></td>
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
</div>
<?= $this->endSection() ?>
