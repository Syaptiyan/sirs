<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="<?= site_url('patients') ?>">Pasien</a></li>
                <li><?= esc($patient['name']) ?></li>
            </ul>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-2">
            <div>
                <h1 class="text-2xl font-bold"><?= esc($patient['name']) ?></h1>
                <p class="text-base-content/70">No. RM: <span class="font-mono badge badge-ghost"><?= esc($patient['mrn']) ?></span></p>
            </div>
            <div class="flex gap-2">
                <a href="<?= site_url('patients/' . $patient['id'] . '/edit') ?>" class="btn btn-outline btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    Edit
                </a>
                <a href="<?= site_url('patients') ?>" class="btn btn-ghost btn-sm">Kembali</a>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div role="tablist" class="tabs tabs-bordered tabs-lg" x-data="{ activeTab: 'info' }">
        <button role="tab" class="tab" :class="{ 'tab-active': activeTab === 'info' }" @click="activeTab = 'info'">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            Info Pasien
        </button>
        <button role="tab" class="tab" :class="{ 'tab-active': activeTab === 'visits' }" @click="activeTab = 'visits'">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            Kunjungan
        </button>
        <button role="tab" class="tab" :class="{ 'tab-active': activeTab === 'allergies' }" @click="activeTab = 'allergies'">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
            Alergi
        </button>
        <button role="tab" class="tab" :class="{ 'tab-active': activeTab === 'chronic' }" @click="activeTab = 'chronic'">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
            Penyakit Kronis
        </button>
        <button role="tab" class="tab" :class="{ 'tab-active': activeTab === 'documents' }" @click="activeTab = 'documents'">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            Dokumen
        </button>
    </div>

    <div x-show="activeTab === 'info'" x-cloak>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <h3 class="font-semibold text-lg border-b pb-2">Identitas Diri</h3>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="text-base-content/70">No. Rekam Medis</div>
                            <div class="font-mono font-medium"><?= esc($patient['mrn']) ?></div>
                            <div class="text-base-content/70">Nama Lengkap</div>
                            <div class="font-medium"><?= esc($patient['name']) ?></div>
                            <div class="text-base-content/70">NIK</div>
                            <div class="font-mono"><?= esc($patient['nik']) ?></div>
                            <div class="text-base-content/70">Jenis Kelamin</div>
                            <div><?= $patient['gender'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></div>
                            <div class="text-base-content/70">Tempat, Tanggal Lahir</div>
                            <div><?= esc($patient['birth_place']) ?>, <?= date('d/m/Y', strtotime($patient['birth_date'])) ?></div>
                            <div class="text-base-content/70">Golongan Darah</div>
                            <div><?= $patient['blood_type'] ?? '-' ?></div>
                            <div class="text-base-content/70">Agama</div>
                            <div><?= $patient['religion'] ?? '-' ?></div>
                            <div class="text-base-content/70">Pendidikan</div>
                            <div><?= $patient['education'] ?? '-' ?></div>
                            <div class="text-base-content/70">Pekerjaan</div>
                            <div><?= $patient['occupation'] ?? '-' ?></div>
                            <div class="text-base-content/70">Status Pernikahan</div>
                            <div><?= $patient['marital_status'] ?? '-' ?></div>
                            <div class="text-base-content/70">Status</div>
                            <div>
                                <?php if ($patient['status'] === 'active'): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-ghost">Non-aktif</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="font-semibold text-lg border-b pb-2">Kontak</h3>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="text-base-content/70">No. Telepon</div>
                            <div><?= esc($patient['phone']) ?></div>
                            <div class="text-base-content/70">Email</div>
                            <div><?= $patient['email'] ? esc($patient['email']) : '-' ?></div>
                            <div class="text-base-content/70 col-span-2">Alamat</div>
                            <div class="col-span-2"><?= nl2br(esc($patient['address'])) ?></div>
                        </div>

                        <h3 class="font-semibold text-lg border-b pb-2 mt-6">Kontak Darurat</h3>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="text-base-content/70">Nama</div>
                            <div><?= esc($patient['emergency_contact_name'] ?? '-') ?></div>
                            <div class="text-base-content/70">Telepon</div>
                            <div><?= esc($patient['emergency_contact_phone'] ?? '-') ?></div>
                            <div class="text-base-content/70">Hubungan</div>
                            <div><?= esc($patient['emergency_contact_relation'] ?? '-') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'visits'" x-cloak>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-lg">Riwayat Kunjungan</h3>
                    <a href="<?= site_url('visits/create?patient_id=' . $patient['id']) ?>" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Kunjungan Baru
                    </a>
                </div>
                <?php if (empty($visits ?? [])): ?>
                    <p class="text-center text-base-content/50 py-8">Belum ada riwayat kunjungan.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>No. Kunjungan</th>
                                    <th>Poli</th>
                                    <th>Dokter</th>
                                    <th>Diagnosa</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($visits as $visit): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($visit['visit_date'])) ?></td>
                                        <td class="font-mono"><?= esc($visit['visit_number']) ?></td>
                                        <td><?= esc($visit['poli_name'] ?? '-') ?></td>
                                        <td><?= esc($visit['doctor_name'] ?? '-') ?></td>
                                        <td><?= esc($visit['diagnosis'] ?? '-') ?></td>
                                        <td>
                                            <span class="badge badge-sm <?= $visit['status'] === 'active' ? 'badge-info' : 'badge-ghost' ?>">
                                                <?= esc($visit['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'allergies'" x-cloak>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-lg">Data Alergi</h3>
                    <button class="btn btn-primary btn-sm" onclick="document.getElementById('modal_allergy').showModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Tambah Alergi
                    </button>
                </div>
                <?php if (empty($allergies ?? [])): ?>
                    <p class="text-center text-base-content/50 py-8">Tidak ada data alergi tercatat.</p>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <?php foreach ($allergies as $allergy): ?>
                            <div class="card card-compact bg-warning/10 border border-warning/30">
                                <div class="card-body">
                                    <h4 class="card-title text-sm"><?= esc($allergy['allergen']) ?></h4>
                                    <p class="text-xs text-base-content/70">Reaksi: <?= esc($allergy['reaction'] ?? '-') ?></p>
                                    <p class="text-xs text-base-content/70">Tingkat: <span class="badge badge-sm badge-warning"><?= esc($allergy['severity'] ?? '-') ?></span></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'chronic'" x-cloak>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-lg">Penyakit Kronis</h3>
                    <button class="btn btn-primary btn-sm" onclick="document.getElementById('modal_chronic').showModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Tambah Penyakit
                    </button>
                </div>
                <?php if (empty($chronicDiseases ?? [])): ?>
                    <p class="text-center text-base-content/50 py-8">Tidak ada data penyakit kronis tercatat.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>Penyakit</th>
                                    <th>ICD Code</th>
                                    <th>Sejak</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($chronicDiseases as $disease): ?>
                                    <tr>
                                        <td class="font-medium"><?= esc($disease['disease_name']) ?></td>
                                        <td class="font-mono text-sm"><?= esc($disease['icd_code'] ?? '-') ?></td>
                                        <td><?= $disease['diagnosed_date'] ? date('d/m/Y', strtotime($disease['diagnosed_date'])) : '-' ?></td>
                                        <td>
                                            <span class="badge badge-sm <?= $disease['status'] === 'active' ? 'badge-error' : 'badge-ghost' ?>">
                                                <?= esc($disease['status']) ?>
                                            </span>
                                        </td>
                                        <td class="text-sm"><?= esc($disease['notes'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'documents'" x-cloak>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-lg">Dokumen</h3>
                    <button class="btn btn-primary btn-sm" onclick="document.getElementById('modal_document').showModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Upload Dokumen
                    </button>
                </div>
                <?php if (empty($documents ?? [])): ?>
                    <p class="text-center text-base-content/50 py-8">Belum ada dokumen terupload.</p>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <?php foreach ($documents as $doc): ?>
                            <div class="card card-compact bg-base-200">
                                <div class="card-body flex-row items-center gap-3">
                                    <div class="text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-sm truncate"><?= esc($doc['document_name']) ?></p>
                                        <p class="text-xs text-base-content/50"><?= esc($doc['document_type']) ?> &middot; <?= date('d/m/Y', strtotime($doc['created_at'])) ?></p>
                                    </div>
                                    <a href="<?= site_url('patients/' . $patient['id'] . '/documents/' . $doc['id'] . '/download') ?>" class="btn btn-ghost btn-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<dialog id="modal_allergy" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Tambah Alergi</h3>
        <form method="POST" action="<?= site_url('patients/' . $patient['id'] . '/allergies') ?>" class="space-y-4 mt-4">
            <div class="form-control">
                <label class="label"><span class="label-text">Alergen <span class="text-error">*</span></span></label>
                <input type="text" name="allergen" class="input input-bordered" required placeholder="Contoh: Penisilin, Udang" />
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Reaksi</span></label>
                <input type="text" name="reaction" class="input input-bordered" placeholder="Contoh: Gatal, Sesak napas" />
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Tingkat Keparahan</span></label>
                <select name="severity" class="select select-bordered">
                    <option value="ringan">Ringan</option>
                    <option value="sedang">Sedang</option>
                    <option value="berat">Berat</option>
                </select>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Keterangan</span></label>
                <textarea name="notes" class="textarea textarea-bordered" rows="2"></textarea>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('modal_allergy').close()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<dialog id="modal_chronic" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Tambah Penyakit Kronis</h3>
        <form method="POST" action="<?= site_url('patients/' . $patient['id'] . '/chronic-diseases') ?>" class="space-y-4 mt-4">
            <div class="form-control">
                <label class="label"><span class="label-text">Nama Penyakit <span class="text-error">*</span></span></label>
                <input type="text" name="disease_name" class="input input-bordered" required placeholder="Contoh: Diabetes Melitus" />
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Kode ICD</span></label>
                <input type="text" name="icd_code" class="input input-bordered" placeholder="Contoh: E11" />
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Tanggal Diagnosa</span></label>
                <input type="date" name="diagnosed_date" class="input input-bordered" />
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Keterangan</span></label>
                <textarea name="notes" class="textarea textarea-bordered" rows="2"></textarea>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('modal_chronic').close()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<dialog id="modal_document" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Upload Dokumen</h3>
        <form method="POST" action="<?= site_url('patients/' . $patient['id'] . '/documents') ?>" enctype="multipart/form-data" class="space-y-4 mt-4">
            <div class="form-control">
                <label class="label"><span class="label-text">Nama Dokumen <span class="text-error">*</span></span></label>
                <input type="text" name="document_name" class="input input-bordered" required placeholder="Contoh: Hasil Lab" />
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Jenis Dokumen</span></label>
                <select name="document_type" class="select select-bordered">
                    <option value="lab">Hasil Lab</option>
                    <option value="radiologi">Radiologi</option>
                    <option value="resep">Resep</option>
                    <option value="surat_rujukan">Surat Rujukan</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">File <span class="text-error">*</span></span></label>
                <input type="file" name="file" class="file-input file-input-bordered w-full" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" />
                <label class="label"><span class="label-text-alt">Format: PDF, JPG, PNG, DOC. Maks 5MB</span></label>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('modal_document').close()">Batal</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>
<?= $this->endSection() ?>
