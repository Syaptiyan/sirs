<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <div class="stat bg-base-100 shadow rounded-box">
        <div class="stat-title">Welcome</div>
        <div class="stat-value text-primary">Hello!</div>
        <div class="stat-desc">Welcome to SIRS</div>
    </div>
    <?php if (!empty($stats)): ?>
        <?php foreach ($stats as $key => $value): ?>
            <div class="stat bg-base-100 shadow rounded-box">
                <div class="stat-title"><?= ucwords(str_replace('_', ' ', $key)) ?></div>
                <div class="stat-value text-secondary"><?= $value ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="card bg-base-100 shadow-md">
    <div class="card-body">
        <h2 class="card-title">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="/profile" class="btn btn-outline btn-primary">My Profile</a>
            <a href="/patients" class="btn btn-outline btn-secondary">Patients</a>
            <a href="/visits" class="btn btn-outline btn-accent">Visits</a>
            <a href="/reports" class="btn btn-outline btn-info">Reports</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
