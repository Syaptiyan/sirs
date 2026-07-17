<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('registration') ?>">Kunjungan</a></li>
                <li><?= esc($visit->visit_number) ?></li>
            </ul>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-2">
            <div>
                <h1 class="text-2xl font-bold">Detail Kunjungan</h1>
                <p class="text-base-content/70">
                    No. Kunjungan: <span class="font-mono badge badge-primary"><?= esc($visit->visit_number) ?></span>
                </p>
            </div>
            <div class="flex gap-2">
                <a href="<?= site_url('registration') ?>" class="btn btn-ghost btn-sm">Kembali</a>
            </div>
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

    <!-- Status Badge -->
    <div class="flex items-center gap-3">
        <?php
        $statusLabels = [
            'waiting'     => 'Menunggu',
            'in_progress' => 'Sedang Dilayani',
            'completed'   => 'Selesai',
            'cancelled'   => 'Dibatalkan',
            'no_show'     => 'Tidak Hadir',
        ];
        $statusClasses = [
            'waiting'     => 'badge-info',
            'in_progress' => 'badge-warning',
            'completed'   => 'badge-success',
            'cancelled'   => 'badge-ghost',
            'no_show'     => 'badge-error',
        ];
        ?>
        <span class="badge badge-lg <?= $statusClasses[$visit->status] ?? 'badge-ghost' ?>">
            <?= $statusLabels[$visit->status] ?? $visit->status ?>
        </span>
        <?php
        $typeClasses = ['RJ' => 'badge-info', 'RI' => 'badge-warning', 'IGD' => 'badge-error'];
        ?>
        <span class="badge badge-lg <?= $typeClasses[$visit->visit_type_code] ?? 'badge-ghost' ?>">
            <?= esc($visit->visit_type_name) ?>
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Patient Info -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-lg border-b pb-2">Data Pasien</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="text-base-content/70">Nama</div>
                    <div class="font-medium"><?= esc($visit->patient_name ?? '-') ?></div>
                    <div class="text-base-content/70">No. RM</div>
                    <div class="font-mono"><?= esc($visit->mrn ?? '-') ?></div>
                    <div class="text-base-content/70">NIK</div>
                    <div class="font-mono"><?= esc($visit->patient_nik ?? '-') ?></div>
                    <div class="text-base-content/70">Jenis Kelamin</div>
                    <div><?= ($visit->patient_gender ?? '') === 'L' ? 'Laki-laki' : 'Perempuan' ?></div>
                    <div class="text-base-content/70">Tanggal Lahir</div>
                    <div><?= $visit->patient_birth_date ? date('d/m/Y', strtotime($visit->patient_birth_date)) : '-' ?></div>
                    <div class="text-base-content/70">Telepon</div>
                    <div><?= esc($visit->patient_phone ?? '-') ?></div>
                </div>
            </div>
        </div>

        <!-- Visit Info -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-lg border-b pb-2">Data Kunjungan</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="text-base-content/70">No. Kunjungan</div>
                    <div class="font-mono font-medium"><?= esc($visit->visit_number) ?></div>
                    <div class="text-base-content/70">Tipe Kunjungan</div>
                    <div><?= esc($visit->visit_type_name) ?> (<?= esc($visit->visit_type_code) ?>)</div>
                    <div class="text-base-content/70">Dokter</div>
                    <div>dr. <?= esc($visit->doctor_name ?? '-') ?></div>
                    <div class="text-base-content/70">Poliklinik</div>
                    <div><?= esc($visit->polyclinic_name ?? '-') ?></div>
                    <?php if ($visit->room_number): ?>
                        <div class="text-base-content/70">Kamar</div>
                        <div><?= esc($visit->room_number) ?></div>
                    <?php endif; ?>
                    <?php if ($visit->bed_number): ?>
                        <div class="text-base-content/70">Tempat Tidur</div>
                        <div><?= esc($visit->bed_number) ?></div>
                    <?php endif; ?>
                    <div class="text-base-content/70">Tanggal Kunjungan</div>
                    <div><?= date('d/m/Y', strtotime($visit->visit_date)) ?></div>
                    <div class="text-base-content/70">Waktu</div>
                    <div><?= date('H:i', strtotime($visit->visit_time)) ?></div>
                </div>
            </div>
        </div>

        <!-- Complaint -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-lg border-b pb-2">Keluhan</h3>
                <p class="text-sm"><?= nl2br(esc($visit->complaint ?? '-')) ?></p>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-lg border-b pb-2">Riwayat Waktu</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="text-base-content/70">Tanggal Masuk</div>
                    <div><?= $visit->admission_date ? date('d/m/Y H:i', strtotime($visit->admission_date)) : '-' ?></div>
                    <div class="text-base-content/70">Tanggal Keluar</div>
                    <div><?= $visit->discharge_date ? date('d/m/Y H:i', strtotime($visit->discharge_date)) : '-' ?></div>
                    <div class="text-base-content/70">Dibuat</div>
                    <div><?= $visit->created_at ? date('d/m/Y H:i', strtotime($visit->created_at)) : '-' ?></div>
                    <div class="text-base-content/70">Diperbarui</div>
                    <div><?= $visit->updated_at ? date('d/m/Y H:i', strtotime($visit->updated_at)) : '-' ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes -->
    <?php if ($visit->notes): ?>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-lg border-b pb-2">Catatan</h3>
                <p class="text-sm"><?= nl2br(esc($visit->notes)) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Actions -->
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <h3 class="card-title text-lg border-b pb-2">Aksi</h3>
            <div class="flex flex-wrap gap-3">
                <?php if ($visit->status === 'waiting'): ?>
                    <form action="<?= site_url('registration/' . $visit->uuid . '/status') ?>" method="POST" class="inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="status" value="in_progress" />
                        <button type="submit" class="btn btn-warning btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Mulai Pelayanan
                        </button>
                    </form>
                    <form action="<?= site_url('registration/' . $visit->uuid . '/status') ?>" method="POST" class="inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="status" value="no_show" />
                        <button type="submit" class="btn btn-error btn-sm" onclick="return confirm('Tandai pasien tidak hadir?')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                            Tidak Hadir
                        </button>
                    </form>
                <?php endif; ?>

                <?php if ($visit->status === 'in_progress'): ?>
                    <form action="<?= site_url('registration/' . $visit->uuid . '/discharge') ?>" method="POST" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Discharge pasien?')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Discharge
                        </button>
                    </form>
                <?php endif; ?>

                <?php if (in_array($visit->status, ['waiting', 'in_progress'])): ?>
                    <form action="<?= site_url('registration/' . $visit->uuid . '/status') ?>" method="POST" class="inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="status" value="cancelled" />
                        <button type="submit" class="btn btn-ghost btn-sm" onclick="return confirm('Batalkan kunjungan?')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Batalkan
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
