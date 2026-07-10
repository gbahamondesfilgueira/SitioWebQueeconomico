@extends('layouts.admin')
@section('title','Crear '.$config['singular'])
@section('page-title','Crear '.$config['singular'])
@section('content')<div class="card border-0 shadow-sm"><div class="card-body"><form method="POST" action="{{ route($config['routePrefix'].'.store') }}" class="row g-3">@csrf @include('admin.commercial.partials.form')</form></div></div>@endsection
