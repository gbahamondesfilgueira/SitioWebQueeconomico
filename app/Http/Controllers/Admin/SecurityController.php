<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogger;

class SecurityController extends Controller
{
    public function index()
    {
        return view('admin.security.index');
    }

    public function users()
    {
        AuditLogger::record('viewed', 'security', 'Usuarios de seguridad consultados.');

        return view('admin.security.users', [
            'users' => User::query()->with('role')->latest()->paginate(20),
        ]);
    }
}
