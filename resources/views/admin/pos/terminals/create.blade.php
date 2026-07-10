@extends('layouts.admin')
@section('title', 'Crear terminal POS')
@section('content')
<h1 class="h4 mb-3">Crear terminal POS</h1>
@include('admin.pos.terminals.form', ['action' => route('admin.pos.terminals.store'), 'method' => 'POST', 'terminal' => null])
@endsection
