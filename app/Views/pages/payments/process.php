<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('payments') ?>">Pembayaran</a></li>
                <li>Proses Pembayaran</li>
            </ul>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-2">
            <div>
                <h1 class="text-2xl font-bold">Proses Pembayaran</h1>
                <p class="text-base-content/70">Form pembayaran tagihan</p>
            </div>
            <a href="<?= site_url('payments') ?>" class="btn btn-ghost btn-sm">Kembali</a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($invoice) && $invoice): ?>
        <!-- Invoice Summary -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-lg border-b pb-2">Informasi Tagihan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="text-base-content/70">No. Tagihan</div>
                        <div class="font-mono font-medium"><?= esc($invoice->invoice_number) ?></div>
                        <div class="text-base-content/70">Pasien</div>
                        <div class="font-medium"><?= esc($invoice->patient_name ?? '-') ?></div>
                        <div class="text-base-content/70">No. RM</div>
                        <div class="font-mono"><?= esc($invoice->mrn ?? '-') ?></div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="text-base-content/70">Total Tagihan</div>
                        <div class="font-mono font-bold text-lg">Rp <?= number_format($invoice->total_amount, 0, ',', '.') ?></div>
                        <div class="text-base-content/70">Sudah Dibayar</div>
                        <div class="font-mono text-success">Rp <?= number_format($invoice->paid_amount, 0, ',', '.') ?></div>
                        <div class="text-base-content/70">Sisa Tagihan</div>
                        <div class="font-mono font-bold text-error">Rp <?= number_format($invoice->remaining_amount, 0, ',', '.') ?></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Payment Form -->
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <h3 class="card-title text-lg border-b pb-2">Form Pembayaran</h3>
            <form action="<?= site_url('payments/process') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">No. Tagihan <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="invoice_uuid" class="input input-bordered w-full" value="<?= esc($invoiceUuid ?? '') ?>" placeholder="UUID Tagihan" required />
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Metode Pembayaran <span class="text-error">*</span></span>
                    </label>
                    <select name="payment_method_id" class="select select-bordered w-full" required>
                        <option value="">Pilih Metode Pembayaran</option>
                        <?php foreach ($paymentMethods as $method): ?>
                            <option value="<?= $method->id ?>"><?= esc($method->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Jumlah Pembayaran (Rp) <span class="text-error">*</span></span>
                    </label>
                    <input type="number" name="amount" class="input input-bordered w-full" value="<?= esc($invoice->remaining_amount ?? '') ?>" min="0" step="0.01" required />
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">No. Referensi</span>
                    </label>
                    <input type="text" name="reference_number" class="input input-bordered w-full" placeholder="No. referensi (opsional)" />
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Catatan</span>
                    </label>
                    <textarea name="notes" class="textarea textarea-bordered w-full" rows="3" placeholder="Catatan pembayaran (opsional)"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="<?= site_url('payments') ?>" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Proses Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
