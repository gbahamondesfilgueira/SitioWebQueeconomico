@extends('layouts.admin')
@section('title', $title)
@section('page-title', $title)
@section('content')
    <form method="POST" action="{{ $item->exists ? route('admin.shipping.'.$route.'.update', $item) : route('admin.shipping.'.$route.'.store') }}" class="card border-0 shadow-sm">
        @csrf @if($item->exists) @method('PUT') @endif
        <div class="card-body row g-3">
            @foreach($fields as $field => $type)
                <div class="col-md-{{ $type === 'textarea' ? 12 : 4 }}">
                    @if($type === 'checkbox')
                        <label class="form-check mt-4"><input type="checkbox" name="{{ $field }}" value="1" class="form-check-input" @checked(old($field, $item->{$field} ?? true))> {{ str_replace('_', ' ', ucfirst($field)) }}</label>
                    @elseif(str_starts_with($type, 'select:'))
                        @php($collection = ${str_replace('select:', '', $type)})
                        <label class="form-label">{{ str_replace('_', ' ', ucfirst($field)) }}</label><select name="{{ $field }}" class="form-select"><option value="">Seleccionar</option>@foreach($collection as $optionKey => $option)@if(is_object($option))<option value="{{ $option->id }}" @selected(old($field, $item->{$field}) == $option->id)>{{ $option->name }}</option>@else<option value="{{ $optionKey }}" @selected(old($field, $item->{$field}) == $optionKey)>{{ $option }}</option>@endif @endforeach</select>
                    @elseif($type === 'textarea')
                        <label class="form-label">{{ str_replace('_', ' ', ucfirst($field)) }}</label><textarea name="{{ $field }}" class="form-control">{{ old($field, $item->{$field}) }}</textarea>
                    @else
                        <label class="form-label">{{ str_replace('_', ' ', ucfirst($field)) }}</label><input type="{{ $type }}" step="0.001" name="{{ $field }}" class="form-control" value="{{ old($field, $item->{$field}) }}">
                    @endif
                </div>
            @endforeach
            @if($errors->any())<div class="col-12"><div class="alert alert-danger">{{ $errors->first() }}</div></div>@endif
        </div>
        <div class="card-footer bg-white text-end"><a href="{{ route('admin.shipping.'.$route.'.index') }}" class="btn btn-outline-secondary">Volver</a> <button class="btn btn-dark">Guardar</button></div>
    </form>
@endsection
