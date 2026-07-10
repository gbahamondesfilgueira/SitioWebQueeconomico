@extends('layouts.admin')
@section('title', 'API Docs')
@section('page-title', 'API Docs')
@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h1 class="h4">API REST protegida</h1>
            <p>Autenticación por token de API Client usando encabezado <code>Authorization: Bearer TOKEN</code>.</p>
            <h2 class="h5 mt-4">Formato estándar</h2>
            <pre class="bg-light p-3">{"success": true, "data": {}, "message": null, "errors": []}</pre>
            <h2 class="h5 mt-4">Endpoints disponibles</h2>
            <table class="table">
                <thead><tr><th>Método</th><th>Endpoint</th><th>Permiso</th></tr></thead>
                <tbody>
                    <tr><td>GET</td><td>/api/v1/products</td><td>read_products</td></tr>
                    <tr><td>GET</td><td>/api/v1/products/{id}</td><td>read_products</td></tr>
                    <tr><td>GET</td><td>/api/v1/stock</td><td>read_stock</td></tr>
                    <tr><td>GET</td><td>/api/v1/orders</td><td>read_orders</td></tr>
                    <tr><td>POST</td><td>/api/v1/orders</td><td>write_orders</td></tr>
                    <tr><td>GET</td><td>/api/v1/customers</td><td>read_customers</td></tr>
                    <tr><td>POST</td><td>/api/v1/customers</td><td>write_customers</td></tr>
                    <tr><td>POST</td><td>/webhooks/{provider}/{event?}</td><td>Firma webhook si aplica</td></tr>
                </tbody>
            </table>
            <h2 class="h5 mt-4">Errores comunes</h2>
            <ul>
                <li><code>401</code>: token ausente o inválido.</li>
                <li><code>403</code>: permiso insuficiente.</li>
                <li><code>422</code>: validación fallida.</li>
                <li><code>429</code>: límite de solicitudes excedido.</li>
            </ul>
        </div>
    </div>
@endsection
