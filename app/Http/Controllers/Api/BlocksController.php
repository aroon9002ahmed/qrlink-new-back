<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\BlockType;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BlocksController extends Controller
{
    /**
     * Get all blocks for a page.
     *
     * GET /api/pages/{pageId}/blocks
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

        $blocks = $page->blocks()->with('blockType')->orderBy('position')->get();

        return response()->json([
            'status' => true,
            'data'   => $blocks,
        ], 200);
    }

    /**
     * Create/Add a new block to a page.
     *
     * POST /api/pages/{pageId}/blocks
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
            'block_type_id' => 'required|integer|exists:block_types,id',
            'settings'      => 'nullable', // validated as array or JSON string
            'position'      => 'nullable|integer',
        ]);

        $blockTypeId = $request->input('block_type_id');
        $settingsInput = $request->input('settings', []);

        // Parse settings if passed as JSON string (useful for FormData uploads)
        if (is_string($settingsInput)) {
            $settings = json_decode($settingsInput, true) ?? [];
        } else {
            $settings = (array) $settingsInput;
        }

        // Process any file uploads and inject them into settings
        foreach ($request->allFiles() as $key => $file) {
            $filename = 'block_' . $key . '_' . $page->id . '_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'upload/blocks/' . $filename;

            if (str_starts_with($file->getMimeType(), 'image/')) {
                $this->resizeAndSaveImage($file->getRealPath(), $path, 800, 800);
            } else {
                Storage::disk('public')->putFileAs('upload/blocks', $file, $filename, 'public');
            }
            $settings[$key] = $path;
        }

        // Determine position
        $position = $request->input('position');
        if ($position === null) {
            $position = $page->blocks()->max('position') + 1;
        }

        $block = $page->blocks()->create([
            'block_type_id' => $blockTypeId,
            'settings'      => $settings,
            'position'      => $position,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Block created successfully.',
            'data'    => $block->load('blockType'),
        ], 201);
    }

    /**
     * Update an existing block.
     *
     * PUT/POST /api/pages/{pageId}/blocks/{blockId}
     */
    public function update(Request $request, int $pageId, int $blockId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);

        if (!$page) {
            return response()->json([
                'status'  => false,
                'message' => 'Page not found.',
            ], 404);
        }

        $block = $page->blocks()->find($blockId);

        if (!$block) {
            return response()->json([
                'status'  => false,
                'message' => 'Block not found.',
            ], 404);
        }

        $request->validate([
            'settings' => 'nullable',
            'position' => 'nullable|integer',
        ]);

        $settingsInput = $request->input('settings');
        if ($settingsInput !== null) {
            if (is_string($settingsInput)) {
                $newSettings = json_decode($settingsInput, true) ?? [];
            } else {
                $newSettings = (array) $settingsInput;
            }

            // Keep existing files if new ones aren't uploaded, or handle deletion
            $currentSettings = $block->settings ?? [];
            foreach ($currentSettings as $key => $val) {
                if (!isset($newSettings[$key]) && is_string($val) && str_starts_with($val, 'upload/blocks/')) {
                    $newSettings[$key] = $val; // preserve
                }
            }

            // Handle removal of specific image files if requested
            if ($request->input('remove_image_keys')) {
                $removeKeys = (array) $request->input('remove_image_keys');
                foreach ($removeKeys as $key) {
                    if (isset($newSettings[$key]) && is_string($newSettings[$key])) {
                        Storage::disk('public')->delete($newSettings[$key]);
                        $newSettings[$key] = null;
                    }
                }
            }

            // Process new file uploads
            foreach ($request->allFiles() as $key => $file) {
                // Delete old image if it exists
                if (isset($currentSettings[$key]) && is_string($currentSettings[$key])) {
                    Storage::disk('public')->delete($currentSettings[$key]);
                }

                $filename = 'block_' . $key . '_' . $page->id . '_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = 'upload/blocks/' . $filename;

                if (str_starts_with($file->getMimeType(), 'image/')) {
                    $this->resizeAndSaveImage($file->getRealPath(), $path, 800, 800);
                } else {
                    Storage::disk('public')->putFileAs('upload/blocks', $file, $filename, 'public');
                }
                $newSettings[$key] = $path;
            }

            $block->settings = $newSettings;
        }

        if ($request->has('position')) {
            $block->position = $request->input('position');
        }

        $block->save();

        return response()->json([
            'status'  => true,
            'message' => 'Block updated successfully.',
            'data'    => $block->load('blockType'),
        ], 200);
    }

    /**
     * Delete a block.
     *
     * DELETE /api/pages/{pageId}/blocks/{blockId}
     */
    public function destroy(Request $request, int $pageId, int $blockId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);

        if (!$page) {
            return response()->json([
                'status'  => false,
                'message' => 'Page not found.',
            ], 404);
        }

        $block = $page->blocks()->find($blockId);

        if (!$block) {
            return response()->json([
                'status'  => false,
                'message' => 'Block not found.',
            ], 404);
        }

        // Delete any uploaded files in settings
        $settings = $block->settings ?? [];
        foreach ($settings as $key => $val) {
            if (is_string($val) && str_starts_with($val, 'upload/blocks/')) {
                Storage::disk('public')->delete($val);
            }
        }

        $block->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Block deleted successfully.',
        ], 200);
    }

    /**
     * Reorder blocks.
     *
     * POST /api/pages/{pageId}/blocks/reorder
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
            'ids.*' => 'integer|exists:blocks,id',
        ]);

        $ids = $request->input('ids');

        DB::transaction(function () use ($page, $ids) {
            foreach ($ids as $index => $id) {
                $page->blocks()->where('id', $id)->update(['position' => $index + 1]);
            }
        });

        return response()->json([
            'status'  => true,
            'message' => 'Blocks reordered successfully.',
        ], 200);
    }

    /**
     * Get all active block types.
     *
     * GET /api/block-types
     */
    public function blockTypes(): JsonResponse
    {
        $blockTypes = BlockType::all();

        return response()->json([
            'status' => true,
            'data'   => $blockTypes,
        ], 200);
    }

    /**
     * Resize and save image using GD library.
     */
    protected function resizeAndSaveImage(string $tempPath, string $targetPath, int $targetWidth, int $targetHeight): void
    {
        $imageInfo = @getimagesize($tempPath);
        if (!$imageInfo) {
            return;
        }

        $mime = $imageInfo['mime'];

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $source = @imagecreatefromjpeg($tempPath);
                break;
            case 'image/png':
                $source = @imagecreatefrompng($tempPath);
                break;
            case 'image/webp':
                $source = @imagecreatefromwebp($tempPath);
                break;
            case 'image/gif':
                $source = @imagecreatefromgif($tempPath);
                break;
            default:
                $source = null;
                break;
        }

        if (!$source) {
            return;
        }

        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

        if ($mime === 'image/png' || $mime === 'image/webp') {
            imagealphablending($targetImage, false);
            imagesavealpha($targetImage, true);
            $transparent = imagecolorallocatealpha($targetImage, 255, 255, 255, 127);
            imagefilledrectangle($targetImage, 0, 0, $targetWidth, $targetHeight, $transparent);
        }

        $origWidth = imagesx($source);
        $origHeight = imagesy($source);

        imagecopyresampled(
            $targetImage,
            $source,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $origWidth,
            $origHeight
        );

        ob_start();
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                imagejpeg($targetImage, null, 90);
                break;
            case 'image/png':
                imagepng($targetImage, null, 6);
                break;
            case 'image/webp':
                imagewebp($targetImage, null, 85);
                break;
            case 'image/gif':
                imagegif($targetImage);
                break;
        }
        $imageData = ob_get_clean();
        imagedestroy($targetImage);
        imagedestroy($source);

        $directory = dirname($targetPath);
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        Storage::disk('public')->put($targetPath, $imageData, 'public');
    }
}
