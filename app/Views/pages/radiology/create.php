<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('radiology') ?>">Order Radiologi</a></li>
                <li>Buat Order</li>
            </ul>
        </div>
        <h1 class="text-2xl font-bold mt-2">Buat Order Radiologi</h1>
        <p class="text-base-content/70">Buat order pemeriksaan radiologi</p>
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

    <form method="POST" action="<?= site_url('radiology') ?>" class="card bg-base-100 shadow-sm" x-data="radiologyOrderForm()">
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
            <h2 class="card-title text-lg border-b pb-2">Pemeriksaan Radiologi</h2>

            <!-- Quick Template Selector -->
            <div class="card bg-base-200/50">
                <div class="card-body p-4">
                    <h3 class="font-semibold text-sm mb-3">Pilih dari Template</h3>
                    <div class="flex flex-wrap gap-2 mb-3">
                        <button type="button" @click="filterCategory('')"
                                :class="activeCategory === '' ? 'btn-primary' : 'btn-ghost'"
                                class="btn btn-sm">
                            Semua
                        </button>
                        <?php foreach ($categories as $cat): ?>
                            <button type="button" @click="filterCategory('<?= esc($cat) ?>')"
                                    :class="activeCategory === '<?= esc($cat) ?>' ? 'btn-primary' : 'btn-ghost'"
                                    class="btn btn-sm">
                                <?= esc($cat) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-48 overflow-y-auto">
                        <?php foreach ($templates as $tpl): ?>
                            <label class="flex items-center gap-2 p-3 rounded bg-base-100 cursor-pointer hover:bg-base-200"
                                   x-show="activeCategory === '' || activeCategory === '<?= esc($tpl->category) ?>'"
                                   x-transition>
                                <input type="radio" class="radio radio-sm radio-primary"
                                       name="template_id"
                                       value="<?= esc($tpl->id) ?>"
                                       x-model="selectedTemplate"
                                       @change="selectTemplate(<?= esc(json_encode($tpl)) ?>)">
                                <div>
                                    <div class="text-sm font-medium"><?= esc($tpl->name) ?></div>
                                    <div class="text-xs text-base-content/50"><?= esc($tpl->category) ?></div>
                                    <?php if ($tpl->description): ?>
                                        <div class="text-xs text-base-content/40 mt-1"><?= esc($tpl->description) ?></div>
                                    <?php endif; ?>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Selected Template Info -->
            <div x-show="selectedTemplateName" class="bg-primary/10 p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-primary">Template Terpilih:</div>
                        <div x-text="selectedTemplateName" class="text-sm"></div>
                    </div>
                    <button type="button" @click="clearTemplate()" class="btn btn-sm btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            <!-- Notes -->
            <div class="form-control">
                <label class="label"><span class="label-text">Catatan</span></label>
                <textarea name="notes" class="textarea textarea-bordered" rows="3" placeholder="Catatan tambahan untuk order radiologi"><?= old('notes') ?></textarea>
            </div>

            <div class="divider"></div>
            <div class="flex justify-end gap-3">
                <a href="<?= site_url('radiology') ?>" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary" <?= !$visit ? 'disabled' : '' ?>>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Simpan Order
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function radiologyOrderForm() {
    return {
        activeCategory: '',
        selectedTemplate: '',
        selectedTemplateName: '',
        templates: <?= json_encode($templates) ?>,

        filterCategory(cat) {
            this.activeCategory = cat;
        },

        selectTemplate(template) {
            this.selectedTemplateName = template.name + ' (' + template.category + ')';
        },

        clearTemplate() {
            this.selectedTemplate = '';
            this.selectedTemplateName = '';
            document.querySelectorAll('input[name="template_id"]').forEach(r => r.checked = false);
        }
    }
}
</script>
<?= $this->endSection() ?>
