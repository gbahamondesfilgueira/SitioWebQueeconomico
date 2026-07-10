<?php

namespace App\Models\Concerns;

use Illuminate\Support\Carbon;

trait HasActiveWindow
{
    public function isCurrentlyActive(): bool
    {
        if (property_exists($this, 'is_active') || array_key_exists('is_active', $this->getAttributes())) {
            if (! $this->is_active) {
                return false;
            }
        }

        $now = Carbon::now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }

        return true;
    }
}
