<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pos\Concerns\ResolvesPosTerminal;
use App\Services\PosService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    use ResolvesPosTerminal;

    public function __invoke(Request $request, PosService $posService)
    {
        $request->validate(['q' => ['nullable', 'string', 'max:120']]);
        return response()->json($posService->searchProducts($this->terminal($request), (string) $request->query('q')));
    }
}
