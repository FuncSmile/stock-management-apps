<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota #<?= $batch_id ?></title>
    <style>
        @page {
            size: auto;
            margin: 0mm;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 58mm; /* Standard minor thermal width */
            padding: 5mm;
            margin: 0 auto;
            background-color: #fff;
            color: #000;
            font-size: 10px;
            line-height: 1.2;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .divider {
            border-top: 1px dashed #000;
            margin: 3mm 0;
            height: 0;
        }
        .header { margin-bottom: 5mm; }
        .header h1 { 
            font-size: 14px; 
            margin: 0; 
            text-transform: uppercase;
        }
        .header p { margin: 1px 0; }
        .item-list { width: 100%; border-collapse: collapse; }
        .item-list td { vertical-align: top; padding: 1mm 0; }
        .total-section { margin-top: 3mm; }
        .total-section td { font-weight: bold; }
        .footer { 
            margin-top: 8mm; 
            margin-bottom: 5mm;
            font-size: 8px;
        }
        .no-print {
            margin-top: 20px;
            padding: 10px;
            background: #f4f4f4;
            border-radius: 5px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background: #4f46e5;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-family: sans-serif;
            font-size: 12px;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="header text-center">
        <h1>NEXSTOCK QR</h1>
        <p>Pasar Sepatu Indonesia</p>
        <p>Jl. Contoh No. 123, Jakarta</p>
        <p>HP: 0812-3456-7890</p>
    </div>

    <div class="divider"></div>

    <div class="info">
        <p>No. Nota: <?= $batch_id ?></p>
        <p>Tanggal : <?= date('d/m/y H:i', strtotime($created_at)) ?></p>
        <p>Kasir   : <?= $staff_name ?></p>
    </div>

    <div class="divider"></div>

    <table class="item-list">
        <?php foreach ($transactions as $t): ?>
        <tr>
            <td colspan="3"><?= $t['item_name'] ?></td>
        </tr>
        <tr>
            <td style="width: 30%"><?= $t['qty'] ?> x</td>
            <td class="text-right"><?= number_format($t['deal_price'], 0, ',', '.') ?></td>
            <td class="text-right" style="width: 35%"><?= number_format($t['deal_price'] * $t['qty'], 0, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="divider"></div>

    <table class="item-list total-section">
        <tr>
            <td style="width: 65%">TOTAL BELANJA</td>
            <td class="text-right">Rp <?= number_format($total_amount, 0, ',', '.') ?></td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="footer text-center">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Barang yang sudah dibeli</p>
        <p>tidak dapat ditukar/dikembalikan.</p>
        <p>*** NEXSTOCK QR ***</p>
    </div>

    <div class="no-print">
        <p>Klik tombol di bawah jika dialog cetak tidak otomatis muncul.</p>
        <button onclick="window.print()" class="btn">Cetak Struk</button>
        <a href="<?= base_url('scan') ?>" class="btn" style="background:#64748b; margin-top:5px;">Kembali ke Scanner</a>
    </div>

    <script>
        // Start printing once the page is loaded
        window.addEventListener('load', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
