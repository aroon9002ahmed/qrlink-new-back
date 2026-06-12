<?php

namespace App\Traits;

use App\Models\ShortCode;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Notifications\ShortCodeCreated;

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
            $code = ShortCode::generateUnique(8);

            ShortCode::create([
                'code'          => $code,
                'codeable_id'   => $model->id,
                'codeable_type' => get_class($model),
                'user_id'       => $model->user_id,
                'clicks'        => 0,
            ]);

            //send notifications to user about creating short code
            if ($model->user_id && $user = ($model->user ?? User::find($model->user_id))) {
                if (env('APP_ENV') === 'production') {
                    $user->notify(new ShortCodeCreated($code, get_class($model), $model->id));
                }
            }
        });

        static::deleting(function ($model) {
            $model->shortCodeRelation()?->delete();
        });
    }
}
