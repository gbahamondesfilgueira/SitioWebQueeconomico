@extends('layouts.admin')

@section('title', 'Editar producto')
@section('page-title', 'Editar producto')

@section('content')
    <div class="d-flex justify-content-end gap-2 mb-3">
        <a class="btn btn-outline-secondary" href="{{ route('admin.products.show', $product) }}">Ver ficha</a>
        <a class="btn btn-outline-success" href="{{ route('admin.stock-adjustments.create', ['product_id' => $product->id]) }}">Agregar stock</a>
        @if ($product->product_type === 'variable')
            <a class="btn btn-outline-primary" href="{{ route('admin.products.variants.index', $product) }}">Gestionar variantes</a>
        @endif
    </div>
    <div class="alert alert-info">
        Para ingresar stock: guarda el producto, luego entra a <strong>Agregar stock</strong> y crea un ajuste de inventario aprobado.
        Si el producto es variable, primero crea sus variantes.
    </div>
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.products.partials.form')
    </form>
@endsection
