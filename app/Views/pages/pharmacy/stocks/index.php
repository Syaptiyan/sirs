<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold">Stok Obat</h1>
            <p class="text-base-content/70">Kelola penerimaan, distribusi, dan pengembalian stok</p>
        </div>
        <div class="flex gap-2">
            <button class="btn btn-success btn-sm" onclick="document.getElementById('modal-receive').showModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Terima Stok
            </button>
            <button class="btn btn-warning btn-sm" onclick="document.getElementById('modal-distribute').showModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                Distribusi
            </button>
            <button class="btn btn-info btn-sm" onclick="document.getElementById('modal-return').showModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                Retur
            </button>
            <button class="btn btn-outline btn-sm" onclick="document.getElementById('modal-opname').showModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                Opname
            </button>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Filter -->
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body p-4">
            <form action="<?= base_url('pharmacy/stocks') ?>" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="form-control sm:w-64">
                    <select name="drug_id" class="select select-bordered w-full">
                        <option value="">Semua Obat</option>
                        <?php foreach ($drugs as $d): ?>
                            <option value="<?= $d->id ?>" <?= ($drugId ?? '') == $d->id ? 'selected' : '' ?>><?= esc($d->code . ' - ' . $d->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="<?= base_url('pharmacy/stocks') ?>" class="btn btn-ghost btn-sm">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card bg-base-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Obat</th>
                        <th>Batch</th>
                        <th>Kadaluarsa</th>
                        <th>Qty</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($stocks)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-8 text-base-content/50">
                                Tidak ada data stok.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($stocks as $i => $s): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td class="font-mono text-sm"><?= esc($s->drug_code ?? '-') ?></td>
                                <td><?= esc($s->drug_name ?? '-') ?></td>
                                <td class="font-mono text-sm"><?= esc($s->batch_number ?? '-') ?></td>
                                <td><?= esc($s->expiry_date ?? '-') ?></td>
                                <td><span class="font-bold"><?= $s->quantity ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Receive Stock -->
<dialog id="modal-receive" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Terima Stok</h3>
        <form action="<?= base_url('pharmacy/stocks/receive') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Obat *</span></label>
                <select name="drug_id" class="select select-bordered w-full" required>
                    <option value="">Pilih Obat</option>
                    <?php foreach ($drugs as $d): ?>
                        <option value="<?= $d->id ?>"><?= esc($d->code . ' - ' . $d->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Batch ID *</span></label>
                <input type="number" name="batch_id" class="input input-bordered w-full" required />
            </div>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Jumlah *</span></label>
                <input type="number" name="quantity" class="input input-bordered w-full" required min="1" />
            </div>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Tanggal Terima *</span></label>
                <input type="date" name="receipt_date" class="input input-bordered w-full" value="<?= date('Y-m-d') ?>" required />
            </div>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Catatan</span></label>
                <textarea name="notes" class="textarea textarea-bordered w-full" rows="2"></textarea>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('modal-receive').close()">Batal</button>
                <button type="submit" class="btn btn-success">Terima</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<!-- Modal: Distribute Stock -->
<dialog id="modal-distribute" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Distribusi Stok</h3>
        <form action="<?= base_url('pharmacy/stocks/distribute') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Obat *</span></label>
                <select name="drug_id" class="select select-bordered w-full" required>
                    <option value="">Pilih Obat</option>
                    <?php foreach ($drugs as $d): ?>
                        <option value="<?= $d->id ?>"><?= esc($d->code . ' - ' . $d->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Batch ID *</span></label>
                <input type="number" name="batch_id" class="input input-bordered w-full" required />
            </div>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Jumlah *</span></label>
                <input type="number" name="quantity" class="input input-bordered w-full" required min="1" />
            </div>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Resep ID</span></label>
                <input type="number" name="prescription_id" class="input input-bordered w-full" />
            </div>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Catatan</span></label>
                <textarea name="notes" class="textarea textarea-bordered w-full" rows="2"></textarea>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('modal-distribute').close()">Batal</button>
                <button type="submit" class="btn btn-warning">Distribusi</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<!-- Modal: Return Stock -->
<dialog id="modal-return" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Retur Stok</h3>
        <form action="<?= base_url('pharmacy/stocks/return') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Obat *</span></label>
                <select name="drug_id" class="select select-bordered w-full" required>
                    <option value="">Pilih Obat</option>
                    <?php foreach ($drugs as $d): ?>
                        <option value="<?= $d->id ?>"><?= esc($d->code . ' - ' . $d->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Batch ID *</span></label>
                <input type="number" name="batch_id" class="input input-bordered w-full" required />
            </div>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Jumlah *</span></label>
                <input type="number" name="quantity" class="input input-bordered w-full" required min="1" />
            </div>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Alasan Retur *</span></label>
                <textarea name="reason" class="textarea textarea-bordered w-full" rows="2" required></textarea>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('modal-return').close()">Batal</button>
                <button type="submit" class="btn btn-info">Retur</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<!-- Modal: Stock Opname -->
<dialog id="modal-opname" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Stok Opname</h3>
        <form action="<?= base_url('pharmacy/stocks/opname') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Obat *</span></label>
                <select name="drug_id" class="select select-bordered w-full" required>
                    <option value="">Pilih Obat</option>
                    <?php foreach ($drugs as $d): ?>
                        <option value="<?= $d->id ?>"><?= esc($d->code . ' - ' . $d->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Jumlah Aktual *</span></label>
                <input type="number" name="actual_quantity" class="input input-bordered w-full" required min="0" />
            </div>
            <div class="form-control mt-3">
                <label class="label"><span class="label-text">Catatan</span></label>
                <textarea name="notes" class="textarea textarea-bordered w-full" rows="2"></textarea>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('modal-opname').close()">Batal</button>
                <button type="submit" class="btn btn-outline">Simpan Opname</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>
<?= $this->endSection() ?>
