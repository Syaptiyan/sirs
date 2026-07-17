<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6 max-w-3xl">
    <div class="flex items-center gap-2 mb-6">
        <a href="<?= base_url('rooms') ?>" class="btn btn-ghost btn-sm btn-square">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Tambah Kamar</h1>
            <p class="text-base-content/70">Tambahkan data kamar baru</p>
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

    <form action="<?= base_url('rooms') ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">Informasi Kamar</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Tipe Kamar <span class="text-error">*</span></span>
                        </label>
                        <select name="room_type_id" class="select select-bordered w-full <?= session('errors.room_type_id') ? 'select-error' : '' ?>" required>
                            <option value="">Pilih Tipe Kamar</option>
                            <?php foreach ($roomTypes as $type): ?>
                                <option value="<?= $type->id ?>" <?= old('room_type_id') == $type->id ? 'selected' : '' ?>>
                                    <?= esc($type->name) ?><?= $type->base_price > 0 ? ' - Rp ' . number_format($type->base_price, 0, ',', '.') : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Nomor Kamar <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="room_number" value="<?= old('room_number') ?>" placeholder="Contoh: R001" class="input input-bordered w-full uppercase <?= session('errors.room_number') ? 'input-error' : '' ?>" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Lantai</span>
                        </label>
                        <input type="number" name="floor" value="<?= old('floor') ?>" placeholder="Contoh: 1" min="0" class="input input-bordered w-full <?= session('errors.floor') ? 'input-error' : '' ?>" />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Kapasitas <span class="text-error">*</span></span>
                        </label>
                        <input type="number" name="capacity" value="<?= old('capacity', 1) ?>" placeholder="1" min="1" class="input input-bordered w-full <?= session('errors.capacity') ? 'input-error' : '' ?>" required />
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text font-medium">Catatan</span>
                        </label>
                        <textarea name="notes" class="textarea textarea-bordered h-24 w-full" placeholder="Catatan tambahan tentang kamar..."><?= old('notes') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?= base_url('rooms') ?>" class="btn btn-ghost">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Simpan
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
