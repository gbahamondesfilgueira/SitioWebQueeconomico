@props(['step'])
@php($steps = ['customer' => 'Cliente', 'shipping-address' => 'Despacho', 'billing-address' => 'Facturación', 'shipping-method' => 'Envío', 'payment-method' => 'Pago', 'review' => 'Revisión'])
<div class="card border-0 shadow-sm mb-3"><div class="card-body d-flex flex-wrap gap-2">@foreach($steps as $key => $label)<span class="badge {{ $step === $key ? 'text-bg-dark' : 'text-bg-light' }}">{{ $label }}</span>@endforeach</div></div>
