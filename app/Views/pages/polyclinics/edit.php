<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6 max-w-3xl">
    <div class="flex items-center gap-2 mb-6">
        <a href="<?= base_url('polyclinics/' . $polyclinic->uuid) ?>" class="btn btn-ghost btn-sm btn-square">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Edit Poliklinik</h1>
            <p class="text-base-content/70">Perbarui data poliklinik</p>
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

    <form action="<?= base_url('polyclinics/' . $polyclinic->uuid) ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">Informasi Poliklinik</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Kode Poliklinik <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="code" value="<?= old('code', $polyclinic->code) ?>" placeholder="Contoh: POL001" class="input input-bordered w-full uppercase <?= session('errors.code') ? 'input-error' : '' ?>" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Nama Poliklinik <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="name" value="<?= old('name', $polyclinic->name) ?>" placeholder="Masukkan nama poliklinik" class="input input-bordered w-full <?= session('errors.name') ? 'input-error' : '' ?>" required />
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text font-medium">Deskripsi</span>
                        </label>
                        <textarea name="description" class="textarea textarea-bordered h-24 w-full" placeholder="Deskripsi singkat tentang poliklinik..."><?= old('description', $polyclinic->description) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">Layanan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Lokasi</span>
                        </label>
                        <input type="text" name="location" value="<?= old('location', $polyclinic->location) ?>" placeholder="Contoh: Lantai 1, Gedung A" class="input input-bordered w-full <?= session('errors.location') ? 'input-error' : '' ?>" />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Kuota Harian</span>
                        </label>
                        <input type="number" name="daily_quota" value="<?= old('daily_quota', $polyclinic->daily_quota) ?>" placeholder="20" min="1" class="input input-bordered w-full <?= session('errors.daily_quota') ? 'input-error' : '' ?>" />
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?= base_url('polyclinics/' . $polyclinic->uuid) ?>" class="btn btn-ghost">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Perbarui
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>