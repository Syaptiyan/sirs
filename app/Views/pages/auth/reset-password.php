<?= $this->extend('layouts/auth') ?>
<?= $this->section('title') ?>Reset Password<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card w-full max-w-md bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title text-2xl font-bold text-center justify-center mb-2">Reset Password</h2>
        <p class="text-center text-sm text-base-content/70 mb-4">Enter your new password below.</p>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-error mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <p><?= $err ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>
        <form action="/reset-password" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= $token ?? old('token') ?>" />
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">New Password</span>
                </label>
                <input type="password" name="password" placeholder="••••••••" class="input input-bordered w-full" required />
            </div>
            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text">Confirm New Password</span>
                </label>
                <input type="password" name="password_confirm" placeholder="••••••••" class="input input-bordered w-full" required />
            </div>
            <div class="form-control">
                <button type="submit" class="btn btn-primary w-full">Reset Password</button>
            </div>
        </form>
        <div class="divider">OR</div>
        <p class="text-center text-sm">
            <a href="/login" class="link link-primary">Back to Login</a>
        </p>
    </div>
</div>
<?= $this->endSection() ?>
