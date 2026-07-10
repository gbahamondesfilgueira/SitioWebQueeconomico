<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingCarrier extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'code', 'website', 'contact_email', 'contact_phone', 'tracking_url_template', 'logo_path', 'is_active', 'supports_api'];
    protected function casts(): array { return ['is_active' => 'boolean', 'supports_api' => 'boolean']; }
    public function services(): HasMany { return $this->hasMany(ShippingService::class); }
    public function rates(): HasMany { return $this->hasMany(ShippingRate::class); }
}
