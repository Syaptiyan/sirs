<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="<?= base_url('warehouse/suppliers') ?>" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Kembali
        </a>
        <div>
            <h1 class="text-2xl font-bold">Tambah Supplier</h1>
            <p class="text-base-content/70">Tambahkan supplier baru ke dalam sistem</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-error mb-4">
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                    <li><?= $err ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <form action="<?= base_url('warehouse/suppliers') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Nama *</span></label>
                        <input type="text" name="name" value="<?= old('name') ?>" class="input input-bordered w-full" required maxlength="200" />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">Perusahaan</span></label>
                        <input type="text" name="company" value="<?= old('company') ?>" class="input input-bordered w-full" maxlength="200" />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">Telepon</span></label>
                        <input type="text" name="phone" value="<?= old('phone') ?>" class="input input-bordered w-full" maxlength="20" />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">Email</span></label>
                        <input type="email" name="email" value="<?= old('email') ?>" class="input input-bordered w-full" maxlength="150" />
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="label"><span class="label-text">Alamat</span></label>
                        <textarea name="address" class="textarea textarea-bordered w-full" rows="3"><?= old('address') ?></textarea>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="flex justify-end gap-2">
                    <a href="<?= base_url('warehouse/suppliers') ?>" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
