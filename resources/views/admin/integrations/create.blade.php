@extends('layouts.admin')
@section('title','Crear integración')
@section('content')<h1 class="h4 mb-3">Crear integración</h1>@include('admin.integrations.form',['action'=>route('admin.integrations.store'),'method'=>'POST'])@endsection
