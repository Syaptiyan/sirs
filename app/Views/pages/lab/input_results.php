<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('lab') ?>">Order Lab</a></li>
                <li><a href="<?= site_url('lab/' . $order->uuid) ?>"><?= esc($order->order_number) ?></a></li>
                <li>Input Hasil</li>
            </ul>
        </div>
        <h1 class="text-2xl font-bold mt-2">Input Hasil Lab</h1>
        <p class="text-base-content/70"><?= esc($order->order_number) ?> - <?= esc($order->patient_name ?? '-') ?></p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= site_url('lab/' . $order->uuid . '/results') ?>">
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
            </div>
        </div>

        <!-- Results Input -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg border-b pb-2">Hasil Pemeriksaan</h2>
                <div class="overflow-x-auto mt-4">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Parameter</th>
                                <th>Satuan</th>
                                <th>Nilai Normal</th>
                                <th>Hasil <span class="text-error">*</span></th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($order->items)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-base-content/50">
                                        Tidak ada item pemeriksaan.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($order->items as $i => $item): ?>
                                    <tr>
                                        <td>
                                            <?= $i + 1 ?>
                                            <input type="hidden" name="items[<?= $i ?>][id]" value="<?= esc($item->id) ?>">
                                        </td>
                                        <td>
                                            <div class="font-medium"><?= esc($item->parameter_name) ?></div>
                                            <?php if ($item->template_name): ?>
                                                <div class="text-xs text-base-content/50"><?= esc($item->template_category ?? '') ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-sm"><?= esc($item->unit ?? '-') ?></td>
                                        <td class="text-sm"><?= esc($item->normal_range ?? '-') ?></td>
                                        <td>
                                            <input type="text"
                                                   name="items[<?= $i ?>][result_value]"
                                                   value="<?= esc($item->result_value ?? '') ?>"
                                                   class="input input-bordered input-sm w-full max-w-xs"
                                                   placeholder="Masukkan hasil"
                                                   required>
                                        </td>
                                        <td>
                                            <input type="text"
                                                   name="items[<?= $i ?>][notes]"
                                                   value="<?= esc($item->notes ?? '') ?>"
                                                   class="input input-bordered input-sm w-full max-w-xs"
                                                   placeholder="Keterangan">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Notes -->
                <div class="form-control mt-6">
                    <label class="label"><span class="label-text">Catatan Hasil</span></label>
                    <textarea name="notes" class="textarea textarea-bordered" rows="3" placeholder="Catatan tambahan untuk hasil lab"></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="<?= site_url('lab/' . $order->uuid) ?>" class="btn btn-ghost">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Simpan Hasil
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
