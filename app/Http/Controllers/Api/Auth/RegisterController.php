<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /**
     * Handle an API registration request.
     *
     * POST /api/auth/register
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        // Get the free plan for new users
        $freePlan = SubscriptionPlan::where('slug', 'free')->first();

        // Create the user
        $user = User::create([
            'name'                         => $request->name,
            'phone'                        => $request->phone,
            'email'                        => $request->email,
            'password'                     => Hash::make($request->password),
            'status'                       => 1,
            'current_subscription_plan_id' => $freePlan?->id,
            'subscription_expires_at'      => null, // Free plan doesn't expire
        ]);

        // Create subscription record
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


        // Send welcome email
        $this->sendWelcomeEmail($user, $request->password);

        // Eager load subscription relationship for response consistency
        $user->load(['activeSubscription.subscriptionPlan']);

        // Create a new Sanctum token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Registration successful.',
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
                        'name' => $user->activeSubscription->subscriptionPlan?->name,
                    ],
                ] : null,
            ],
        ], 201);
    }

    /**
     * Send a welcome email with login credentials to the newly registered user.
     */
    private function sendWelcomeEmail($user, string $password): void
    {
        $emailData = [
            'subject'   => 'Welcome to ' . config('app.name') . '!',
            'name'      => $user->name,
            'email'     => $user->email,
            'password'  => $password,
            'login_url' => rtrim(config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:3000')), '/') . '/auth/login',
        ];

        try {
            Mail::send('email.UserInformation', $emailData, function ($message) use ($user, $emailData) {
                $message->to($user->email)
                    ->subject($emailData['subject']);
            });
            Log::info('Welcome email sent to: ' . $user->email);
        } catch (Exception $e) {
            Log::error('Failed to send welcome email: ' . $e->getMessage());
        }
    }
}
