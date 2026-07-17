<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('patients') ?>">Pasien</a></li>
                <li><a href="<?= site_url('patients/' . $patient['id']) ?>"><?= esc($patient['name']) ?></a></li>
                <li>Edit</li>
            </ul>
        </div>
        <h1 class="text-2xl font-bold mt-2">Edit Data Pasien</h1>
        <p class="text-base-content/70">Perbarui data diri pasien <strong><?= esc($patient['name']) ?></strong></p>
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

    <form method="POST" action="<?= site_url('patients/' . $patient['id']) ?>" class="card bg-base-100 shadow-sm">
        <input type="hidden" name="_method" value="PUT" />
        <div class="card-body space-y-6">
            <h2 class="card-title text-lg border-b pb-2">Data Identitas</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">No. Rekam Medis (MRN) <span class="text-error">*</span></span></label>
                    <input type="text" name="mrn" class="input input-bordered <?= session('errors.mrn') ? 'input-error' : '' ?>"
                           value="<?= old('mrn', $patient['mrn']) ?>" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Nama Lengkap <span class="text-error">*</span></span></label>
                    <input type="text" name="name" class="input input-bordered <?= session('errors.name') ? 'input-error' : '' ?>"
                           value="<?= old('name', $patient['name']) ?>" required />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">NIK <span class="text-error">*</span></span></label>
                    <input type="text" name="nik" class="input input-bordered <?= session('errors.nik') ? 'input-error' : '' ?>"
                           value="<?= old('nik', $patient['nik']) ?>" maxlength="16" pattern="[0-9]{16}" placeholder="16 digit NIK" required />
                </div>
            </div>

            <h2 class="card-title text-lg border-b pb-2">Data Kelahiran & Demografi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Tempat Lahir <span class="text-error">*</span></span></label>
                    <input type="text" name="birth_place" class="input input-bordered <?= session('errors.birth_place') ? 'input-error' : '' ?>"
                           value="<?= old('birth_place', $patient['birth_place']) ?>" required />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Tanggal Lahir <span class="text-error">*</span></span></label>
                    <input type="date" name="birth_date" class="input input-bordered <?= session('errors.birth_date') ? 'input-error' : '' ?>"
                           value="<?= old('birth_date', $patient['birth_date']) ?>" required />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Jenis Kelamin <span class="text-error">*</span></span></label>
                    <select name="gender" class="select select-bordered <?= session('errors.gender') ? 'select-error' : '' ?>" required>
                        <option value="">-- Pilih --</option>
                        <option value="L" <?= old('gender', $patient['gender']) === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= old('gender', $patient['gender']) === 'P' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Golongan Darah</span></label>
                    <select name="blood_type" class="select select-bordered">
                        <option value="">-- Pilih --</option>
                        <?php foreach (['A', 'B', 'AB', 'O'] as $bt): ?>
                            <option value="<?= $bt ?>" <?= old('blood_type', $patient['blood_type'] ?? '') === $bt ? 'selected' : '' ?>><?= $bt ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Agama</span></label>
                    <select name="religion" class="select select-bordered">
                        <option value="">-- Pilih --</option>
                        <?php foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'] as $r): ?>
                            <option value="<?= $r ?>" <?= old('religion', $patient['religion'] ?? '') === $r ? 'selected' : '' ?>><?= $r ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Pendidikan</span></label>
                    <select name="education" class="select select-bordered">
                        <option value="">-- Pilih --</option>
                        <?php foreach (['Tidak Sekolah', 'SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2', 'S3'] as $e): ?>
                            <option value="<?= $e ?>" <?= old('education', $patient['education'] ?? '') === $e ? 'selected' : '' ?>><?= $e ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Pekerjaan</span></label>
                    <input type="text" name="occupation" class="input input-bordered" value="<?= old('occupation', $patient['occupation'] ?? '') ?>" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Status Pernikahan</span></label>
                    <select name="marital_status" class="select select-bordered">
                        <option value="">-- Pilih --</option>
                        <?php foreach (['Belum Menikah', 'Menikah', 'Cerai', 'Janda/Duda'] as $ms): ?>
                            <option value="<?= $ms ?>" <?= old('marital_status', $patient['marital_status'] ?? '') === $ms ? 'selected' : '' ?>><?= $ms ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <h2 class="card-title text-lg border-b pb-2">Kontak</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">No. Telepon <span class="text-error">*</span></span></label>
                    <input type="tel" name="phone" class="input input-bordered <?= session('errors.phone') ? 'input-error' : '' ?>"
                           value="<?= old('phone', $patient['phone']) ?>" placeholder="08xxxxxxxxxx" required />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Email</span></label>
                    <input type="email" name="email" class="input input-bordered <?= session('errors.email') ? 'input-error' : '' ?>"
                           value="<?= old('email', $patient['email'] ?? '') ?>" placeholder="email@example.com" />
                </div>
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">Alamat Lengkap <span class="text-error">*</span></span></label>
                <textarea name="address" class="textarea textarea-bordered <?= session('errors.address') ? 'textarea-error' : '' ?>"
                          rows="3" required><?= old('address', $patient['address']) ?></textarea>
            </div>

            <h2 class="card-title text-lg border-b pb-2">Kontak Darurat</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Nama Kontak Darurat <span class="text-error">*</span></span></label>
                    <input type="text" name="emergency_contact_name" class="input input-bordered <?= session('errors.emergency_contact_name') ? 'input-error' : '' ?>"
                           value="<?= old('emergency_contact_name', $patient['emergency_contact_name'] ?? '') ?>" required />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">No. Telepon Darurat <span class="text-error">*</span></span></label>
                    <input type="tel" name="emergency_contact_phone" class="input input-bordered <?= session('errors.emergency_contact_phone') ? 'input-error' : '' ?>"
                           value="<?= old('emergency_contact_phone', $patient['emergency_contact_phone'] ?? '') ?>" required />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Hubungan <span class="text-error">*</span></span></label>
                    <select name="emergency_contact_relation" class="select select-bordered" required>
                        <option value="">-- Pilih --</option>
                        <?php foreach (['Suami', 'Istri', 'Ayah', 'Ibu', 'Anak', 'Saudara', 'Kerabat', 'Teman', 'Lainnya'] as $rel): ?>
                            <option value="<?= $rel ?>" <?= old('emergency_contact_relation', $patient['emergency_contact_relation'] ?? '') === $rel ? 'selected' : '' ?>><?= $rel ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="divider"></div>
            <div class="flex justify-end gap-3">
                <a href="<?= site_url('patients/' . $patient['id']) ?>" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
