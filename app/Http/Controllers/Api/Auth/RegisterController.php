<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

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

        event(new Registered($user));

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
}
