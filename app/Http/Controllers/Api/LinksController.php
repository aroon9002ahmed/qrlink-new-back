<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Link;
use App\Models\ShortCode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\LinkResource;
use App\Traits\ChecksBlacklist;

class LinksController extends Controller
{
    use ChecksBlacklist;
    /**
     * Get a list of all links belonging to the authenticated user.
     *
     * GET /api/links
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $total = $user->links()->count();

        $query = $user->links()->with(['user', 'shortCodeRelation'])->orderBy('created_at', 'desc');

        if ($request->has('limit')) {
            $query->limit((int) $request->query('limit'));
        }

        $links = $query->get();

        return response()->json([
            'status' => true,
            'total'  => $total,
            'data'   => LinkResource::collection($links),
        ], 200);
    }

    /**
     * Get a specific link belonging to the authenticated user.
     *
     * GET /api/links/{id}
     */
    public function show(Request $request, $id): JsonResponse
    {
        $link = $request->user()->links()->with(['user', 'shortCodeRelation'])->find($id);

        if (!$link) {
            return response()->json([
                'status'  => false,
                'message' => 'Link not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => new LinkResource($link),
        ], 200);
    }

    /**
     * Create a new shortened link.
     *
     * POST /api/links
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title'        => 'nullable|string|max:255',
            'original_url' => 'required_without:url|url|max:2048',
            'url'          => 'required_without:original_url|url|max:2048',
            'is_active'    => 'nullable|boolean',
            'expiresAt'    => 'nullable|date',
        ]);

        $originalUrl = $request->input('original_url') ?: $request->input('url');

        $this->checkDomainBlacklist($originalUrl);

        $urlHash = hash('sha256', $originalUrl);

        // Check if user already has a shortened link for this URL
        $existingLink = $request->user()->links()->with('shortCodeRelation')->where('url_hash', $urlHash)->first();
        if ($existingLink) {
            return response()->json([
                'status' => true,
                'data'   => new LinkResource($existingLink),
            ], 200);
        }

        // ShortCode is generated automatically by HasShortCode boot
        $link = Link::create([
            'user_id'      => $request->user()->id,
            'title'        => $request->input('title') ?: $request->input('name'),
            'original_url' => $originalUrl,
            'url_hash'     => $urlHash,
            'expires_at'   => $request->input('expiresAt', null),
            'is_active'    => $request->input('is_active', true),
        ]);

        $link->load('shortCodeRelation');

        return response()->json([
            'status' => true,
            'data'   => new LinkResource($link),
        ], 201);
    }

    /**
     * Update a link.
     *
     * PUT /api/links/{id}
     */
    public function update(Request $request, $id): JsonResponse
    {
        $link = $request->user()->links()->with('shortCodeRelation')->find($id);

        if (!$link) {
            return response()->json([
                'status'  => false,
                'message' => 'Link not found',
            ], 404);
        }

        $request->validate([
            'title'        => 'nullable|string|max:255',
            'original_url' => 'required_without:url|url|max:2048',
            'url'          => 'required_without:original_url|url|max:2048',
            'is_active'    => 'nullable|boolean',
            'expiresAt'    => 'nullable|date',
        ]);

        $originalUrl = $request->input('original_url') ?: $request->input('url');

        $this->checkDomainBlacklist($originalUrl);

        $urlHash = hash('sha256', $originalUrl);

        // Check for duplicate URL (excluding current link)
        $duplicateLink = $request->user()->links()
            ->where('url_hash', $urlHash)
            ->where('id', '!=', $id)
            ->first();

        if ($duplicateLink) {
            return response()->json([
                'status'  => false,
                'message' => 'You already have another short link for this URL',
            ], 422);
        }

        $link->update([
            'title'        => $request->input('title') ?: $request->input('name') ?: $link->title,
            'original_url' => $originalUrl,
            'url_hash'     => $urlHash,
            'expires_at'   => $request->input('expiresAt', $link->expires_at),
            'is_active'    => $request->input('is_active', $link->is_active),
        ]);

        return response()->json([
            'status' => true,
            'data'   => new LinkResource($link),
        ], 200);
    }

    /**
     * Delete a link.
     *
     * DELETE /api/links/{id}
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $link = $request->user()->links()->find($id);

        if (!$link) {
            return response()->json([
                'status'  => false,
                'message' => 'Link not found',
            ], 404);
        }

        $link->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Link deleted successfully',
        ], 200);
    }

    /**
     * Resolve short code → increment clicks → return link details.
     *
     * GET /api/r/{code}
     */
    public function click(string $code): JsonResponse
    {
        $shortCode = ShortCode::where('code', $code)
            ->with('codeable')
            ->first();

        if (!$shortCode || !$shortCode->codeable instanceof Link) {
            return response()->json([
                'status'  => false,
                'message' => 'Link not found',
            ], 404);
        }

        $link = $shortCode->codeable;

        if (!$link->is_active) {
            return response()->json([
                'status'  => false,
                'message' => 'Link is inactive',
            ], 400);
        }

        if ($link->expires_at && Carbon::parse($link->expires_at)->isPast()) {
            return response()->json([
                'status'  => false,
                'message' => 'Link has expired',
            ], 400);
        }

        // Increment clicks on the short_codes table
        $shortCode->increment('clicks');

        $link->setRelation('shortCodeRelation', $shortCode);

        return response()->json([
            'status' => true,
            'data'   => new LinkResource($link),
        ], 200);
    }}
