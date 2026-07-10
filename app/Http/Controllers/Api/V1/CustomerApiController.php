<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ApiResponses;
use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{
    use ApiResponses;

    public function index()
    {
        return $this->ok(CustomerProfile::query()->where('is_active', true)->paginate(25));
    }

    public function store(Request $request)
    {
        return $this->fail(['customer' => ['Creación API preparada para flujo productivo posterior.']], 'Endpoint preparado', 202);
    }
}
