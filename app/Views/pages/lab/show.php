<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('lab') ?>">Order Lab</a></li>
                <li><?= esc($order->order_number) ?></li>
            </ul>
        </div>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mt-2 gap-4">
            <div>
                <h1 class="text-2xl font-bold">Detail Order Lab</h1>
                <p class="text-base-content/70"><?= esc($order->order_number) ?></p>
            </div>
            <div class="flex gap-2">
                <a href="<?= site_url('lab') ?>" class="btn btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali
                </a>
                <?php if ($order->status === 'completed'): ?>
                    <a href="<?= site_url('lab/' . $order->uuid . '/print') ?>" class="btn btn-ghost" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        Cetak
                    </a>
                <?php endif; ?>
                <?php if (!in_array($order->status, ['completed', 'cancelled'])): ?>
                    <a href="<?= site_url('lab/' . $order->uuid . '/results') ?>" class="btn btn-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Input Hasil
                    </a>
                    <form action="<?= site_url('lab/' . $order->uuid . '/cancel') ?>" method="POST" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-error" onclick="return confirm('Batalkan order lab ini?')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Batalkan
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Order Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card bg-base-100 shadow-sm lg:col-span-2">
            <div class="card-body">
                <h2 class="card-title text-lg border-b pb-2">Informasi Order</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <div class="text-sm text-base-content/70">No. Order</div>
                        <div class="font-bold text-primary"><?= esc($order->order_number) ?></div>
                    </div>
                    <div>
                        <div class="text-sm text-base-content/70">Tanggal Order</div>
                        <div class="font-medium"><?= esc($order->order_date) ?></div>
                    </div>
                    <div>
                        <div class="text-sm text-base-content/70">No. Kunjungan</div>
                        <div class="font-medium"><?= esc($order->visit_number ?? '-') ?></div>
                    </div>
                    <div>
                        <div class="text-sm text-base-content/70">Status</div>
                        <?php
                        $statusLabels = [
                            'pending'    => 'Pending',
                            'collected'  => 'Dikumpulkan',
                            'processing' => 'Diproses',
                            'completed'  => 'Selesai',
                            'cancelled'  => 'Dibatalkan',
                        ];
                        $statusClasses = [
                            'pending'    => 'badge-warning',
                            'collected'  => 'badge-info',
                            'processing' => 'badge-info',
                            'completed'  => 'badge-success',
                            'cancelled'  => 'badge-ghost',
                        ];
                        ?>
                        <span class="badge <?= $statusClasses[$order->status] ?? 'badge-ghost' ?>">
                            <?= $statusLabels[$order->status] ?? $order->status ?>
                        </span>
                    </div>
                    <div>
                        <div class="text-sm text-base-content/70">Pasien</div>
                        <div class="font-medium"><?= esc($order->patient_name ?? '-') ?></div>
                        <div class="text-xs text-base-content/50"><?= esc($order->mrn ?? '') ?></div>
                    </div>
                    <div>
                        <div class="text-sm text-base-content/70">Dokter</div>
                        <div class="font-medium">dr. <?= esc($order->doctor_name ?? '-') ?></div>
                    </div>
                </div>
                <?php if ($order->notes): ?>
                    <div class="mt-4">
                        <div class="text-sm text-base-content/70">Catatan</div>
                        <div class="mt-1 p-3 bg-base-200 rounded-lg text-sm"><?= nl2br(esc($order->notes)) ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg border-b pb-2">Ringkasan</h2>
                <div class="space-y-3 mt-4">
                    <div class="flex justify-between">
                        <span class="text-base-content/70">Jumlah Parameter</span>
                        <span class="font-bold"><?= count($order->items ?? []) ?> item</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-base-content/70">Hasil Diinput</span>
                        <span class="font-bold"><?= count(array_filter($order->items ?? [], fn($i) => !empty($i->result_value))) ?> item</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-base-content/70">Dibuat</span>
                        <span class="text-sm"><?= esc($order->created_at ?? '-') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-base-content/70">Diperbarui</span>
                        <span class="text-sm"><?= esc($order->updated_at ?? '-') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lab Order Items -->
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-lg border-b pb-2">Daftar Pemeriksaan</h2>
            <div class="overflow-x-auto mt-4">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Parameter</th>
                            <th>Kategori</th>
                            <th>Hasil</th>
                            <th>Satuan</th>
                            <th>Nilai Normal</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($order->items)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-8 text-base-content/50">
                                    Tidak ada item pemeriksaan.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($order->items as $i => $item): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td>
                                        <div class="font-medium"><?= esc($item->parameter_name) ?></div>
                                    </td>
                                    <td class="text-sm text-base-content/70"><?= esc($item->template_category ?? '-') ?></td>
                                    <td>
                                        <?php if (!empty($item->result_value)): ?>
                                            <span class="font-bold"><?= esc($item->result_value) ?></span>
                                        <?php else: ?>
                                            <span class="text-base-content/40">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-sm"><?= esc($item->unit ?? '-') ?></td>
                                    <td class="text-sm"><?= esc($item->normal_range ?? '-') ?></td>
                                    <td class="text-sm text-base-content/70"><?= esc($item->notes ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Results History -->
    <?php if (!empty($order->results)): ?>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg border-b pb-2">Riwayat Input Hasil</h2>
                <div class="overflow-x-auto mt-4">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order->results as $i => $result): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= esc($result->result_date) ?></td>
                                    <td class="text-sm text-base-content/70"><?= esc($result->notes ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
