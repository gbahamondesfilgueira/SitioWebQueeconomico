@extends('layouts.store')

@section('content')
    <div class="container py-4">
        <x-store.breadcrumb :items="[$category->name => route('store.categories.show', $category->slug)]" />
        <div class="mb-4"><h1 class="h3">{{ $category->name }}</h1><p class="text-secondary">{{ $category->description }}</p></div>
        @if($category->children->isNotEmpty())<div class="row g-3 mb-4">@foreach($category->children as $child)<div class="col-md-3"><x-store.category-card :category="$child" /></div>@endforeach</div>@endif
        <div class="row g-4"><aside class="col-lg-3"><x-store.filter-sidebar :categories="$categories" :brands="$brands" :origin-countries="$originCountries" /></aside><section class="col-lg-9"><div class="row g-3">@forelse($presentedProducts as $display)<div class="col-sm-6 col-xl-4"><x-store.product-card :display="$display" /></div>@empty<div class="col-12 text-secondary">Sin productos en esta categoría.</div>@endforelse</div><x-store.pagination :items="$products" /></section></div>
    </div>
@endsection
