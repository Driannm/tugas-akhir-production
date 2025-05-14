<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ringkasan Proyek</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { font-size: 22px; font-weight: bold; margin-bottom: 20px; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 16px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <div class="header">Laporan Ringkasan Proyek</div>

    <div class="section">
        <div><strong>Proyek:</strong> {{ $summary->construction->construction_name }}</div>
        <div><strong>Tanggal Ringkasan:</strong> {{ $summary->date->format('d M Y') }}</div>
    </div>

    <div class="section">
        <div class="section-title">Deskripsi</div>
        <p>{{ $summary->description }}</p>
    </div>

    @if ($summary->notes)
    <div class="section">
        <div class="section-title">Catatan Tambahan</div>
        <p>{{ $summary->notes }}</p>
    </div>
    @endif

    @if ($summary->workers->count())
    <div class="section">
        <div class="section-title">Daftar Pekerja</div>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Posisi</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($summary->workers as $worker)
                    <tr>
                        <td>{{ $worker->worker_name }}</td>
                        <td>{{ $worker->position }}</td>
                        <td>{{ \Carbon\Carbon::parse($worker->pivot->start_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($worker->pivot->end_date)->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if ($summary->dailyReports->count())
    <div class="section">
        <div class="section-title">Laporan Harian</div>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Cuaca</th>
                    <th>Status</th>
                    <th>Deskripsi</th>
                    <th>Permasalahan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($summary->dailyReports as $report)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}</td>
                        <td>{{ $report->weather ?? '-' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $report->status)) }}</td>
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
        <div class="section-title">Permintaan Material</div>
        <table>
            <thead>
                <tr>
                    <th>Dibuat Oleh</th>
                    <th>Catatan</th>
                    <th>Item Material</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($summary->materialRequests as $request)
                    <tr>
                        <td>{{ $request->supervisor->name ?? 'N/A' }}</td>
                        <td>{{ $request->notes ?? '-' }}</td>
                        <td>
                            <ul>
                                @foreach ($request->materialRequestItems as $item)
                                    <li>
                                        {{ $item->material->material_name ?? 'Material Tidak Ditemukan' }} - 
                                        {{ $item->quantity }} pcs ({{ $item->status }})
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

</body>
</html>