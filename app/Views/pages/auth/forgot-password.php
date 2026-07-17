<?= $this->extend('layouts/auth') ?>
<?= $this->section('title') ?>Forgot Password<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card w-full max-w-md bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title text-2xl font-bold text-center justify-center mb-2">Forgot Password</h2>
        <p class="text-center text-sm text-base-content/70 mb-4">Enter your email address and we'll send you a link to reset your password.</p>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>
        <form action="/forgot-password" method="POST">
            <?= csrf_field() ?>
            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text">Email</span>
                </label>
                <input type="email" name="email" placeholder="email@example.com" class="input input-bordered w-full" value="<?= old('email') ?>" required />
            </div>
            <div class="form-control">
                <button type="submit" class="btn btn-primary w-full">Send Reset Link</button>
            </div>
        </form>
        <div class="divider">OR</div>
        <p class="text-center text-sm">
            Remember your password? <a href="/login" class="link link-primary">Back to Login</a>
        </p>
    </div>
</div>
<?= $this->endSection() ?>
