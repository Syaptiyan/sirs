<?= $this->extend('layouts/auth') ?>
<?= $this->section('title') ?>Register<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card w-full max-w-md bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title text-2xl font-bold text-center justify-center mb-4">Register</h2>
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
        <form action="/register" method="POST">
            <?= csrf_field() ?>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Full Name</span>
                </label>
                <input type="text" name="name" placeholder="John Doe" class="input input-bordered w-full" value="<?= old('name') ?>" required />
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Email</span>
                </label>
                <input type="email" name="email" placeholder="email@example.com" class="input input-bordered w-full" value="<?= old('email') ?>" required />
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Password</span>
                </label>
                <input type="password" name="password" placeholder="••••••••" class="input input-bordered w-full" required />
            </div>
            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text">Confirm Password</span>
                </label>
                <input type="password" name="password_confirm" placeholder="••••••••" class="input input-bordered w-full" required />
            </div>
            <div class="form-control">
                <button type="submit" class="btn btn-primary w-full">Register</button>
            </div>
        </form>
        <div class="divider">OR</div>
        <p class="text-center text-sm">
            Already have an account? <a href="/login" class="link link-primary">Login</a>
        </p>
    </div>
</div>
<?= $this->endSection() ?>
