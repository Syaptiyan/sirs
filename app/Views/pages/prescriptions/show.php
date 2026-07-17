<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('prescriptions') ?>">Resep</a></li>
                <li><?= esc($prescription->prescription_number) ?></li>
            </ul>
        </div>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mt-2 gap-4">
            <div>
                <h1 class="text-2xl font-bold">Detail Resep</h1>
                <p class="text-base-content/70"><?= esc($prescription->prescription_number) ?></p>
            </div>
            <div class="flex gap-2">
                <a href="<?= site_url('prescriptions') ?>" class="btn btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali
                </a>
                <a href="<?= site_url('prescriptions/' . $prescription->uuid . '/print') ?>" class="btn btn-ghost" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Cetak
                </a>
                <?php if ($prescription->status === 'pending'): ?>
                    <form action="<?= site_url('prescriptions/' . $prescription->uuid . '/dispense') ?>" method="POST" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success" onclick="return confirm('Dispensasi resep ini?')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Dispensasi
                        </button>
                    </form>
                    <form action="<?= site_url('prescriptions/' . $prescription->uuid . '/cancel') ?>" method="POST" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-error" onclick="return confirm('Batalkan resep ini?')">
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

    <!-- Prescription Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card bg-base-100 shadow-sm lg:col-span-2">
            <div class="card-body">
                <h2 class="card-title text-lg border-b pb-2">Informasi Resep</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <div class="text-sm text-base-content/70">No. Resep</div>
                        <div class="font-bold text-primary"><?= esc($prescription->prescription_number) ?></div>
                    </div>
                    <div>
                        <div class="text-sm text-base-content/70">Tanggal</div>
                        <div class="font-medium"><?= esc($prescription->prescription_date) ?></div>
                    </div>
                    <div>
                        <div class="text-sm text-base-content/70">No. Kunjungan</div>
                        <div class="font-medium"><?= esc($prescription->visit_number ?? '-') ?></div>
                    </div>
                    <div>
                        <div class="text-sm text-base-content/70">Status</div>
                        <?php
                        $statusLabels = [
                            'pending'   => 'Pending',
                            'dispensed' => 'Didispensasi',
                            'partial'   => 'Sebagian',
                            'cancelled' => 'Dibatalkan',
                        ];
                        $statusClasses = [
                            'pending'   => 'badge-warning',
                            'dispensed' => 'badge-success',
                            'partial'   => 'badge-info',
                            'cancelled' => 'badge-ghost',
                        ];
                        ?>
                        <span class="badge <?= $statusClasses[$prescription->status] ?? 'badge-ghost' ?>">
                            <?= $statusLabels[$prescription->status] ?? $prescription->status ?>
                        </span>
                    </div>
                    <div>
                        <div class="text-sm text-base-content/70">Pasien</div>
                        <div class="font-medium"><?= esc($prescription->patient_name ?? '-') ?></div>
                        <div class="text-xs text-base-content/50"><?= esc($prescription->mrn ?? '') ?></div>
                    </div>
                    <div>
                        <div class="text-sm text-base-content/70">Dokter</div>
                        <div class="font-medium">dr. <?= esc($prescription->doctor_name ?? '-') ?></div>
                    </div>
                </div>
                <?php if ($prescription->notes): ?>
                    <div class="mt-4">
                        <div class="text-sm text-base-content/70">Catatan</div>
                        <div class="mt-1 p-3 bg-base-200 rounded-lg text-sm"><?= nl2br(esc($prescription->notes)) ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg border-b pb-2">Ringkasan</h2>
                <div class="space-y-3 mt-4">
                    <div class="flex justify-between">
                        <span class="text-base-content/70">Jumlah Obat</span>
                        <span class="font-bold"><?= count($prescription->details ?? []) ?> item</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-base-content/70">Dibuat</span>
                        <span class="text-sm"><?= esc($prescription->created_at ?? '-') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-base-content/70">Diperbarui</span>
                        <span class="text-sm"><?= esc($prescription->updated_at ?? '-') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prescription Details -->
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-lg border-b pb-2">Daftar Obat</h2>
            <div class="overflow-x-auto mt-4">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Obat</th>
                            <th>Dosis</th>
                            <th>Frekuensi</th>
                            <th>Durasi</th>
                            <th class="text-right">Jumlah</th>
                            <th class="text-right">Didispensasi</th>
                            <th>Instruksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($prescription->details)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-8 text-base-content/50">
                                    Tidak ada item obat.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($prescription->details as $i => $detail): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td>
                                        <div class="font-medium"><?= esc($detail->drug_name ?? '-') ?></div>
                                        <div class="text-xs text-base-content/50"><?= esc($detail->drug_code ?? '') ?></div>
                                    </td>
                                    <td><?= esc($detail->dosage) ?></td>
                                    <td><?= esc($detail->frequency) ?></td>
                                    <td><?= esc($detail->duration ?? '-') ?></td>
                                    <td class="text-right">
                                        <?= number_format($detail->quantity, 2) ?> <?= esc($detail->unit) ?>
                                    </td>
                                    <td class="text-right">
                                        <?php
                                        $dispensedClass = $detail->dispensed_quantity >= $detail->quantity ? 'text-success' : 'text-warning';
                                        ?>
                                        <span class="<?= $dispensedClass ?>">
                                            <?= number_format($detail->dispensed_quantity, 2) ?> <?= esc($detail->unit) ?>
                                        </span>
                                    </td>
                                    <td class="text-sm text-base-content/70"><?= esc($detail->instructions ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
