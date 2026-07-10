<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditLogger;

class ApiDocumentationController extends Controller
{
    public function __invoke()
    {
        AuditLogger::record('viewed', 'api_docs', 'API Docs consultada.');

        return view('admin.api-docs.index');
    }
}
