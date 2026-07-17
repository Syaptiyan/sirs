<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Pending Invoices</div>
        <div class="stat-value text-warning"><?= $stats['pending_invoices'] ?? 0 ?></div>
        <div class="stat-desc">Unpaid invoices</div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Today Payments</div>
        <div class="stat-value text-primary"><?= $stats['today_payments'] ?? 0 ?></div>
        <div class="stat-desc">Payments received</div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Today Revenue</div>
        <div class="stat-value text-success">Rp <?= number_format($stats['today_revenue'] ?? 0, 0, ',', '.') ?></div>
        <div class="stat-desc">Revenue today</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title">Pending Invoices</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Patient</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stats['invoice_list'])): ?>
                            <?php foreach ($stats['invoice_list'] as $invoice): ?>
                                <tr>
                                    <td><?= $invoice['number'] ?? '-' ?></td>
                                    <td><?= $invoice['patient'] ?? '-' ?></td>
                                    <td>Rp <?= number_format($invoice['amount'] ?? 0, 0, ',', '.') ?></td>
                                    <td>
                                        <a href="/invoices/<?= $invoice['id'] ?? '' ?>" class="btn btn-xs btn-primary">Process</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">No pending invoices</td></tr>
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
                <a href="/invoices" class="btn btn-outline btn-primary">All Invoices</a>
                <a href="/payments/create" class="btn btn-outline btn-secondary">New Payment</a>
                <a href="/billing" class="btn btn-outline btn-accent">Billing</a>
                <a href="/reports/finance" class="btn btn-outline btn-info">Finance Report</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
