<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center gap-2">
        <a href="/roles/<?= $role->uuid ?>" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold">Permission: <?= esc($role->name) ?></h1>
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

    <form method="POST" action="/roles/<?= $role->uuid ?>/permissions" x-data="{ selectAll: false }">
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-base-content/60">
                        Pilih permission yang akan diberikan ke role <strong><?= esc($role->name) ?></strong>
                    </p>
                    <div class="flex gap-2">
                        <button type="button" class="btn btn-ghost btn-sm" @click="
                            selectAll = !selectAll;
                            document.querySelectorAll('input[name=&quot;permissions[]&quot;]').forEach(cb => cb.checked = selectAll);
                        ">
                            <span x-text="selectAll ? 'Batal Pilih Semua' : 'Pilih Semua'"></span>
                        </button>
                    </div>
                </div>

                <?php if (empty($permissionsByModule)): ?>
                    <p class="text-base-content/50 text-center py-8">Tidak ada permission tersedia</p>
                <?php else: ?>
                    <div class="space-y-6">
                        <?php foreach ($permissionsByModule as $module => $permissions): ?>
                            <div class="border border-base-200 rounded-lg p-4" x-data="{ open: true }">
                                <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                                    <h3 class="font-semibold text-lg capitalize"><?= esc($module) ?></h3>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform" :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                                <div x-show="open" x-collapse class="mt-3">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                        <?php foreach ($permissions as $permission): ?>
                                            <label class="flex items-start gap-3 p-3 rounded-lg cursor-pointer hover:bg-base-200 transition-colors">
                                                <input
                                                    type="checkbox"
                                                    name="permissions[]"
                                                    value="<?= $permission->id ?>"
                                                    class="checkbox checkbox-sm checkbox-primary mt-0.5"
                                                    <?= in_array($permission->id, $rolePermissionIds) ? 'checked' : '' ?>
                                                />
                                                <div>
                                                    <div class="font-medium text-sm"><?= esc($permission->name) ?></div>
                                                    <div class="text-xs text-base-content/50"><?= esc($permission->slug) ?></div>
                                                    <?php if ($permission->description): ?>
                                                        <div class="text-xs text-base-content/40 mt-1"><?= esc($permission->description) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-actions justify-end p-4 border-t border-base-200">
                <a href="/roles/<?= $role->uuid ?>" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Permission</button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
