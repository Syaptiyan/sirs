<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('medical/records') ?>">Rekam Medis</a></li>
                <li><?= esc($record->record_number) ?></li>
            </ul>
        </div>
        <div class="flex justify-between items-center mt-2">
            <div>
                <h1 class="text-2xl font-bold">Detail Rekam Medis</h1>
                <p class="text-base-content/70">
                    <?= esc($record->record_number) ?> | 
                    Pasien: <span class="font-semibold"><?= esc($record->patient_name) ?></span>
                    (MRN: <?= esc($record->mrn) ?>)
                </p>
            </div>
            <div class="flex gap-2">
                <a href="<?= site_url('medical/records/' . $record->uuid . '/edit') ?>" class="btn btn-sm btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    Edit
                </a>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-lg border-b pb-2">SOAP Notes</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-primary">S - Subjective</h3>
                            <p class="whitespace-pre-line mt-1"><?= esc($record->subjective ?? '-') ?></p>
                        </div>

                        <div class="divider my-2"></div>

                        <div>
                            <h3 class="font-semibold text-primary">O - Objective</h3>
                            <p class="whitespace-pre-line mt-1"><?= esc($record->objective ?? '-') ?></p>
                        </div>

                        <div class="divider my-2"></div>

                        <div>
                            <h3 class="font-semibold text-primary">A - Assessment</h3>
                            <p class="whitespace-pre-line mt-1"><?= esc($record->assessment ?? '-') ?></p>
                        </div>

                        <div class="divider my-2"></div>

                        <div>
                            <h3 class="font-semibold text-primary">P - Plan</h3>
                            <p class="whitespace-pre-line mt-1"><?= esc($record->plan ?? '-') ?></p>
                        </div>
                    </div>

                    <?php if (!empty($record->notes)): ?>
                        <div class="divider"></div>
                        <div>
                            <h3 class="font-semibold">Catatan Tambahan</h3>
                            <p class="whitespace-pre-line mt-1"><?= esc($record->notes) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <div class="flex justify-between items-center border-b pb-2">
                        <h2 class="card-title text-lg">Diagnosis</h2>
                        <button class="btn btn-sm btn-primary" onclick="document.getElementById('diagnosisModal').showModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Tambah
                        </button>
                    </div>

                    <?php if (empty($record->diagnoses)): ?>
                        <p class="text-base-content/50 text-center py-4">Belum ada diagnosis</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="table table-zebra">
                                <thead>
                                    <tr>
                                        <th>Kode ICD-10</th>
                                        <th>Deskripsi</th>
                                        <th>Tipe</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($record->diagnoses as $diagnosis): ?>
                                        <tr>
                                            <td class="font-mono"><?= esc($diagnosis->icd10_code) ?></td>
                                            <td><?= esc($diagnosis->icd10_description) ?></td>
                                            <td>
                                                <span class="badge badge-sm <?= $diagnosis->diagnosis_type === 'primary' ? 'badge-primary' : 'badge-secondary' ?>">
                                                    <?= ucfirst($diagnosis->diagnosis_type) ?>
                                                </span>
                                            </td>
                                            <td><?= esc($diagnosis->notes ?? '-') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <div class="flex justify-between items-center border-b pb-2">
                        <h2 class="card-title text-lg">Tindakan</h2>
                        <button class="btn btn-sm btn-primary" onclick="document.getElementById('actionModal').showModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Tambah
                        </button>
                    </div>

                    <?php if (empty($record->actions)): ?>
                        <p class="text-base-content/50 text-center py-4">Belum ada tindakan</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="table table-zebra">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Tindakan</th>
                                        <th>Jumlah</th>
                                        <th>Harga Satuan</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $grandTotal = 0;
                                    foreach ($record->actions as $action): 
                                        $grandTotal += $action->total_price;
                                    ?>
                                        <tr>
                                            <td class="font-mono"><?= esc($action->action_type_code) ?></td>
                                            <td><?= esc($action->action_type_name) ?></td>
                                            <td><?= $action->quantity ?></td>
                                            <td>Rp <?= number_format($action->unit_price, 0, ',', '.') ?></td>
                                            <td class="font-semibold">Rp <?= number_format($action->total_price, 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="font-bold">
                                        <td colspan="4" class="text-right">Total:</td>
                                        <td>Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-lg border-b pb-2">Informasi</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-base-content/70">Nomor Rekam Medis</p>
                            <p class="font-mono font-semibold"><?= esc($record->record_number) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Dokter</p>
                            <p class="font-semibold"><?= esc($record->doctor_name ?? '-') ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Tanggal Dibuat</p>
                            <p><?= date('d/m/Y H:i', strtotime($record->created_at)) ?></p>
                        </div>
                        <?php if ($record->updated_at !== $record->created_at): ?>
                            <div>
                                <p class="text-sm text-base-content/70">Terakhir Diupdate</p>
                                <p><?= date('d/m/Y H:i', strtotime($record->updated_at)) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-3">
        <a href="<?= site_url('medical/records?visit_id=' . $record->visit_id) ?>" class="btn btn-ghost">Kembali</a>
    </div>
</div>

<dialog id="diagnosisModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Tambah Diagnosis</h3>
        <form method="POST" action="<?= site_url('medical/records/' . $record->uuid . '/diagnoses') ?>" class="space-y-4 mt-4">
            <div class="form-control">
                <label class="label"><span class="label-text">Kode ICD-10 <span class="text-error">*</span></span></label>
                <div class="join w-full">
                    <input type="text" id="icd10_search" class="input input-bordered join-item flex-1" placeholder="Cari kode atau deskripsi..." oninput="searchICD10(this.value)">
                </div>
                <div id="icd10_results" class="mt-2 max-h-40 overflow-y-auto hidden"></div>
                <input type="hidden" name="icd10_code_id" id="icd10_code_id" required>
                <div id="selected_icd10" class="mt-2 hidden">
                    <span class="badge badge-primary" id="selected_icd10_label"></span>
                </div>
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">Tipe Diagnosis <span class="text-error">*</span></span></label>
                <select name="diagnosis_type" class="select select-bordered" required>
                    <option value="primary">Primary</option>
                    <option value="secondary">Secondary</option>
                </select>
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">Catatan</span></label>
                <textarea name="notes" class="textarea textarea-bordered" rows="2" placeholder="Catatan (opsional)"></textarea>
            </div>

            <div class="modal-action">
                <button type="button" class="btn" onclick="document.getElementById('diagnosisModal').close()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<dialog id="actionModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Tambah Tindakan</h3>
        <form method="POST" action="<?= site_url('medical/records/' . $record->uuid . '/actions') ?>" class="space-y-4 mt-4">
            <div class="form-control">
                <label class="label"><span class="label-text">Jenis Tindakan <span class="text-error">*</span></span></label>
                <select name="action_type_id" class="select select-bordered" id="action_type_select" required onchange="updateUnitPrice(this)">
                    <option value="">Pilih Tindakan</option>
                </select>
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">Jumlah <span class="text-error">*</span></span></label>
                <input type="number" name="quantity" class="input input-bordered" value="1" min="1" required oninput="calculateTotal()">
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">Harga Satuan <span class="text-error">*</span></span></label>
                <input type="number" name="unit_price" class="input input-bordered" step="0.01" min="0" required oninput="calculateTotal()">
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">Total Harga</span></label>
                <input type="text" id="total_price_display" class="input input-bordered bg-base-200" readonly value="Rp 0">
            </div>

            <div class="modal-action">
                <button type="button" class="btn" onclick="document.getElementById('actionModal').close()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
let searchTimeout;

function searchICD10(query) {
    clearTimeout(searchTimeout);
    const resultsDiv = document.getElementById('icd10_results');
    
    if (query.length < 2) {
        resultsDiv.classList.add('hidden');
        return;
    }

    searchTimeout = setTimeout(() => {
        fetch(`<?= site_url('medical/icd10/search') ?>?q=${encodeURIComponent(query)}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                resultsDiv.innerHTML = data.data.map(item => `
                    <div class="p-2 hover:bg-base-200 cursor-pointer border-b" onclick="selectICD10(${item.id}, '${item.code}', '${item.description.replace(/'/g, "\\'")}')">
                        <span class="font-mono font-semibold">${item.code}</span> - ${item.description}
                    </div>
                `).join('');
                resultsDiv.classList.remove('hidden');
            } else {
                resultsDiv.innerHTML = '<div class="p-2 text-base-content/50">Tidak ditemukan</div>';
                resultsDiv.classList.remove('hidden');
            }
        });
    }, 300);
}

function selectICD10(id, code, description) {
    document.getElementById('icd10_code_id').value = id;
    document.getElementById('selected_icd10_label').textContent = code + ' - ' + description;
    document.getElementById('selected_icd10').classList.remove('hidden');
    document.getElementById('icd10_search').value = '';
    document.getElementById('icd10_results').classList.add('hidden');
}

function loadActionTypes() {
    fetch(`<?= site_url('medical/action-types') ?>`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const select = document.getElementById('action_type_select');
            data.data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = `${item.code} - ${item.name}`;
                option.dataset.price = item.base_price;
                select.appendChild(option);
            });
        }
    });
}

function updateUnitPrice(select) {
    const selected = select.options[select.selectedIndex];
    if (selected && selected.dataset.price) {
        document.querySelector('input[name="unit_price"]').value = selected.dataset.price;
        calculateTotal();
    }
}

function calculateTotal() {
    const quantity = parseInt(document.querySelector('input[name="quantity"]').value) || 0;
    const unitPrice = parseFloat(document.querySelector('input[name="unit_price"]').value) || 0;
    const total = quantity * unitPrice;
    document.getElementById('total_price_display').value = 'Rp ' + total.toLocaleString('id-ID');
}

document.addEventListener('DOMContentLoaded', function() {
    loadActionTypes();
});
</script>
<?= $this->endSection() ?>
