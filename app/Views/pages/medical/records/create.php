<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('registration') ?>">Registrasi</a></li>
                <li><a href="<?= site_url('registration/' . $visit->id) ?>"><?= $visit->visit_number ?></a></li>
                <li>Buat Rekam Medis</li>
            </ul>
        </div>
        <h1 class="text-2xl font-bold mt-2">Buat Rekam Medis</h1>
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

    <form method="POST" action="<?= site_url('medical/records') ?>" class="space-y-6">
        <input type="hidden" name="visit_id" value="<?= $visit->id ?>">
        <input type="hidden" name="patient_id" value="<?= $visit->patient_id ?>">

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body space-y-4">
                <h2 class="card-title text-lg border-b pb-2">Informasi Dasar</h2>
                <div class="form-control">
                    <label class="label"><span class="label-text">Dokter <span class="text-error">*</span></span></label>
                    <select name="doctor_id" class="select select-bordered" required>
                        <option value="">Pilih Dokter</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?= $doctor->id ?>" <?= old('doctor_id') == $doctor->id ? 'selected' : '' ?>>
                                <?= esc($doctor->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="card-title text-lg">SOAP Notes</h2>
                    <div class="dropdown dropdown-end">
                        <label tabindex="0" class="btn btn-sm btn-ghost">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                            Template
                        </label>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <?php foreach ($templates as $template): ?>
                                <li><a onclick="applyTemplate('<?= esc($template->content, 'js') ?>')"><?= esc($template->name) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">S - Subjective</span></label>
                    <textarea name="subjective" class="textarea textarea-bordered" rows="4"
                              placeholder="Keluhan pasien, riwayat penyakit sekarang"><?= old('subjective') ?></textarea>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">O - Objective</span></label>
                    <textarea name="objective" class="textarea textarea-bordered" rows="4"
                              placeholder="Hasil pemeriksaan fisik, tanda vital, hasil lab"><?= old('objective') ?></textarea>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">A - Assessment</span></label>
                    <textarea name="assessment" class="textarea textarea-bordered" rows="4"
                              placeholder="Diagnosis, diagnosis banding"><?= old('assessment') ?></textarea>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">P - Plan</span></label>
                    <textarea name="plan" class="textarea textarea-bordered" rows="4"
                              placeholder="Rencana terapi, tindakan, edukasi"><?= old('plan') ?></textarea>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body space-y-4">
                <h2 class="card-title text-lg border-b pb-2">Catatan Tambahan</h2>
                <div class="form-control">
                    <textarea name="notes" class="textarea textarea-bordered" rows="3"
                              placeholder="Catatan tambahan (opsional)"><?= old('notes') ?></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?= site_url('medical/records?visit_id=' . $visit->id) ?>" class="btn btn-ghost">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Simpan Rekam Medis
            </button>
        </div>
    </form>
</div>

<script>
function applyTemplate(content) {
    const textarea = document.querySelector('textarea[name="subjective"]');
    if (textarea && !textarea.value) {
        textarea.value = content;
    }
}
</script>
<?= $this->endSection() ?>
