@extends('layouts.admin')
@section('title',$config['title'])
@section('page-title',$config['title'])
@section('content')
<div class="d-flex justify-content-between mb-3"><h1 class="h4">{{ $config['title'] }}</h1><a class="btn btn-primary" href="{{ route($config['routePrefix'].'.create') }}">Crear</a></div>
<form class="card border-0 shadow-sm mb-3"><div class="card-body"><div class="input-group"><input class="form-control" name="search" value="{{ $search }}" placeholder="Buscar..."><button class="btn btn-outline-secondary">Buscar</button></div></div></form>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr>@foreach($config['columns'] as $label)<th>{{ $label }}</th>@endforeach<th class="text-end">Acciones</th></tr></thead><tbody>
@forelse($items as $item)<tr>@foreach($config['columns'] as $key=>$label)<td>@if(is_bool($item->{$key})){{ $item->{$key} ? 'Sí':'No' }}@elseif($item->{$key} instanceof \Illuminate\Support\Carbon){{ $item->{$key}->format('d/m/Y H:i') }}@else{{ $item->{$key} ?? '-' }}@endif</td>@endforeach<td class="text-end"><div class="btn-group"><a class="btn btn-sm btn-outline-secondary" href="{{ route($config['routePrefix'].'.show',$item) }}">Ver</a><a class="btn btn-sm btn-outline-primary" href="{{ route($config['routePrefix'].'.edit',$item) }}">Editar</a>@if(in_array('is_active',$config['booleanFields']))<form method="POST" action="{{ route($config['routePrefix'].'.toggle-active',$item) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-warning">Estado</button></form>@endif</div></td></tr>@empty<tr><td colspan="{{ count($config['columns']) + 1 }}" class="text-center text-secondary py-4">Sin registros.</td></tr>@endforelse
</tbody></table></div>@if($items->hasPages())<div class="card-footer bg-white">{{ $items->links() }}</div>@endif</div>
@endsection
