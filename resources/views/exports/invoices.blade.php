<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>INVOICE - {{ $payment->reference_number }}</title>
    <style>
        body {
            font-family: Inter, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header img {
            max-height: 60px;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
        }

        .company-info {
            text-align: center;
            font-size: 10px;
            margin-top: 5px;
        }

        .details, .table {
            width: 100%;
            margin-top: 20px;
        }

        .details td {
            padding: 4px;
            vertical-align: top;
        }

        .table th, .table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        .table {
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 40px;
            font-size: 11px;
            text-align: right;
        }

        .signature {
            margin-top: 60px;
            text-align: right;
            font-size: 12px;
        }

        .signature p {
            margin: 0;
        }

        .note {
            font-size: 10px;
            margin-top: 30px;
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('images/logo/logo_text.png') }}" alt="Logo Perusahaan">
            <div class="company-info">
                PT Arjuna Lingga Property<br>
                Jl. Raya Hebat No. 123, Jakarta<br>
                Telp: (021) 123-4567 | Email: info@keren.co.id
            </div>
        </div>

        <table class="details">
            <tr>
                <td><strong>No. Invoice:</strong></td>
                <td>{{ $payment->reference_number }}</td>
                <td><strong>Tanggal:</strong></td>
                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
            </tr>
            <tr>
                <td><strong>Proyek:</strong></td>
                <td>{{ $construction->construction_name }}</td>
                <td><strong>Klien:</strong></td>
                <td>{{ $construction->client_name ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>{{ ucfirst($payment->status) }}</td>
                <td><strong>Jatuh Tempo:</strong></td>
                <td>{{ $payment->due_date ? \Carbon\Carbon::parse($payment->due_date)->format('d M Y') : '-' }}</td>
            </tr>
        </table>

        <table class="table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pembayaran proyek: {{ $construction->construction_name }}</td>
                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th>Rp {{ number_format($payment->amount, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <div class="signature">
            <p>Hormat kami,</p>
            <p style="margin-top:60px;"><strong>PT Arjuna Lingga Property</strong></p>
        </div>

        <div class="note">
            <p>Terima kasih atas kepercayaan Anda kepada kami.</p>
        </div>
    </div>
</body>
</html>
