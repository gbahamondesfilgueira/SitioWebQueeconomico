@props(['categories', 'brands', 'originCountries', 'submitLabel' => 'Aplicar filtros'])
<form class="card border-0 shadow-sm">
    @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
    <div class="card-header bg-white fw-semibold">Filtros</div>
    <div class="card-body">
        <label class="form-label">Categoría</label>
        <select name="category_id" class="form-select mb-3"><option value="">Todas</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected((int)request('category_id') === $category->id)>{{ $category->name }}</option>@endforeach</select>
        <label class="form-label">Marca</label>
        <select name="brand_id" class="form-select mb-3"><option value="">Todas</option>@foreach($brands as $brand)<option value="{{ $brand->id }}" @selected((int)request('brand_id') === $brand->id)>{{ $brand->name }}</option>@endforeach</select>
        <div class="row g-2 mb-3"><div class="col"><label class="form-label">Precio mín.</label><input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control"></div><div class="col"><label class="form-label">Precio máx.</label><input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control"></div></div>
        <label class="form-label">País origen</label>
        <select name="origin_country_id" class="form-select mb-3"><option value="">Todos</option>@foreach($originCountries as $country)<option value="{{ $country->id }}" @selected((int)request('origin_country_id') === $country->id)>{{ $country->name }}</option>@endforeach</select>
        <label class="form-check mb-2"><input type="checkbox" name="on_sale" value="1" class="form-check-input" @checked(request()->boolean('on_sale'))> En oferta</label>
        <label class="form-check mb-2"><input type="checkbox" name="featured" value="1" class="form-check-input" @checked(request()->boolean('featured'))> Destacados</label>
        <label class="form-check mb-3"><input type="checkbox" name="with_stock" value="1" class="form-check-input" @checked(request()->boolean('with_stock'))> Con stock</label>
        <button class="btn btn-dark w-100">{{ $submitLabel }}</button>
    </div>
</form>
