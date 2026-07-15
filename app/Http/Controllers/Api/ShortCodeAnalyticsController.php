<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShortCodeAnalyticResource;
use App\Models\ShortCode;
use App\Models\ShortCodeAnalytic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location;

class ShortCodeAnalyticsController extends Controller
{
    /**
     * Store a new scan analytic record and increment the clicks count for the short code.
     *
     * POST /api/short-code-analytics
     */
    public function store(Request $request): JsonResponse
    {
        // Validate request input
        $request->validate([
            'short_code_id' => 'required_without:code|nullable|integer|exists:short_codes,id',
            'code'          => 'required_without:short_code_id|nullable|string|exists:short_codes,code',
        ]);

        if ($request->filled('short_code_id')) {
            $shortCodeId = $request->input('short_code_id');
        } else {
            $shortCodeId = ShortCode::where('code', $request->input('code'))->value('id');
        }
        // Resolve the real client IP, bypassing proxy/load-balancer addresses
        $ip = null;
        if ($request->hasHeader('CF-Connecting-IP')) {
            $ip = $request->header('CF-Connecting-IP');
        } elseif ($request->hasHeader('X-Forwarded-For')) {
            $forwarded = $request->header('X-Forwarded-For');
            $ips = array_map('trim', explode(',', $forwarded));
            $ip = $ips[0] ?? null;
        } elseif ($request->hasHeader('X-Real-IP')) {
            $ip = $request->header('X-Real-IP');
        } else {
            $ip = $request->ip();
        }

        $userAgent = $request->userAgent();
        $country = null;
        $city = null;

        // Perform IP Geolocation check for non-loopback IPs
        if ($ip && !in_array($ip, ['127.0.0.1', '::1'])) {
            try {
                if ($position = Location::get($ip)) {
                    $country = $position->countryName;
                    $city = $position->cityName;
                }
            } catch (\Exception $e) {
                // Log lookup failure to avoid failing the analytics recording completely
                Log::error('Location lookup failed for IP ' . $ip . ': ' . $e->getMessage());
            }
        } else {
            // Local fallback values for development/testing
            $country = 'Local';
            $city = 'Local';
        }

        // Secure DB Operations using a Database Transaction
        try {
            DB::transaction(function () use ($shortCodeId, $ip, $userAgent, $country, $city) {
                // Check if a scan with the same IP already exists for this short code to avoid duplicates
                $exists = ShortCodeAnalytic::where('short_code_id', $shortCodeId)
                    ->where('ip_address', $ip)
                    ->exists();

                if (!$exists) {
                    // 1. Create the analytic record
                    ShortCodeAnalytic::create([
                        'short_code_id' => $shortCodeId,
                        'ip_address'    => $ip,
                        'user_agent'    => $userAgent,
                        'country'       => $country,
                        'city'          => $city,
                    ]);

                    // 2. Increment the clicks column in the related short_codes table
                    ShortCode::where('id', $shortCodeId)->increment('clicks');
                }
            });

            return response()->json([
                'status'  => true,
                'message' => 'Scan tracked successfully.',
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to store scan analytic or increment clicks: ' . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'Failed to track scan analytics.',
            ], 500);
        }
    }

    /**
     * View analytics data for short codes owned by the authenticated user.
     *
     * GET /api/short-code-analytics
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Query only the analytics belonging to short codes owned by the current user
        $query = ShortCodeAnalytic::whereHas('shortCode', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        // Filter by a specific short_code_id or code string if provided and verified
        if ($request->has('short_code_id')) {
            $shortCodeId = $request->query('short_code_id');

            // Verify that the requested short code is actually owned by the current user
            $shortCodeExists = ShortCode::where('id', $shortCodeId)
                ->where('user_id', $user->id)
                ->exists();

            if (!$shortCodeExists) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Short code not found or access denied.',
                ], 403);
            }

            $query->where('short_code_id', $shortCodeId);
        } elseif ($request->has('code')) {
            $code = $request->query('code');

            // Verify that the short code belongs to the user
            $shortCode = ShortCode::where('code', $code)
                ->where('user_id', $user->id)
                ->first();

            if (!$shortCode) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Short code not found or access denied.',
                ], 403);
            }

            $query->where('short_code_id', $shortCode->id);
        }

        // Filter by date range if provided
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->query('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->query('end_date'));
        }

        // Retrieve paginated records
        $perPage = $request->query('per_page', 15);
        $analytics = $query->with('shortCode')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'total'  => $analytics->total(),
            'data'   => ShortCodeAnalyticResource::collection($analytics->items()),
            'pagination' => [
                'current_page' => $analytics->currentPage(),
                'last_page'    => $analytics->lastPage(),
                'per_page'     => $analytics->perPage(),
            ]
        ], 200);
    }
}
