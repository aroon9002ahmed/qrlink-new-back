<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile.
     *
     * GET /api/user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user()->load(['activeSubscription.subscriptionPlan']);

        return response()->json([
            'status' => true,
            'data'   => new UserResource($user),
        ], 200);
    }

    public function updateProfile(Request $request): JsonResponse
    {

        $user = $request->user();


        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        $user->load(['activeSubscription.subscriptionPlan']);

        return response()->json([
            'status'  => true,
            'message' => 'Profile updated successfully.',
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
    }

    public function updatePassword(Request $request): JsonResponse
    {

        $user = $request->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'The provided password does not match your current password.',
                'errors'  => [
                    'current_password' => ['The provided password does not match your current password.']
                ]
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Password updated successfully.',
        ], 200);
    }
}
