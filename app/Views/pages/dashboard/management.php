<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Total Patients</div>
        <div class="stat-value text-primary"><?= $stats['total_patients'] ?? 0 ?></div>
        <div class="stat-desc">All patients</div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Today Visits</div>
        <div class="stat-value text-secondary"><?= $stats['today_visits'] ?? 0 ?></div>
        <div class="stat-desc">Visits today</div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Monthly Revenue</div>
        <div class="stat-value text-success">Rp <?= number_format($stats['monthly_revenue'] ?? 0, 0, ',', '.') ?></div>
        <div class="stat-desc">This month</div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Visit Trend</div>
        <div class="stat-value text-accent">
            <?php $trend = $stats['visit_trend'] ?? 0; ?>
            <?= $trend >= 0 ? '+' : '' ?><?= $trend ?>%
        </div>
        <div class="stat-desc">vs last month</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title">Monthly Summary</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Metric</th>
                            <th>This Month</th>
                            <th>Last Month</th>
                            <th>Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stats['monthly_summary'])): ?>
                            <?php foreach ($stats['monthly_summary'] as $summary): ?>
                                <tr>
                                    <td><?= $summary['metric'] ?? '-' ?></td>
                                    <td><?= $summary['current'] ?? '-' ?></td>
                                    <td><?= $summary['previous'] ?? '-' ?></td>
                                    <td>
                                        <?php $change = $summary['change'] ?? 0; ?>
                                        <span class="text-<?= $change >= 0 ? 'success' : 'error' ?>">
                                            <?= $change >= 0 ? '+' : '' ?><?= $change ?>%
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">No data available</td></tr>
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
                <a href="/reports" class="btn btn-outline btn-primary">All Reports</a>
                <a href="/reports/finance" class="btn btn-outline btn-secondary">Finance Report</a>
                <a href="/reports/operational" class="btn btn-outline btn-accent">Operational Report</a>
                <a href="/settings" class="btn btn-outline btn-info">Settings</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
