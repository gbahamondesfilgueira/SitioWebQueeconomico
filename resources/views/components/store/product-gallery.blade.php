@props(['product', 'display'])
<div class="card border-0 shadow-sm">
    <div class="ratio ratio-1x1 bg-light">
        @if($display['image'])
            <img src="{{ $display['image'] }}" alt="{{ $product->name }}" class="object-fit-cover">
        @else
            <div class="d-flex align-items-center justify-content-center text-secondary">Sin imagen</div>
        @endif
    </div>
    @if($product->images->count() > 1)
        <div class="card-body d-flex gap-2 flex-wrap">@foreach($product->images as $image)<img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->alt_text ?: $product->name }}" style="width:64px;height:64px" class="object-fit-cover rounded border">@endforeach</div>
    @endif
</div>
