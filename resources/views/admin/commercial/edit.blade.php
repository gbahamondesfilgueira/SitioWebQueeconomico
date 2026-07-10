@extends('layouts.admin')
@section('title','Editar '.$config['singular'])
@section('page-title','Editar '.$config['singular'])
@section('content')<div class="card border-0 shadow-sm"><div class="card-body"><form method="POST" action="{{ route($config['routePrefix'].'.update',$item) }}" class="row g-3">@csrf @method('PUT') @include('admin.commercial.partials.form')</form></div></div>@endsection
