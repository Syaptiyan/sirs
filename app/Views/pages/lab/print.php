<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Lab <?= esc($order->order_number) ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 14px; margin: 20px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0 0; font-size: 12px; color: #666; }
        .info { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .info-col { flex: 1; }
        .info-col .label { font-size: 11px; color: #666; text-transform: uppercase; }
        .info-col .value { font-weight: bold; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #f5f5f5; text-align: left; padding: 8px; border: 1px solid #ddd; font-size: 12px; }
        td { padding: 8px; border: 1px solid #ddd; }
        .item-number { width: 30px; text-align: center; }
        .result-value { font-weight: bold; font-size: 16px; }
        .abnormal { color: #dc2626; }
        .footer { margin-top: 40px; display: flex; justify-content: space-between; }
        .signature { text-align: center; width: 200px; }
        .signature .line { border-top: 1px solid #333; margin-top: 60px; padding-top: 5px; }
        .notes { background: #f9f9f9; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 12px; }
        @media print { body { margin: 0; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>HASIL PEMERIKSAAN LABORATORIUM</h1>
        <p>Rumah Sakit</p>
    </div>

    <div class="info">
        <div class="info-col">
            <div class="label">No. Order</div>
            <div class="value"><?= esc($order->order_number) ?></div>
        </div>
        <div class="info-col">
            <div class="label">Tanggal Order</div>
            <div class="value"><?= esc($order->order_date) ?></div>
        </div>
        <div class="info-col">
            <div class="label">No. Kunjungan</div>
            <div class="value"><?= esc($order->visit_number ?? '-') ?></div>
        </div>
    </div>

    <div class="info">
        <div class="info-col">
            <div class="label">Pasien</div>
            <div class="value"><?= esc($order->patient_name ?? '-') ?></div>
            <div style="font-size:12px; color:#666;"><?= esc($order->mrn ?? '') ?></div>
        </div>
        <div class="info-col">
            <div class="label">Jenis Kelamin</div>
            <div class="value"><?= esc($order->gender ?? '-') ?></div>
        </div>
        <div class="info-col">
            <div class="label">Dokter Pengirim</div>
            <div class="value">dr. <?= esc($order->doctor_name ?? '-') ?></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="item-number">No</th>
                <th>Pemeriksaan</th>
                <th>Hasil</th>
                <th>Satuan</th>
                <th>Nilai Rujukan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($order->items)): ?>
                <?php
                $currentCategory = null;
                foreach ($order->items as $i => $item):
                    if ($item->template_category !== $currentCategory):
                        $currentCategory = $item->template_category;
                ?>
                    <tr>
                        <td colspan="6" style="background:#e5e7eb; font-weight:bold; font-size:12px;">
                            <?= esc($currentCategory ?? 'Lainnya') ?>
                        </td>
                    </tr>
                <?php
                    endif;
                ?>
                    <tr>
                        <td class="item-number"><?= $i + 1 ?></td>
                        <td><?= esc($item->parameter_name) ?></td>
                        <td class="result-value"><?= esc($item->result_value ?? '-') ?></td>
                        <td><?= esc($item->unit ?? '-') ?></td>
                        <td><?= esc($item->normal_range ?? '-') ?></td>
                        <td style="font-size:12px;"><?= esc($item->notes ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <?php
    $resultDate = !empty($order->results) ? $order->results[0]->result_date ?? date('Y-m-d') : date('Y-m-d');
    ?>

    <?php if ($order->notes): ?>
        <div class="notes">
            <strong>Catatan:</strong> <?= esc($order->notes) ?>
        </div>
    <?php endif; ?>

    <div class="footer">
        <div class="signature">
            <div style="font-size:11px; color:#666;"><?= esc($resultDate) ?></div>
            <div class="line">Petugas Lab</div>
        </div>
        <div class="signature">
            <div class="line">dr. <?= esc($order->doctor_name ?? '-') ?></div>
            <div style="font-size:11px; color:#666;">Dokter Pengirim</div>
        </div>
    </div>

    <script>window.onload = function() { window.print(); }</script>
</body>
</html>
