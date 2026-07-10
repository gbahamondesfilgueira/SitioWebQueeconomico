@props(['columns' => [], 'rows' => [], 'paginator' => null])

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    @foreach ($columns as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr>
                        @foreach ($row as $index => $value)
                            <td>
                                @if (is_string($value) && str_starts_with($value, 'http'))
                                    <a href="{{ $value }}" target="_blank">Ver</a>
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ max(count($columns), 1) }}" class="text-center text-secondary py-4">Sin datos para los filtros seleccionados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($paginator)
        <div class="card-footer bg-white">
            {{ $paginator->links() }}
        </div>
    @endif
</div>
