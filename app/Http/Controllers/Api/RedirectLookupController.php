<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RedirectLookupController extends Controller
{
    /**
     * Handle incoming path lookup for redirects.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $path = $request->query('path');

        if (! $path) {
            return response()->json([
                'has_redirect' => false,
            ]);
        }

        // Normalize path: ensure leading slash, trim trailing slash unless root
        $normalizedPath = '/' . ltrim(rtrim($path, '/'), '/');

        // Look for exact match or full URL match
        $redirect = Redirect::where('is_active', true)
            ->where(function ($query) use ($path, $normalizedPath) {
                $query->where('old_path', $normalizedPath)
                    ->orWhere('old_path', $path);
            })
            ->first();

        if (! $redirect) {
            return response()->json([
                'has_redirect' => false,
            ]);
        }

        // Increment hit counter asynchronously/silently
        $redirect->increment('hits');

        return response()->json([
            'has_redirect' => true,
            'target_url'   => $redirect->new_path,
            'status_code'  => $redirect->status_code,
        ]);
    }
}
