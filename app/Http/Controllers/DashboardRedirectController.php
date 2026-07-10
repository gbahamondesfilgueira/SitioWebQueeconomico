<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user?->hasRole(['super-admin', 'administrador'])) {
            return redirect()->route('admin.dashboard');
        }

        if ($user?->hasRole('cliente')) {
            return redirect()->route('account.dashboard');
        }

        return redirect()->route('profile.edit');
    }
}
