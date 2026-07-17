<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="/audit" class="link link-hover">Audit Log</a></li>
                <li>Detail #<?= esc($log['id']) ?></li>
            </ul>
        </div>
        <h1 class="text-2xl font-bold mt-1">Detail Audit Log</h1>
    </div>
    <a href="/audit" class="btn btn-ghost btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali
    </a>
</div>

<!-- Info Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card bg-base-200 shadow-sm">
        <div class="card-body p-4">
            <div class="text-xs text-base-content/60 uppercase tracking-wide">Timestamp</div>
            <div class="text-sm font-semibold mt-1"><?= date('d M Y H:i:s', strtotime($log['created_at'])) ?></div>
        </div>
    </div>
    <div class="card bg-base-200 shadow-sm">
        <div class="card-body p-4">
            <div class="text-xs text-base-content/60 uppercase tracking-wide">User</div>
            <div class="flex items-center gap-2 mt-1">
                <div class="avatar placeholder">
                    <div class="bg-neutral text-neutral-content rounded-full w-6 h-6 text-xs">
                        <?= strtoupper(substr($log['user_name'] ?? 'U', 0, 1)) ?>
                    </div>
                </div>
                <span class="text-sm font-semibold"><?= esc($log['user_name'] ?? '-') ?></span>
            </div>
        </div>
    </div>
    <div class="card bg-base-200 shadow-sm">
        <div class="card-body p-4">
            <div class="text-xs text-base-content/60 uppercase tracking-wide">Module</div>
            <div class="mt-1"><span class="badge badge-outline"><?= esc($log['module']) ?></span></div>
        </div>
    </div>
    <div class="card bg-base-200 shadow-sm">
        <div class="card-body p-4">
            <div class="text-xs text-base-content/60 uppercase tracking-wide">Action</div>
            <div class="mt-1">
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
                <span class="badge <?= $badgeClass ?>"><?= ucfirst(esc($log['action'])) ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Description -->
<div class="card bg-base-100 shadow-sm mb-6">
    <div class="card-body p-4">
        <h2 class="card-title text-sm">Description</h2>
        <p class="text-sm"><?= esc($log['description'] ?? '-') ?></p>
    </div>
</div>

<!-- Additional Info -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body p-4">
            <h2 class="card-title text-sm">IP Address</h2>
            <p class="text-sm font-mono"><?= esc($log['ip_address'] ?? '-') ?></p>
        </div>
    </div>
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body p-4">
            <h2 class="card-title text-sm">User Agent</h2>
            <p class="text-sm truncate"><?= esc($log['user_agent'] ?? '-') ?></p>
        </div>
    </div>
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body p-4">
            <h2 class="card-title text-sm">Record ID</h2>
            <p class="text-sm font-mono"><?= esc($log['record_id'] ?? '-') ?></p>
        </div>
    </div>
</div>

<!-- Old / New Values Comparison -->
<?php
$oldValues = !empty($log['old_values']) ? (is_string($log['old_values']) ? json_decode($log['old_values'], true) : $log['old_values']) : [];
$newValues = !empty($log['new_values']) ? (is_string($log['new_values']) ? json_decode($log['new_values'], true) : $log['new_values']) : [];
$allKeys   = array_unique(array_merge(array_keys($oldValues ?: []), array_keys($newValues ?: [])));
?>

<?php if (!empty($allKeys)): ?>
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body p-4">
            <h2 class="card-title text-base mb-4">Perubahan Data</h2>
            <div class="overflow-x-auto">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th class="w-1/4">Field</th>
                            <th class="w-[37.5%]">Old Value</th>
                            <th class="w-[37.5%]">New Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allKeys as $key): ?>
                            <?php
                            $old = $oldValues[$key] ?? null;
                            $new = $newValues[$key] ?? null;
                            $isChanged = $old !== $new;
                            ?>
                            <tr class="<?= $isChanged ? 'bg-warning/10' : '' ?>">
                                <td class="font-semibold text-sm"><?= esc($key) ?></td>
                                <td>
                                    <?php if ($old !== null): ?>
                                        <div class="text-sm <?= $isChanged ? 'text-error line-through' : '' ?>">
                                            <?php if (is_array($old)): ?>
                                                <pre class="whitespace-pre-wrap text-xs bg-base-200 rounded p-2"><?= esc(json_encode($old, JSON_PRETTY_PRINT)) ?></pre>
                                            <?php else: ?>
                                                <?= esc((string)$old) ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-base-content/40 text-sm">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($new !== null): ?>
                                        <div class="text-sm <?= $isChanged ? 'text-success font-semibold' : '' ?>">
                                            <?php if (is_array($new)): ?>
                                                <pre class="whitespace-pre-wrap text-xs bg-base-200 rounded p-2"><?= esc(json_encode($new, JSON_PRETTY_PRINT)) ?></pre>
                                            <?php else: ?>
                                                <?= esc((string)$new) ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-base-content/40 text-sm">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php elseif ($log['action'] === 'delete'): ?>
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body p-4">
            <h2 class="card-title text-base mb-4">Data yang Dihapus</h2>
            <?php if (!empty($oldValues)): ?>
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th class="w-1/4">Field</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($oldValues as $key => $val): ?>
                                <tr>
                                    <td class="font-semibold text-sm"><?= esc($key) ?></td>
                                    <td class="text-sm">
                                        <?php if (is_array($val)): ?>
                                            <pre class="whitespace-pre-wrap text-xs bg-base-200 rounded p-2"><?= esc(json_encode($val, JSON_PRETTY_PRINT)) ?></pre>
                                        <?php else: ?>
                                            <?= esc((string)$val) ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-sm text-base-content/50">Tidak ada data tersimpan.</p>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body p-4">
            <h2 class="card-title text-base mb-2">Perubahan Data</h2>
            <p class="text-sm text-base-content/50">Tidak ada data perubahan tersimpan.</p>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
