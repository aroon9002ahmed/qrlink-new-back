<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Qrcode;
use App\Models\ShortCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\QrCodeResource;
use App\Traits\ChecksBlacklist;

class QrcodesController extends Controller
{
    use ChecksBlacklist;
    /**
     * GET /api/qrcodes
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $total = $user->qrcodes()->count();

        $query = $user->qrcodes()->with(['user', 'shortCodeRelation'])->orderBy('created_at', 'desc');

        if ($request->has('limit')) {
            $query->limit((int) $request->query('limit'));
        }

        $qrcodes = $query->get();

        return response()->json([
            'status' => true,
            'total'  => $total,
            'data'   => QrCodeResource::collection($qrcodes),
        ], 200);
    }

    /**
     * GET /api/qrcodes/{id}
     */
    public function show(Request $request, $id): JsonResponse
    {
        $qrcode = $request->user()->qrcodes()->with(['user', 'shortCodeRelation'])->find($id);

        if (!$qrcode) {
            return response()->json([
                'status'  => false,
                'message' => 'QR Code not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => new QrCodeResource($qrcode),
        ], 200);
    }

    /**
     * POST /api/qrcodes
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title'        => 'nullable|string|max:255',
            'url'          => 'required_without:original_url|nullable|url|max:2048',
            'original_url' => 'required_without:url|nullable|url|max:2048',
            'is_active'    => 'nullable|boolean',
        ]);

        $url = $request->input('original_url') ?? $request->input('url') ?? $request->input('originalUrl');

        $this->checkDomainBlacklist($url);

        // ShortCode is generated automatically by HasShortCode boot
        $qrcode = Qrcode::create([
            'user_id'      => $request->user()->id,
            'title'        => $request->input('title'),
            'original_url' => $url,
            'is_active'    => $request->input('is_active', true),
        ]);

        $qrcode->load('shortCodeRelation');

        return response()->json([
            'status' => true,
            'data'   => new QrCodeResource($qrcode),
        ], 201);
    }

    /**
     * PUT /api/qrcodes/{id}
     */
    public function update(Request $request, $id): JsonResponse
    {
        $qrcode = $request->user()->qrcodes()->with('shortCodeRelation')->find($id);

        if (!$qrcode) {
            return response()->json([
                'status'  => false,
                'message' => 'QR Code not found',
            ], 404);
        }

        $request->validate([
            'title'        => 'nullable|string|max:255',
            'url'          => 'sometimes|required_without:original_url|url|max:2048',
            'original_url' => 'sometimes|required_without:url|url|max:2048',
            'is_active'    => 'nullable|boolean',
            'expiresAt'    => 'nullable|date',
        ]);

        $url = $qrcode->original_url;
        if ($request->has('original_url') || $request->has('url') || $request->has('originalUrl')) {
            $url = $request->input('original_url') ?? $request->input('url') ?? $request->input('originalUrl');
        }

        $this->checkDomainBlacklist($url);

        $qrcode->update([
            'title'        => $request->input('title', $qrcode->title),
            'original_url' => $url,
            'is_active'    => $request->input('is_active', $qrcode->is_active),
            'expires_at'   => $request->input('expiresAt', $qrcode->expires_at),
        ]);

        return response()->json([
            'status' => true,
            'data'   => new QrCodeResource($qrcode),
        ], 200);
    }

    /**
     * DELETE /api/qrcodes/{id}
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $qrcode = $request->user()->qrcodes()->find($id);

        if (!$qrcode) {
            return response()->json([
                'status'  => false,
                'message' => 'QR Code not found',
            ], 404);
        }

        $qrcode->delete();

        return response()->json([
            'status'  => true,
            'message' => 'QR Code deleted successfully',
        ], 200);
    }

    /**
     * Resolve QR short code → increment scans → return qrcode details.
     *
     * GET /api/r/{code}  (handled via unified route)
     */
    public function click(string $code): JsonResponse
    {
        $shortCode = ShortCode::where('code', $code)
            ->with('codeable')
            ->first();

        if (!$shortCode || !$shortCode->codeable instanceof Qrcode) {
            return response()->json([
                'status'  => false,
                'message' => 'QR Code not found',
            ], 404);
        }

        $qrcode = $shortCode->codeable;

        if (!$qrcode->is_active) {
            return response()->json([
                'status'  => false,
                'message' => 'QR Code is inactive',
            ], 400);
        }

        if ($qrcode->expires_at && Carbon::parse($qrcode->expires_at)->isPast()) {
            return response()->json([
                'status'  => false,
                'message' => 'QR Code has expired',
            ], 400);
        }

        $shortCode->increment('clicks');
        $qrcode->setRelation('shortCodeRelation', $shortCode);

        return response()->json([
            'status' => true,
            'data'   => new QrCodeResource($qrcode),
        ], 200);
    }
}
