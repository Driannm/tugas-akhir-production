<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Ringkasan Proyek - {{ $summary->construction->construction_name }}</title>
    <style>
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 12px; 
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #FFFFFF;
        }
        .header { 
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2980b9;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 26px;
            font-weight: bold;
            color: #2980b9;
            margin: 0 0 5px 0;
        }
        .header .subtitle {
            font-size: 16px;
            color: #34495e;
        }
        .section { 
            margin-bottom: 25px;
            page-break-inside: avoid;
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .section-title { 
            font-size: 16px; 
            font-weight: bold; 
            margin-bottom: 10px; 
            color: #2980b9;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 5px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
            font-size: 12px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: left;
            vertical-align: top;
        }
        th { 
            background-color: #2980b9; 
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .company-info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
            color: #000;
        }
        .project-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .project-info div {
            width: 48%;
        }
        .footer {
            position: running(footer);
            font-size: 10px;
            line-height: 1.3;
            text-align: center;
            color: #7f8c8d;
            border-top: 1px solid #ecf0f1;
            padding-top: 10px;
            margin-top: 30px;
            font-style: italic;
        }
        .footer span {
            font-weight: bold;
            color: #2980b9;
        }
        ul {
            margin: 0;
            padding-left: 15px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-completed {
            background-color: #2ecc71;
            color: white;
        }
        .badge-pending {
            background-color: #f39c12;
            color: white;
        }
        .badge-cancelled {
            background-color: #e74c3c;
            color: white;
        }
        @page {
            margin: 20mm 20mm 30mm 20mm; /* top, right, bottom, left */
            @bottom-center {
                content: element(footer);
            }
        }
        .page-number:after {
            content: counter(page);
        }
        .total-pages:after {
            content: counter(pages);
        }
    </style>
</head>
<body>
    <div class="company-info">
        <strong>PT. ARJUNA LINGGA PROPERTY</strong><br>
        Jl. Pembangunan No. 123, Jakarta Selatan 12345<br>
        Telp: (021) 12345678 | Email: info@arjunalingga.co.id
    </div>

    <div class="header">
        <h1>LAPORAN RINGKASAN PROYEK</h1>
        <div class="subtitle">{{ $summary->construction->construction_name }}</div>
    </div>

    <div class="project-info">
        <div>
            <strong>Lokasi Proyek:</strong> {{ $summary->construction->location ?? '-' }}<br>
            <strong>Manajer Proyek:</strong> {{ $summary->construction->supervisor_id ?? '-' }}
        </div>
        <div>
            <strong>Tanggal Ringkasan:</strong> {{ $summary->date->format('d F Y') }}<br>
            <strong>Nomor Dokumen:</strong> SUM/{{ $summary->date->format('Ym') }}/{{ str_pad($summary->id, 4, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">DESKRIPSI PROYEK</div>
        <p>{{ $summary->description }}</p>
    </div>

    @if ($summary->notes)
    <div class="section">
        <div class="section-title">CATATAN KHUSUS</div>
        <p>{{ $summary->notes }}</p>
    </div>
    @endif

    @if ($summary->workers->count())
    <div class="section">
        <div class="section-title">TIM PEKERJA</div>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Nama</th>
                    <th width="20%">Posisi</th>
                    <th width="25%">Periode Kerja</th>
                    <th width="25%">Durasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($summary->workers as $index => $worker)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $worker->worker_name }}</td>
                        <td>{{ $worker->position }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($worker->pivot->start_date)->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($worker->pivot->end_date)->format('d M Y') }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($worker->pivot->start_date)->diffInDays(\Carbon\Carbon::parse($worker->pivot->end_date)) }} hari
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if ($summary->dailyReports->count())
    <div class="section">
        <div class="section-title">LAPORAN HARIAN PROYEK</div>
        <table>
            <thead>
                <tr>
                    <th width="10%">Tanggal</th>
                    <th width="10%">Cuaca</th>
                    <th width="15%">Status</th>
                    <th width="35%">Pekerjaan</th>
                    <th width="30%">Kendala</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($summary->dailyReports as $report)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}</td>
                        <td>{{ $report->weather ?? '-' }}</td>
                        <td>
                            @php
                                $statusClass = '';
                                if ($report->status === 'completed') $statusClass = 'badge-completed';
                                elseif ($report->status === 'in_progress') $statusClass = 'badge-pending';
                                else $statusClass = 'badge-cancelled';
                            @endphp
                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </td>
                        <td>{{ $report->description ?? '-' }}</td>
                        <td>{{ $report->issues ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if ($summary->materialRequests->count())
    <div class="section">
        <div class="section-title">PERMINTAAN MATERIAL</div>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="20%">Dibuat Oleh</th>
                    <th width="20%">Status</th>
                    <th width="40%">Daftar Material</th>
                </tr>
            </thead>
            <tbody>
                @foreach($summary->materialRequests as $i => $request)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $request->created_at->format('d M Y') }}</td>
                    <td>{{ $request->requestedBy->name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge 
                            {{ $request->overall_status === 'approved' ? 'badge-completed' : 
                                ($request->overall_status === 'pending' ? 'badge-pending' : 'badge-cancelled') }}">
                            {{ ucfirst($request->overall_status) }}
                        </span>
                    </td>
                    <td>
                        <ul>
                            @foreach($request->materialRequestItems as $item)
                            <li>
                                {{ $item->material->material_name ?? 'Tidak ditemukan' }}: 
                                {{ $item->quantity }} {{ $item->material->unit ?? 'unit' }} 
                                - <strong>{{ ucfirst($item->status) }}</strong>
                            </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        Dokumen ini dicetak pada {{ now()->format('d F Y H:i') }} oleh {{ Auth::user()->name ?? 'System' }}<br>
        Halaman <span class="page-number"></span> dari <span class="total-pages"></span>
    </div>
</body>
</html>
