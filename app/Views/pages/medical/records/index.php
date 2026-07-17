<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('registration') ?>">Registrasi</a></li>
                <?php if ($visitId): ?>
                    <li><a href="<?= site_url('registration/' . $visitId) ?>">Kunjungan</a></li>
                <?php endif; ?>
                <li>Rekam Medis</li>
            </ul>
        </div>
        <div class="flex justify-between items-center mt-2">
            <h1 class="text-2xl font-bold">Daftar Rekam Medis</h1>
            <?php if ($visitId): ?>
                <a href="<?= site_url('medical/records/create?visit_id=' . $visitId) ?>" class="btn btn-primary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Buat Rekam Medis
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <?php if (empty($records)): ?>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <p class="text-base-content/50 mt-4">Belum ada rekam medis</p>
                <?php if ($visitId): ?>
                    <a href="<?= site_url('medical/records/create?visit_id=' . $visitId) ?>" class="btn btn-primary btn-sm mt-4">
                        Buat Rekam Medis Pertama
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="grid gap-4">
            <?php foreach ($records as $record): ?>
                <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="card-body">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="card-title text-lg"><?= esc($record->record_number) ?></h3>
                                <p class="text-sm text-base-content/70">
                                    Dokter: <?= esc($record->doctor_name ?? '-') ?> | 
                                    <?= date('d/m/Y H:i', strtotime($record->created_at)) ?>
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <a href="<?= site_url('medical/records/' . $record->uuid) ?>" class="btn btn-sm btn-outline">
                                    Detail
                                </a>
                            </div>
                        </div>

                        <?php if (!empty($record->subjective)): ?>
                            <div class="mt-3">
                                <p class="text-sm font-semibold text-base-content/70">Subjective:</p>
                                <p class="text-sm line-clamp-2"><?= esc($record->subjective) ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($record->assessment)): ?>
                            <div class="mt-2">
                                <p class="text-sm font-semibold text-base-content/70">Assessment:</p>
                                <p class="text-sm line-clamp-2"><?= esc($record->assessment) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
