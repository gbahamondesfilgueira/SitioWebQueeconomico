<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; }
        table { border-collapse: collapse; width: 100%; font-size: 12px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
        .summary { display: flex; flex-wrap: wrap; gap: 10px; margin: 16px 0; }
        .summary div { border: 1px solid #ddd; padding: 10px; min-width: 160px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <button class="no-print" onclick="window.print()">Imprimir / guardar PDF</button>
    <h1>{{ $title }}</h1>
    <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>

    <div class="summary">
        @foreach (($report['summary'] ?? []) as $label => $value)
            <div><strong>{{ $label }}</strong><br>{{ $value }}</div>
        @endforeach
    </div>

    <table>
        <thead>
            <tr>
                @foreach (($report['columns'] ?? []) as $column)
                    <th>{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach (($report['rows'] ?? []) as $row)
                <tr>
                    @foreach ($row as $value)
                        <td>{{ $value }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
