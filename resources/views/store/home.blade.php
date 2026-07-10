@extends('layouts.store')

@section('content')
    <section class="qe-home-hero">
        <div class="container-fluid px-0">
            <div id="homeHero" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner overflow-hidden">
                    <div class="carousel-item active">
                        <a href="{{ route('store.shop', ['q' => 'Bota con chiporro']) }}" class="qe-home-image-slide">
                            <img src="{{ asset('images/home/slider1.png') }}" alt="Cyberday bota con chiporro">
                        </a>
                    </div>
                    <div class="carousel-item">
                        <a href="{{ route('store.shop', ['q' => 'Crocs con chiporro']) }}" class="qe-home-image-slide">
                            <img src="{{ asset('images/home/slider2.png') }}" alt="Cyberday crocs con chiporro">
                        </a>
                    </div>
                    <div class="carousel-item">
                        <a href="{{ route('store.shop', ['q' => 'Botas de impacto']) }}" class="qe-home-image-slide">
                            <img src="{{ asset('images/home/slider3.png') }}" alt="Cyberday botas de impacto">
                        </a>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#homeHero" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                <button class="carousel-control-next" type="button" data-bs-target="#homeHero" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
            </div>
        </div>
    </section>

    <section class="container py-4">
        <div class="row g-3">
            @foreach(['Despacho preparado', 'Compra segura', 'Retiro en tienda', 'Ofertas activas'] as $benefit)
                <div class="col-6 col-lg-3">
                    <div class="qe-benefit-pill">{{ $benefit }}</div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">Categorias destacadas</h2>
            <a href="{{ route('store.shop') }}">Ver todo</a>
        </div>
        <div class="row g-3">
            @foreach($featuredCategories as $category)
                <div class="col-6 col-md-4 col-lg-2"><x-store.category-card :category="$category" /></div>
            @endforeach
        </div>
    </section>

    <section class="container py-4">
        <div class="row g-3">
            <div class="col-md-4">
                <a href="{{ route('store.shop', ['q' => 'Polerones']) }}" class="qe-promo-tile">
                    <img src="{{ asset('images/home/slider3.png') }}" alt="Botas de impacto">
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('store.shop', ['q' => 'Botas']) }}" class="qe-promo-tile">
                    <img src="{{ asset('images/home/slider1.png') }}" alt="Botas con chiporro">
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('store.shop', ['q' => 'Invierno']) }}" class="qe-promo-tile">
                    <img src="{{ asset('images/home/slider2.png') }}" alt="Crocs con chiporro">
                </a>
            </div>
        </div>
    </section>

    @foreach([['Productos destacados', $featuredProducts], ['Ofertas vigentes', $offerProducts], ['Ultimos productos', $latestProducts]] as [$title, $items])
        <section class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 mb-0">{{ $title }}</h2>
                <a href="{{ route('store.shop') }}">Ver mas</a>
            </div>
            <div class="row g-3 qe-compact-product-grid">
                @forelse($items as $display)
                    <div class="col-6 col-md-4 col-xl-2"><x-store.product-card :display="$display" /></div>
                @empty
                    <div class="col-12 text-secondary">Sin productos para mostrar.</div>
                @endforelse
            </div>
        </section>
    @endforeach

    <section class="qe-banner-band">
        <div class="container">
            <div class="row g-3 align-items-center">
                <div class="col-lg-7">
                    <h2 class="h3">Packs visibles y precios calculados</h2>
                    <p class="mb-0">El stock del pack se calcula desde sus componentes y el precio muestra el ahorro real.</p>
                </div>
                <div class="col-lg-5 text-lg-end"><a href="{{ route('store.packs.index') }}" class="btn btn-dark btn-lg">Explorar packs</a></div>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <h2 class="h4 mb-3">Packs visibles</h2>
        <div class="row g-3">
            @forelse($packs as $display)
                <div class="col-md-3">
                    <a class="card border-0 shadow-sm text-decoration-none text-dark h-100" href="{{ route('store.packs.show', $display['pack']->slug) }}">
                        <div class="ratio ratio-1x1 bg-light">@if($display['image'])<img src="{{ $display['image'] }}" class="object-fit-cover" alt="{{ $display['pack']->name }}">@endif</div>
                        <div class="card-body"><div class="fw-semibold">{{ $display['pack']->name }}</div><div class="text-danger fw-bold">${{ number_format($display['pack_price'], 0, ',', '.') }}</div><div class="small text-secondary">Ahorro ${{ number_format($display['saving'], 0, ',', '.') }}</div></div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-secondary">Sin packs visibles.</div>
            @endforelse
        </div>
    </section>

    <section class="container pb-5">
        <div class="qe-newsletter-box">
            <div>
                <h2 class="h4">Recibe novedades y ofertas</h2>
                <p class="text-secondary mb-0">Promociones, nuevos productos y campanas comerciales.</p>
            </div>
            <form method="POST" action="{{ route('store.newsletter') }}" class="d-flex gap-2">
                @csrf
                <input type="hidden" name="source" value="home">
                <input type="email" name="email" class="form-control" placeholder="tu@email.cl" required>
                <button class="btn btn-dark">Suscribirme</button>
            </form>
        </div>
    </section>
@endsection
