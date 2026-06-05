<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\Auth\LoginRequest;
use Carbon\Carbon;

class LoginController extends Controller
{
    /**
     * Handle an API login request.
     *
     * POST /api/auth/login
     *
     * @param  \App\Http\Requests\Api\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        // Attempt authentication
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status'  => false,
                'message' => __('auth.failed'),
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load(['activeSubscription.subscriptionPlan']);

        // Revoke all previous tokens for this device (optional: single-session enforcement)
        // $user->tokens()->delete();

        // Create a new Sanctum token
        // $token = $user->createToken('api-token')->plainTextToken;
        $token = $user->createToken('api-token', ['*'], now()->addDays(7))->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login successful.',
            'token' => $token,
            'user'    => [
                'id'                  => $user->id,
                'name'                => $user->name,
                'email'               => $user->email,
                'phone'               => $user->phone,
                'created_at'          => $user->created_at?->toIso8601String(),
                'active_subscription' => $user->activeSubscription ? [
                    'ends_at' => Carbon::parse($user->activeSubscription->ends_at)->format('Y-m-d'),
                    'plan'    => [
                        'plan_id' => $user->activeSubscription->subscriptionPlan?->id,
                        'name'    => $user->activeSubscription->subscriptionPlan?->getTranslations('name'),
                    ],
                ] : null,
            ],
        ], 200);
    }
}
