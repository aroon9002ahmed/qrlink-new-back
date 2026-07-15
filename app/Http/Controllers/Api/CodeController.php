<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Link;
use App\Models\Qrcode;
use App\Models\ShortCode;
use App\Models\CodeReport;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\public\CodeResource;

class CodeController extends Controller
{
    /**
     * Resolve a short code, determine its type (Link or QR Code),
     * check validity/expiration, increment clicks, and return the proper Resource.
     *
     * GET /api/r/{code}
     */
    public function resolve(string $code): JsonResponse
    {
        // 1. Fetch short code with its polymorphic relation
        $shortCode = ShortCode::where('code', $code)->with('codeable')->first();

        if (!$shortCode || !$shortCode->codeable) {
            return response()->json([
                'status'  => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $model = $shortCode->codeable;

        // 2. Generic validation check for is_active
        if (isset($model->is_active) && !$model->is_active) {
            return response()->json([
                'status'  => false,
                'message' => 'Resource is inactive',
            ], 400);
        }

        // 3. Generic validation check for expires_at
        if (isset($model->expires_at) && $model->expires_at && Carbon::parse($model->expires_at)->isPast()) {
            return response()->json([
                'status'  => false,
                'message' => 'Resource has expired',
            ], 400);
        }

        // Push the refreshed/updated shortCode relation back into the model
        $model->setRelation('shortCodeRelation', $shortCode);

        // 5. Handle responses based on the model class type
        if ($model instanceof Link) {
            return response()->json([
                'status' => true,
                'type'   => 'link',
                'data'   => new CodeResource($model),
            ], 200);
        }

        if ($model instanceof Qrcode) {
            return response()->json([
                'status' => true,
                'type'   => 'qrcode',
                'data'   => new CodeResource($model),
            ], 200);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Unknown resource type',
        ], 400);
    }

    /**
     * Report a short code by its code string.
     *
     * POST /api/codes/report
     */
    public function report(Request $request): JsonResponse
    {
        $request->validate([
            'shortCode' => 'required|string|exists:short_codes,code',
            'reason'    => 'nullable|string|max:1000',
        ]);

        $shortCode = ShortCode::where('code', $request->input('shortCode'))->first();

        if (!$shortCode) {
            return response()->json([
                'status'  => false,
                'message' => 'Resource not found',
            ], 404);
        }

        $ip = $request->ip();

        $alreadyReported = CodeReport::where('short_code_id', $shortCode->id)
            ->where('ip_address', $ip)
            ->exists();

        if ($alreadyReported) {
            return response()->json([
                'status'  => false,
                'message' => 'You have already reported this resource.',
            ], 409);
        }

        CodeReport::create([
            'short_code_id' => $shortCode->id,
            'ip_address'    => $ip,
            'reason'        => $request->input('reason'),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Resource reported successfully.',
        ], 201);
    }
}
