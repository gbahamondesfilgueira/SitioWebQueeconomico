<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PosPaymentMethod extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'code', 'payment_type', 'is_active', 'requires_reference'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'requires_reference' => 'boolean'];
    }

    public function payments(): HasMany { return $this->hasMany(PosCartPayment::class, 'payment_method_id'); }
}
