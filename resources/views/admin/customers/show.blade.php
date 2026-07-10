@extends('layouts.admin')

@section('title', 'Ficha cliente')
@section('page-title', 'Ficha cliente')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="h4 mb-1">{{ $customer->display_name }}</h2>
            <div class="text-secondary">{{ $customer->email }} · {{ $customer->customer_type === 'company' ? 'Empresa' : 'Particular' }}</div>
        </div>
        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-dark">Editar cliente</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-secondary">Pedidos</div><div class="fs-4 fw-bold">0</div></div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-secondary">Monto comprado</div><div class="fs-4 fw-bold">$0</div></div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-secondary">Última compra</div><div class="fs-5 fw-bold">Pendiente</div></div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-secondary">Puntos</div><div class="fs-4 fw-bold">{{ number_format((float) $customer->reward_points, 0, ',', '.') }}</div></div></div></div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-white fw-semibold">Información general</div><div class="card-body">
                <dl><dt>RUT</dt><dd>{{ $customer->rut ?: '-' }}</dd><dt>Teléfono</dt><dd>{{ $customer->phone ?: '-' }}</dd><dt>Lista precio</dt><dd>{{ $customer->preferredPriceList?->name ?: 'Sin preferencia' }}</dd><dt>Consentimientos</dt><dd>{{ $customer->newsletter ? 'Newsletter' : 'Sin newsletter' }}</dd></dl>
                @foreach($customer->tags as $tag)<span class="badge text-bg-light">{{ $tag->name }}</span> @endforeach
            </div></div>
            <div class="card border-0 shadow-sm"><div class="card-header bg-white fw-semibold">Nueva nota</div><div class="card-body">
                <form method="POST" action="{{ route('admin.customers.notes.store', $customer) }}">@csrf
                    <textarea name="note" class="form-control mb-2" rows="3" required></textarea>
                    <label class="form-check mb-2"><input type="checkbox" name="is_private" value="1" class="form-check-input" checked> Nota privada</label>
                    <button class="btn btn-sm btn-dark">Guardar nota</button>
                </form>
            </div></div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white d-flex justify-content-between"><span class="fw-semibold">Direcciones</span><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.customers.addresses.create', $customer) }}">Agregar</a></div>
                <div class="table-responsive"><table class="table mb-0"><tbody>@forelse($customer->addresses as $address)<tr><td>{{ $address->address_type }} · {{ $address->address_label }}</td><td>{{ $address->street }} {{ $address->number }}, {{ $address->commune }}</td><td>{{ $address->is_default ? 'Default' : '' }}</td><td><a href="{{ route('admin.customers.addresses.edit', [$customer, $address]) }}" class="btn btn-sm btn-outline-secondary">Editar</a></td></tr>@empty<tr><td class="text-secondary">Sin direcciones.</td></tr>@endforelse</tbody></table></div>
            </div>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white d-flex justify-content-between"><span class="fw-semibold">Empresas</span><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.customers.companies.create', $customer) }}">Agregar</a></div>
                <div class="table-responsive"><table class="table mb-0"><tbody>@forelse($customer->companies as $company)<tr><td>{{ $company->company_name }}</td><td>{{ $company->rut }}</td><td>{{ $company->business_activity }}</td><td><a href="{{ route('admin.customers.companies.edit', [$customer, $company]) }}" class="btn btn-sm btn-outline-secondary">Editar</a></td></tr>@empty<tr><td class="text-secondary">Sin empresas.</td></tr>@endforelse</tbody></table></div>
            </div>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Documentos</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.customers.documents.store', $customer) }}" enctype="multipart/form-data" class="row g-2 mb-3">@csrf
                        <div class="col-md-3"><select name="document_type" class="form-select"><option value="rut">RUT</option><option value="company_certificate">Certificado empresa</option><option value="tax_document">Documento tributario</option><option value="other">Otro</option></select></div>
                        <div class="col-md-4"><input type="file" name="document" class="form-control" required></div>
                        <div class="col-md-3"><input name="description" class="form-control" placeholder="Descripción"></div>
                        <div class="col-md-2"><button class="btn btn-dark w-100">Subir</button></div>
                    </form>
                    @foreach($customer->documents as $document)<div class="d-flex justify-content-between border-top py-2"><span>{{ $document->document_type }} · {{ $document->description }}</span><form method="POST" action="{{ route('admin.customers.documents.destroy', [$customer, $document]) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Eliminar</button></form></div>@endforeach
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Puntos y notas</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.customers.rewards.store', $customer) }}" class="row g-2 mb-3">@csrf
                        <div class="col-md-3"><select name="type" class="form-select"><option value="earn">Sumar</option><option value="redeem">Canjear</option><option value="adjustment">Ajuste</option></select></div>
                        <div class="col-md-2"><input type="number" step="0.01" name="points" class="form-control" placeholder="Puntos" required></div>
                        <div class="col-md-5"><input name="description" class="form-control" placeholder="Descripción" required></div>
                        <div class="col-md-2"><button class="btn btn-dark w-100">Registrar</button></div>
                    </form>
                    <div class="row g-3">
                        <div class="col-md-6"><h3 class="h6">Historial puntos</h3>@forelse($customer->rewardTransactions as $tx)<div class="small border-top py-2">{{ $tx->created_at?->format('d/m/Y H:i') }} · {{ $tx->type }} · {{ $tx->points }} · {{ $tx->description }}</div>@empty<div class="text-secondary small">Sin movimientos.</div>@endforelse</div>
                        <div class="col-md-6"><h3 class="h6">Notas</h3>@forelse($customer->notes as $note)<div class="small border-top py-2">{{ $note->user?->name }} · {{ $note->note }}</div>@empty<div class="text-secondary small">Sin notas.</div>@endforelse</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
