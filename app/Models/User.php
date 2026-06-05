<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function getUserPlan(): HasOneThrough
    {
        return $this->hasOneThrough(
            SubscriptionPlan::class,
            UserSubscription::class,
            'user_id',             // Foreign key on user_subscriptions table
            'id',                  // Foreign key on subscription_plans table
            'id',                  // Local key on users table
            'subscription_plan_id' // Local key on user_subscriptions table
        )->where('user_subscriptions.status', 'active')
            ->where(function ($query) {
                $query->whereNull('user_subscriptions.ends_at')
                    ->orWhere('user_subscriptions.ends_at', '>', now());
            })->latest('user_subscriptions.created_at');
    }


    public function activeSubscription(): HasOne
    {
        return $this->hasOne(UserSubscription::class)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            });
    }

    //check if user has active subscription or not [return true or false]
    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }


    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    public function qrcodes(): HasMany
    {
        return $this->hasMany(Qrcode::class);
    }
}
