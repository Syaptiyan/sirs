<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center gap-2">
        <a href="/users/<?= $user->uuid ?>" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold">Aktivitas: <?= esc($user->name) ?></h1>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <?php if (empty($activities)): ?>
                <p class="text-base-content/50 text-center py-8">Tidak ada riwayat aktivitas</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Aksi</th>
                                <th>Model</th>
                                <th>Deskripsi</th>
                                <th>IP Address</th>
                                <th>User Agent</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($activities as $log): ?>
                                <tr>
                                    <td><span class="badge badge-sm"><?= esc($log->action) ?></span></td>
                                    <td><?= esc($log->model) ?></td>
                                    <td><?= esc($log->description ?? '-') ?></td>
                                    <td class="text-sm font-mono"><?= esc($log->ip_address ?? '-') ?></td>
                                    <td class="text-sm max-w-xs truncate"><?= esc($log->user_agent ?? '-') ?></td>
                                    <td class="text-sm"><?= date('d M Y H:i', strtotime($log->created_at)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($meta['total_pages'] > 1): ?>
                    <div class="flex justify-center mt-4">
                        <div class="join">
                            <?php for ($i = 1; $i <= $meta['total_pages']; $i++): ?>
                                <a href="/users/<?= $user->uuid ?>/activity?page=<?= $i ?>"
                                   class="join-item btn btn-sm <?= $meta['page'] == $i ? 'btn-active' : '' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
