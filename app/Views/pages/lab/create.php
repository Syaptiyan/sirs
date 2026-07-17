<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('lab') ?>">Order Lab</a></li>
                <li>Buat Order</li>
            </ul>
        </div>
        <h1 class="text-2xl font-bold mt-2">Buat Order Lab</h1>
        <p class="text-base-content/70">Buat order pemeriksaan laboratorium</p>
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

    <form method="POST" action="<?= site_url('lab') ?>" class="card bg-base-100 shadow-sm" x-data="labOrderForm()">
        <div class="card-body space-y-6">
            <!-- Visit Info -->
            <h2 class="card-title text-lg border-b pb-2">Informasi Kunjungan</h2>
            <?php if ($visit): ?>
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
            <?php else: ?>
                <div class="text-center py-4 text-base-content/50">
                    <p>Silakan pilih kunjungan terlebih dahulu dari halaman kunjungan.</p>
                </div>
            <?php endif; ?>

            <!-- Template Selector -->
            <h2 class="card-title text-lg border-b pb-2">Pemeriksaan Lab</h2>

            <!-- Quick Template Selector -->
            <div class="card bg-base-200/50">
                <div class="card-body p-4">
                    <h3 class="font-semibold text-sm mb-3">Pilih dari Template</h3>
                    <div class="flex flex-wrap gap-2 mb-3">
                        <?php foreach ($categories as $cat): ?>
                            <button type="button" @click="filterCategory('<?= esc($cat) ?>')"
                                    :class="activeCategory === '<?= esc($cat) ?>' ? 'btn-primary' : 'btn-ghost'"
                                    class="btn btn-sm">
                                <?= esc($cat) ?>
                            </button>
                        <?php endforeach; ?>
                        <button type="button" @click="filterCategory('')"
                                :class="activeCategory === '' ? 'btn-primary' : 'btn-ghost'"
                                class="btn btn-sm">
                            Semua
                        </button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-48 overflow-y-auto">
                        <?php foreach ($templates as $tpl): ?>
                            <label class="flex items-center gap-2 p-2 rounded bg-base-100 cursor-pointer hover:bg-base-200"
                                   x-show="activeCategory === '' || activeCategory === '<?= esc($tpl->category) ?>'"
                                   x-transition>
                                <input type="checkbox" class="checkbox checkbox-sm checkbox-primary"
                                       value="<?= esc($tpl->id) ?>"
                                       @change="toggleTemplate(<?= esc(json_encode($tpl)) ?>, $event.target.checked)">
                                <div>
                                    <div class="text-sm font-medium"><?= esc($tpl->name) ?></div>
                                    <div class="text-xs text-base-content/50"><?= esc($tpl->category) ?></div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="space-y-4">
                <template x-for="(item, index) in items" :key="index">
                    <div class="border rounded-lg p-4 bg-base-200/50">
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-semibold text-sm" x-text="'Parameter #' + (index + 1)"></span>
                            <button type="button" @click="removeItem(index)" class="btn btn-sm btn-ghost btn-square text-error" x-show="items.length > 1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <input type="hidden" :name="'items[' + index + '][template_id]'" :value="item.template_id || ''">
                            <div class="form-control">
                                <label class="label"><span class="label-text text-xs">Parameter <span class="text-error">*</span></span></label>
                                <input type="text" :name="'items[' + index + '][parameter_name]'" x-model="item.parameter_name" class="input input-bordered input-sm" required placeholder="Nama parameter">
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text text-xs">Satuan</span></label>
                                <input type="text" :name="'items[' + index + '][unit]'" x-model="item.unit" class="input input-bordered input-sm" placeholder="cth: mg/dL">
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text text-xs">Nilai Normal</span></label>
                                <input type="text" :name="'items[' + index + '][normal_range]'" x-model="item.normal_range" class="input input-bordered input-sm" placeholder="cth: 70-110">
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <button type="button" @click="addItem()" class="btn btn-ghost btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah Parameter
            </button>

            <!-- Notes -->
            <div class="form-control">
                <label class="label"><span class="label-text">Catatan</span></label>
                <textarea name="notes" class="textarea textarea-bordered" rows="3" placeholder="Catatan tambahan untuk order lab"><?= old('notes') ?></textarea>
            </div>

            <div class="divider"></div>
            <div class="flex justify-end gap-3">
                <a href="<?= site_url('lab') ?>" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary" <?= !$visit ? 'disabled' : '' ?>>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Simpan Order
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function labOrderForm() {
    return {
        items: [{}],
        activeCategory: '',
        templates: <?= json_encode($templates) ?>,

        filterCategory(cat) {
            this.activeCategory = cat;
        },

        toggleTemplate(template, checked) {
            if (checked) {
                // Check if template already added
                const exists = this.items.some(i => i.template_id == template.id);
                if (!exists) {
                    // If first item is empty, replace it
                    if (this.items.length === 1 && !this.items[0].parameter_name) {
                        this.items[0] = {
                            template_id: template.id,
                            parameter_name: template.name,
                            unit: '',
                            normal_range: ''
                        };
                    } else {
                        this.items.push({
                            template_id: template.id,
                            parameter_name: template.name,
                            unit: '',
                            normal_range: ''
                        });
                    }
                }
            } else {
                const index = this.items.findIndex(i => i.template_id == template.id);
                if (index > -1) {
                    this.items.splice(index, 1);
                    if (this.items.length === 0) {
                        this.items.push({});
                    }
                }
            }
        },

        addItem() {
            this.items.push({});
        },

        removeItem(index) {
            this.items.splice(index, 1);
            if (this.items.length === 0) {
                this.items.push({});
            }
        }
    }
}
</script>
<?= $this->endSection() ?>
