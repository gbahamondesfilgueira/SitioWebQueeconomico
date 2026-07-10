@extends('layouts.store')

@section('content')
    <div class="container py-5">
        <x-store.breadcrumb :items="[$title => url($slug)]" />
        <div class="card border-0 shadow-sm"><div class="card-body p-4"><h1 class="h3">{{ $title }}</h1><p class="text-secondary">Contenido base preparado para administración legal y comercial de la tienda. Esta página puede ampliarse con textos definitivos antes de publicar el ecommerce.</p></div></div>
    </div>
@endsection
