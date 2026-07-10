@extends('layouts.admin')
@section('title', 'Cajas POS')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h4">Historial de cajas POS</h1></div>
<div class="card"><div class="card-body table-responsive">
<table class="table align-middle"><thead><tr><th>ID</th><th>Terminal</th><th>Abierta por</th><th>Estado</th><th>Inicial</th><th>Esperado</th><th>Diferencia</th><th>Fecha</th><th></th></tr></thead><tbody>
@forelse($sessions as $session)
<tr><td>#{{ $session->id }}</td><td>{{ $session->terminal?->name }}</td><td>{{ $session->opener?->name }}</td><td>{{ $session->status }}</td><td>${{ number_format($session->opening_amount, 0, ',', '.') }}</td><td>${{ number_format($session->expected_cash_amount, 0, ',', '.') }}</td><td>${{ number_format($session->cash_difference ?? 0, 0, ',', '.') }}</td><td>{{ $session->opened_at->format('d/m/Y H:i') }}</td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.cash-registers.show', $session) }}">Ver</a></td></tr>
@empty <tr><td colspan="9" class="text-muted">Sin cajas registradas.</td></tr> @endforelse
</tbody></table>{{ $sessions->links() }}</div></div>
@endsection
