<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('payments') ?>">Pembayaran</a></li>
                <li><?= esc($payment->payment_number) ?></li>
            </ul>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-2">
            <div>
                <h1 class="text-2xl font-bold">Detail Pembayaran</h1>
                <p class="text-base-content/70">
                    No. Pembayaran: <span class="font-mono badge badge-primary"><?= esc($payment->payment_number) ?></span>
                </p>
            </div>
            <div class="flex gap-2">
                <a href="<?= site_url('payments/' . $payment->uuid . '/receipt') ?>" class="btn btn-outline btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Cetak Receipt
                </a>
                <a href="<?= site_url('payments') ?>" class="btn btn-ghost btn-sm">Kembali</a>
            </div>
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Payment Info -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-lg border-b pb-2">Data Pembayaran</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="text-base-content/70">No. Pembayaran</div>
                    <div class="font-mono font-medium"><?= esc($payment->payment_number) ?></div>
                    <div class="text-base-content/70">No. Tagihan</div>
                    <div class="font-mono">
                        <a href="<?= site_url('billing/' . ($payment->invoice_uuid ?? '')) ?>" class="text-primary hover:underline">
                            <?= esc($payment->invoice_number ?? '-') ?>
                        </a>
                    </div>
                    <div class="text-base-content/70">Tanggal Pembayaran</div>
                    <div><?= date('d/m/Y', strtotime($payment->payment_date)) ?></div>
                    <div class="text-base-content/70">Metode Pembayaran</div>
                    <div>
                        <span class="badge badge-sm badge-outline"><?= esc($payment->payment_method_name ?? '-') ?></span>
                    </div>
                    <?php if ($payment->reference_number): ?>
                        <div class="text-base-content/70">No. Referensi</div>
                        <div class="font-mono"><?= esc($payment->reference_number) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Patient Info -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-lg border-b pb-2">Data Pasien</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="text-base-content/70">Nama</div>
                    <div class="font-medium"><?= esc($payment->patient_name ?? '-') ?></div>
                    <div class="text-base-content/70">No. RM</div>
                    <div class="font-mono"><?= esc($payment->mrn ?? '-') ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Amount -->
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <h3 class="card-title text-lg border-b pb-2">Jumlah Pembayaran</h3>
            <div class="text-center py-6">
                <div class="text-4xl font-bold text-primary">
                    Rp <?= number_format($payment->amount, 0, ',', '.') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Info -->
    <?php if ($receipt): ?>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-lg border-b pb-2">Informasi Receipt</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="text-base-content/70">No. Receipt</div>
                    <div class="font-mono font-medium"><?= esc($receipt->receipt_number) ?></div>
                    <div class="text-base-content/70">Dibuat</div>
                    <div><?= $receipt->created_at ? date('d/m/Y H:i', strtotime($receipt->created_at)) : '-' ?></div>
                </div>
                <div class="mt-4">
                    <a href="<?= site_url('payments/' . $payment->uuid . '/receipt') ?>" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        Cetak Receipt
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Notes -->
    <?php if ($payment->notes): ?>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-lg border-b pb-2">Catatan</h3>
                <p class="text-sm"><?= nl2br(esc($payment->notes)) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Timestamps -->
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <h3 class="card-title text-lg border-b pb-2">Riwayat Waktu</h3>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="text-base-content/70">Dibuat</div>
                <div><?= $payment->created_at ? date('d/m/Y H:i', strtotime($payment->created_at)) : '-' ?></div>
                <div class="text-base-content/70">Diperbarui</div>
                <div><?= $payment->updated_at ? date('d/m/Y H:i', strtotime($payment->updated_at)) : '-' ?></div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
