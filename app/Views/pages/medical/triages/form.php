<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('registration') ?>">Registrasi</a></li>
                <li><a href="<?= site_url('registration/' . $visit->id) ?>"><?= $visit->visit_number ?></a></li>
                <li>Triase IGD</li>
            </ul>
        </div>
        <h1 class="text-2xl font-bold mt-2">Form Triase IGD</h1>
        <p class="text-base-content/70">
            Pasien: <span class="font-semibold"><?= esc($visit->patient_name) ?></span>
            (MRN: <?= esc($visit->mrn) ?>)
        </p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-warning">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
            <div>
                <p class="font-semibold">Terdapat kesalahan validasi:</p>
                <ul class="list-disc list-inside text-sm">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= $err ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= site_url('medical/triages') ?>" class="space-y-6">
        <input type="hidden" name="visit_id" value="<?= $visit->id ?>">
        <input type="hidden" name="patient_id" value="<?= $visit->patient_id ?>">

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body space-y-6">
                <h2 class="card-title text-lg border-b pb-2">Tingkat Triase</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="triage_level" value="emergency" class="peer hidden" <?= old('triage_level') === 'emergency' ? 'checked' : '' ?> required>
                        <div class="border-2 border-base-300 rounded-lg p-4 text-center transition-all peer-checked:border-error peer-checked:bg-error/10 hover:border-error/50">
                            <div class="w-12 h-12 rounded-full bg-error mx-auto mb-2 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-error-content" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                            </div>
                            <div class="font-bold text-error text-lg">EMERGENCY</div>
                            <div class="text-sm text-base-content/70">Resusitasi / Kritis</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="triage_level" value="urgent" class="peer hidden" <?= old('triage_level') === 'urgent' ? 'checked' : '' ?>>
                        <div class="border-2 border-base-300 rounded-lg p-4 text-center transition-all peer-checked:border-warning peer-checked:bg-warning/10 hover:border-warning/50">
                            <div class="w-12 h-12 rounded-full bg-warning mx-auto mb-2 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-warning-content" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div class="font-bold text-warning text-lg">URGENT</div>
                            <div class="text-sm text-base-content/70">Darurat Tidak Kritis</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="triage_level" value="non_urgent" class="peer hidden" <?= old('triage_level') === 'non_urgent' ? 'checked' : '' ?>>
                        <div class="border-2 border-base-300 rounded-lg p-4 text-center transition-all peer-checked:border-success peer-checked:bg-success/10 hover:border-success/50">
                            <div class="w-12 h-12 rounded-full bg-success mx-auto mb-2 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-success-content" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div class="font-bold text-success text-lg">NON-URGENT</div>
                            <div class="text-sm text-base-content/70">Non Darurat</div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body space-y-6">
                <h2 class="card-title text-lg border-b pb-2">Keluhan Utama</h2>
                <div class="form-control">
                    <label class="label"><span class="label-text">Chief Complaint <span class="text-error">*</span></span></label>
                    <textarea name="chief_complaint" class="textarea textarea-bordered <?= session('errors.chief_complaint') ? 'textarea-error' : '' ?>"
                              rows="4" required placeholder="Deskripsikan keluhan utama pasien"><?= old('chief_complaint') ?></textarea>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body space-y-6">
                <h2 class="card-title text-lg border-b pb-2">Tanda Vital</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Tekanan Darah <span class="text-error">*</span></span></label>
                        <input type="text" name="vital_signs[blood_pressure]" class="input input-bordered <?= session('errors.vital_signs.blood_pressure') ? 'input-error' : '' ?>"
                               value="<?= old('vital_signs.blood_pressure') ?>" placeholder="120/80" required />
                        <label class="label"><span class="label-text-alt">Format: Sistol/Diastol (mmHg)</span></label>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Denyut Jantung <span class="text-error">*</span></span></label>
                        <input type="number" name="vital_signs[heart_rate]" class="input input-bordered <?= session('errors.vital_signs.heart_rate') ? 'input-error' : '' ?>"
                               value="<?= old('vital_signs.heart_rate') ?>" placeholder="80" min="1" required />
                        <label class="label"><span class="label-text-alt">x/menit</span></label>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Suhu Tubuh <span class="text-error">*</span></span></label>
                        <input type="number" name="vital_signs[temperature]" class="input input-bordered <?= session('errors.vital_signs.temperature') ? 'input-error' : '' ?>"
                               value="<?= old('vital_signs.temperature') ?>" placeholder="36.5" step="0.1" min="30" max="45" required />
                        <label class="label"><span class="label-text-alt">Celsius</span></label>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Frekuensi Napas <span class="text-error">*</span></span></label>
                        <input type="number" name="vital_signs[respiratory_rate]" class="input input-bordered <?= session('errors.vital_signs.respiratory_rate') ? 'input-error' : '' ?>"
                               value="<?= old('vital_signs.respiratory_rate') ?>" placeholder="20" min="1" required />
                        <label class="label"><span class="label-text-alt">x/menit</span></label>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">SpO2 <span class="text-error">*</span></span></label>
                        <input type="number" name="vital_signs[spo2]" class="input input-bordered <?= session('errors.vital_signs.spo2') ? 'input-error' : '' ?>"
                               value="<?= old('vital_signs.spo2') ?>" placeholder="98" step="0.1" min="0" max="100" required />
                        <label class="label"><span class="label-text-alt">%</span></label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body space-y-6">
                <h2 class="card-title text-lg border-b pb-2">Penilaian Klinis</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Tingkat Kesadaran <span class="text-error">*</span></span></label>
                        <div class="space-y-2">
                            <?php
                            $consciousnessLevels = [
                                'alert'        => ['label' => 'Alert', 'desc' => 'Sadar penuh', 'color' => 'success'],
                                'confused'     => ['label' => 'Confused', 'desc' => 'Bingung', 'color' => 'warning'],
                                'drowsy'       => ['label' => 'Drowsy', 'desc' => 'Mengantuk', 'color' => 'warning'],
                                'unresponsive' => ['label' => 'Unresponsive', 'desc' => 'Tidak responsif', 'color' => 'error'],
                            ];
                            ?>
                            <?php foreach ($consciousnessLevels as $value => $config): ?>
                                <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-base-200 transition-colors">
                                    <input type="radio" name="consciousness_level" value="<?= $value ?>"
                                           class="radio radio-<?= $config['color'] ?>"
                                           <?= old('consciousness_level', 'alert') === $value ? 'checked' : '' ?> <?= $value === 'alert' ? 'required' : '' ?> />
                                    <div>
                                        <div class="font-semibold"><?= $config['label'] ?></div>
                                        <div class="text-sm text-base-content/70"><?= $config['desc'] ?></div>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">Skala Nyeri (0-10) <span class="text-error">*</span></span></label>
                        <input type="range" name="pain_scale" min="0" max="10" value="<?= old('pain_scale', 0) ?>"
                               class="range range-primary" step="1" id="pain_scale_input" required
                               oninput="document.getElementById('pain_scale_value').textContent = this.value" />
                        <div class="w-full flex justify-between text-xs px-1 mt-1">
                            <span>0</span><span>1</span><span>2</span><span>3</span><span>4</span>
                            <span>5</span><span>6</span><span>7</span><span>8</span><span>9</span><span>10</span>
                        </div>
                        <div class="text-center mt-2">
                            <span class="text-2xl font-bold" id="pain_scale_value"><?= old('pain_scale', 0) ?></span>
                            <span class="text-sm text-base-content/70">
                                - <span id="pain_label">Tidak Nyeri</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body space-y-6">
                <h2 class="card-title text-lg border-b pb-2">Catatan Tambahan</h2>
                <div class="form-control">
                    <label class="label"><span class="label-text">Notes</span></label>
                    <textarea name="notes" class="textarea textarea-bordered" rows="3"
                              placeholder="Catatan tambahan (opsional)"><?= old('notes') ?></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?= site_url('registration/' . $visit->id) ?>" class="btn btn-ghost">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Simpan Triase
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const painInput = document.getElementById('pain_scale_input');
    const painLabel = document.getElementById('pain_label');

    function updatePainLabel(value) {
        const labels = ['Tidak Nyeri', 'Sangat Ringan', 'Ringan', 'Ringan-Sedang', 'Sedang',
                        'Sedang-Berat', 'Berat', 'Sangat Berat', 'Sangat Berat', 'Sangat Berat', 'Nyeri Tak Tertahankan'];
        painLabel.textContent = labels[value] || '';
    }

    painInput.addEventListener('input', function() {
        updatePainLabel(this.value);
    });

    updatePainLabel(painInput.value);
});
</script>
<?= $this->endSection() ?>
