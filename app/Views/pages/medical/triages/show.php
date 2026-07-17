<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('registration') ?>">Registrasi</a></li>
                <li><a href="<?= site_url('registration/' . $triage->visit_id) ?>">Kunjungan</a></li>
                <li>Detail Triase</li>
            </ul>
        </div>
        <h1 class="text-2xl font-bold mt-2">Detail Triase</h1>
        <p class="text-base-content/70">
            Pasien: <span class="font-semibold"><?= esc($triage->patient_name) ?></span>
            (MRN: <?= esc($triage->mrn) ?>)
        </p>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php
    $levelConfig = [
        'emergency'  => ['label' => 'EMERGENCY', 'color' => 'error', 'bg' => 'bg-error'],
        'urgent'     => ['label' => 'URGENT', 'color' => 'warning', 'bg' => 'bg-warning'],
        'non_urgent' => ['label' => 'NON-URGENT', 'color' => 'success', 'bg' => 'bg-success'],
    ];
    $level = $levelConfig[$triage->triage_level] ?? $levelConfig['non_urgent'];
    ?>

    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <div class="flex items-center gap-4 mb-4">
                <span class="badge badge-<?= $level['color'] ?> badge-lg"><?= $level['label'] ?></span>
                <span class="text-sm text-base-content/70">
                    Ditriase oleh <?= esc($triage->triaged_by_name) ?> pada <?= date('d/m/Y H:i', strtotime($triage->triaged_at)) ?>
                </span>
            </div>

            <div class="divider"></div>

            <h3 class="font-semibold mb-2">Keluhan Utama</h3>
            <p class="whitespace-pre-line"><?= esc($triage->chief_complaint) ?></p>

            <div class="divider"></div>

            <h3 class="font-semibold mb-4">Tanda Vital</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="stat bg-base-200 rounded-lg p-3">
                    <div class="stat-title text-xs">Tekanan Darah</div>
                    <div class="stat-value text-lg"><?= esc($triage->vital_signs['blood_pressure'] ?? '-') ?></div>
                    <div class="stat-desc">mmHg</div>
                </div>
                <div class="stat bg-base-200 rounded-lg p-3">
                    <div class="stat-title text-xs">Denyut Jantung</div>
                    <div class="stat-value text-lg"><?= esc($triage->vital_signs['heart_rate'] ?? '-') ?></div>
                    <div class="stat-desc">x/menit</div>
                </div>
                <div class="stat bg-base-200 rounded-lg p-3">
                    <div class="stat-title text-xs">Suhu</div>
                    <div class="stat-value text-lg"><?= esc($triage->vital_signs['temperature'] ?? '-') ?></div>
                    <div class="stat-desc">&deg;C</div>
                </div>
                <div class="stat bg-base-200 rounded-lg p-3">
                    <div class="stat-title text-xs">Frekuensi Napas</div>
                    <div class="stat-value text-lg"><?= esc($triage->vital_signs['respiratory_rate'] ?? '-') ?></div>
                    <div class="stat-desc">x/menit</div>
                </div>
                <div class="stat bg-base-200 rounded-lg p-3">
                    <div class="stat-title text-xs">SpO2</div>
                    <div class="stat-value text-lg"><?= esc($triage->vital_signs['spo2'] ?? '-') ?></div>
                    <div class="stat-desc">%</div>
                </div>
            </div>

            <div class="divider"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold mb-2">Tingkat Kesadaran</h3>
                    <?php
                    $consciousnessLabels = [
                        'alert'        => ['label' => 'Alert', 'color' => 'success'],
                        'confused'     => ['label' => 'Confused', 'color' => 'warning'],
                        'drowsy'       => ['label' => 'Drowsy', 'color' => 'warning'],
                        'unresponsive' => ['label' => 'Unresponsive', 'color' => 'error'],
                    ];
                    $cLevel = $consciousnessLabels[$triage->consciousness_level] ?? $consciousnessLabels['alert'];
                    ?>
                    <span class="badge badge-<?= $cLevel['color'] ?> badge-lg"><?= $cLevel['label'] ?></span>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">Skala Nyeri</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-3xl font-bold"><?= $triage->pain_scale ?>/10</span>
                        <progress class="progress progress-warning w-32" value="<?= $triage->pain_scale ?>" max="10"></progress>
                    </div>
                </div>
            </div>

            <?php if (!empty($triage->notes)): ?>
                <div class="divider"></div>
                <h3 class="font-semibold mb-2">Catatan</h3>
                <p class="whitespace-pre-line"><?= esc($triage->notes) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="flex justify-end gap-3">
        <a href="<?= site_url('registration/' . $triage->visit_id) ?>" class="btn btn-ghost">Kembali</a>
    </div>
</div>
<?= $this->endSection() ?>
