<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\RestaurantMenuCategory;
use App\Models\RestaurantMenuItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RestaurantMenuController extends Controller
{
    // categories list
    public function indexCategories(Request $request, $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (!$page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $categories = RestaurantMenuCategory::where('page_id', $page->id)
            ->with(['items' => function ($query) {
                $query->with('extras')->orderBy('position');
            }])
            ->orderBy('position')
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'page_id' => $cat->page_id,
                    'title' => $cat->title,
                    'settings' => $cat->settings,
                    'position' => $cat->position,
                    'created_at' => $cat->created_at,
                    'updated_at' => $cat->updated_at,
                    'items' => $cat->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'category_id' => $item->category_id,
                            'page_id' => $item->page_id,
                            'name' => $item->name,
                            'description' => $item->description,
                            'price' => $item->price,
                            'image' => $item->image,
                            'image_url' => $item->image ? asset('storage/' . $item->image) : null,
                            'position' => $item->position,
                            'is_available' => (bool) $item->is_available,
                            'created_at' => $item->created_at,
                            'updated_at' => $item->updated_at,
                            'extras' => $item->extras->map(fn($e) => [
                                'id' => $e->id,
                                'name' => $e->name,
                                'price' => $e->price,
                                'is_available' => (bool) $e->is_available,
                            ])->toArray()
                        ];
                    })
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $categories
        ]);
    }

    // store category
    public function storeCategory(Request $request, $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (!$page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'settings' => 'nullable|array',
            'settings.display_style' => 'nullable|string|in:list,grid,cards',
            'settings.show_images' => 'nullable|boolean',
            'settings.show_prices' => 'nullable|boolean',
            'settings.enable_orders' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $maxPosition = RestaurantMenuCategory::where('page_id', $page->id)->max('position') ?? 0;

        // default settings
        $settings = array_merge([
            'display_style' => 'cards',
            'show_images' => true,
            'show_prices' => true,
            'enable_orders' => true,
        ], $request->input('settings', []));

        $category = RestaurantMenuCategory::create([
            'page_id' => $page->id,
            'title' => $request->title,
            'settings' => $settings,
            'position' => $maxPosition + 1
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category created successfully.',
            'data' => $category
        ], 201);
    }

    // update category
    public function updateCategory(Request $request, $pageId, $categoryId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (!$page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $category = RestaurantMenuCategory::where('page_id', $page->id)->find($categoryId);
        if (!$category) {
            return response()->json(['status' => false, 'message' => 'Category not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'settings' => 'nullable|array',
            'settings.display_style' => 'nullable|string|in:list,grid,cards',
            'settings.show_images' => 'nullable|boolean',
            'settings.show_prices' => 'nullable|boolean',
            'settings.enable_orders' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        if ($request->has('title')) {
            $category->title = $request->title;
        }

        if ($request->has('settings')) {
            $currentSettings = $category->settings ?? [];
            $category->settings = array_merge($currentSettings, $request->input('settings', []));
        }

        $category->save();

        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully.',
            'data' => $category
        ]);
    }

    // delete category
    public function destroyCategory(Request $request, $pageId, $categoryId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (!$page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $category = RestaurantMenuCategory::where('page_id', $page->id)->find($categoryId);
        if (!$category) {
            return response()->json(['status' => false, 'message' => 'Category not found.'], 404);
        }

        // Delete associated item images first
        $items = $category->items;
        foreach ($items as $item) {
            if ($item->image) {
                Storage::disk('public')->delete(str_replace('storage/', '', $item->image));
                Storage::disk('public')->delete($item->image);
            }
        }

        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully.'
        ]);
    }

    // reorder categories
    public function reorderCategories(Request $request, $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (!$page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:restaurant_menu_categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $ids = $request->input('ids');
        foreach ($ids as $index => $id) {
            RestaurantMenuCategory::where('page_id', $page->id)
                ->where('id', $id)
                ->update(['position' => $index + 1]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Categories reordered successfully.'
        ]);
    }

    // store item
    public function storeItem(Request $request, $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (!$page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer|exists:restaurant_menu_categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0.50',
            'description' => 'nullable|string',
            'is_available' => 'nullable|string', // accepts '1' / '0' / 'true' / 'false'
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120', // Max 5MB
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $category = RestaurantMenuCategory::where('page_id', $page->id)->find($request->category_id);
        if (!$category) {
            return response()->json(['status' => false, 'message' => 'Invalid category for this page.'], 422);
        }

        $maxPosition = RestaurantMenuItem::where('category_id', $category->id)->max('position') ?? 0;

        $path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'item_' . $page->id . '_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'upload/restaurant/items/' . $filename;
            $this->resizeAndSaveImage($file->getRealPath(), $path, 500, 500);
        }

        // Parse boolean
        $isAvailableVal = $request->has('is_available') 
            ? filter_var($request->input('is_available'), FILTER_VALIDATE_BOOLEAN) 
            : true;

        $item = RestaurantMenuItem::create([
            'category_id' => $category->id,
            'page_id' => $page->id,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'is_available' => $isAvailableVal,
            'image' => $path,
            'position' => $maxPosition + 1
        ]);

        // Parse and create extras
        $extrasData = json_decode($request->input('extras', '[]'), true);
        if (is_array($extrasData)) {
            // First validate all extras
            foreach ($extrasData as $extra) {
                if (!empty($extra['name'])) {
                    $extraPrice = floatval($extra['price'] ?? 0);
                    if ($extraPrice < 0.50) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Extra item "' . $extra['name'] . '" price must be at least 0.50.'
                        ], 422);
                    }
                }
            }

            foreach ($extrasData as $extra) {
                if (!empty($extra['name'])) {
                    $item->extras()->create([
                        'name' => $extra['name'],
                        'price' => floatval($extra['price']),
                        'is_available' => true
                    ]);
                }
            }
        }

        // Wrap response data with image_url and extras
        $itemData = [
            'id' => $item->id,
            'category_id' => $item->category_id,
            'page_id' => $item->page_id,
            'name' => $item->name,
            'description' => $item->description,
            'price' => $item->price,
            'image' => $item->image,
            'image_url' => $item->image ? asset('storage/' . $item->image) : null,
            'position' => $item->position,
            'is_available' => (bool) $item->is_available,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'extras' => $item->extras()->get()->map(fn($e) => [
                'id' => $e->id,
                'name' => $e->name,
                'price' => $e->price,
                'is_available' => (bool) $e->is_available,
            ])->toArray()
        ];

        return response()->json([
            'status' => true,
            'message' => 'Item created successfully.',
            'data' => $itemData
        ], 201);
    }

    // update item
    public function updateItem(Request $request, $pageId, $itemId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (!$page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $item = RestaurantMenuItem::where('page_id', $page->id)->find($itemId);
        if (!$item) {
            return response()->json(['status' => false, 'message' => 'Item not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|required|integer|exists:restaurant_menu_categories,id',
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0.50',
            'description' => 'nullable|string',
            'is_available' => 'sometimes|required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120', // Max 5MB
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        if ($request->has('category_id')) {
            $category = RestaurantMenuCategory::where('page_id', $page->id)->find($request->category_id);
            if (!$category) {
                return response()->json(['status' => false, 'message' => 'Invalid category for this page.'], 422);
            }
            $item->category_id = $category->id;
        }

        if ($request->has('name')) {
            $item->name = $request->name;
        }
        if ($request->has('price')) {
            $item->price = $request->price;
        }
        if ($request->has('description')) {
            $item->description = $request->description;
        }
        if ($request->has('is_available')) {
            $item->is_available = filter_var($request->input('is_available'), FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($item->image) {
                Storage::disk('public')->delete(str_replace('storage/', '', $item->image));
                Storage::disk('public')->delete($item->image);
            }
            $file = $request->file('image');
            $filename = 'item_' . $page->id . '_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'upload/restaurant/items/' . $filename;
            $this->resizeAndSaveImage($file->getRealPath(), $path, 500, 500);
            $item->image = $path;
        }

        $item->save();

        // Sync extras
        if ($request->has('extras')) {
            $extrasData = json_decode($request->input('extras', '[]'), true);
            if (is_array($extrasData)) {
                // First validate all extras
                foreach ($extrasData as $extra) {
                    if (!empty($extra['name'])) {
                        $extraPrice = floatval($extra['price'] ?? 0);
                        if ($extraPrice < 0.50) {
                            return response()->json([
                                'status' => false,
                                'message' => 'Extra item "' . $extra['name'] . '" price must be at least 0.50.'
                            ], 422);
                        }
                    }
                }

                $item->extras()->delete();
                foreach ($extrasData as $extra) {
                    if (!empty($extra['name'])) {
                        $item->extras()->create([
                            'name' => $extra['name'],
                            'price' => floatval($extra['price']),
                            'is_available' => true
                        ]);
                    }
                }
            }
        }

        $itemData = [
            'id' => $item->id,
            'category_id' => $item->category_id,
            'page_id' => $item->page_id,
            'name' => $item->name,
            'description' => $item->description,
            'price' => $item->price,
            'image' => $item->image,
            'image_url' => $item->image ? asset('storage/' . $item->image) : null,
            'position' => $item->position,
            'is_available' => (bool) $item->is_available,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'extras' => $item->extras()->get()->map(fn($e) => [
                'id' => $e->id,
                'name' => $e->name,
                'price' => $e->price,
                'is_available' => (bool) $e->is_available,
            ])->toArray()
        ];

        return response()->json([
            'status' => true,
            'message' => 'Item updated successfully.',
            'data' => $itemData
        ]);
    }

    // destroy item
    public function destroyItem(Request $request, $pageId, $itemId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (!$page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $item = RestaurantMenuItem::where('page_id', $page->id)->find($itemId);
        if (!$item) {
            return response()->json(['status' => false, 'message' => 'Item not found.'], 404);
        }

        if ($item->image) {
            Storage::disk('public')->delete(str_replace('storage/', '', $item->image));
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'Item deleted successfully.'
        ]);
    }

    // reorder items
    public function reorderItems(Request $request, $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (!$page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:restaurant_menu_items,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $ids = $request->input('ids');
        foreach ($ids as $index => $id) {
            RestaurantMenuItem::where('page_id', $page->id)
                ->where('id', $id)
                ->update(['position' => $index + 1]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Items reordered successfully.'
        ]);
    }

    // move item to another category
    public function moveItem(Request $request, $pageId, $itemId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (!$page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $item = RestaurantMenuItem::where('page_id', $page->id)->find($itemId);
        if (!$item) {
            return response()->json(['status' => false, 'message' => 'Item not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer|exists:restaurant_menu_categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $category = RestaurantMenuCategory::where('page_id', $page->id)->find($request->category_id);
        if (!$category) {
            return response()->json(['status' => false, 'message' => 'Invalid category for this page.'], 422);
        }

        $maxPosition = RestaurantMenuItem::where('category_id', $category->id)->max('position') ?? 0;

        $item->category_id = $category->id;
        $item->position = $maxPosition + 1;
        $item->save();

        $itemData = [
            'id' => $item->id,
            'category_id' => $item->category_id,
            'page_id' => $item->page_id,
            'name' => $item->name,
            'description' => $item->description,
            'price' => $item->price,
            'image' => $item->image,
            'image_url' => $item->image ? asset('storage/' . $item->image) : null,
            'position' => $item->position,
            'is_available' => (bool) $item->is_available,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
        ];

        return response()->json([
            'status' => true,
            'message' => 'Item moved successfully.',
            'data' => $itemData
        ]);
    }

    // Helper: resize image to square format (500x500)
    private function resizeAndSaveImage(string $sourcePath, string $targetPath, int $targetWidth, int $targetHeight): void
    {
        list($origWidth, $origHeight, $imageType) = getimagesize($sourcePath);
        $mime = image_type_to_mime_type($imageType);

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $source = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $source = imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $source = imagecreatefromwebp($sourcePath);
                break;
            case 'image/gif':
                $source = imagecreatefromgif($sourcePath);
                break;
            default:
                $source = imagecreatefromstring(file_get_contents($sourcePath));
        }

        if (!$source) {
            return;
        }

        // Center crop to square before scaling
        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

        // transparency settings for png & webp
        if ($mime == 'image/png' || $mime == 'image/webp') {
            imagealphablending($targetImage, false);
            imagesavealpha($targetImage, true);
        }

        $minDimension = min($origWidth, $origHeight);
        $cropX = ($origWidth - $minDimension) / 2;
        $cropY = ($origHeight - $minDimension) / 2;

        imagecopyresampled(
            $targetImage,
            $source,
            0,
            0,
            $cropX,
            $cropY,
            $targetWidth,
            $targetHeight,
            $minDimension,
            $minDimension
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
