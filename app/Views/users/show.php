<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6" x-data="{ tab: 'info' }">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <a href="/users" class="btn btn-ghost btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold"><?= esc($user->name) ?></h1>
            <?php if ($user->is_active): ?>
                <span class="badge badge-success">Aktif</span>
            <?php else: ?>
                <span class="badge badge-error">Nonaktif</span>
            <?php endif; ?>
        </div>
        <div class="flex gap-2">
            <a href="/users/<?= $user->uuid ?>/edit" class="btn btn-outline btn-sm">Edit</a>
            <?php if ($user->is_active): ?>
                <form method="POST" action="/users/<?= $user->uuid ?>/deactivate" onsubmit="return confirm('Nonaktifkan user ini?')">
                    <button type="submit" class="btn btn-warning btn-sm">Nonaktifkan</button>
                </form>
            <?php else: ?>
                <form method="POST" action="/users/<?= $user->uuid ?>/activate">
                    <button type="submit" class="btn btn-success btn-sm">Aktifkan</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="tabs tabs-boxed bg-base-100">
        <a class="tab" :class="tab === 'info' && 'tab-active'" @click="tab = 'info'">Info</a>
        <a class="tab" :class="tab === 'roles' && 'tab-active'" @click="tab = 'roles'">Roles</a>
        <a class="tab" :class="tab === 'activity' && 'tab-active'" @click="tab = 'activity'">Aktivitas</a>
    </div>

    <div x-show="tab === 'info'">
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm text-base-content/60">Nama</label>
                        <p class="font-medium"><?= esc($user->name) ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-base-content/60">Email</label>
                        <p class="font-medium"><?= esc($user->email) ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-base-content/60">Telepon</label>
                        <p class="font-medium"><?= esc($user->phone ?? '-') ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-base-content/60">Status</label>
                        <p>
                            <?php if ($user->is_active): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-error">Nonaktif</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-base-content/60">Terakhir Login</label>
                        <p class="font-medium"><?= $user->last_login_at ? date('d M Y H:i', strtotime($user->last_login_at)) : '-' ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-base-content/60">Terakhir Login IP</label>
                        <p class="font-medium"><?= esc($user->last_login_ip ?? '-') ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-base-content/60">Email Terverifikasi</label>
                        <p class="font-medium"><?= $user->email_verified_at ? date('d M Y H:i', strtotime($user->email_verified_at)) : 'Belum' ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-base-content/60">Tanggal Dibuat</label>
                        <p class="font-medium"><?= date('d M Y H:i', strtotime($user->created_at)) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="tab === 'roles'" x-cloak>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Roles</h3>
                    <form method="POST" action="/users/<?= $user->uuid ?>/assign-role" class="flex gap-2">
                        <select name="role_id" class="select select-bordered select-sm" required>
                            <option value="">Pilih Role</option>
                            <?php
                            $allRoles = $roles ?? [];
                            $userRoleIds = array_map(fn($r) => $r->role_id, $user->roles);
                            foreach ($allRoles as $role):
                                if (!in_array($role->id, $userRoleIds)):
                            ?>
                                <option value="<?= $role->id ?>"><?= esc($role->name) ?></option>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Assign</button>
                    </form>
                </div>

                <?php if (empty($user->roles)): ?>
                    <p class="text-base-content/50 text-center py-4">Belum ada role yang diassign</p>
                <?php else: ?>
                    <div class="space-y-2">
                        <?php foreach ($user->roles as $role): ?>
                            <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                                <div>
                                    <span class="font-medium"><?= esc($role->role_name) ?></span>
                                    <span class="text-sm text-base-content/50 ml-2">(<?= esc($role->role_slug) ?>)</span>
                                </div>
                                <form method="POST" action="/users/<?= $user->uuid ?>/remove-role" onsubmit="return confirm('Hapus role ini?')">
                                    <input type="hidden" name="role_id" value="<?= $role->role_id ?>">
                                    <button type="submit" class="btn btn-ghost btn-xs text-error">Hapus</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div x-show="tab === 'activity'" x-cloak>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h3 class="text-lg font-semibold mb-4">Riwayat Aktivitas</h3>

                <?php if (empty($activity)): ?>
                    <p class="text-base-content/50 text-center py-4">Tidak ada riwayat aktivitas</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra table-sm">
                            <thead>
                                <tr>
                                    <th>Aksi</th>
                                    <th>Model</th>
                                    <th>Deskripsi</th>
                                    <th>IP</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($activity as $log): ?>
                                    <tr>
                                        <td><span class="badge badge-sm"><?= esc($log->action) ?></span></td>
                                        <td><?= esc($log->model) ?></td>
                                        <td><?= esc($log->description ?? '-') ?></td>
                                        <td class="text-sm"><?= esc($log->ip_address ?? '-') ?></td>
                                        <td class="text-sm"><?= date('d M Y H:i', strtotime($log->created_at)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($activityMeta['total_pages'] > 1): ?>
                        <div class="flex justify-center mt-4">
                            <div class="join">
                                <?php for ($i = 1; $i <= $activityMeta['total_pages']; $i++): ?>
                                    <button @click="$el.closest('[x-data]').__x.$data.tab === 'activity' && window.location.reload()" class="join-item btn btn-sm <?= $activityMeta['page'] == $i ? 'btn-active' : '' ?>">
                                        <?= $i ?>
                                    </button>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
