@extends('layouts.admin')
@section('title','Integraciones envío')
@section('page-title','Integraciones envío')
@section('content')
    <form method="POST" action="{{ route('admin.shipping.integrations.store') }}" class="card border-0 shadow-sm mb-3">@csrf<div class="card-body row g-2"><div class="col-md-3"><select name="shipping_carrier_id" class="form-select">@foreach($carriers as $carrier)<option value="{{ $carrier->id }}">{{ $carrier->name }}</option>@endforeach</select></div><div class="col-md-2"><select name="integration_type" class="form-select"><option value="manual">Manual</option><option value="api">API</option></select></div><div class="col-md-3"><input name="api_base_url" class="form-control" placeholder="URL API"></div><div class="col-md-2"><input name="api_key" class="form-control" placeholder="API Key"></div><div class="col-md-2"><button class="btn btn-dark w-100">Guardar</button></div></div></form>
    <div class="card border-0 shadow-sm"><table class="table mb-0"><tbody>@foreach($integrations as $integration)<tr><td>{{ $integration->carrier?->name }}</td><td>{{ $integration->integration_type }}</td><td>{{ $integration->api_base_url }}</td><td>{{ $integration->is_active ? 'Activa' : 'Inactiva' }}</td></tr>@endforeach</tbody></table></div>
@endsection
