<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6 max-w-3xl">
    <div class="flex items-center gap-2 mb-6">
        <a href="<?= base_url('nurses') ?>" class="btn btn-ghost btn-sm btn-square">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Tambah Perawat</h1>
            <p class="text-base-content/70">Tambahkan data perawat baru</p>
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

    <form action="<?= base_url('nurses') ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">Informasi Dasar</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">ID Karyawan <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="employee_id" value="<?= old('employee_id') ?>" placeholder="Masukkan ID karyawan" class="input input-bordered w-full <?= session('errors.employee_id') ? 'input-error' : '' ?>" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Nama Perawat <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="name" value="<?= old('name') ?>" placeholder="Masukkan nama perawat" class="input input-bordered w-full <?= session('errors.name') ? 'input-error' : '' ?>" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">SIP (Surat Izin Praktik)</span>
                        </label>
                        <input type="text" name="sip" value="<?= old('sip') ?>" placeholder="Nomor Surat Izin Praktik" class="input input-bordered w-full <?= session('errors.sip') ? 'input-error' : '' ?>" />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">User ID</span>
                        </label>
                        <input type="text" name="user_id" value="<?= old('user_id') ?>" placeholder="ID User (opsional)" class="input input-bordered w-full" />
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">Kontak</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Telepon</span>
                        </label>
                        <input type="text" name="phone" value="<?= old('phone') ?>" placeholder="08xxxxxxxxxx" class="input input-bordered w-full" />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Email</span>
                        </label>
                        <input type="email" name="email" value="<?= old('email') ?>" placeholder="perawat@email.com" class="input input-bordered w-full <?= session('errors.email') ? 'input-error' : '' ?>" />
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?= base_url('nurses') ?>" class="btn btn-ghost">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Simpan
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
