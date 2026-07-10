@props(['label', 'value'])

<div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
            <div class="small text-secondary">{{ $label }}</div>
            <div class="fs-4 fw-bold">{{ $value }}</div>
        </div>
    </div>
</div>
