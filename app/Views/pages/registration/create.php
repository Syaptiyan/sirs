<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('registration') ?>">Kunjungan</a></li>
                <li>Pendaftaran Baru</li>
            </ul>
        </div>
        <h1 class="text-2xl font-bold mt-2">Pendaftaran Kunjungan</h1>
        <p class="text-base-content/70">Daftarkan kunjungan baru untuk pasien</p>
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

    <form method="POST" action="<?= site_url('registration') ?>" class="card bg-base-100 shadow-sm" x-data="{ visitType: 'RJ' }">
        <div class="card-body space-y-6">
            <h2 class="card-title text-lg border-b pb-2">Tipe Kunjungan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <?php foreach ($visitTypes as $vt): ?>
                    <label class="cursor-pointer">
                        <input type="radio" name="visit_type" value="<?= esc($vt->code) ?>" class="peer hidden"
                               x-model="visitType" <?= old('visit_type', 'RJ') === $vt->code ? 'checked' : '' ?> />
                        <div class="border-2 rounded-lg p-4 text-center transition-all peer-checked:border-primary peer-checked:bg-primary/10 hover:border-primary/50"
                             :class="{ 'border-primary bg-primary/10': visitType === '<?= esc($vt->code) ?>' }">
                            <div class="font-bold text-lg"><?= esc($vt->name) ?></div>
                            <div class="text-sm text-base-content/70"><?= esc($vt->code) ?></div>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>

            <h2 class="card-title text-lg border-b pb-2">Data Pasien</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text">Pasien <span class="text-error">*</span></span></label>
                    <select name="patient_id" class="select select-bordered <?= session('errors.patient_id') ? 'select-error' : '' ?>" required>
                        <option value="">-- Pilih Pasien --</option>
                        <?php foreach ($patients as $patient): ?>
                            <option value="<?= $patient->id ?>" <?= old('patient_id', $patientId ?? '') == $patient->id ? 'selected' : '' ?>>
                                <?= esc($patient->mrn) ?> - <?= esc($patient->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <h2 class="card-title text-lg border-b pb-2">Data Kunjungan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-show="visitType !== 'IGD'">
                <div class="form-control">
                    <label class="label"><span class="label-text">Dokter <span class="text-error">*</span></span></label>
                    <select name="doctor_id" class="select select-bordered <?= session('errors.doctor_id') ? 'select-error' : '' ?>">
                        <option value="">-- Pilih Dokter --</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?= $doctor->id ?>" <?= old('doctor_id') == $doctor->id ? 'selected' : '' ?>>
                                dr. <?= esc($doctor->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Poliklinik <span class="text-error">*</span></span></label>
                    <select name="polyclinic_id" class="select select-bordered <?= session('errors.polyclinic_id') ? 'select-error' : '' ?>">
                        <option value="">-- Pilih Poliklinik --</option>
                        <?php foreach ($polyclinics as $poly): ?>
                            <option value="<?= $poly->id ?>" <?= old('polyclinic_id') == $poly->id ? 'selected' : '' ?>>
                                <?= esc($poly->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-show="visitType === 'IGD'" x-cloak>
                <div class="form-control">
                    <label class="label"><span class="label-text">Dokter Jaga</span></label>
                    <select name="doctor_id" class="select select-bordered">
                        <option value="">-- Pilih Dokter (Opsional) --</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?= $doctor->id ?>" <?= old('doctor_id') == $doctor->id ? 'selected' : '' ?>>
                                dr. <?= esc($doctor->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-show="visitType === 'RI'" x-cloak>
                <div class="form-control">
                    <label class="label"><span class="label-text">Kamar <span class="text-error">*</span></span></label>
                    <select name="room_id" id="room_id" class="select select-bordered <?= session('errors.room_id') ? 'select-error' : '' ?>"
                            @change="fetchBeds($event.target.value)">
                        <option value="">-- Pilih Kamar --</option>
                        <?php foreach ($rooms as $room): ?>
                            <option value="<?= $room->id ?>" <?= old('room_id') == $room->id ? 'selected' : '' ?>>
                                <?= esc($room->room_number) ?> (Kapasitas: <?= $room->current_occupancy ?>/<?= $room->capacity ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Tempat Tidur</span></label>
                    <select name="bed_id" id="bed_id" class="select select-bordered">
                        <option value="">-- Pilih Tempat Tidur (Opsional) --</option>
                    </select>
                </div>
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">Keluhan</span></label>
                <textarea name="complaint" class="textarea textarea-bordered" rows="3" placeholder="Deskripsikan keluhan pasien"><?= old('complaint') ?></textarea>
            </div>

            <div class="divider"></div>
            <div class="flex justify-end gap-3">
                <a href="<?= site_url('registration') ?>" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Daftarkan Kunjungan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function fetchBeds(roomId) {
    const bedSelect = document.getElementById('bed_id');
    bedSelect.innerHTML = '<option value="">-- Pilih Tempat Tidur (Opsional) --</option>';

    if (!roomId) return;

    fetch('<?= site_url('registration/beds') ?>?room_id=' + roomId)
        .then(response => response.json())
        .then(beds => {
            beds.forEach(bed => {
                const option = document.createElement('option');
                option.value = bed.id;
                option.textContent = bed.bed_number;
                bedSelect.appendChild(option);
            });
        });
}
</script>
<?= $this->endSection() ?>
