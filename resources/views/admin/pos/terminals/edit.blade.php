@extends('layouts.admin')
@section('title', 'Editar terminal POS')
@section('content')
<h1 class="h4 mb-3">Editar terminal POS</h1>
@include('admin.pos.terminals.form', ['action' => route('admin.pos.terminals.update', $terminal), 'method' => 'PUT'])
@endsection
