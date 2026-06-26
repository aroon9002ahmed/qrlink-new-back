<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BannersController extends Controller
{
    /**
     * Get all banners for a specific page.
     *
     * GET /api/pages/{pageId}/banners
     */
    public function index(Request $request, int $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);

        if (!$page) {
            return response()->json([
                'status'  => false,
                'message' => 'Page not found.',
            ], 404);
        }

        $banners = $page->banners()->orderBy('position')->get()->map(function ($banner) {
            return [
                'id'         => $banner->id,
                'page_id'    => $banner->page_id,
                'title'      => $banner->title,
                'link'       => $banner->link,
                'image'      => $banner->image,
                'image_url'  => asset('storage/' . $banner->image),
                'status'     => (bool) $banner->status,
                'position'   => $banner->position,
                'end_date'   => $banner->end_date,
                'created_at' => $banner->created_at,
                'updated_at' => $banner->updated_at,
            ];
        });

        return response()->json([
            'status' => true,
            'data'   => $banners,
        ], 200);
    }

    /**
     * Create a new banner.
     *
     * POST /api/pages/{pageId}/banners
     */
    public function store(Request $request, int $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);

        if (!$page) {
            return response()->json([
                'status'  => false,
                'message' => 'Page not found.',
            ], 404);
        }

        $request->validate([
            'title'    => 'required|string|max:255',
            'link'     => 'nullable|string|max:2048',
            'image'    => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'status'   => 'nullable|boolean',
            'end_date' => 'nullable|date',
        ]);

        // Get the next position for the new banner
        $nextPosition = $page->banners()->max('position') + 1;

        // Store the uploaded image
        $file = $request->file('image');
        $filename = 'banner_' . $page->id . '_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('upload/banners', $filename, 'public');

        $banner = $page->banners()->create([
            'title'    => $request->input('title'),
            'link'     => $request->input('link'),
            'image'    => $path,
            'status'   => filter_var($request->input('status', true), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            'position' => $nextPosition,
            'end_date' => $request->input('end_date'),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Banner created successfully.',
            'data'    => array_merge($banner->toArray(), [
                'status'    => (bool) $banner->status,
                'image_url' => asset('storage/' . $banner->image)
            ]),
        ], 201);
    }

    /**
     * Update an existing banner.
     *
     * POST /api/pages/{pageId}/banners/{bannerId}
     * or PUT /api/pages/{pageId}/banners/{bannerId}
     */
    public function update(Request $request, int $pageId, int $bannerId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);

        if (!$page) {
            return response()->json([
                'status'  => false,
                'message' => 'Page not found.',
            ], 404);
        }

        $banner = $page->banners()->find($bannerId);

        if (!$banner) {
            return response()->json([
                'status'  => false,
                'message' => 'Banner not found.',
            ], 404);
        }

        $request->validate([
            'title'    => 'sometimes|required|string|max:255',
            'link'     => 'sometimes|nullable|string|max:2048',
            'image'    => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'status'   => 'sometimes|required|boolean',
            'end_date' => 'sometimes|nullable|date',
        ]);

        $updateData = [];

        if ($request->has('title')) {
            $updateData['title'] = $request->input('title');
        }
        if ($request->has('link')) {
            $updateData['link'] = $request->input('link');
        }
        if ($request->has('status')) {
            $updateData['status'] = filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        }
        if ($request->has('end_date')) {
            $updateData['end_date'] = $request->input('end_date');
        }

        // Handle image upload if a new image is provided
        if ($request->hasFile('image')) {
            // Delete the old banner image from public storage
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }

            $file = $request->file('image');
            $filename = 'banner_' . $page->id . '_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('upload/banners', $filename, 'public');
            $updateData['image'] = $path;
        }

        $banner->update($updateData);

        return response()->json([
            'status'  => true,
            'message' => 'Banner updated successfully.',
            'data'    => array_merge($banner->toArray(), [
                'status'    => (bool) $banner->status,
                'image_url' => asset('storage/' . $banner->image)
            ]),
        ], 200);
    }

    /**
     * Delete a banner.
     *
     * DELETE /api/pages/{pageId}/banners/{bannerId}
     */
    public function destroy(Request $request, int $pageId, int $bannerId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);

        if (!$page) {
            return response()->json([
                'status'  => false,
                'message' => 'Page not found.',
            ], 404);
        }

        $banner = $page->banners()->find($bannerId);

        if (!$banner) {
            return response()->json([
                'status'  => false,
                'message' => 'Banner not found.',
            ], 404);
        }

        // Delete the image file from public storage
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        // Reorder remaining banners to maintain sequential positions
        DB::transaction(function () use ($page) {
            $banners = $page->banners()->orderBy('position')->get();
            foreach ($banners as $index => $b) {
                $b->update(['position' => $index + 1]);
            }
        });

        return response()->json([
            'status'  => true,
            'message' => 'Banner deleted successfully.',
        ], 200);
    }

    /**
     * Reorder banners for a specific page.
     *
     * POST /api/pages/{pageId}/banners/reorder
     */
    public function reorder(Request $request, int $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);

        if (!$page) {
            return response()->json([
                'status'  => false,
                'message' => 'Page not found.',
            ], 404);
        }

        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'required|integer',
        ]);

        $ids = $request->input('ids');

        DB::transaction(function () use ($page, $ids) {
            foreach ($ids as $index => $id) {
                $page->banners()->where('id', $id)->update([
                    'position' => $index + 1
                ]);
            }
        });

        return response()->json([
            'status'  => true,
            'message' => 'Banners reordered successfully.',
        ], 200);
    }
}
