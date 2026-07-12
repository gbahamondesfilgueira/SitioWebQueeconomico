@props(['category'])
<a href="{{ route('store.categories.show', $category->slug) }}" class="card border-0 shadow-sm text-decoration-none text-dark h-100">
    <div class="ratio ratio-16x9 bg-light">
        @if($category->image_path)
            <img
    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(
        str_starts_with(ltrim($category->image_path, '/'), 'storage/')
            ? substr(ltrim($category->image_path, '/'), strlen('storage/'))
            : ltrim($category->image_path, '/')
    ) }}"
    alt="{{ $category->name }}"
    class="object-fit-cover"
>
        @else
            <div class="d-flex align-items-center justify-content-center fw-semibold">{{ $category->name }}</div>
        @endif
    </div>
    <div class="card-body"><div class="fw-semibold">{{ $category->name }}</div><div class="small text-secondary">{{ $category->description }}</div></div>
</a>
