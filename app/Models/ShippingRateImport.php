<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingRateImport extends Model
{
    protected $fillable = ['shipping_carrier_id', 'file_path', 'file_type', 'status', 'total_rows', 'successful_rows', 'failed_rows', 'error_report_path', 'imported_by', 'processed_at'];
    protected function casts(): array { return ['processed_at' => 'datetime']; }
    public function carrier(): BelongsTo { return $this->belongsTo(ShippingCarrier::class, 'shipping_carrier_id'); }
    public function importedBy(): BelongsTo { return $this->belongsTo(User::class, 'imported_by'); }
}
