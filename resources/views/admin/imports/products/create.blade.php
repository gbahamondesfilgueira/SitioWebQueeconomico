@extends('layouts.admin')

@section('title', 'Subir productos')
@section('page-title', 'Subir productos')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('admin.imports.products.store') }}" enctype="multipart/form-data" class="card border-0 shadow-sm">
                @csrf
                <div class="card-body">
                    <h1 class="h4">Subir CSV WooCommerce</h1>
                    <p class="text-secondary">Formato soportado: exportaci&oacute;n de productos WooCommerce en espa&ntilde;ol. El importador crea o actualiza productos por SKU, crea maestros relacionados cuando no existan y carga stock inicial si el CSV trae inventario.</p>

                    <div class="mb-3">
                        <label class="form-label">Archivo CSV</label>
                        <input type="file" name="file" accept=".csv,text/csv" class="form-control" required>
                        @error('file')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="alert alert-info mb-0">
                        Se importar&aacute;: productos simples, productos variables, variantes, categor&iacute;as, marcas, etiquetas, atributos, precios, descripciones, SKU, c&oacute;digos de barra, peso, medidas, im&aacute;genes por URL y stock inicial en Bodega Principal cuando corresponda.
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between">
                    <a href="{{ route('admin.imports.products.index') }}" class="btn btn-outline-secondary">Volver</a>
                    <button class="btn btn-dark">Procesar importaci&oacute;n</button>
                </div>
            </form>
        </div>
    </div>
@endsection
