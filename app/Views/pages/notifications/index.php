<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold">Notifikasi</h1>
    <div class="flex gap-2">
        <button id="btn-mark-all" class="btn btn-ghost btn-sm" onclick="markAllRead()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Tandai Semua Dibaca
        </button>
    </div>
</div>

<!-- Filter -->
<form method="GET" class="flex flex-wrap gap-2 mb-6">
    <select name="status" class="select select-bordered select-sm">
        <option value="">Semua Status</option>
        <option value="unread" <?= ($filters['status'] ?? '') === 'unread' ? 'selected' : '' ?>>Belum Dibaca</option>
        <option value="read" <?= ($filters['status'] ?? '') === 'read' ? 'selected' : '' ?>>Sudah Dibaca</option>
    </select>
    <select name="type" class="select select-bordered select-sm">
        <option value="">Semua Tipe</option>
        <option value="info" <?= ($filters['type'] ?? '') === 'info' ? 'selected' : '' ?>>Info</option>
        <option value="warning" <?= ($filters['type'] ?? '') === 'warning' ? 'selected' : '' ?>>Warning</option>
        <option value="success" <?= ($filters['type'] ?? '') === 'success' ? 'selected' : '' ?>>Success</option>
        <option value="error" <?= ($filters['type'] ?? '') === 'error' ? 'selected' : '' ?>>Error</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    <a href="/notifications" class="btn btn-ghost btn-sm">Reset</a>
</form>

<!-- Notification List -->
<div class="space-y-2">
    <?php if (!empty($notifications)): ?>
        <?php foreach ($notifications as $notif): ?>
            <div class="card bg-base-100 shadow-sm border-l-4 notif-item <?= $notif['is_read'] ? 'border-base-300' : 'border-primary' ?>"
                 data-id="<?= $notif['id'] ?>"
                 data-read="<?= $notif['is_read'] ? '1' : '0' ?>">
                <div class="card-body p-4">
                    <div class="flex items-start gap-3">
                        <!-- Icon -->
                        <div class="flex-shrink-0 mt-0.5">
                            <?php
                            $typeIcons = [
                                'info'    => 'bg-info/20 text-info',
                                'warning' => 'bg-warning/20 text-warning',
                                'success' => 'bg-success/20 text-success',
                                'error'   => 'bg-error/20 text-error',
                            ];
                            $iconClass = $typeIcons[$notif['type'] ?? 'info'] ?? 'bg-base-200 text-base-content';
                            ?>
                            <div class="w-8 h-8 rounded-full flex items-center justify-center <?= $iconClass ?>">
                                <?php if (($notif['type'] ?? 'info') === 'info'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <?php elseif (($notif['type'] ?? 'info') === 'warning'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                                <?php elseif (($notif['type'] ?? 'info') === 'success'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="text-sm font-semibold <?= $notif['is_read'] ? '' : 'text-primary' ?>">
                                    <?= esc($notif['title'] ?? '-') ?>
                                </h3>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <?php if (!$notif['is_read']): ?>
                                        <span class="badge badge-primary badge-sm">Baru</span>
                                    <?php endif; ?>
                                    <span class="text-xs text-base-content/50 whitespace-nowrap">
                                        <?= date('d M Y H:i', strtotime($notif['created_at'])) ?>
                                    </span>
                                </div>
                            </div>
                            <p class="text-sm text-base-content/70 mt-1"><?= esc($notif['message'] ?? '-') ?></p>
                            <div class="flex items-center gap-3 mt-2">
                                <?php if (!empty($notif['link'])): ?>
                                    <a href="<?= esc($notif['link']) ?>" class="link link-primary text-xs">Lihat Detail</a>
                                <?php endif; ?>
                                <?php if (!$notif['is_read']): ?>
                                    <button class="btn btn-ghost btn-xs mark-read-btn" data-id="<?= $notif['id'] ?>">
                                        Tandai Dibaca
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body py-12 text-center text-base-content/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Tidak ada notifikasi.
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if (!empty($notifications) && $pager->getPageCount() > 1): ?>
    <div class="flex justify-center mt-6">
        <?= $pager->links('default', 'daisyui_full') ?>
    </div>
<?php endif; ?>

<script>
function markAllRead() {
    if (!confirm('Tandai semua notifikasi sebagai dibaca?')) return;

    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.notif-item[data-read="0"]').forEach(el => {
                el.dataset.read = '1';
                el.classList.remove('border-primary');
                el.classList.add('border-base-300');
                el.querySelector('.badge-primary')?.remove();
                el.querySelector('.text-primary')?.classList.remove('text-primary');
                el.querySelector('.mark-read-btn')?.remove();
            });
        }
    })
    .catch(() => alert('Gagal menandai notifikasi.'));
}

document.querySelectorAll('.mark-read-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const item = document.querySelector(`.notif-item[data-id="${id}"]`);
                if (item) {
                    item.dataset.read = '1';
                    item.classList.remove('border-primary');
                    item.classList.add('border-base-300');
                    item.querySelector('.badge-primary')?.remove();
                    const h3 = item.querySelector('h3');
                    if (h3) h3.classList.remove('text-primary');
                    this.remove();
                }
            }
        })
        .catch(() => alert('Gagal menandai notifikasi.'));
    });
});
</script>

<?= $this->endSection() ?>
