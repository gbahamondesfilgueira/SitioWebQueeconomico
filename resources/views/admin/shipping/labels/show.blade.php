@extends('layouts.admin')
@section('title','Etiqueta')
@section('page-title','Etiqueta '.$label->label_number)
@section('content')
    <div class="card border-0 shadow-sm"><div class="card-body"><p>Pedido: {{ $label->order?->order_number }}</p><p>Tracking: {{ $label->tracking_number }}</p><a href="{{ route('admin.shipping.labels.print',$label) }}" class="btn btn-dark">Imprimir</a></div></div>
@endsection
