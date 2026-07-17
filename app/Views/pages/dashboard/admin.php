<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Total Users</div>
        <div class="stat-value text-primary"><?= $stats['total_users'] ?? 0 ?></div>
        <div class="stat-desc">Registered users</div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Total Patients</div>
        <div class="stat-value text-secondary"><?= $stats['total_patients'] ?? 0 ?></div>
        <div class="stat-desc">All patients</div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Today Visits</div>
        <div class="stat-value text-accent"><?= $stats['today_visits'] ?? 0 ?></div>
        <div class="stat-desc">Visits today</div>
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
            <h2 class="card-title">Recent Activities</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stats['recent_activities'])): ?>
                            <?php foreach ($stats['recent_activities'] as $activity): ?>
                                <tr>
                                    <td><?= $activity['time'] ?? '-' ?></td>
                                    <td><?= $activity['user'] ?? '-' ?></td>
                                    <td><?= $activity['action'] ?? '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center">No recent activities</td></tr>
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
                <a href="/users" class="btn btn-outline btn-primary">Manage Users</a>
                <a href="/patients" class="btn btn-outline btn-secondary">View Patients</a>
                <a href="/visits" class="btn btn-outline btn-accent">All Visits</a>
                <a href="/reports" class="btn btn-outline btn-info">Reports</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
