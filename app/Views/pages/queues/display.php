<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold">Display Antrian</h1>
            <p class="text-base-content/70">Monitor antrian secara real-time</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= base_url('queues') ?>" class="btn btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>
            <button onclick="location.reload()" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Filter -->
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body p-4">
            <form action="<?= base_url('queues/display') ?>" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="form-control sm:w-56">
                    <select name="polyclinic_id" class="select select-bordered w-full">
                        <option value="">Semua Poliklinik</option>
                        <?php foreach ($polyclinics as $poly): ?>
                            <option value="<?= $poly->id ?>" <?= ($polyclinicId ?? '') == $poly->id ? 'selected' : '' ?>>
                                <?= esc($poly->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="<?= base_url('queues/display') ?>" class="btn btn-ghost">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Currently Serving -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    Sedang Dipanggil
                </h2>

                <?php if (empty($displayData['current'])): ?>
                    <div class="text-center py-12 text-base-content/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <p class="text-lg">Tidak ada antrian sedang dipanggil</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($displayData['current'] as $queue): ?>
                            <div class="flex items-center gap-4 p-4 bg-warning/10 rounded-lg border border-warning/20">
                                <div class="text-3xl font-bold text-warning min-w-[80px]">
                                    <?= esc($queue->queue_number) ?>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium text-lg"><?= esc($queue->patient_name ?? '-') ?></div>
                                    <div class="text-sm text-base-content/70">
                                        dr. <?= esc($queue->doctor_name ?? '-') ?> - <?= esc($queue->polyclinic_name ?? '-') ?>
                                    </div>
                                </div>
                                <div class="badge badge-warning">
                                    <?= $queue->status === 'called' ? 'Dipanggil' : 'Sedang Dilayani' ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Next Queue -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-info" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Antrian Berikutnya
                </h2>

                <?php if (empty($displayData['next'])): ?>
                    <div class="text-center py-12 text-base-content/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <p class="text-lg">Semua antrian sudah dilayani</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($displayData['next'] as $i => $queue): ?>
                            <div class="flex items-center gap-4 p-4 bg-base-200 rounded-lg">
                                <div class="text-2xl font-bold text-base-content/50 min-w-[80px]">
                                    <?= esc($queue->queue_number) ?>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium"><?= esc($queue->patient_name ?? '-') ?></div>
                                    <div class="text-sm text-base-content/70">
                                        dr. <?= esc($queue->doctor_name ?? '-') ?> - <?= esc($queue->polyclinic_name ?? '-') ?>
                                    </div>
                                </div>
                                <div class="badge badge-info">Menunggu</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>
<?= $this->endSection() ?>
