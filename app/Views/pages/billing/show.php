<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('billing') ?>">Tagihan</a></li>
                <li><?= esc($invoice->invoice_number) ?></li>
            </ul>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-2">
            <div>
                <h1 class="text-2xl font-bold">Detail Tagihan</h1>
                <p class="text-base-content/70">
                    No. Tagihan: <span class="font-mono badge badge-primary"><?= esc($invoice->invoice_number) ?></span>
                </p>
            </div>
            <div class="flex gap-2">
                <a href="<?= site_url('billing/' . $invoice->uuid . '/print') ?>" class="btn btn-outline btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Cetak
                </a>
                <a href="<?= site_url('billing') ?>" class="btn btn-ghost btn-sm">Kembali</a>
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

    <!-- Status Badge -->
    <div class="flex items-center gap-3">
        <?php
        $statusLabels = [
            'unpaid'    => 'Belum Dibayar',
            'partial'   => 'Sebagian',
            'paid'      => 'Lunas',
            'cancelled' => 'Dibatalkan',
        ];
        $statusClasses = [
            'unpaid'    => 'badge-error',
            'partial'   => 'badge-warning',
            'paid'      => 'badge-success',
            'cancelled' => 'badge-ghost',
        ];
        ?>
        <span class="badge badge-lg <?= $statusClasses[$invoice->status] ?? 'badge-ghost' ?>">
            <?= $statusLabels[$invoice->status] ?? $invoice->status ?>
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Invoice Info -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-lg border-b pb-2">Data Tagihan</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="text-base-content/70">No. Tagihan</div>
                    <div class="font-mono font-medium"><?= esc($invoice->invoice_number) ?></div>
                    <div class="text-base-content/70">No. Kunjungan</div>
                    <div class="font-mono"><?= esc($invoice->visit_number ?? '-') ?></div>
                    <div class="text-base-content/70">Tanggal Tagihan</div>
                    <div><?= date('d/m/Y', strtotime($invoice->invoice_date)) ?></div>
                </div>
            </div>
        </div>

        <!-- Patient Info -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-lg border-b pb-2">Data Pasien</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="text-base-content/70">Nama</div>
                    <div class="font-medium"><?= esc($invoice->patient_name ?? '-') ?></div>
                    <div class="text-base-content/70">No. RM</div>
                    <div class="font-mono"><?= esc($invoice->mrn ?? '-') ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Items -->
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h3 class="card-title text-lg">Rincian Item</h3>
                <?php if ($invoice->status !== 'paid' && $invoice->status !== 'cancelled'): ?>
                    <button class="btn btn-primary btn-sm" onclick="document.getElementById('addItemModal').showModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Tambah Item
                    </button>
                <?php endif; ?>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tipe</th>
                            <th>Nama Item</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Harga Satuan</th>
                            <th class="text-right">Total</th>
                            <?php if ($invoice->status !== 'paid' && $invoice->status !== 'cancelled'): ?>
                                <th class="text-center">Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-8 text-base-content/50">
                                    Belum ada item.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($items as $i => $item): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td>
                                        <?php
                                        $typeLabels = [
                                            'consultation' => 'Konsultasi',
                                            'action'       => 'Tindakan',
                                            'drug'         => 'Obat',
                                            'lab'          => 'Lab',
                                            'radiology'    => 'Radiologi',
                                            'room'         => 'Kamar',
                                        ];
                                        $typeClasses = [
                                            'consultation' => 'badge-info',
                                            'action'       => 'badge-warning',
                                            'drug'         => 'badge-success',
                                            'lab'          => 'badge-primary',
                                            'radiology'    => 'badge-secondary',
                                            'room'         => 'badge-accent',
                                        ];
                                        ?>
                                        <span class="badge badge-sm <?= $typeClasses[$item->item_type] ?? 'badge-ghost' ?>">
                                            <?= $typeLabels[$item->item_type] ?? $item->item_type ?>
                                        </span>
                                    </td>
                                    <td><?= esc($item->item_name) ?></td>
                                    <td class="text-center"><?= $item->quantity ?></td>
                                    <td class="text-right font-mono">Rp <?= number_format($item->unit_price, 0, ',', '.') ?></td>
                                    <td class="text-right font-mono">Rp <?= number_format($item->total_price, 0, ',', '.') ?></td>
                                    <?php if ($invoice->status !== 'paid' && $invoice->status !== 'cancelled'): ?>
                                        <td class="text-center">
                                            <form action="<?= site_url('billing/items/' . $item->id . '/remove') ?>" method="POST" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-error btn-square" title="Hapus" onclick="return confirm('Hapus item ini?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-lg border-b pb-2">Ringkasan Pembayaran</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="text-base-content/70">Subtotal</div>
                    <div class="text-right font-mono">Rp <?= number_format($invoice->subtotal, 0, ',', '.') ?></div>
                    <div class="text-base-content/70">Diskon</div>
                    <div class="text-right font-mono text-error">- Rp <?= number_format($invoice->discount_amount, 0, ',', '.') ?></div>
                    <div class="text-base-content/70">Pajak</div>
                    <div class="text-right font-mono">Rp <?= number_format($invoice->tax_amount, 0, ',', '.') ?></div>
                    <div class="border-t pt-2 font-bold text-base-content/70">Total</div>
                    <div class="border-t pt-2 text-right font-mono font-bold text-lg">Rp <?= number_format($invoice->total_amount, 0, ',', '.') ?></div>
                    <div class="text-base-content/70">Sudah Dibayar</div>
                    <div class="text-right font-mono text-success">Rp <?= number_format($invoice->paid_amount, 0, ',', '.') ?></div>
                    <div class="font-bold text-base-content/70">Sisa Tagihan</div>
                    <div class="text-right font-mono font-bold text-error">Rp <?= number_format($invoice->remaining_amount, 0, ',', '.') ?></div>
                </div>
            </div>
        </div>

        <?php if ($invoice->status !== 'paid' && $invoice->status !== 'cancelled'): ?>
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-lg border-b pb-2">Aksi</h3>
                    <div class="space-y-4">
                        <!-- Apply Discount -->
                        <form action="<?= site_url('billing/' . $invoice->uuid . '/discount') ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Diskon (Rp)</span>
                                </label>
                                <div class="join">
                                    <input type="number" name="discount_amount" class="input input-bordered join-item w-full" value="<?= $invoice->discount_amount ?>" min="0" step="0.01" />
                                    <button type="submit" class="btn btn-primary join-item">Terapkan</button>
                                </div>
                            </div>
                        </form>

                        <!-- Pay Button -->
                        <a href="<?= site_url('payments/process?invoice_uuid=' . $invoice->uuid) ?>" class="btn btn-success w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            Bayar Tagihan
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Notes -->
    <?php if ($invoice->notes): ?>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-lg border-b pb-2">Catatan</h3>
                <p class="text-sm"><?= nl2br(esc($invoice->notes)) ?></p>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Add Item Modal -->
<dialog id="addItemModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Tambah Item</h3>
        <form action="<?= site_url('billing/' . $invoice->uuid . '/items') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="space-y-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Tipe Item</span>
                    </label>
                    <select name="item_type" class="select select-bordered w-full" required>
                        <option value="">Pilih Tipe</option>
                        <option value="consultation">Konsultasi</option>
                        <option value="action">Tindakan</option>
                        <option value="drug">Obat</option>
                        <option value="lab">Lab</option>
                        <option value="radiology">Radiologi</option>
                        <option value="room">Kamar</option>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">ID Item</span>
                    </label>
                    <input type="number" name="item_id" class="input input-bordered w-full" required />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Nama Item</span>
                    </label>
                    <input type="text" name="item_name" class="input input-bordered w-full" required />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Jumlah</span>
                    </label>
                    <input type="number" name="quantity" class="input input-bordered w-full" value="1" min="1" required />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Harga Satuan (Rp)</span>
                    </label>
                    <input type="number" name="unit_price" class="input input-bordered w-full" min="0" step="0.01" required />
                </div>
            </div>
            <div class="modal-action mt-6">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('addItemModal').close()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
<?= $this->endSection() ?>
