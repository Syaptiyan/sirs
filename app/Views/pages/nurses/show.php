<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="flex items-center gap-2 mb-6">
        <a href="<?= base_url('nurses') ?>" class="btn btn-ghost btn-sm btn-square">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Detail Perawat</h1>
            <p class="text-base-content/70">Informasi lengkap perawat</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body items-center text-center">
                    <?php if ($nurse->photo): ?>
                        <div class="avatar mb-4">
                            <div class="w-24 rounded-full">
                                <img src="<?= esc($nurse->photo) ?>" alt="<?= esc($nurse->name) ?>" />
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="avatar placeholder mb-4">
                            <div class="bg-primary text-primary-content rounded-full w-24">
                                <span class="text-3xl"><?= strtoupper(substr($nurse->name, 0, 2)) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <h2 class="card-title text-xl"><?= esc($nurse->name) ?></h2>

                    <?php if ($nurse->is_active): ?>
                        <span class="badge badge-success">Aktif</span>
                    <?php else: ?>
                        <span class="badge badge-ghost">Nonaktif</span>
                    <?php endif; ?>

                    <div class="divider my-2"></div>

                    <div class="w-full space-y-2 text-left">
                        <?php if ($nurse->phone): ?>
                            <div class="flex items-center gap-2 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                <span><?= esc($nurse->phone) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($nurse->email): ?>
                            <div class="flex items-center gap-2 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                <span><?= esc($nurse->email) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="w-full mt-4">
                        <a href="<?= base_url('nurses/' . $nurse->uuid . '/edit') ?>" class="btn btn-primary btn-sm w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            Edit Perawat
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-lg">Informasi Profesional</h3>
                    <div class="overflow-x-auto">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="font-medium text-base-content/70 w-40">UUID</td>
                                    <td><span class="font-mono text-sm"><?= esc($nurse->uuid) ?></span></td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-base-content/70">ID Karyawan</td>
                                    <td><span class="font-mono text-sm"><?= esc($nurse->employee_id) ?></span></td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-base-content/70">SIP</td>
                                    <td>
                                        <span class="font-mono text-sm"><?= esc($nurse->sip ?? '-') ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-base-content/70">User ID</td>
                                    <td><?= esc($nurse->user_id ?? '-') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-lg">Metadata</h3>
                    <div class="overflow-x-auto">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="font-medium text-base-content/70 w-40">Dibuat</td>
                                    <td><?= $nurse->created_at ? date('d M Y H:i', strtotime($nurse->created_at)) : '-' ?></td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-base-content/70">Diperbarui</td>
                                    <td><?= $nurse->updated_at ? date('d M Y H:i', strtotime($nurse->updated_at)) : '-' ?></td>
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
