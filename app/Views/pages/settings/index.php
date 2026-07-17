<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Pengaturan Sistem</h1>
</div>

<?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span><?= session()->getFlashdata('message') ?></span>
    </div>
<?php endif; ?>

<div class="tabs tabs-boxed mb-6 bg-base-100 p-1">
    <a class="tab <?= ($activeTab ?? 'general') === 'general' ? 'tab-active' : '' ?>"
       href="/settings?tab=general">Umum</a>
    <a class="tab <?= ($activeTab ?? 'general') === 'email' ? 'tab-active' : '' ?>"
       href="/settings?tab=email">Email</a>
    <a class="tab <?= ($activeTab ?? 'general') === 'payment' ? 'tab-active' : '' ?>"
       href="/settings?tab=payment">Pembayaran</a>
</div>

<div class="card bg-base-100 shadow-sm">
    <div class="card-body">
        <?php if (($activeTab ?? 'general') === 'general'): ?>
            <form method="POST" action="/settings/update">
                <input type="hidden" name="tab" value="general">
                <h2 class="card-title mb-4">Pengaturan Umum</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Nama Rumah Sakit</span></label>
                        <input type="text" name="app_name"
                               value="<?= esc($settings['general']['app_name'] ?? '') ?>"
                               class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Tagline</span></label>
                        <input type="text" name="app_tagline"
                               value="<?= esc($settings['general']['app_tagline'] ?? '') ?>"
                               class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Alamat</span></label>
                        <textarea name="app_address" class="textarea textarea-bordered"><?= esc($settings['general']['app_address'] ?? '') ?></textarea>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Telepon</span></label>
                        <input type="text" name="app_phone"
                               value="<?= esc($settings['general']['app_phone'] ?? '') ?>"
                               class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Email</span></label>
                        <input type="email" name="app_email"
                               value="<?= esc($settings['general']['app_email'] ?? '') ?>"
                               class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Website</span></label>
                        <input type="url" name="app_website"
                               value="<?= esc($settings['general']['app_website'] ?? '') ?>"
                               class="input input-bordered" />
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="btn btn-primary">Simpan Pengaturan Umum</button>
                </div>
            </form>

        <?php elseif (($activeTab ?? 'general') === 'email'): ?>
            <form method="POST" action="/settings/update">
                <input type="hidden" name="tab" value="email">
                <h2 class="card-title mb-4">Pengaturan Email</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">SMTP Host</span></label>
                        <input type="text" name="email_smtp_host"
                               value="<?= esc($settings['email']['email_smtp_host'] ?? '') ?>"
                               class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">SMTP Port</span></label>
                        <input type="number" name="email_smtp_port"
                               value="<?= esc($settings['email']['email_smtp_port'] ?? '') ?>"
                               class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">SMTP Username</span></label>
                        <input type="text" name="email_smtp_user"
                               value="<?= esc($settings['email']['email_smtp_user'] ?? '') ?>"
                               class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">SMTP Password</span></label>
                        <input type="password" name="email_smtp_pass"
                               value="<?= esc($settings['email']['email_smtp_pass'] ?? '') ?>"
                               class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">From Email</span></label>
                        <input type="email" name="email_from"
                               value="<?= esc($settings['email']['email_from'] ?? '') ?>"
                               class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">From Name</span></label>
                        <input type="text" name="email_from_name"
                               value="<?= esc($settings['email']['email_from_name'] ?? '') ?>"
                               class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Encryption</span></label>
                        <select name="email_encryption" class="select select-bordered">
                            <option value="tls" <?= ($settings['email']['email_encryption'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS</option>
                            <option value="ssl" <?= ($settings['email']['email_encryption'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                            <option value="" <?= ($settings['email']['email_encryption'] ?? '') === '' ? 'selected' : '' ?>>None</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="btn btn-primary">Simpan Pengaturan Email</button>
                </div>
            </form>

        <?php elseif (($activeTab ?? 'general') === 'payment'): ?>
            <form method="POST" action="/settings/update">
                <input type="hidden" name="tab" value="payment">
                <h2 class="card-title mb-4">Pengaturan Pembayaran</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Nama Bank</span></label>
                        <input type="text" name="payment_bank_name"
                               value="<?= esc($settings['payment']['payment_bank_name'] ?? '') ?>"
                               class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Nomor Rekening</span></label>
                        <input type="text" name="payment_account_number"
                               value="<?= esc($settings['payment']['payment_account_number'] ?? '') ?>"
                               class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Atas Nama</span></label>
                        <input type="text" name="payment_account_name"
                               value="<?= esc($settings['payment']['payment_account_name'] ?? '') ?>"
                               class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Mata Uang</span></label>
                        <select name="payment_currency" class="select select-bordered">
                            <option value="IDR" <?= ($settings['payment']['payment_currency'] ?? '') === 'IDR' ? 'selected' : '' ?>>IDR (Rupiah)</option>
                            <option value="USD" <?= ($settings['payment']['payment_currency'] ?? '') === 'USD' ? 'selected' : '' ?>>USD (Dollar)</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">PPN (%)</span></label>
                        <input type="number" name="payment_tax_rate" step="0.01"
                               value="<?= esc($settings['payment']['payment_tax_rate'] ?? '') ?>"
                               class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Prefix Invoice</span></label>
                        <input type="text" name="payment_invoice_prefix"
                               value="<?= esc($settings['payment']['payment_invoice_prefix'] ?? '') ?>"
                               class="input input-bordered" />
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="btn btn-primary">Simpan Pengaturan Pembayaran</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
