<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\RestaurantOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RestaurantOrdersController extends Controller
{
    /**
     * Display a listing of orders for the specific page.
     */
    public function index(Request $request, $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (! $page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $isArchivedQuery = $request->query('type') === 'archived' || $request->query('archived') === 'true';

        $query = RestaurantOrder::where('page_id', $page->id)
            ->where('is_archived', $isArchivedQuery);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->query('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->query('end_date'));
        }

        $orders = $query->with(['items.menuItem', 'items.extras', 'table', 'branch'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Display the specified order.
     */
    public function show(Request $request, $pageId, $orderId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (! $page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $order = RestaurantOrder::where('page_id', $page->id)
            ->with(['items.menuItem', 'items.extras', 'table', 'branch'])
            ->find($orderId);

        if (! $order) {
            return response()->json(['status' => false, 'message' => 'Order not found.'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $order,
        ]);
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, $pageId, $orderId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (! $page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $order = RestaurantOrder::where('page_id', $page->id)->find($orderId);
        if (! $order) {
            return response()->json(['status' => false, 'message' => 'Order not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:pending,confirmed,preparing,completed,delivered,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $order->status = $request->status;
        $order->save();

        // Reload with relations
        $order->load(['items.menuItem', 'items.extras', 'table', 'branch']);

        return response()->json([
            'status' => true,
            'message' => 'Order status updated successfully.',
            'data' => $order,
        ]);
    }

    /**
     * Store a new public order submitted by a customer.
     */
    public function storePublic(Request $request, string $slug): JsonResponse
    {
        $page = Page::where('slug', $slug)->first();
        if (! $page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:255',
            'customer_address' => 'nullable|string',
            'branch_id' => 'nullable|integer|exists:restaurant_branches,id',
            'type' => 'required|string|in:table,delivery,takeaway',
            'table_id' => 'nullable|integer|exists:restaurant_tables,id',
            'total_price' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|integer|exists:restaurant_menu_items,id',
            'items.*.variation_name' => 'nullable|string',
            'items.*.variation_price' => 'nullable|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
            'items.*.extras' => 'nullable|array',
            'items.*.extras.*.name' => 'required|string',
            'items.*.extras.*.price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        // Create the order
        $order = RestaurantOrder::create([
            'page_id' => $page->id,
            'table_id' => $request->table_id,
            'type' => $request->type,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'branch_id' => $request->branch_id,
            'status' => 'pending',
            'total_price' => $request->total_price,
        ]);

        // Create items and their extras
        foreach ($request->items as $itemData) {
            $orderItem = $order->items()->create([
                'menu_item_id' => $itemData['menu_item_id'],
                'variation_name' => $itemData['variation_name'] ?? null,
                'variation_price' => $itemData['variation_price'] ?? null,
                'quantity' => $itemData['quantity'],
                'price' => $itemData['price'],
            ]);

            if (! empty($itemData['extras'])) {
                foreach ($itemData['extras'] as $extraData) {
                    $orderItem->extras()->create([
                        'name' => $extraData['name'],
                        'price' => $extraData['price'],
                    ]);
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Order stored successfully.',
            'data' => $order->load(['items.menuItem', 'items.extras', 'table', 'branch']),
        ], 201);
    }

    /**
     * Handover shift: archive all completed and delivered active orders.
     */
    public function handoverShift(Request $request, $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (! $page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        RestaurantOrder::where('page_id', $page->id)
            ->where('is_archived', false)
            ->whereIn('status', ['completed', 'delivered'])
            ->update(['is_archived' => true]);

        return response()->json([
            'status' => true,
            'message' => 'Shift handed over successfully. All completed orders have been archived.',
        ]);
    }

    /**
     * Close the business day: archive all orders after verifying no pending/active orders exist.
     */
    public function closeDay(Request $request, $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (! $page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        // Check for any uncompleted/active orders
        $activeCount = RestaurantOrder::where('page_id', $page->id)
            ->where('is_archived', false)
            ->whereIn('status', ['pending', 'confirmed', 'preparing'])
            ->count();

        if ($activeCount > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot close the day. There are still active orders that must be completed or cancelled first.',
            ], 422);
        }

        // Archive all remaining orders (completed, delivered, cancelled)
        RestaurantOrder::where('page_id', $page->id)
            ->where('is_archived', false)
            ->update(['is_archived' => true]);

        return response()->json([
            'status' => true,
            'message' => 'Day closed successfully. All orders have been archived.',
        ]);
    }
}
