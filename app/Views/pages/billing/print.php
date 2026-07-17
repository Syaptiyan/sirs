<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?= esc($invoice->invoice_number) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.5;
        }
        .invoice {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .company-info h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
            color: #333;
        }
        .company-info p {
            margin: 2px 0;
            color: #666;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-info h2 {
            margin: 0 0 10px 0;
            font-size: 20px;
            color: #333;
        }
        .invoice-info p {
            margin: 2px 0;
            color: #666;
        }
        .details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .patient-info, .invoice-details {
            flex: 1;
        }
        .patient-info h3, .invoice-details h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .info-row {
            display: flex;
            margin: 5px 0;
        }
        .info-label {
            width: 120px;
            color: #666;
        }
        .info-value {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            margin-left: auto;
            width: 300px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        .totals-row.total {
            border-top: 2px solid #333;
            font-weight: bold;
            font-size: 16px;
            padding-top: 10px;
            margin-top: 5px;
        }
        .footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
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
            .invoice { border: none; }
        }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="header">
            <div class="company-info">
                <h1>RUMAH SAKIT</h1>
                <p>Jl. Contoh No. 123</p>
                <p>Telp: (021) 1234567</p>
                <p>Email: info@rumahsakit.com</p>
            </div>
            <div class="invoice-info">
                <h2>INVOICE</h2>
                <p><strong>No: <?= esc($invoice->invoice_number) ?></strong></p>
                <p>Tanggal: <?= date('d/m/Y', strtotime($invoice->invoice_date)) ?></p>
            </div>
        </div>

        <div class="details">
            <div class="patient-info">
                <h3>Informasi Pasien</h3>
                <div class="info-row">
                    <span class="info-label">Nama</span>
                    <span class="info-value"><?= esc($invoice->patient_name ?? '-') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. RM</span>
                    <span class="info-value"><?= esc($invoice->mrn ?? '-') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. Kunjungan</span>
                    <span class="info-value"><?= esc($invoice->visit_number ?? '-') ?></span>
                </div>
            </div>
            <div class="invoice-details">
                <h3>Informasi Invoice</h3>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <?php
                        $statusLabels = [
                            'unpaid'    => 'Belum Dibayar',
                            'partial'   => 'Sebagian',
                            'paid'      => 'Lunas',
                            'cancelled' => 'Dibatalkan',
                        ];
                        echo $statusLabels[$invoice->status] ?? $invoice->status;
                        ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jatuh Tempo</span>
                    <span class="info-value">-</span>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tipe</th>
                    <th>Nama Item</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999;">Tidak ada item</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $i => $item): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <?php
                                $typeLabels = [
                                    'consultation' => 'Konsultasi',
                                    'action'       => 'Tindakan',
                                    'drug'         => 'Obat',
                                    'lab'          => 'Lab',
                                    'radiology'    => 'Radiologi',
                                    'room'         => 'Kamar',
                                ];
                                echo $typeLabels[$item->item_type] ?? $item->item_type;
                                ?>
                            </td>
                            <td><?= esc($item->item_name) ?></td>
                            <td class="text-right"><?= $item->quantity ?></td>
                            <td class="text-right">Rp <?= number_format($item->unit_price, 0, ',', '.') ?></td>
                            <td class="text-right">Rp <?= number_format($item->total_price, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="totals">
            <div class="totals-row">
                <span>Subtotal</span>
                <span>Rp <?= number_format($invoice->subtotal, 0, ',', '.') ?></span>
            </div>
            <div class="totals-row">
                <span>Diskon</span>
                <span>- Rp <?= number_format($invoice->discount_amount, 0, ',', '.') ?></span>
            </div>
            <div class="totals-row">
                <span>Pajak</span>
                <span>Rp <?= number_format($invoice->tax_amount, 0, ',', '.') ?></span>
            </div>
            <div class="totals-row total">
                <span>TOTAL</span>
                <span>Rp <?= number_format($invoice->total_amount, 0, ',', '.') ?></span>
            </div>
            <div class="totals-row">
                <span>Sudah Dibayar</span>
                <span>Rp <?= number_format($invoice->paid_amount, 0, ',', '.') ?></span>
            </div>
            <div class="totals-row total">
                <span>SISA TAGIHAN</span>
                <span>Rp <?= number_format($invoice->remaining_amount, 0, ',', '.') ?></span>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih atas kunjungan Anda</p>
            <p>Dokumen ini dicetak secara otomatis dan sah tanpa tanda tangan</p>
            <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
        </div>
    </div>

    <button class="print-btn" onclick="window.print()">Cetak Invoice</button>
</body>
</html>
