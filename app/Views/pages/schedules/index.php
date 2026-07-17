<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold">Jadwal Dokter</h1>
            <p class="text-base-content/70">Kelola jadwal praktik dokter</p>
        </div>
        <a href="<?= base_url('schedules/create') ?>" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Jadwal
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Filter -->
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body p-4">
            <form action="<?= base_url('schedules') ?>" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="form-control sm:w-56">
                    <select name="doctor_id" class="select select-bordered w-full">
                        <option value="">Semua Dokter</option>
                        <?php foreach ($doctors as $doc): ?>
                            <option value="<?= $doc->id ?>" <?= ($doctorId ?? '') == $doc->id ? 'selected' : '' ?>>
                                dr. <?= esc($doc->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-control sm:w-56">
                    <select name="polyclinic_id" class="select select-bordered w-full">
                        <option value="">Semua Poliklinik</option>
                        <?php foreach ($polyclinics as $poly): ?>
                            <option value="<?= $poly->id ?>" <?= ($polyclinicId ?? '') == $poly->id ? 'selected' : '' ?>>
                                <?= esc($poly->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Filter
                    </button>
                    <a href="<?= base_url('schedules') ?>" class="btn btn-ghost">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card bg-base-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Dokter</th>
                        <th>Poliklinik</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Kuota</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    ?>
                    <?php if (empty($schedules['data'])): ?>
                        <tr>
                            <td colspan="8" class="text-center py-8 text-base-content/50">
                                Tidak ada jadwal ditemukan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($schedules['data'] as $i => $schedule): ?>
                            <tr>
                                <td><?= ($schedules['current_page'] - 1) * $schedules['per_page'] + $i + 1 ?></td>
                                <td>
                                    <div class="font-medium">dr. <?= esc($schedule->doctor_name ?? '-') ?></div>
                                </td>
                                <td>
                                    <span class="badge badge-primary badge-outline"><?= esc($schedule->polyclinic_name ?? '-') ?></span>
                                </td>
                                <td><?= esc($days[$schedule->day_of_week] ?? '-') ?></td>
                                <td class="text-sm"><?= esc($schedule->start_time) ?> - <?= esc($schedule->end_time) ?></td>
                                <td class="text-center"><?= esc($schedule->quota) ?></td>
                                <td>
                                    <?php if ($schedule->is_active): ?>
                                        <span class="badge badge-success badge-sm">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-ghost badge-sm">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <a href="<?= base_url('schedules/' . $schedule->uuid . '/edit') ?>" class="btn btn-sm btn-ghost btn-square" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        <form action="<?= base_url('schedules/' . $schedule->uuid) ?>" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-ghost btn-square text-error" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (isset($schedules['last_page']) && $schedules['last_page'] > 1): ?>
            <div class="card-body pt-2">
                <?= pager() ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
