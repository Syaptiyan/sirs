<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Pending Orders</div>
        <div class="stat-value text-warning"><?= $stats['pending_orders'] ?? 0 ?></div>
        <div class="stat-desc">Lab orders waiting</div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Completed Today</div>
        <div class="stat-value text-success"><?= $stats['completed_today'] ?? 0 ?></div>
        <div class="stat-desc">Results ready</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title">Pending Lab Orders</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Patient</th>
                            <th>Test Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stats['order_list'])): ?>
                            <?php foreach ($stats['order_list'] as $order): ?>
                                <tr>
                                    <td><?= $order['number'] ?? '-' ?></td>
                                    <td><?= $order['patient'] ?? '-' ?></td>
                                    <td><?= $order['test_type'] ?? '-' ?></td>
                                    <td>
                                        <a href="/lab-orders/<?= $order['id'] ?? '' ?>" class="btn btn-xs btn-primary">Process</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">No pending orders</td></tr>
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
                <a href="/lab-orders" class="btn btn-outline btn-primary">Lab Orders</a>
                <a href="/lab-results/create" class="btn btn-outline btn-secondary">Enter Results</a>
                <a href="/lab-tests" class="btn btn-outline btn-accent">Test Catalog</a>
                <a href="/reports/lab" class="btn btn-outline btn-info">Lab Report</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
