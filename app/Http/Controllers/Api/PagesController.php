<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\RestaurantMenuCategory;
use App\Models\Page;

class PagesController extends Controller
{
    /**
     * Get a list of all pages belonging to the authenticated user.
     *
     * GET /api/pages
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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
            'total'  => $pages->count(),
            'data'   => PageResource::collection($pages),
        ], 200);
    }

    /**
     * Get a single page by ID belonging to the authenticated user.
     *
     * GET /api/pages/{id}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $page): JsonResponse
    {
        $page = $request->user()
            ->pages()
            ->with(['pageType', 'template'])
            ->find($page);

        if (! $page) {
            return response()->json([
                'status'  => false,
                'message' => 'Page not found.',
            ], 404);
        }

        // 1. Social Links
        $socialLinks = $page->socialLinks()
            ->with('socialPlatform')
            ->get()
            ->map(function ($link) {
                return [
                    'id'            => $link->id,
                    'page_id'       => $link->page_id,
                    'platform_id'   => $link->platform_id,
                    'platform_name' => $link->socialPlatform?->name,
                    'platform_icon' => $link->socialPlatform?->icon,
                    'color'         => $link->socialPlatform?->color,
                    'value'         => $link->value,
                    'sort_order'    => $link->sort_order,
                    'created_at'    => $link->created_at,
                    'updated_at'    => $link->updated_at,
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
                'categories' => $restaurantCategories->map(function ($category) {
                    return [
                        'category_id' => $category->id,
                        'category_title' => $category->title,
                        'category_position' => $category->position,
                        'items_count' => $category->items->count(),
                        'settings' => $category->settings ?? [
                            'show_images' => true,
                            'show_prices' => true,
                            'display_style' => 'cards'
                        ],
                        'items' => $category->items->map(function ($item) {
                            return [
                                'item_id' => $item->id,
                                'name' => $item->name,
                                'description' => $item->description,
                                'price' => $item->price,
                                'image' => $item->image,
                                'image_url' => $item->image ? asset('storage/' . $item->image) : null,
                                'is_available' => $item->is_available,
                                'variations' => $item->variations->map(fn($v) => [
                                    'id' => $v->id,
                                    'name' => $v->name,
                                    'price' => $v->price,
                                ])->toArray(),
                                'extras' => $item->extras->map(fn($e) => [
                                    'id' => $e->id,
                                    'name' => $e->name,
                                    'price' => $e->price,
                                ])->toArray(),
                            ];
                        })->toArray()
                    ];
                })->values()->toArray()
            ];
        }

        return response()->json([
            'status' => true,
            'data'   => [
                'page'           => new PageResource($page),
                'socialLinks'    => $socialLinks,
                'banners'        => $banners,
                'blocks'         => $blocks,
                'restaurantMenu' => $restaurantMenu ?: null,
            ]
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
                'status'  => false,
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
                    'color'         => $link->socialPlatform?->color,
                    'value'         => $link->value,
                    'sort_order'    => $link->sort_order,
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
                'categories' => $restaurantCategories->map(function ($category) {
                    return [
                        'category_id' => $category->id,
                        'category_title' => $category->title,
                        'category_position' => $category->position,
                        'items_count' => $category->items->count(),
                        'settings' => $category->settings ?? [
                            'show_images' => true,
                            'show_prices' => true,
                            'display_style' => 'cards'
                        ],
                        'items' => $category->items->map(function ($item) {
                            return [
                                'item_id' => $item->id,
                                'name' => $item->name,
                                'description' => $item->description,
                                'price' => $item->price,
                                'image' => $item->image,
                                'image_url' => $item->image ? asset('storage/' . $item->image) : null,
                                'is_available' => $item->is_available,
                                'variations' => $item->variations->map(fn($v) => [
                                    'id' => $v->id,
                                    'name' => $v->name,
                                    'price' => $v->price,
                                ])->toArray(),
                                'extras' => $item->extras->map(fn($e) => [
                                    'id' => $e->id,
                                    'name' => $e->name,
                                    'price' => $e->price,
                                ])->toArray(),
                            ];
                        })->toArray()
                    ];
                })->values()->toArray()
            ];
        }

        return response()->json([
            'status' => true,
            'data'   => [
                'page'           => new PageResource($page),
                'socialLinks'    => $socialLinks,
                'banners'        => $banners,
                'blocks'         => $blocks,
                'restaurantMenu' => $restaurantMenu ?: null,
            ]
        ], 200);
}

}
