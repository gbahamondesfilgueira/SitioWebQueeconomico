@extends('layouts.admin')
@section('title','Cotizador envíos')
@section('page-title','Cotizador envíos')
@section('content')
    <form method="POST" class="card border-0 shadow-sm mb-3">@csrf<div class="card-body row g-2">@foreach(['country'=>'País','region'=>'Región','commune'=>'Comuna','city'=>'Ciudad','weight'=>'Peso','height'=>'Alto','width'=>'Ancho','length'=>'Largo'] as $field=>$label)<div class="col-md-3"><label class="form-label">{{ $label }}</label><input name="{{ $field }}" class="form-control" value="{{ old($field, request($field, $field==='country'?'Chile':'')) }}"></div>@endforeach<div class="col-12"><button class="btn btn-dark">Calcular</button></div></div></form>
    <div class="card border-0 shadow-sm"><table class="table mb-0"><thead><tr><th>Transportista</th><th>Servicio</th><th>Zona</th><th>Precio</th></tr></thead><tbody>@forelse($quotes as $rate)<tr><td>{{ $rate->carrier?->name }}</td><td>{{ $rate->service?->name }}</td><td>{{ $rate->zone?->name }}</td><td>${{ number_format((float)$rate->price,0,',','.') }}</td></tr>@empty<tr><td colspan="4" class="text-secondary text-center py-4">Sin tarifas.</td></tr>@endforelse</tbody></table></div>
@endsection
