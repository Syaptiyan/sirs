<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Manajemen Role</h1>
        <a href="/roles/create" class="btn btn-primary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Role
        </a>
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
            <form method="GET" action="/roles" class="flex gap-4">
                <div class="form-control flex-1">
                    <input type="text" name="search" placeholder="Cari role..." class="input input-bordered input-sm w-full" value="<?= $filters['search'] ?? '' ?>" />
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                <a href="/roles" class="btn btn-ghost btn-sm">Reset</a>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php if (empty($roles)): ?>
            <div class="col-span-full text-center py-8 text-base-content/50">
                Tidak ada data role
            </div>
        <?php else: ?>
            <?php foreach ($roles as $role): ?>
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="card-title text-base">
                                    <a href="/roles/<?= $role->uuid ?>" class="link link-primary"><?= esc($role->name) ?></a>
                                </h3>
                                <p class="text-sm text-base-content/50 mt-1"><?= esc($role->slug) ?></p>
                            </div>
                            <div class="flex gap-1">
                                <a href="/roles/<?= $role->uuid ?>/edit" class="btn btn-ghost btn-xs" title="Edit">Edit</a>
                                <form method="POST" action="/roles/<?= $role->uuid ?>" onsubmit="return confirm('Hapus role ini?')">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-error btn-xs" title="Hapus">Hapus</button>
                                </form>
                            </div>
                        </div>

                        <?php if ($role->description): ?>
                            <p class="text-sm mt-2"><?= esc($role->description) ?></p>
                        <?php endif; ?>

                        <div class="flex gap-4 mt-3">
                            <div class="flex items-center gap-1 text-sm text-base-content/60">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <?= $role->permission_count ?> Permission
                            </div>
                            <div class="flex items-center gap-1 text-sm text-base-content/60">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <?= $role->user_count ?> User
                            </div>
                        </div>

                        <div class="card-actions justify-end mt-3">
                            <a href="/roles/<?= $role->uuid ?>/permissions" class="btn btn-outline btn-xs">Kelola Permission</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if ($meta['total_pages'] > 1): ?>
        <div class="flex justify-center">
            <div class="join">
                <?php for ($i = 1; $i <= $meta['total_pages']; $i++): ?>
                    <a href="?page=<?= $i ?>&search=<?= urlencode($filters['search'] ?? '') ?>"
                       class="join-item btn btn-sm <?= $meta['page'] == $i ? 'btn-active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
