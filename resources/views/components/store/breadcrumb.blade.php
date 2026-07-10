@props(['items' => [], 'mobile' => false])
<nav class="qe-store-breadcrumb {{ $mobile ? 'qe-store-breadcrumb-mobile' : '' }}" aria-label="breadcrumb">
    <ol class="breadcrumb small mb-0">
        <li class="breadcrumb-item"><a href="{{ route('store.home') }}">Inicio</a></li>
        @foreach($items as $label => $url)
            <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">@if($loop->last){{ $label }}@else<a href="{{ $url }}">{{ $label }}</a>@endif</li>
        @endforeach
    </ol>
</nav>
