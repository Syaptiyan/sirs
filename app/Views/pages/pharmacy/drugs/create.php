<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="<?= base_url('pharmacy/drugs') ?>" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Kembali
        </a>
        <div>
            <h1 class="text-2xl font-bold">Tambah Obat</h1>
            <p class="text-base-content/70">Tambahkan obat baru ke dalam sistem</p>
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
            <form action="<?= base_url('pharmacy/drugs') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Kode Obat *</span></label>
                        <input type="text" name="code" value="<?= old('code') ?>" class="input input-bordered w-full" required maxlength="20" />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">Nama Obat *</span></label>
                        <input type="text" name="name" value="<?= old('name') ?>" class="input input-bordered w-full" required maxlength="200" />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">Nama Generik</span></label>
                        <input type="text" name="generic_name" value="<?= old('generic_name') ?>" class="input input-bordered w-full" maxlength="200" />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">Kategori *</span></label>
                        <select name="category_id" class="select select-bordered w-full" required>
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat->id ?>" <?= old('category_id') == $cat->id ? 'selected' : '' ?>><?= esc($cat->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">Bentuk Sediaan *</span></label>
                        <select name="form" class="select select-bordered w-full" required>
                            <option value="">Pilih Bentuk</option>
                            <option value="tablet" <?= old('form') === 'tablet' ? 'selected' : '' ?>>Tablet</option>
                            <option value="kapsul" <?= old('form') === 'kapsul' ? 'selected' : '' ?>>Kapsul</option>
                            <option value="sirup" <?= old('form') === 'sirup' ? 'selected' : '' ?>>Sirup</option>
                            <option value="injeksi" <?= old('form') === 'injeksi' ? 'selected' : '' ?>>Injeksi</option>
                            <option value="salep" <?= old('form') === 'salep' ? 'selected' : '' ?>>Salep</option>
                            <option value="tetes" <?= old('form') === 'tetes' ? 'selected' : '' ?>>Tetes</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">Kekuatan</span></label>
                        <input type="text" name="strength" value="<?= old('strength') ?>" class="input input-bordered w-full" placeholder="contoh: 500mg" maxlength="100" />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">Satuan *</span></label>
                        <input type="text" name="unit" value="<?= old('unit') ?>" class="input input-bordered w-full" placeholder="contoh: tablet, botol, strip" required maxlength="20" />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">Pabrikan</span></label>
                        <input type="text" name="manufacturer" value="<?= old('manufacturer') ?>" class="input input-bordered w-full" maxlength="200" />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">Harga Beli</span></label>
                        <input type="number" name="buy_price" value="<?= old('buy_price', 0) ?>" class="input input-bordered w-full" step="0.01" min="0" />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">Harga Jual</span></label>
                        <input type="number" name="sell_price" value="<?= old('sell_price', 0) ?>" class="input input-bordered w-full" step="0.01" min="0" />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">Stok Minimum</span></label>
                        <input type="number" name="min_stock" value="<?= old('min_stock', 0) ?>" class="input input-bordered w-full" min="0" />
                    </div>
                </div>

                <div class="divider"></div>

                <div class="flex justify-end gap-2">
                    <a href="<?= base_url('pharmacy/drugs') ?>" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
