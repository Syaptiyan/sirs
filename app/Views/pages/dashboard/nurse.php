<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Today Tasks</div>
        <div class="stat-value text-primary"><?= $stats['today_tasks'] ?? 0 ?></div>
        <div class="stat-desc">Tasks assigned today</div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Inpatients</div>
        <div class="stat-value text-secondary"><?= $stats['inpatients'] ?? 0 ?></div>
        <div class="stat-desc">Current inpatients</div>
    </div>
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Pending Assessments</div>
        <div class="stat-value text-warning"><?= $stats['pending_assessments'] ?? 0 ?></div>
        <div class="stat-desc">Need assessment</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title">Today's Tasks</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Patient</th>
                            <th>Task</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stats['task_list'])): ?>
                            <?php foreach ($stats['task_list'] as $task): ?>
                                <tr>
                                    <td><?= $task['time'] ?? '-' ?></td>
                                    <td><?= $task['patient'] ?? '-' ?></td>
                                    <td><?= $task['task'] ?? '-' ?></td>
                                    <td>
                                        <span class="badge badge-<?= ($task['status'] ?? '') === 'done' ? 'success' : 'warning' ?>">
                                            <?= $task['status'] ?? '-' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">No tasks today</td></tr>
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
                <a href="/triage" class="btn btn-outline btn-primary">Triage</a>
                <a href="/inpatients" class="btn btn-outline btn-secondary">Inpatients</a>
                <a href="/assessments" class="btn btn-outline btn-accent">Assessments</a>
                <a href="/vitals" class="btn btn-outline btn-info">Record Vitals</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
