<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold">Audit Log</h1>
    <a href="/audit/export<?= '?' . http_build_query($_GET) ?>" class="btn btn-outline btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Export CSV
    </a>
</div>

<!-- Filter Form -->
<form method="GET" class="card bg-base-200 shadow-sm mb-6">
    <div class="card-body p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="form-control">
                <label class="label"><span class="label-text">Module</span></label>
                <select name="module" class="select select-bordered select-sm">
                    <option value="">Semua Module</option>
                    <?php if (!empty($modules)): ?>
                        <?php foreach ($modules as $mod): ?>
                            <option value="<?= esc($mod) ?>" <?= ($filters['module'] ?? '') === $mod ? 'selected' : '' ?>>
                                <?= esc($mod) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">Action</span></label>
                <select name="action" class="select select-bordered select-sm">
                    <option value="">Semua Action</option>
                    <?php
                    $actions = ['create', 'update', 'delete', 'login', 'logout', 'export'];
                    foreach ($actions as $act):
                    ?>
                        <option value="<?= $act ?>" <?= ($filters['action'] ?? '') === $act ? 'selected' : '' ?>>
                            <?= ucfirst($act) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">Dari Tanggal</span></label>
                <input type="date" name="date_from" value="<?= esc($filters['date_from'] ?? '') ?>"
                    class="input input-bordered input-sm" />
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">Sampai Tanggal</span></label>
                <input type="date" name="date_to" value="<?= esc($filters['date_to'] ?? '') ?>"
                    class="input input-bordered input-sm" />
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">&nbsp;</span></label>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-1">Filter</button>
                    <a href="/audit" class="btn btn-ghost btn-sm">Reset</a>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Table -->
<div class="card bg-base-100 shadow-sm">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table table-zebra table-sm">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Module</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP Address</th>
                        <th class="w-16">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($logs)): ?>
                        <?php foreach ($logs as $i => $log): ?>
                            <tr>
                                <td class="text-base-content/50"><?= ($pager->getCurrentPage() - 1) * $pager->getPerPage() + $i + 1 ?></td>
                                <td class="whitespace-nowrap text-sm">
                                    <?= date('d M Y H:i:s', strtotime($log['created_at'])) ?>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="avatar placeholder">
                                            <div class="bg-neutral text-neutral-content rounded-full w-6 h-6 text-xs">
                                                <?= strtoupper(substr($log['user_name'] ?? 'U', 0, 1)) ?>
                                            </div>
                                        </div>
                                        <span class="text-sm"><?= esc($log['user_name'] ?? '-') ?></span>
                                    </div>
                                </td>
                                <td><span class="badge badge-outline badge-sm"><?= esc($log['module']) ?></span></td>
                                <td>
                                    <?php
                                    $actionColors = [
                                        'create' => 'badge-success',
                                        'update' => 'badge-warning',
                                        'delete' => 'badge-error',
                                        'login'  => 'badge-info',
                                        'logout' => 'badge-ghost',
                                        'export' => 'badge-accent',
                                    ];
                                    $badgeClass = $actionColors[$log['action']] ?? 'badge-neutral';
                                    ?>
                                    <span class="badge <?= $badgeClass ?> badge-sm"><?= ucfirst(esc($log['action'])) ?></span>
                                </td>
                                <td class="max-w-xs truncate text-sm"><?= esc($log['description'] ?? '-') ?></td>
                                <td class="text-sm font-mono"><?= esc($log['ip_address'] ?? '-') ?></td>
                                <td>
                                    <a href="/audit/<?= $log['id'] ?>" class="btn btn-ghost btn-xs" title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-12 text-base-content/50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Tidak ada data audit log.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
<?php if (!empty($logs) && $pager->getPageCount() > 1): ?>
    <div class="flex justify-center mt-6">
        <?= $pager->links('default', 'daisyui_full') ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
