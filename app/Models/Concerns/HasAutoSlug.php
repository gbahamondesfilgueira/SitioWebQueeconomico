<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasAutoSlug
{
    protected static function bootHasAutoSlug(): void
    {
        static::saving(function ($model): void {
            $source = method_exists($model, 'slugSourceValue') ? $model->slugSourceValue() : $model->name;

            if (blank($model->slug) && filled($source)) {
                $model->slug = static::uniqueSlug($source, $model->getKey());
            }
        });
    }

    protected static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 2;

        while (static::query()
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
