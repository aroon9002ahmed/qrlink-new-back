<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SocialLoginController extends Controller
{
    public function socialLogin(Request $request): JsonResponse
    {
        $request->validate([
            'email'        => 'required|email',
            'name'         => 'required|string',
            'provider'     => 'required|string|in:google,facebook',
            'provider_uid' => 'required|string',
        ]);

        try {
            $email = $request->email;
            $nameInput = $request->name;
            $provider = $request->provider;
            $providerUid = $request->provider_uid;

            // Check if user exists by provider UID
            $user = User::where('oauth_uid', $providerUid)
                ->where('oauth_provider', $provider)
                ->first();

            if (!$user) {
                // Check if user exists by email
                $user = User::where('email', $email)->first();

                if ($user) {
                    // Update user's provider info
                    $user->update([
                        'oauth_provider' => $provider,
                        'oauth_uid'      => $providerUid,
                        'updated_at'     => Carbon::now()
                    ]);
                } else {
                    // Register new user
                    $nameParts = Str::of($nameInput)->ucsplit();
                    $first_name = str_replace(' ', '', $nameParts->count() > 1 ? $nameParts->first() : $nameInput);
                    $last_name = str_replace(' ', '', $nameParts->count() > 1 ? $nameParts->last() : $nameInput);

                    $generatedPassword = Str::random(12);
                    $freePlan = SubscriptionPlan::where('slug', 'free')->first();

                    $user = User::create([
                        'name'                         => $first_name . ' ' . $last_name,
                        'email'                        => $email,
                        'oauth_provider'               => $provider,
                        'oauth_uid'                    => $providerUid,
                        'password'                     => Hash::make($generatedPassword),
                        'status'                       => 1,
                        'current_subscription_plan_id' => $freePlan?->id,
                        'subscription_expires_at'      => null,
                    ]);

                    if ($freePlan) {
                        $user->subscriptions()->create([
                            'subscription_plan_id' => $freePlan->id,
                            'billing_cycle'        => 'monthly',
                            'status'               => 'active',
                            'starts_at'            => now(),
                            'ends_at'              => null,
                            'amount_paid'          => 0.00,
                            'payment_method'       => 'free',
                        ]);
                    }

                    // Mark email as verified for social registration to prevent verification mail route errors
                    $user->email_verified_at = now();
                    $user->last_login_at = now();
                    $user->save();

                    event(new Registered($user));

                    // Send welcome email with credentials
                    $this->sendWelcomeEmail($user, $generatedPassword);
                }
            } else {
                // Update last visit quietly to preserve updated_at
                $user->updateQuietly(['last_login_at' => now()]);
            }

            $user->load(['activeSubscription.subscriptionPlan']);
            // $token = $user->createToken('api-token')->plainTextToken;
            $token = $user->createToken('api-token', ['*'], now()->addDays(7))->plainTextToken;

            return response()->json([
                'status'  => true,
                'message' => 'Login successful.',
                'token'   => $token,
                'user'    => [
                    'id'                  => $user->id,
                    'name'                => $user->name,
                    'email'               => $user->email,
                    'phone'               => $user->phone,
                    'created_at'          => $user->created_at?->toIso8601String(),
                    'active_subscription' => $user->activeSubscription ? [
                        'ends_at' => $user->activeSubscription->ends_at
                            ? Carbon::parse($user->activeSubscription->ends_at)->format('Y-m-d')
                            : null,
                        'plan'    => [
                            'plan_id' => $user->activeSubscription->subscriptionPlan?->id,
                            'name'    => $user->activeSubscription->subscriptionPlan?->getTranslations('name'),
                        ],
                    ] : null,
                ],
            ], 200);

        } catch (Exception $e) {
            Log::error('Social login API error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Unable to authenticate with social login. Please try again.'
            ], 500);
        }
    }

    private function sendWelcomeEmail(User $user, string $password)
    {
        $emailData = [
            'subject'   => 'Your Account Details - ' . config('app.name'),
            'name'      => $user->name,
            'email'     => $user->email,
            'password'  => $password,
            'login_url' => url('/login'),
        ];

        try {
            Mail::send('email.UserInformation', $emailData, function ($message) use ($user, $emailData) {
                $message->to($user->email)
                    ->subject($emailData['subject']);
            });
            Log::info('Welcome email sent from social login API to: ' . $user->email);
        } catch (Exception $e) {
            Log::error('Failed to send welcome email from social login API: ' . $e->getMessage());
        }
    }
}
