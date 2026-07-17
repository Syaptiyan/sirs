<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('radiology') ?>">Order Radiologi</a></li>
                <li><a href="<?= site_url('radiology/' . $order->uuid) ?>"><?= esc($order->order_number) ?></a></li>
                <li>Input Hasil</li>
            </ul>
        </div>
        <h1 class="text-2xl font-bold mt-2">Input Hasil Radiologi</h1>
        <p class="text-base-content/70"><?= esc($order->order_number) ?> - <?= esc($order->patient_name ?? '-') ?></p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-warning">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
            <div>
                <p class="font-semibold">Terdapat kesalahan validasi:</p>
                <ul class="list-disc list-inside text-sm">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= $err ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= site_url('radiology/' . $order->uuid . '/store-result') ?>">
        <?= csrf_field() ?>

        <!-- Order Info -->
        <div class="card bg-base-100 shadow-sm mb-6">
            <div class="card-body p-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-base-content/70">No. Order:</span>
                        <span class="font-medium ml-1"><?= esc($order->order_number) ?></span>
                    </div>
                    <div>
                        <span class="text-base-content/70">Pasien:</span>
                        <span class="font-medium ml-1"><?= esc($order->patient_name ?? '-') ?></span>
                    </div>
                    <div>
                        <span class="text-base-content/70">Dokter:</span>
                        <span class="font-medium ml-1">dr. <?= esc($order->doctor_name ?? '-') ?></span>
                    </div>
                    <div>
                        <span class="text-base-content/70">Tanggal:</span>
                        <span class="font-medium ml-1"><?= esc($order->order_date) ?></span>
                    </div>
                </div>
                <?php if ($order->template_name): ?>
                    <div class="mt-2 text-sm">
                        <span class="text-base-content/70">Pemeriksaan:</span>
                        <span class="font-medium ml-1"><?= esc($order->template_name) ?> (<?= esc($order->template_category ?? '') ?>)</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Result Input -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body space-y-6">
                <h2 class="card-title text-lg border-b pb-2">Hasil Pemeriksaan Radiologi</h2>

                <!-- Result Text -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Temuan / Hasil Pemeriksaan <span class="text-error">*</span></span>
                    </label>
                    <textarea name="result_text"
                              class="textarea textarea-bordered min-h-[200px]"
                              placeholder="Deskripsikan temuan hasil pemeriksaan radiologi secara detail..."
                              required><?= old('result_text') ?></textarea>
                </div>

                <!-- Impression -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Impresi / Kesimpulan</span>
                    </label>
                    <textarea name="impression"
                              class="textarea textarea-bordered min-h-[120px]"
                              placeholder="Tulis kesimpulan atau impresi dari hasil pemeriksaan..."><?= old('impression') ?></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="<?= site_url('radiology/' . $order->uuid) ?>" class="btn btn-ghost">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Simpan Hasil
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
