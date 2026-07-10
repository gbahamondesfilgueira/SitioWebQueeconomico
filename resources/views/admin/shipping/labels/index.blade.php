@extends('layouts.admin')
@section('title','Etiquetas')
@section('page-title','Etiquetas')
@section('content')
    <div class="card border-0 shadow-sm"><table class="table mb-0"><thead><tr><th>Etiqueta</th><th>Pedido</th><th>Tracking</th><th>Estado</th><th></th></tr></thead><tbody>@foreach($labels as $label)<tr><td>{{ $label->label_number }}</td><td>{{ $label->order?->order_number }}</td><td>{{ $label->tracking_number }}</td><td>{{ $label->status }}</td><td><a href="{{ route('admin.shipping.labels.show',$label) }}" class="btn btn-sm btn-outline-dark">Ver</a></td></tr>@endforeach</tbody></table></div>
@endsection
