@extends('layouts.admin')
@section('title','Tracking')
@section('page-title','Tracking')
@section('content')
    <form method="POST" action="{{ route('admin.shipping.tracking.store') }}" class="card border-0 shadow-sm mb-3">@csrf<div class="card-body row g-2"><div class="col-md-3"><select name="order_shipment_id" class="form-select">@foreach($shipments as $shipment)<option value="{{ $shipment->id }}">{{ $shipment->order?->order_number }} - {{ $shipment->service_name }}</option>@endforeach</select></div><div class="col-md-2"><input name="status" class="form-control" placeholder="Estado"></div><div class="col-md-3"><input name="description" class="form-control" placeholder="Descripción"></div><div class="col-md-2"><input name="location" class="form-control" placeholder="Ubicación"></div><div class="col-md-2"><button class="btn btn-dark w-100">Registrar</button></div></div></form>
    <div class="card border-0 shadow-sm"><table class="table mb-0"><tbody>@foreach($events as $event)<tr><td>{{ $event->created_at?->format('d/m/Y H:i') }}</td><td>{{ $event->shipment?->order?->order_number }}</td><td>{{ $event->status }}</td><td>{{ $event->description }}</td><td>{{ $event->location }}</td></tr>@endforeach</tbody></table></div>
@endsection
