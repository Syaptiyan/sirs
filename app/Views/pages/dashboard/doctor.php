<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Today Schedule</div>
        <div class="stat-value text-primary"><?= $stats['today_schedule'] ?? 0 ?></div>
        <div class="stat-desc">Appointments today</div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Waiting Patients</div>
        <div class="stat-value text-warning"><?= $stats['waiting_patients'] ?? 0 ?></div>
        <div class="stat-desc">Patients waiting</div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Pending Prescriptions</div>
        <div class="stat-value text-error"><?= $stats['pending_prescriptions'] ?? 0 ?></div>
        <div class="stat-desc">Need attention</div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Today Visits</div>
        <div class="stat-value text-success"><?= $stats['today_visits'] ?? 0 ?></div>
        <div class="stat-desc">Completed visits</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title">Today's Schedule</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Patient</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stats['schedule_list'])): ?>
                            <?php foreach ($stats['schedule_list'] as $schedule): ?>
                                <tr>
                                    <td><?= $schedule['time'] ?? '-' ?></td>
                                    <td><?= $schedule['patient'] ?? '-' ?></td>
                                    <td>
                                        <span class="badge badge-<?= ($schedule['status'] ?? '') === 'completed' ? 'success' : 'warning' ?>">
                                            <?= $schedule['status'] ?? '-' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center">No schedule today</td></tr>
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
                <a href="/visits/create" class="btn btn-outline btn-primary">New Visit</a>
                <a href="/prescriptions" class="btn btn-outline btn-secondary">Prescriptions</a>
                <a href="/patients" class="btn btn-outline btn-accent">Patients</a>
                <a href="/medical-records" class="btn btn-outline btn-info">Medical Records</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
