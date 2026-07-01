<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Models\RestaurantMenuCategory;
use App\Models\SocialPlatform;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PagesController extends Controller
{
    /**
     * Get a list of all pages belonging to the authenticated user.
     *
     * GET /api/pages
     */
    public function index(Request $request): JsonResponse
    {
        $pages = $request->user()
            ->pages()
            ->with(['pageType', 'template'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'total' => $pages->count(),
            'data' => PageResource::collection($pages),
        ], 200);
    }

    /**
     * Get a single page by ID belonging to the authenticated user.
     *
     * GET /api/pages/{id}
     *
     * @param  int  $id
     */
    public function show(Request $request, int $page): JsonResponse
    {
        $page = $request->user()
            ->pages()
            ->with(['pageType', 'template'])
            ->find($page);

        if (! $page) {
            return response()->json([
                'status' => false,
                'message' => 'Page not found.',
            ], 404);
        }

        // 1. Social Links
        $socialLinks = $page->socialLinks()
            ->with('socialPlatform')
            ->get()
            ->map(function ($link) {
                return [
                    'id' => $link->id,
                    'page_id' => $link->page_id,
                    'platform_id' => $link->platform_id,
                    'platform_name' => $link->socialPlatform?->name,
                    'platform_icon' => $link->socialPlatform?->icon,
                    'color' => $link->socialPlatform?->color,
                    'value' => $link->value,
                    'sort_order' => $link->sort_order,
                    'created_at' => $link->created_at,
                    'updated_at' => $link->updated_at,
                ];
            });

        // 2. Banners (filtered exactly like QrController)
        $banners = $page->banners()
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now()->format('Y-m-d'));
            })
            ->orderBy('position')
            ->get();

        // 3. Blocks
        $blocks = $page->blocks()->get();

        // 4. Restaurant Menu
        $restaurantMenu = [];
        if ($page->pageType->slug == 'restaurant') {
            $restaurantCategories = RestaurantMenuCategory::where('page_id', $page->id)
                ->with(['items' => function ($query) {
                    $query->where('is_available', true); // Only load available items
                }])
                ->orderBy('position')
                ->get()
                ->filter(function ($category) {
                    // Only include categories that have at least one available item
                    return $category->items->count() > 0;
                });

            $restaurantSettings = $page->restaurantSettings;

            // Format restaurant settings properties (price formatting closure is handled client-side)
            $formattedSettings = null;
            if ($restaurantSettings) {
                $currencySymbol = $restaurantSettings->currency_symbol ?? 'ج.م';
                $currencyPosition = $restaurantSettings->currency_position ?? 'after';
                $enableOrders = $restaurantSettings->enable_orders ?? true;
                $isArabicCurrency = in_array($currencySymbol, ['ج.م', 'ر.س', 'د.إ']);

                $formattedSettings = [
                    'currencySymbol' => $currencySymbol,
                    'currencyPosition' => $currencyPosition,
                    'enableOrders' => $enableOrders,
                    'isArabicCurrency' => $isArabicCurrency,
                    'hotline' => $restaurantSettings->hotline,
                ];
            }

            $restaurantMenu = [
                'restaurantSettings' => $formattedSettings,
                'branches' => $page->branches()
                    ->where('status', true)
                    ->orderBy('main', 'desc')
                    ->orderBy('name')
                    ->get()
                    ->map(function ($branch) {
                        return [
                            'id' => $branch->id,
                            'name' => $branch->name,
                            'address' => $branch->address,
                            'latitude' => $branch->latitude,
                            'longitude' => $branch->longitude,
                            'main' => (bool) $branch->main,
                        ];
                    })->toArray(),
                'categories' => $restaurantCategories->map(function ($category) {
                    return [
                        'category_id' => $category->id,
                        'category_title' => $category->title,
                        'category_position' => $category->position,
                        'items_count' => $category->items->count(),
                        'settings' => $category->settings ?? [
                            'show_images' => true,
                            'show_prices' => true,
                            'display_style' => 'cards',
                        ],
                        'items' => $category->items->map(function ($item) {
                            return [
                                'item_id' => $item->id,
                                'name' => $item->name,
                                'description' => $item->description,
                                'price' => $item->price,
                                'image' => $item->image,
                                'image_url' => $item->image ? asset('storage/'.$item->image) : null,
                                'is_available' => $item->is_available,
                                'variations' => $item->variations->map(fn ($v) => [
                                    'id' => $v->id,
                                    'name' => $v->name,
                                    'price' => $v->price,
                                    'position' => $v->position,
                                ])->toArray(),
                                'extras' => $item->extras->map(fn ($e) => [
                                    'id' => $e->id,
                                    'name' => $e->name,
                                    'price' => $e->price,
                                    'position' => $e->position,
                                ])->toArray(),
                            ];
                        })->toArray(),
                    ];
                })->values()->toArray(),
            ];
        }

        return response()->json([
            'status' => true,
            'data' => [
                'page' => new PageResource($page),
                'socialLinks' => $socialLinks,
                'banners' => $banners,
                'blocks' => $blocks,
                'restaurantMenu' => $restaurantMenu ?: null,
            ],
        ], 200);
    }

    /**
     * Get a single public page by ID (without authentication).
     *
     * GET /api/p/{slug}
     */
    public function showPublic(Request $request, string $slug): JsonResponse
    {
        $page = Page::with(['pageType', 'template'])
            ->where('slug', $slug)
            ->first();

        if (! $page) {
            return response()->json([
                'status' => false,
                'message' => 'Page not found.',
            ], 404);
        }

        // 1. Social Links
        $socialLinks = $page->socialLinks()
            ->with('socialPlatform')
            ->get()
            ->map(function ($link) {
                return [
                    'platform_name' => $link->socialPlatform?->name,
                    'platform_icon' => $link->socialPlatform?->icon,
                    'color' => $link->socialPlatform?->color,
                    'value' => $link->value,
                    'sort_order' => $link->sort_order,
                ];
            });

        // 2. Banners (filtered exactly like QrController)
        $banners = $page->banners()
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now()->format('Y-m-d'));
            })
            ->orderBy('position')
            ->get();

        // 3. Blocks
        $blocks = $page->blocks()->get();

        // 4. Restaurant Menu
        $restaurantMenu = [];
        if ($page->pageType->slug == 'restaurant') {
            $restaurantCategories = RestaurantMenuCategory::where('page_id', $page->id)
                ->with(['items' => function ($query) {
                    $query->where('is_available', true); // Only load available items
                }])
                ->orderBy('position')
                ->get()
                ->filter(function ($category) {
                    // Only include categories that have at least one available item
                    return $category->items->count() > 0;
                });

            $restaurantSettings = $page->restaurantSettings;

            // Format restaurant settings properties (price formatting closure is handled client-side)
            $formattedSettings = null;
            if ($restaurantSettings) {
                $currencySymbol = $restaurantSettings->currency_symbol ?? 'ج.م';
                $currencyPosition = $restaurantSettings->currency_position ?? 'after';
                $enableOrders = $restaurantSettings->enable_orders ?? true;
                $isArabicCurrency = in_array($currencySymbol, ['ج.م', 'ر.س', 'د.إ']);

                $formattedSettings = [
                    'currencySymbol' => $currencySymbol,
                    'currencyPosition' => $currencyPosition,
                    'enableOrders' => $enableOrders,
                    'isArabicCurrency' => $isArabicCurrency,
                    'hotline' => $restaurantSettings->hotline,
                ];
            }

            $restaurantMenu = [
                'restaurantSettings' => $formattedSettings,
                'branches' => $page->branches()
                    ->where('status', true)
                    ->orderBy('main', 'desc')
                    ->orderBy('name')
                    ->get()
                    ->map(function ($branch) {
                        return [
                            'id' => $branch->id,
                            'name' => $branch->name,
                            'address' => $branch->address,
                            'latitude' => $branch->latitude,
                            'longitude' => $branch->longitude,
                            'main' => (bool) $branch->main,
                        ];
                    })->toArray(),
                'tables' => $page->tables()
                    ->where('type', 'table')
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('restaurant_orders')
                            ->whereColumn('restaurant_orders.table_id', 'restaurant_tables.id')
                            ->whereNotIn('restaurant_orders.status', ['completed', 'cancelled']);
                    })
                    ->orderByRaw('CAST(table_number AS UNSIGNED) ASC')
                    ->orderBy('table_number')
                    ->get()
                    ->map(function ($table) {
                        return [
                            'id' => $table->id,
                            'table_number' => $table->table_number,
                            'seating_capacity' => $table->seating_capacity,
                        ];
                    })->toArray(),
                'categories' => $restaurantCategories->map(function ($category) {
                    return [
                        'category_id' => $category->id,
                        'category_title' => $category->title,
                        'category_position' => $category->position,
                        'items_count' => $category->items->count(),
                        'settings' => $category->settings ?? [
                            'show_images' => true,
                            'show_prices' => true,
                            'display_style' => 'cards',
                        ],
                        'items' => $category->items->map(function ($item) {
                            return [
                                'item_id' => $item->id,
                                'name' => $item->name,
                                'description' => $item->description,
                                'price' => $item->price,
                                'image' => $item->image,
                                'image_url' => $item->image ? asset('storage/'.$item->image) : null,
                                'is_available' => $item->is_available,
                                'variations' => $item->variations->map(fn ($v) => [
                                    'id' => $v->id,
                                    'name' => $v->name,
                                    'price' => $v->price,
                                    'position' => $v->position,
                                ])->toArray(),
                                'extras' => $item->extras->map(fn ($e) => [
                                    'id' => $e->id,
                                    'name' => $e->name,
                                    'price' => $e->price,
                                    'position' => $e->position,
                                ])->toArray(),
                            ];
                        })->toArray(),
                    ];
                })->values()->toArray(),
            ];
        }

        return response()->json([
            'status' => true,
            'data' => [
                'page' => new PageResource($page),
                'socialLinks' => $socialLinks,
                'banners' => $banners,
                'blocks' => $blocks,
                'restaurantMenu' => $restaurantMenu ?: null,
            ],
        ], 200);
    }

    /**
     * Update an existing page.
     *
     * PUT/POST /api/pages/{page}
     */
    public function update(Request $request, int $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);

        if (! $page) {
            return response()->json([
                'status' => false,
                'message' => 'Page not found.',
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'language' => 'sometimes|required|string|in:ar,en',
            'copyright' => 'sometimes|required|boolean',
            'template_id' => 'sometimes|nullable|integer|exists:templates,id',
            'settings' => 'sometimes|nullable|string',
            'image_path' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'remove_image' => 'sometimes|nullable|in:0,1',
            'status' => 'sometimes|required|boolean',
        ]);

        $updateData = [];

        if ($request->has('title')) {
            $updateData['title'] = $validated['title'];
        }
        if ($request->has('description')) {
            $updateData['description'] = $validated['description'];
        }
        if ($request->has('language')) {
            $updateData['language'] = $validated['language'];
        }
        if ($request->has('copyright')) {
            $updateData['copyright'] = filter_var($request->input('copyright'), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        }
        if ($request->has('template_id')) {
            $updateData['template_id'] = $validated['template_id'];
        }
        if ($request->has('status')) {
            $updateData['status'] = filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        }
        if ($request->has('settings')) {
            $settingsVal = $request->input('settings');
            $updateData['settings'] = is_string($settingsVal)
                ? json_decode($settingsVal, true)
                : $settingsVal;
        }

        // Handle image deletion
        if ($request->input('remove_image') === '1') {
            if ($page->image_path) {
                Storage::disk('public')->delete($page->image_path);
            }
            $updateData['image_path'] = null;
        }

        // Handle image upload
        if ($request->hasFile('image_path')) {
            if ($page->image_path) {
                Storage::disk('public')->delete($page->image_path);
            }

            $file = $request->file('image_path');
            $filename = $page->slug.'_'.$page->id.'_'.now()->format('Ymd_His').'.'.$file->getClientOriginalExtension();
            $path = 'upload/logo/'.$filename;
            $this->resizeAndSaveImage($file->getRealPath(), $path, 118, 118);
            $updateData['image_path'] = $path;
        }

        $page->update($updateData);

        return response()->json([
            'status' => true,
            'data' => new PageResource($page->load(['pageType', 'template'])),
        ], 200);
    }

    /**
     * Update only the page status.
     *
     * PUT /api/pages/{page}/status
     */
    public function updateStatus(Request $request, int $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);

        if (! $page) {
            return response()->json([
                'status' => false,
                'message' => 'Page not found.',
            ], 404);
        }

        $validated = $request->validate([
            'status' => 'required|boolean',
        ]);

        $status = filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

        $page->update([
            'status' => $status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Page status updated successfully.',
            'data' => new PageResource($page->load(['pageType', 'template'])),
        ], 200);
    }

    /**
     * Get all social platforms.
     */
    public function socialPlatforms(): JsonResponse
    {
        $platforms = SocialPlatform::where('status', true)->get();

        return response()->json([
            'status' => true,
            'data' => $platforms,
        ], 200);
    }

    /**
     * Sync update all social links for a page.
     */
    public function updateSocialLinks(Request $request, int $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);

        if (! $page) {
            return response()->json([
                'status' => false,
                'message' => 'Page not found.',
            ], 404);
        }

        $validated = $request->validate([
            'links' => 'present|array',
            'links.*.platform_id' => 'required|integer|exists:social_platforms,id',
            'links.*.value' => 'required|string|max:255',
            'links.*.sort_order' => 'nullable|integer',
        ]);

        // Wrap operations in transaction to prevent partial state on database failure
        DB::transaction(function () use ($page, $validated) {
            // Delete existing social links
            $page->socialLinks()->delete();

            // Insert updated social links
            foreach ($validated['links'] as $index => $linkData) {
                $page->socialLinks()->create([
                    'platform_id' => $linkData['platform_id'],
                    'value' => $linkData['value'],
                    'sort_order' => $linkData['sort_order'] ?? ($index + 1),
                ]);
            }
        });

        // Get the updated social links collection
        $socialLinks = $page->socialLinks()
            ->with('socialPlatform')
            ->get()
            ->map(function ($link) {
                return [
                    'id' => $link->id,
                    'page_id' => $link->page_id,
                    'platform_id' => $link->platform_id,
                    'platform_name' => $link->socialPlatform?->name,
                    'platform_icon' => $link->socialPlatform?->icon,
                    'color' => $link->socialPlatform?->color,
                    'value' => $link->value,
                    'sort_order' => $link->sort_order,
                    'created_at' => $link->created_at,
                    'updated_at' => $link->updated_at,
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'Social links updated successfully.',
            'data' => $socialLinks,
        ], 200);
    }

    /**
     * Resize and save image using GD library.
     */
    protected function resizeAndSaveImage(string $tempPath, string $targetPath, int $targetWidth, int $targetHeight): void
    {
        $imageInfo = @getimagesize($tempPath);
        if (! $imageInfo) {
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

        if (! $source) {
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
        if (! Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        Storage::disk('public')->put($targetPath, $imageData, 'public');
    }
}
