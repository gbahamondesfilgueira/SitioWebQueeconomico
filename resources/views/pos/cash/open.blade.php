@extends('layouts.pos')
@section('title', 'Abrir caja')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="bg-white border rounded p-4">
            <h1 class="h4 mb-3">Abrir caja</h1>
            @if($openSession)
                <div class="alert alert-info">Este terminal ya tiene una caja abierta.</div>
                <a class="btn btn-primary" href="{{ route('pos.cash.current') }}">Ver caja actual</a>
            @else
                <form method="POST" action="{{ route('pos.cash.open.store') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Terminal POS</label>
                        <select name="terminal_id" class="form-select">
                            @foreach($terminals as $item)
                                <option value="{{ $item->id }}" @selected($item->id === $terminal->id)>{{ $item->name }} / {{ $item->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Monto inicial</label>
                        <input name="opening_amount" type="number" min="0" step="1" value="0" class="form-control form-control-lg" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Observaciones</label>
                        <textarea name="notes" class="form-control"></textarea>
                    </div>
                    <div class="col-12 d-grid"><button class="btn btn-success btn-lg">Abrir caja</button></div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
