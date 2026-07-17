<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Pending Prescriptions</div>
        <div class="stat-value text-warning"><?= $stats['pending_prescriptions'] ?? 0 ?></div>
        <div class="stat-desc">Need to process</div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Low Stock Drugs</div>
        <div class="stat-value text-error"><?= $stats['low_stock_drugs'] ?? 0 ?></div>
        <div class="stat-desc">Need restock</div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Expiring Soon</div>
        <div class="stat-value text-warning"><?= $stats['expiring_drugs'] ?? 0 ?></div>
        <div class="stat-desc">Within 30 days</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title">Pending Prescriptions</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Prescription #</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stats['prescription_list'])): ?>
                            <?php foreach ($stats['prescription_list'] as $prescription): ?>
                                <tr>
                                    <td><?= $prescription['number'] ?? '-' ?></td>
                                    <td><?= $prescription['patient'] ?? '-' ?></td>
                                    <td><?= $prescription['doctor'] ?? '-' ?></td>
                                    <td>
                                        <a href="/prescriptions/<?= $prescription['id'] ?? '' ?>" class="btn btn-xs btn-primary">Process</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">No pending prescriptions</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="/prescriptions" class="btn btn-outline btn-primary">Prescriptions</a>
                <a href="/drugs" class="btn btn-outline btn-secondary">Drug Inventory</a>
                <a href="/drugs/stock" class="btn btn-outline btn-accent">Stock Management</a>
                <a href="/reports/pharmacy" class="btn btn-outline btn-info">Pharmacy Report</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
