<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center gap-2">
        <a href="/roles" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold">Tambah Role</h1>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                    <li><?= $err ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" action="/roles">
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Nama Role <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="name" class="input input-bordered w-full" value="<?= old('name') ?>" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Slug</span>
                        </label>
                        <input type="text" name="slug" class="input input-bordered w-full" value="<?= old('slug') ?>" placeholder="Otomatis dari nama" />
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">Kosongkan untuk generate otomatis</span>
                        </label>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Deskripsi</span>
                    </label>
                    <textarea name="description" class="textarea textarea-bordered w-full" rows="3"><?= old('description') ?></textarea>
                </div>
            </div>
            <div class="card-actions justify-end p-4 border-t border-base-200">
                <a href="/roles" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
