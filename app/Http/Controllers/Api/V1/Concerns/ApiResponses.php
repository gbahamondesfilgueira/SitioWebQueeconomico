<?php

namespace App\Http\Controllers\Api\V1\Concerns;

trait ApiResponses
{
    protected function ok($data = null, ?string $message = null)
    {
        return response()->json(['success' => true, 'data' => $data, 'message' => $message, 'errors' => []]);
    }

    protected function fail(array $errors, string $message = 'Error', int $status = 422)
    {
        return response()->json(['success' => false, 'data' => null, 'message' => $message, 'errors' => $errors], $status);
    }
}
