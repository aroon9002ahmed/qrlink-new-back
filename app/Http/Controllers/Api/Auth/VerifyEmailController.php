<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|string  $id
     * @param  string  $hash
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request, $id, $hash): JsonResponse
    {
        /** @var User $user */
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid or expired verification link.',
            ], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status'  => true,
                'message' => 'Email address already verified.',
            ]);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'status'  => true,
            'message' => 'Email address verified successfully.',
        ]);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'status'  => false,
                'message' => 'Email address already verified.',
            ], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'status'  => true,
            'message' => 'Verification link sent successfully.',
        ]);
    }
}
