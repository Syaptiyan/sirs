<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('radiology') ?>">Order Radiologi</a></li>
                <li><?= esc($order->order_number) ?></li>
            </ul>
        </div>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mt-2 gap-4">
            <div>
                <h1 class="text-2xl font-bold">Detail Order Radiologi</h1>
                <p class="text-base-content/70"><?= esc($order->order_number) ?></p>
            </div>
            <div class="flex gap-2">
                <a href="<?= site_url('radiology') ?>" class="btn btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali
                </a>
                <?php if (!in_array($order->status, ['completed', 'cancelled'])): ?>
                    <a href="<?= site_url('radiology/' . $order->uuid . '/input-result') ?>" class="btn btn-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Input Hasil
                    </a>
                    <form action="<?= site_url('radiology/' . $order->uuid . '/cancel') ?>" method="POST" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-error" onclick="return confirm('Batalkan order radiologi ini?')">
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
                            'in_progress'=> 'Diproses',
                            'completed'  => 'Selesai',
                            'cancelled'  => 'Dibatalkan',
                        ];
                        $statusClasses = [
                            'pending'    => 'badge-warning',
                            'in_progress'=> 'badge-info',
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
                    <?php if ($order->template_name): ?>
                        <div>
                            <div class="text-sm text-base-content/70">Template</div>
                            <div class="font-medium"><?= esc($order->template_name) ?></div>
                            <div class="text-xs text-base-content/50"><?= esc($order->template_category ?? '') ?></div>
                        </div>
                    <?php endif; ?>
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
                        <span class="text-base-content/70">Jumlah Hasil</span>
                        <span class="font-bold"><?= count($order->results ?? []) ?> item</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-base-content/70">Jumlah Gambar</span>
                        <span class="font-bold"><?= count($order->images ?? []) ?> file</span>
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

    <!-- Results -->
    <?php if (!empty($order->results)): ?>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg border-b pb-2">Hasil Radiologi</h2>
                <div class="space-y-4 mt-4">
                    <?php foreach ($order->results as $result): ?>
                        <div class="border rounded-lg p-4 bg-base-200/50">
                            <div class="flex justify-between items-start mb-2">
                                <div class="text-sm text-base-content/70">
                                    Tanggal: <?= esc($result->result_date) ?>
                                </div>
                                <?php if ($result->result_by_name): ?>
                                    <div class="text-sm text-base-content/70">
                                        Oleh: <?= esc($result->result_by_name) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mt-2">
                                <div class="text-sm font-semibold text-base-content/70 mb-1">Temuan:</div>
                                <div class="p-3 bg-base-100 rounded text-sm"><?= nl2br(esc($result->result_text)) ?></div>
                            </div>
                            <?php if ($result->impression): ?>
                                <div class="mt-3">
                                    <div class="text-sm font-semibold text-base-content/70 mb-1">Impresi:</div>
                                    <div class="p-3 bg-base-100 rounded text-sm"><?= nl2br(esc($result->impression)) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Images -->
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <div class="flex justify-between items-center border-b pb-2">
                <h2 class="card-title text-lg">Gambar Radiologi</h2>
                <?php if (!in_array($order->status, ['cancelled'])): ?>
                    <form action="<?= site_url('radiology/' . $order->uuid . '/upload-image') ?>" method="POST" enctype="multipart/form-data" class="flex gap-2">
                        <?= csrf_field() ?>
                        <input type="file" name="image" class="file-input file-input-bordered file-input-sm w-full max-w-xs" accept="image/*,.dcm" required>
                        <input type="text" name="description" class="input input-bordered input-sm" placeholder="Deskripsi (opsional)">
                        <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                    </form>
                <?php endif; ?>
            </div>

            <?php if (empty($order->images)): ?>
                <div class="text-center py-8 text-base-content/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    <p>Belum ada gambar diupload.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
                    <?php foreach ($order->images as $image): ?>
                        <div class="border rounded-lg overflow-hidden">
                            <div class="aspect-square bg-base-200 flex items-center justify-center">
                                <img src="<?= base_url($image->file_path) ?>" alt="<?= esc($image->file_name) ?>" class="object-contain h-full w-full">
                            </div>
                            <div class="p-2">
                                <div class="text-sm font-medium truncate" title="<?= esc($image->file_name) ?>"><?= esc($image->file_name) ?></div>
                                <?php if ($image->description): ?>
                                    <div class="text-xs text-base-content/50 truncate"><?= esc($image->description) ?></div>
                                <?php endif; ?>
                                <div class="text-xs text-base-content/40 mt-1">
                                    <?= $image->file_size ? number_format($image->file_size / 1024, 1) . ' KB' : '' ?>
                                    <?= esc($image->created_at) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
