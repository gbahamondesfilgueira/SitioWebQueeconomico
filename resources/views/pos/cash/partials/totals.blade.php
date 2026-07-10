<div class="bg-white border rounded p-3">
    <h2 class="h5">Totales</h2>
    <div class="row g-3">
        <div class="col-md-3"><strong>Inicial:</strong> ${{ number_format($summary['opening'], 0, ',', '.') }}</div>
        <div class="col-md-3"><strong>Ventas:</strong> ${{ number_format($summary['sales'], 0, ',', '.') }}</div>
        <div class="col-md-3"><strong>Ingresos:</strong> ${{ number_format($summary['income'], 0, ',', '.') }}</div>
        <div class="col-md-3"><strong>Egresos:</strong> ${{ number_format($summary['expense'], 0, ',', '.') }}</div>
        <div class="col-md-3"><strong>Retiros:</strong> ${{ number_format($summary['withdrawal'], 0, ',', '.') }}</div>
        <div class="col-md-3"><strong>Anulaciones:</strong> ${{ number_format($summary['cancellation'], 0, ',', '.') }}</div>
        <div class="col-md-3"><strong>Devoluciones:</strong> ${{ number_format($summary['refund'], 0, ',', '.') }}</div>
        <div class="col-md-3"><strong>Efectivo esperado:</strong> ${{ number_format($summary['cashExpected'], 0, ',', '.') }}</div>
    </div>
    <hr>
    <h3 class="h6">Ventas por medio de pago</h3>
    <div class="table-responsive">
        <table class="table table-sm"><thead><tr><th>Medio</th><th>Ventas</th><th>Ingresos</th><th>Egresos</th><th>Retiros</th><th>Devoluciones</th><th>Anulaciones</th></tr></thead><tbody>
        @foreach($summary['byPaymentMethod'] as $method => $row)
            <tr><td>{{ $method }}</td><td>${{ number_format($row['sales'], 0, ',', '.') }}</td><td>${{ number_format($row['income'], 0, ',', '.') }}</td><td>${{ number_format($row['expense'], 0, ',', '.') }}</td><td>${{ number_format($row['withdrawal'], 0, ',', '.') }}</td><td>${{ number_format($row['refund'], 0, ',', '.') }}</td><td>${{ number_format($row['cancellation'], 0, ',', '.') }}</td></tr>
        @endforeach
        </tbody></table>
    </div>
</div>
