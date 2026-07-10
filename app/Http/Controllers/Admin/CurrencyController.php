<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CurrencyController extends Controller
{
    public function index() { return view('admin.currencies.index', ['currencies' => Currency::query()->orderByDesc('is_default')->orderBy('code')->paginate(20)]); }
    public function create() { return view('admin.currencies.create', ['currency' => new Currency()]); }
    public function edit(Currency $currency) { return view('admin.currencies.edit', compact('currency')); }

    public function store(Request $request)
    {
        $currency = DB::transaction(function () use ($request) {
            $data = $this->validated($request);
            if ($data['is_default']) {
                Currency::query()->update(['is_default' => false]);
            }
            return Currency::query()->create($data);
        });
        AuditLogger::record('created', 'currencies', "Moneda {$currency->code} creada.");
        return redirect()->route('admin.currencies.index')->with('success', 'Moneda creada.');
    }

    public function update(Request $request, Currency $currency)
    {
        DB::transaction(function () use ($request, $currency) {
            $data = $this->validated($request, $currency);
            if ($data['is_default']) {
                Currency::query()->whereKeyNot($currency->id)->update(['is_default' => false]);
            }
            $currency->update($data);
        });
        AuditLogger::record('updated', 'currencies', "Moneda {$currency->code} editada.");
        return redirect()->route('admin.currencies.index')->with('success', 'Moneda actualizada.');
    }

    private function validated(Request $request, ?Currency $currency = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', Rule::unique('currencies', 'code')->ignore($currency)],
            'symbol' => ['required', 'string', 'max:10'],
            'decimal_places' => ['required', 'integer', 'min:0', 'max:6'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_default' => $request->boolean('is_default'), 'is_active' => $request->boolean('is_active')];
    }
}
