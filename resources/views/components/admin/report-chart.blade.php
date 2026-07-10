@props(['title', 'chart'])

<div class="card border-0 shadow-sm h-100">
    <div class="card-body">
        <h3 class="h6 mb-3">{{ $title }}</h3>
        @php
            $max = max(collect($chart['values'] ?? [0])->map(fn ($value) => (float) $value)->max() ?: 1, 1);
        @endphp
        <div class="d-flex flex-column gap-2">
            @foreach (($chart['labels'] ?? []) as $index => $label)
                @php
                    $value = (float) (($chart['values'][$index] ?? 0));
                    $width = max(4, ($value / $max) * 100);
                @endphp
                <div>
                    <div class="d-flex justify-content-between small">
                        <span>{{ $label }}</span>
                        <span>{{ number_format($value, 0, ',', '.') }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-dark" style="width: {{ $width }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
