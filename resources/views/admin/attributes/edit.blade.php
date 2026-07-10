@extends('layouts.admin')
@section('title', 'Editar atributo')
@section('page-title', 'Editar atributo')
@section('content')
    <div class="card border-0 shadow-sm"><div class="card-body"><form method="POST" action="{{ route('admin.attributes.update', $attribute) }}" class="row g-3">@csrf @method('PUT') @include('admin.attributes.partials.form')</form></div></div>
@endsection
