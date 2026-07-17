<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Pembayaran - <?= esc($payment->payment_number) ?></title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.4;
        }
        .receipt {
            max-width: 400px;
            margin: 0 auto;
            border: 1px dashed #ccc;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 5px 0;
        }
        .header p {
            margin: 2px 0;
            font-size: 12px;
            color: #666;
        }
        .info {
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
        .info-label {
            color: #666;
        }
        .info-value {
            font-weight: bold;
        }
        .divider {
            border-top: 1px dashed #ccc;
            margin: 15px 0;
        }
        .total {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            border-top: 1px dashed #ccc;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 12px;
            color: #666;
        }
        .print-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 30px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        @media print {
            .print-btn { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>RUMAH SAKIT</h1>
            <p>Jl. Contoh No. 123</p>
            <p>Telp: (021) 1234567</p>
        </div>

        <div class="info">
            <div class="info-row">
                <span class="info-label">No. Pembayaran</span>
                <span class="info-value"><?= esc($payment->payment_number) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">No. Tagihan</span>
                <span class="info-value"><?= esc($payment->invoice_number ?? '-') ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal</span>
                <span class="info-value"><?= date('d/m/Y H:i', strtotime($payment->payment_date)) ?></span>
            </div>
        </div>

        <div class="divider"></div>

        <div class="info">
            <div class="info-row">
                <span class="info-label">Pasien</span>
                <span class="info-value"><?= esc($payment->patient_name ?? '-') ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">No. RM</span>
                <span class="info-value"><?= esc($payment->mrn ?? '-') ?></span>
            </div>
        </div>

        <div class="divider"></div>

        <div class="info">
            <div class="info-row">
                <span class="info-label">Metode Bayar</span>
                <span class="info-value"><?= esc($payment->payment_method_name ?? '-') ?></span>
            </div>
            <?php if ($payment->reference_number): ?>
                <div class="info-row">
                    <span class="info-label">No. Referensi</span>
                    <span class="info-value"><?= esc($payment->reference_number) ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="divider"></div>

        <div class="total">
            JUMLAH BAYAR<br>
            Rp <?= number_format($payment->amount, 0, ',', '.') ?>
        </div>

        <div class="divider"></div>

        <div class="info">
            <div class="info-row">
                <span class="info-label">No. Receipt</span>
                <span class="info-value"><?= esc($receipt->receipt_number ?? '-') ?></span>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih atas pembayaran Anda</p>
            <p>Dokumen ini adalah bukti pembayaran yang sah</p>
            <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
        </div>
    </div>

    <button class="print-btn" onclick="window.print()">Cetak Receipt</button>
</body>
</html>
