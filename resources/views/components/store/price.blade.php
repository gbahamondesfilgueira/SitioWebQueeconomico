@props(['display'])
<div>
    @if($display['discount_percentage'] > 0)
        <div class="small text-secondary text-decoration-line-through">${{ number_format($display['regular_price'], 0, ',', '.') }}</div>
        <div class="fs-4 fw-bold text-danger">${{ number_format($display['final_price'], 0, ',', '.') }}</div>
        <span class="badge text-bg-danger">-{{ $display['discount_percentage'] }}%</span>
    @else
        <div class="fs-5 fw-bold">${{ number_format($display['final_price'], 0, ',', '.') }}</div>
    @endif
</div>
