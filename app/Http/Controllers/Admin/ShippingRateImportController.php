<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingCarrier;
use App\Models\ShippingRate;
use App\Models\ShippingRateImport;
use App\Models\ShippingService as ShippingServiceModel;
use App\Models\ShippingZone;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ShippingRateImportController extends Controller
{
    public function index(): View { return view('admin.shipping.imports.index', ['imports' => ShippingRateImport::query()->with('carrier', 'importedBy')->latest()->paginate(15)]); }
    public function create(): View { return view('admin.shipping.imports.create', ['carriers' => ShippingCarrier::query()->orderBy('name')->get()]); }
    public function show(ShippingRateImport $import): View { return view('admin.shipping.imports.show', compact('import')); }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['shipping_carrier_id' => ['required', 'exists:shipping_carriers,id'], 'file' => ['required', 'file', 'mimes:csv,txt,xlsx', 'max:10240']]);
        $file = $request->file('file');
        $path = $file->store('shipping-imports');
        $type = strtolower($file->getClientOriginalExtension()) === 'xlsx' ? 'xlsx' : 'csv';
        $import = ShippingRateImport::query()->create(['shipping_carrier_id' => $data['shipping_carrier_id'], 'file_path' => $path, 'file_type' => $type, 'imported_by' => $request->user()->id]);
        AuditLogger::record('started', 'shipping_imports', "Importación iniciada #{$import->id}");
        if ($type === 'csv') $this->processCsv($import);
        return redirect()->route('admin.shipping.imports.show', $import)->with('success', 'Importación registrada.');
    }

    private function processCsv(ShippingRateImport $import): void
    {
        $required = ['carrier_code','service_code','country','region','commune','city','min_weight','max_weight','max_height','max_width','max_length','price','starts_at','ends_at','is_active'];
        $rows = array_map('str_getcsv', file(Storage::disk('local')->path($import->file_path)));
        $header = array_map('trim', array_shift($rows) ?: []);
        $errors = []; $success = 0;
        foreach ($rows as $i => $row) {
            $data = array_combine($header, array_pad($row, count($header), null)) ?: [];
            if (array_diff($required, array_keys($data)) || ! is_numeric($data['price'])) { $errors[] = 'Fila '.($i + 2).' inválida'; continue; }
            $zone = ShippingZone::query()->firstOrCreate(['country' => $data['country'], 'region' => $data['region'] ?: null, 'commune' => $data['commune'] ?: null, 'city' => $data['city'] ?: null], ['name' => trim(($data['region'] ?: $data['country']).' '.($data['commune'] ?: '')), 'is_active' => true]);
            $service = ShippingServiceModel::query()->whereHas('carrier', fn ($q) => $q->where('code', $data['carrier_code']))->where('code', $data['service_code'])->first();
            ShippingRate::query()->firstOrCreate(
                ['shipping_carrier_id' => $import->shipping_carrier_id, 'shipping_service_id' => $service?->id, 'shipping_zone_id' => $zone->id, 'min_weight' => (float) $data['min_weight'], 'max_weight' => $data['max_weight'] !== '' ? (float) $data['max_weight'] : null, 'price' => (float) $data['price']],
                ['max_height' => $data['max_height'] ?: null, 'max_width' => $data['max_width'] ?: null, 'max_length' => $data['max_length'] ?: null, 'starts_at' => $data['starts_at'] ?: null, 'ends_at' => $data['ends_at'] ?: null, 'is_active' => (bool) $data['is_active']]
            );
            $success++;
        }
        $report = null;
        if ($errors) { $report = 'shipping-imports/errors-'.$import->id.'.txt'; Storage::disk('local')->put($report, implode(PHP_EOL, $errors)); }
        $import->update(['status' => $errors ? 'failed' : 'processed', 'total_rows' => count($rows), 'successful_rows' => $success, 'failed_rows' => count($errors), 'error_report_path' => $report, 'processed_at' => now()]);
        AuditLogger::record($errors ? 'failed' : 'processed', 'shipping_imports', "Importación procesada #{$import->id}");
    }

    public function downloadErrors(ShippingRateImport $import)
    {
        abort_unless($import->error_report_path && Storage::disk('local')->exists($import->error_report_path), 404);

        return Storage::disk('local')->download($import->error_report_path);
    }
}
