<?php

namespace App\Models;

use App\Notifications\ResetPasswordRequestNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'oauth_provider',
        'oauth_uid',
        'last_login_at',
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
            'last_login_at' => 'datetime',
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

    /**
     * Send the password reset notification using our custom premium email template.
     */
    public function sendPasswordResetNotification($token): void
    {
        $frontendUrl = rtrim(config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:3000')), '/');
        $resetUrl = $frontendUrl . '/auth/reset-password?token=' . $token . '&email=' . urlencode($this->getEmailForPasswordReset());

        $this->notify(new ResetPasswordRequestNotification($resetUrl));
    }
}
