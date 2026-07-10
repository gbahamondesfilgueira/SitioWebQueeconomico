<?php

namespace App\Models\Concerns;

use Illuminate\Support\Carbon;

trait HasProductPricingAndWeight
{
    public function getVolumetricWeight(): float
    {
        if (! $this->height || ! $this->width || ! $this->length) {
            return 0;
        }

        return round(((float) $this->height * (float) $this->width * (float) $this->length) / (float) config('products.volumetric_factor', 4000), 3);
    }

    public function getBillableWeight(): float
    {
        return max((float) ($this->weight ?? 0), $this->getVolumetricWeight());
    }

    public function hasActiveSale(): bool
    {
        if (! $this->sale_price || ! $this->regular_price || (float) $this->sale_price > (float) $this->regular_price) {
            return false;
        }

        $now = Carbon::now();

        if ($this->sale_starts_at && $now->lt($this->sale_starts_at)) {
            return false;
        }

        if ($this->sale_ends_at && $now->gt($this->sale_ends_at)) {
            return false;
        }

        return true;
    }

    public function getFinalPrice(): ?float
    {
        if ($this->hasActiveSale()) {
            return (float) $this->sale_price;
        }

        return $this->regular_price !== null ? (float) $this->regular_price : null;
    }
}
