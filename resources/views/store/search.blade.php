@extends('layouts.store')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h1 class="h3 mb-1">Resultados de busqueda</h1>
                <p class="text-secondary mb-0">{{ $products->total() }} resultado(s) para "{{ $query }}"</p>
            </div>
            <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#searchFilters" aria-controls="searchFilters">
                Filtros
            </button>
        </div>

        <div class="row g-4">
            <aside class="col-lg-3 d-none d-lg-block">
                <x-store.filter-sidebar :categories="$categories" :brands="$brands" :origin-countries="$originCountries" />
            </aside>
            <section class="col-lg-9">
                <div class="row row-cols-2 row-cols-md-4 row-cols-xl-5 g-3 qe-shop-product-grid">
                    @forelse($presentedProducts as $display)
                        <div class="col"><x-store.product-card :display="$display" /></div>
                    @empty
                        <div class="col-12"><div class="alert alert-light border">No encontramos productos para tu busqueda.</div></div>
                    @endforelse
                </div>
                <x-store.pagination :items="$products" />
            </section>
        </div>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="searchFilters" aria-labelledby="searchFiltersLabel">
        <div class="offcanvas-header">
            <h2 class="h5 mb-0" id="searchFiltersLabel">Filtros</h2>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body">
            <x-store.filter-sidebar :categories="$categories" :brands="$brands" :origin-countries="$originCountries" submit-label="Filtrar" />
        </div>
    </div>
@endsection
