<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6 max-w-3xl">
    <div class="flex items-center gap-2 mb-6">
        <a href="<?= base_url('inventory') ?>" class="btn btn-ghost btn-sm btn-square">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Tambah Inventaris</h1>
            <p class="text-base-content/70">Tambahkan data aset/inventaris baru</p>
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

    <form action="<?= base_url('inventory') ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">Informasi Dasar</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Kode <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="code" value="<?= old('code') ?>" placeholder="Contoh: INV-001" class="input input-bordered w-full <?= session('errors.code') ? 'input-error' : '' ?>" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Nama <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="name" value="<?= old('name') ?>" placeholder="Nama aset/inventaris" class="input input-bordered w-full <?= session('errors.name') ? 'input-error' : '' ?>" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Kategori <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="category" value="<?= old('category') ?>" placeholder="Contoh: Elektronik, Furniture" class="input input-bordered w-full <?= session('errors.category') ? 'input-error' : '' ?>" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Lokasi</span>
                        </label>
                        <input type="text" name="location" value="<?= old('location') ?>" placeholder="Lokasi penempatan" class="input input-bordered w-full" />
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">Pembelian & Nilai</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Tanggal Pembelian</span>
                        </label>
                        <input type="date" name="purchase_date" value="<?= old('purchase_date') ?>" class="input input-bordered w-full" />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Harga Beli (Rp)</span>
                        </label>
                        <input type="number" name="purchase_price" value="<?= old('purchase_price', 0) ?>" placeholder="0" min="0" step="0.01" class="input input-bordered w-full" />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Nilai Saat Ini (Rp)</span>
                        </label>
                        <input type="number" name="current_value" value="<?= old('current_value', 0) ?>" placeholder="0" min="0" step="0.01" class="input input-bordered w-full" />
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">Kondisi & Status</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Kondisi <span class="text-error">*</span></span>
                        </label>
                        <select name="condition" class="select select-bordered w-full <?= session('errors.condition') ? 'select-error' : '' ?>" required>
                            <option value="">Pilih Kondisi</option>
                            <option value="good" <?= old('condition') === 'good' ? 'selected' : '' ?>>Baik (Good)</option>
                            <option value="fair" <?= old('condition') === 'fair' ? 'selected' : '' ?>>Cukup (Fair)</option>
                            <option value="poor" <?= old('condition') === 'poor' ? 'selected' : '' ?>>Buruk (Poor)</option>
                            <option value="disposed" <?= old('condition') === 'disposed' ? 'selected' : '' ?>>Disposed</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Status <span class="text-error">*</span></span>
                        </label>
                        <select name="status" class="select select-bordered w-full <?= session('errors.status') ? 'select-error' : '' ?>" required>
                            <option value="">Pilih Status</option>
                            <option value="active" <?= old('status') === 'active' ? 'selected' : '' ?>>Aktif</option>
                            <option value="maintenance" <?= old('status') === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                            <option value="disposed" <?= old('status') === 'disposed' ? 'selected' : '' ?>>Disposed</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">Catatan</h2>
                <div class="form-control">
                    <textarea name="notes" class="textarea textarea-bordered h-24 w-full" placeholder="Catatan tambahan mengenai inventaris ini..."><?= old('notes') ?></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?= base_url('inventory') ?>" class="btn btn-ghost">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Simpan
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
