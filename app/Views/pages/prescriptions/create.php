<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('prescriptions') ?>">Resep</a></li>
                <li>Buat Resep</li>
            </ul>
        </div>
        <h1 class="text-2xl font-bold mt-2">Buat Resep</h1>
        <p class="text-base-content/70">Buat resep obat untuk kunjungan</p>
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

    <form method="POST" action="<?= site_url('prescriptions') ?>" class="card bg-base-100 shadow-sm" x-data="prescriptionForm()">
        <div class="card-body space-y-6">
            <!-- Visit Info -->
            <h2 class="card-title text-lg border-b pb-2">Informasi Kunjungan</h2>
            <input type="hidden" name="visit_id" value="<?= esc($visit->id) ?>">
            <input type="hidden" name="patient_id" value="<?= esc($visit->patient_id) ?>">
            <input type="hidden" name="doctor_id" value="<?= esc($visit->doctor_id) ?>">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">No. Kunjungan</span></label>
                    <input type="text" class="input input-bordered" value="<?= esc($visit->visit_number) ?>" readonly>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Pasien</span></label>
                    <input type="text" class="input input-bordered" value="<?= esc($visit->mrn) ?> - <?= esc($visit->patient_name) ?>" readonly>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Dokter</span></label>
                    <input type="text" class="input input-bordered" value="dr. <?= esc($visit->doctor_name) ?>" readonly>
                </div>
            </div>

            <!-- Prescription Items -->
            <h2 class="card-title text-lg border-b pb-2">Obat</h2>
            <div class="space-y-4">
                <template x-for="(item, index) in items" :key="index">
                    <div class="border rounded-lg p-4 bg-base-200/50">
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-semibold text-sm" x-text="'Obat #' + (index + 1)"></span>
                            <button type="button" @click="removeItem(index)" class="btn btn-sm btn-ghost btn-square text-error" x-show="items.length > 1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            <div class="form-control">
                                <label class="label"><span class="label-text text-xs">Obat <span class="text-error">*</span></span></label>
                                <select :name="'items[' + index + '][drug_id]'" class="select select-bordered select-sm w-full" required>
                                    <option value="">-- Pilih Obat --</option>
                                    <?php foreach ($drugs as $drug): ?>
                                        <option value="<?= $drug->id ?>"><?= esc($drug->name) ?> (<?= esc($drug->code) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text text-xs">Jumlah <span class="text-error">*</span></span></label>
                                <input type="number" :name="'items[' + index + '][quantity]'" class="input input-bordered input-sm" step="0.01" min="0.01" required placeholder="0">
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text text-xs">Satuan <span class="text-error">*</span></span></label>
                                <select :name="'items[' + index + '][unit]'" class="select select-bordered select-sm w-full" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="tablet">Tablet</option>
                                    <option value="kapsul">Kapsul</option>
                                    <option value="botol">Botol</option>
                                    <option value="tube">Tube</option>
                                    <option value="sachet">Sachet</option>
                                    <option value="ampul">Ampul</option>
                                    <option value="vial">Vial</option>
                                    <option value="strip">Strip</option>
                                    <option value="pcs">Pcs</option>
                                </select>
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text text-xs">Dosis <span class="text-error">*</span></span></label>
                                <input type="text" :name="'items[' + index + '][dosage]'" class="input input-bordered input-sm" required placeholder="cth: 500mg">
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text text-xs">Frekuensi <span class="text-error">*</span></span></label>
                                <select :name="'items[' + index + '][frequency]'" class="select select-bordered select-sm w-full" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="1x sehari">1x sehari</option>
                                    <option value="2x sehari">2x sehari</option>
                                    <option value="3x sehari">3x sehari</option>
                                    <option value="4x sehari">4x sehari</option>
                                    <option value="Saat dibutuhkan">Saat dibutuhkan</option>
                                </select>
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text text-xs">Durasi</span></label>
                                <input type="text" :name="'items[' + index + '][duration]'" class="input input-bordered input-sm" placeholder="cth: 5 hari">
                            </div>
                        </div>
                        <div class="form-control mt-3">
                            <label class="label"><span class="label-text text-xs">Instruksi</span></label>
                            <textarea :name="'items[' + index + '][instructions]'" class="textarea textarea-bordered textarea-sm" rows="2" placeholder="cth: Dimakan setelah makan"></textarea>
                        </div>
                    </div>
                </template>
            </div>

            <button type="button" @click="addItem()" class="btn btn-ghost btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah Obat
            </div>

            <!-- Notes -->
            <div class="form-control">
                <label class="label"><span class="label-text">Catatan</span></label>
                <textarea name="notes" class="textarea textarea-bordered" rows="3" placeholder="Catatan tambahan untuk resep"><?= old('notes') ?></textarea>
            </div>

            <div class="divider"></div>
            <div class="flex justify-end gap-3">
                <a href="<?= site_url('prescriptions') ?>" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Simpan Resep
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function prescriptionForm() {
    return {
        items: [{}],
        addItem() {
            this.items.push({});
        },
        removeItem(index) {
            this.items.splice(index, 1);
        }
    }
}
</script>
<?= $this->endSection() ?>
