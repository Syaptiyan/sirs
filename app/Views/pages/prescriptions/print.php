<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resep <?= esc($prescription->prescription_number) ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 14px; margin: 20px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0 0; font-size: 12px; color: #666; }
        .info { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .info-col { flex: 1; }
        .info-col .label { font-size: 11px; color: #666; text-transform: uppercase; }
        .info-col .value { font-weight: bold; margin-top: 2px; }
        .rx-symbol { font-size: 32px; font-weight: bold; font-style: italic; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #f5f5f5; text-align: left; padding: 8px; border: 1px solid #ddd; font-size: 12px; }
        td { padding: 8px; border: 1px solid #ddd; }
        .item-number { width: 30px; text-align: center; }
        .footer { margin-top: 40px; display: flex; justify-content: space-between; }
        .signature { text-align: center; width: 200px; }
        .signature .line { border-top: 1px solid #333; margin-top: 60px; padding-top: 5px; }
        .notes { background: #f9f9f9; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 12px; }
        @media print { body { margin: 0; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>RESEP OBAT</h1>
        <p>Rumah Sakit</p>
    </div>

    <div class="info">
        <div class="info-col">
            <div class="label">No. Resep</div>
            <div class="value"><?= esc($prescription->prescription_number) ?></div>
        </div>
        <div class="info-col">
            <div class="label">Tanggal</div>
            <div class="value"><?= esc($prescription->prescription_date) ?></div>
        </div>
        <div class="info-col">
            <div class="label">No. Kunjungan</div>
            <div class="value"><?= esc($prescription->visit_number ?? '-') ?></div>
        </div>
    </div>

    <div class="info">
        <div class="info-col">
            <div class="label">Pasien</div>
            <div class="value"><?= esc($prescription->patient_name ?? '-') ?></div>
            <div style="font-size:12px; color:#666;"><?= esc($prescription->mrn ?? '') ?></div>
        </div>
        <div class="info-col">
            <div class="label">Dokter</div>
            <div class="value">dr. <?= esc($prescription->doctor_name ?? '-') ?></div>
        </div>
    </div>

    <div class="rx-symbol">R<sub>x</sub></div>

    <table>
        <thead>
            <tr>
                <th class="item-number">No</th>
                <th>Obat</th>
                <th>Dosis</th>
                <th>Frekuensi</th>
                <th>Durasi</th>
                <th>Jumlah</th>
                <th>Instruksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($prescription->details)): ?>
                <?php foreach ($prescription->details as $i => $detail): ?>
                    <tr>
                        <td class="item-number"><?= $i + 1 ?></td>
                        <td>
                            <strong><?= esc($detail->drug_name ?? '-') ?></strong>
                        </td>
                        <td><?= esc($detail->dosage) ?></td>
                        <td><?= esc($detail->frequency) ?></td>
                        <td><?= esc($detail->duration ?? '-') ?></td>
                        <td><?= number_format($detail->quantity, 2) ?> <?= esc($detail->unit) ?></td>
                        <td><?= esc($detail->instructions ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($prescription->notes): ?>
        <div class="notes">
            <strong>Catatan:</strong> <?= esc($prescription->notes) ?>
        </div>
    <?php endif; ?>

    <div class="footer">
        <div class="signature">
            <div class="line">Pasien</div>
        </div>
        <div class="signature">
            <div class="line">dr. <?= esc($prescription->doctor_name ?? '-') ?></div>
        </div>
    </div>

    <script>window.onload = function() { window.print(); }</script>
</body>
</html>
