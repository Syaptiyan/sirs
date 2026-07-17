<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6 max-w-3xl">
    <div class="flex items-center gap-2 mb-6">
        <a href="<?= base_url('schedules') ?>" class="btn btn-ghost btn-sm btn-square">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Edit Jadwal Dokter</h1>
            <p class="text-base-content/70">Perbarui jadwal praktik dokter</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-error mb-4">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <ul class="list-disc list-inside text-sm">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= $err ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('schedules/' . $schedule->uuid) ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">Informasi Jadwal</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Dokter <span class="text-error">*</span></span>
                        </label>
                        <select name="doctor_id" class="select select-bordered w-full <?= session('errors.doctor_id') ? 'select-error' : '' ?>" required>
                            <option value="">Pilih Dokter</option>
                            <?php foreach ($doctors as $doc): ?>
                                <option value="<?= $doc->id ?>" <?= old('doctor_id', $schedule->doctor_id) == $doc->id ? 'selected' : '' ?>>
                                    dr. <?= esc($doc->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Poliklinik <span class="text-error">*</span></span>
                        </label>
                        <select name="polyclinic_id" class="select select-bordered w-full <?= session('errors.polyclinic_id') ? 'select-error' : '' ?>" required>
                            <option value="">Pilih Poliklinik</option>
                            <?php foreach ($polyclinics as $poly): ?>
                                <option value="<?= $poly->id ?>" <?= old('polyclinic_id', $schedule->polyclinic_id) == $poly->id ? 'selected' : '' ?>>
                                    <?= esc($poly->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Hari <span class="text-error">*</span></span>
                        </label>
                        <select name="day_of_week" class="select select-bordered w-full <?= session('errors.day_of_week') ? 'select-error' : '' ?>" required>
                            <option value="">Pilih Hari</option>
                            <?php foreach ($days as $key => $day): ?>
                                <option value="<?= $key ?>" <?= old('day_of_week', $schedule->day_of_week) == $key ? 'selected' : '' ?>>
                                    <?= esc($day) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Kuota</span>
                        </label>
                        <input type="number" name="quota" value="<?= old('quota', $schedule->quota) ?>" placeholder="20" min="1" class="input input-bordered w-full" />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Jam Mulai <span class="text-error">*</span></span>
                        </label>
                        <input type="time" name="start_time" value="<?= old('start_time', $schedule->start_time) ?>" class="input input-bordered w-full <?= session('errors.start_time') ? 'input-error' : '' ?>" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Jam Selesai <span class="text-error">*</span></span>
                        </label>
                        <input type="time" name="end_time" value="<?= old('end_time', $schedule->end_time) ?>" class="input input-bordered w-full <?= session('errors.end_time') ? 'input-error' : '' ?>" required />
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="cursor-pointer justify-start gap-3">
                            <input type="checkbox" name="is_active" value="1" class="checkbox checkbox-primary" <?= old('is_active', $schedule->is_active) ? 'checked' : '' ?> />
                            <span class="label-text font-medium">Aktif</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?= base_url('schedules') ?>" class="btn btn-ghost">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Perbarui
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
