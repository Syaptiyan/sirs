<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="flex items-center gap-2 mb-6">
        <a href="<?= base_url('inventory') ?>" class="btn btn-ghost btn-sm btn-square">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Detail Inventaris</h1>
            <p class="text-base-content/70">Informasi lengkap inventaris</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Card -->
        <div class="lg:col-span-1">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body items-center text-center">
                    <div class="avatar placeholder mb-4">
                        <div class="bg-primary text-primary-content rounded-full w-24">
                            <span class="text-3xl"><?= strtoupper(substr($item->name, 0, 2)) ?></span>
                        </div>
                    </div>
                    <h2 class="card-title text-xl"><?= esc($item->name) ?></h2>
                    <span class="font-mono text-sm text-base-content/70"><?= esc($item->code) ?></span>
                    <span class="badge badge-outline mt-1"><?= esc($item->category) ?></span>

                    <div class="divider my-2"></div>

                    <?php
                    $conditionClass = match($item->condition) {
                        'good' => 'badge-success',
                        'fair' => 'badge-warning',
                        'poor' => 'badge-error',
                        'disposed' => 'badge-ghost',
                        default => 'badge-ghost',
                    };
                    $statusClass = match($item->status) {
                        'active' => 'badge-success',
                        'maintenance' => 'badge-warning',
                        'disposed' => 'badge-error',
                        default => 'badge-ghost',
                    };
                    ?>

                    <div class="flex gap-2">
                        <span class="badge <?= $conditionClass ?>"><?= ucfirst(esc($item->condition)) ?></span>
                        <span class="badge <?= $statusClass ?>"><?= ucfirst(esc($item->status)) ?></span>
                    </div>

                    <div class="w-full mt-4">
                        <a href="<?= base_url('inventory/' . $item->uuid . '/edit') ?>" class="btn btn-primary btn-sm w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            Edit Inventaris
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-lg">Informasi Aset</h3>
                    <div class="overflow-x-auto">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="font-medium text-base-content/70 w-40">Kode</td>
                                    <td><span class="font-mono"><?= esc($item->code) ?></span></td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-base-content/70">Nama</td>
                                    <td><?= esc($item->name) ?></td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-base-content/70">Kategori</td>
                                    <td><span class="badge badge-outline"><?= esc($item->category) ?></span></td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-base-content/70">Lokasi</td>
                                    <td><?= esc($item->location ?? '-') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-lg">Pembelian & Nilai</h3>
                    <div class="overflow-x-auto">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="font-medium text-base-content/70 w-40">Tanggal Pembelian</td>
                                    <td><?= $item->purchase_date ? date('d M Y', strtotime($item->purchase_date)) : '-' ?></td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-base-content/70">Harga Beli</td>
                                    <td><span class="text-lg font-semibold text-primary">Rp <?= number_format($item->purchase_price ?? 0, 2, ',', '.') ?></span></td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-base-content/70">Nilai Saat Ini</td>
                                    <td><span class="text-lg font-semibold">Rp <?= number_format($item->current_value ?? 0, 2, ',', '.') ?></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php if ($item->notes): ?>
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title text-lg">Catatan</h3>
                        <p class="text-base-content/80"><?= nl2br(esc($item->notes)) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-lg">Metadata</h3>
                    <div class="overflow-x-auto">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="font-medium text-base-content/70 w-40">Dibuat</td>
                                    <td><?= $item->created_at ? date('d M Y H:i', strtotime($item->created_at)) : '-' ?></td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-base-content/70">Diperbarui</td>
                                    <td><?= $item->updated_at ? date('d M Y H:i', strtotime($item->updated_at)) : '-' ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
