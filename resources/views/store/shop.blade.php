@extends('layouts.store')

@section('content')
    <div class="container py-4">
        <x-store.breadcrumb :items="['Tienda' => route('store.shop')]" />

        <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
            <h1 class="h3 mb-0">Tienda</h1>

            <div class="d-flex gap-2">
                <button class="btn btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#storeFilters" aria-controls="storeFilters">
                    Filtros
                </button>

                <form>
                    @foreach(request()->except('sort', 'page') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $item)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="recent">Mas recientes</option>
                        <option value="price_asc" @selected(request('sort') === 'price_asc')>Menor precio</option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>Mayor precio</option>
                        <option value="name_asc" @selected(request('sort') === 'name_asc')>Nombre A-Z</option>
                        <option value="name_desc" @selected(request('sort') === 'name_desc')>Nombre Z-A</option>
                        <option value="featured" @selected(request('sort') === 'featured')>Destacados</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <aside class="col-lg-3 d-none d-lg-block">
                <x-store.filter-sidebar :categories="$categories" :brands="$brands" :origin-countries="$originCountries" />
            </aside>

            <section class="col-lg-9">
                <div class="row row-cols-2 row-cols-md-4 row-cols-xl-5 g-3 qe-shop-product-grid">
                    @forelse($presentedProducts as $display)
                        <div class="col">
                            <x-store.product-card :display="$display" />
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-light border">No hay productos con esos filtros.</div>
                        </div>
                    @endforelse
                </div>

                <x-store.pagination :items="$products" />
            </section>
        </div>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="storeFilters" aria-labelledby="storeFiltersLabel">
        <div class="offcanvas-header">
            <h2 class="h5 mb-0" id="storeFiltersLabel">Filtros</h2>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body">
            <x-store.filter-sidebar :categories="$categories" :brands="$brands" :origin-countries="$originCountries" submit-label="Filtrar" />
        </div>
    </div>
@endsection
