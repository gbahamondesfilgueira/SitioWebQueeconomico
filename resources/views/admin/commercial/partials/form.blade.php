@foreach($config['fields'] as $field)
@php($name=$field['name']) @php($type=$field['type'])
@if($type==='checkbox')
<div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="{{ $name }}" value="1" @checked(old($name,$item->exists ? $item->{$name} : false))><label class="form-check-label">{{ $field['label'] }}</label></div></div>
@elseif($type==='textarea')
<div class="col-12"><label class="form-label">{{ $field['label'] }}</label><textarea class="form-control" name="{{ $name }}" rows="3">{{ old($name,$item->{$name}) }}</textarea></div>
@elseif($type==='select')
<div class="col-md-6"><label class="form-label">{{ $field['label'] }}</label><select class="form-select" name="{{ $name }}">@foreach($config['options'][$name] ?? [] as $option)<option value="{{ $option }}" @selected(old($name,$item->{$name})===$option)>{{ $option }}</option>@endforeach</select></div>
@elseif(in_array($type,['product','variant','category','brand']))
<div class="col-md-6"><label class="form-label">{{ $field['label'] }}</label><select class="form-select" name="{{ $name }}"><option value="">Sin asignar</option>@foreach(${$type.'s'} ?? [] as $option)<option value="{{ $option->id }}" @selected(old($name,$item->{$name})==$option->id)>{{ $type==='variant' ? ($option->product->name.' / '.$option->sku) : $option->name }}</option>@endforeach</select></div>
@else
<div class="col-md-6"><label class="form-label">{{ $field['label'] }}</label><input class="form-control" name="{{ $name }}" type="{{ $type }}" step="0.01" value="{{ old($name, $item->{$name} instanceof \Illuminate\Support\Carbon ? $item->{$name}->format('Y-m-d\TH:i') : $item->{$name}) }}"></div>
@endif
@error($name)<div class="text-danger small">{{ $message }}</div>@enderror
@endforeach
<div class="col-12 d-flex justify-content-end gap-2"><a class="btn btn-outline-secondary" href="{{ route($config['routePrefix'].'.index') }}">Cancelar</a><button class="btn btn-primary">Guardar</button></div>
