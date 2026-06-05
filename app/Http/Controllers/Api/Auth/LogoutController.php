<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    /**
     * Revoke the current user's token.
     *
     * DELETE /api/auth/logout
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Revoke only the token that was used in this request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logged out successfully.',
        ], 200);
    }
}
