@extends('layouts.admin')
@section('title','Editar integración')
@section('content')<h1 class="h4 mb-3">Editar integración</h1>@include('admin.integrations.form',['action'=>route('admin.integrations.update',$integration),'method'=>'PUT'])@endsection
