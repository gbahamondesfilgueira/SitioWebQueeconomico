<?php

namespace App\Http\Controllers\Pos\Concerns;

use App\Models\PosTerminal;
use Illuminate\Http\Request;

trait ResolvesPosTerminal
{
    protected function terminal(Request $request): PosTerminal
    {
        $terminalId = $request->integer('terminal_id') ?: session('pos_terminal_id');
        $terminal = $terminalId
            ? PosTerminal::query()->whereKey($terminalId)->where('is_active', true)->first()
            : PosTerminal::query()->where('is_active', true)->orderBy('id')->first();

        abort_unless($terminal, 422, 'No hay terminal POS activo configurado.');
        session(['pos_terminal_id' => $terminal->id]);

        return $terminal;
    }
}
