<?php

namespace App\Traits;

use App\Models\ShortCode;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasShortCode
{
    /**
     * Polymorphic relation to the short_codes table.
     */
    public function shortCodeRelation(): MorphOne
    {
        return $this->morphOne(ShortCode::class, 'codeable');
    }

    /**
     * Accessor: $model->short_code → returns the code string.
     */
    public function getShortCodeAttribute(): ?string
    {
        return $this->shortCodeRelation?->code;
    }

    /**
     * Accessor: $model->clicks → returns click count from short_codes table.
     */
    public function getClicksAttribute(): int
    {
        return $this->shortCodeRelation?->clicks ?? 0;
    }

    /**
     * Boot the trait: auto-generate a ShortCode when a model is created.
     */
    protected static function bootHasShortCode(): void
    {
        static::created(function ($model) {
            ShortCode::create([
                'code'          => ShortCode::generateUnique(8),
                'codeable_id'   => $model->id,
                'codeable_type' => get_class($model),
                'user_id'       => $model->user_id,
                'clicks'        => 0,
            ]);
        });

        static::deleting(function ($model) {
            $model->shortCodeRelation()?->delete();
        });
    }
}
