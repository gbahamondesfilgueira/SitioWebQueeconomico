@props(['filters' => [], 'showChannel' => true, 'showStatus' => true])

<form method="GET" class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Fecha desde</label>
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha hasta</label>
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="form-control">
            </div>
            @if ($showChannel)
                <div class="col-md-3">
                    <label class="form-label">Canal</label>
                    <select name="channel" class="form-select">
                        <option value="">Todos</option>
                        @foreach (['ecommerce' => 'Ecommerce', 'pos' => 'POS', 'manual' => 'Manual'] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['channel'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            @if ($showStatus)
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <input type="text" name="status" value="{{ $filters['status'] ?? '' }}" class="form-control" placeholder="Estado">
                </div>
            @endif
            <div class="col-md-3">
                <button class="btn btn-dark w-100" type="submit">Filtrar</button>
            </div>
        </div>
    </div>
</form>
