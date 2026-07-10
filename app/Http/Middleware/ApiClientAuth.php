<?php

namespace App\Http\Middleware;

use App\Models\ApiClient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ApiClientAuth
{
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        $token = (string) $request->bearerToken();
        abort_if($token === '', 401, 'Token API requerido.');

        $client = ApiClient::query()->where('is_active', true)->get()->first(fn (ApiClient $client) => Hash::check($token, $client->token_hash));
        abort_unless($client, 401, 'Token API inválido.');

        $key = 'api-client:'.$client->id.':'.$request->ip();
        abort_if(RateLimiter::tooManyAttempts($key, $client->rate_limit_per_minute), 429, 'Rate limit excedido.');
        RateLimiter::hit($key, 60);

        $allowedIps = $client->allowed_ips ?? [];
        abort_if($allowedIps && ! in_array($request->ip(), $allowedIps, true), 403, 'IP no autorizada.');
        abort_if($permission && ! in_array($permission, $client->permissions ?? [], true), 403, 'Permiso API insuficiente.');

        $client->update(['last_used_at' => now()]);
        $request->attributes->set('api_client', $client);

        return $next($request);
    }
}
