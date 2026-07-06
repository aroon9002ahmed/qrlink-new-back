<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\RestaurantOrder;
use App\Models\RestaurantShiftHandover;
use App\Models\RestaurantDayClosure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $currentStatus = $order->status;
        $newStatus = $request->status;

        if ($currentStatus !== $newStatus) {
            // 1. Final states cannot transition
            if (in_array($currentStatus, ['completed', 'cancelled'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot change status of a completed or cancelled order.',
                ], 422);
            }

            // 2. Preparing cannot roll back to pending/confirmed
            if ($currentStatus === 'preparing' && in_array($newStatus, ['pending', 'confirmed'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot transition back to '.$newStatus.' once order is preparing.',
                ], 422);
            }

            // 3. Delivered cannot roll back to pending/confirmed/preparing
            if ($currentStatus === 'delivered' && in_array($newStatus, ['pending', 'confirmed', 'preparing'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot transition back to '.$newStatus.' once order is delivered.',
                ], 422);
            }

            // 4. Takeaway and Table orders cannot have delivered status
            if (in_array($order->type, ['takeaway', 'table']) && $newStatus === 'delivered') {
                return response()->json([
                    'status' => false,
                    'message' => 'Takeaway and Table orders cannot transition to delivered status.',
                ], 422);
            }
        }

        $order->status = $newStatus;
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

        // Check if the table has active orders
        if ($request->type === 'table' && $request->table_id) {
            $hasActiveOrder = RestaurantOrder::where('table_id', $request->table_id)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->exists();

            if ($hasActiveOrder) {
                return response()->json([
                    'status' => false,
                    'message' => 'This table has a pending or active order. Please select a different table or wait until it is cleared.',
                ], 422);
            }
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
     * Handover shift: archive all completed active orders.
     */
    public function handoverShift(Request $request, $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (! $page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'cashier_name' => 'required|string|max:255',
            'opening_cash' => 'required|numeric|min:0',
            'total_cash' => 'required|numeric|min:0',
            'next_opening_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        if ($data['next_opening_cash'] > $data['total_cash']) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => [
                    'next_opening_cash' => ['Next opening cash cannot exceed total cash in drawer.'],
                ],
            ], 422);
        }

        try {
            $handover = DB::transaction(function () use ($page, $data) {
                // Fetch active completed orders locking them for update to avoid race conditions
                $orders = RestaurantOrder::where('page_id', $page->id)
                    ->where('is_archived', false)
                    ->where('status', 'completed')
                    ->lockForUpdate()
                    ->get();

                $systemSales = $orders->sum('total_price');
                $expectedTotal = round(floatval($data['opening_cash']) + floatval($systemSales), 2);
                $totalCash = round(floatval($data['total_cash']), 2);
                $cashDifference = round($totalCash - $expectedTotal, 2);

                if ($cashDifference != 0.0) {
                    throw new \InvalidArgumentException('Shift handover rejected: Total cash in drawer (' . $totalCash . ') does not match expected amount (' . $expectedTotal . ').');
                }

                // Create handover record
                $handoverRecord = RestaurantShiftHandover::create([
                    'page_id' => $page->id,
                    'cashier_name' => $data['cashier_name'],
                    'opening_cash' => $data['opening_cash'],
                    'system_sales' => $systemSales,
                    'total_cash' => $data['total_cash'],
                    'next_opening_cash' => $data['next_opening_cash'],
                    'cash_difference' => $cashDifference,
                    'notes' => $data['notes'] ?? null,
                ]);

                // Archive only the specific orders included in the calculation
                if ($orders->isNotEmpty()) {
                    RestaurantOrder::whereIn('id', $orders->pluck('id'))->update(['is_archived' => true]);
                }

                return $handoverRecord;
            });

            return response()->json([
                'status' => true,
                'message' => 'Shift handed over successfully.',
                'data' => $handover,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => [
                    'total_cash' => [$e->getMessage()],
                ],
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during shift handover: ' . $e->getMessage(),
            ], 500);
        }
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

        $validator = Validator::make($request->all(), [
            'manager_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Check for any uncompleted/active orders (pending, confirmed, preparing)
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

        try {
            $closure = DB::transaction(function () use ($page, $request, $data) {
                // Get all non-archived orders to sum them up before archiving
                $orders = RestaurantOrder::where('page_id', $page->id)
                    ->where('is_archived', false)
                    ->lockForUpdate()
                    ->get();

                $totalOrders = $orders->count();
                $cashSales = $orders->where('payment_method', 'cash')->sum('total_price');
                $cardSales = $orders->where('payment_method', '!=', 'cash')->sum('total_price');
                $totalSales = $orders->sum('total_price');

                // Create day closure entry
                $closureRecord = RestaurantDayClosure::create([
                    'page_id' => $page->id,
                    'user_id' => $request->user()->id,
                    'manager_name' => $data['manager_name'] ?? null,
                    'total_orders' => $totalOrders,
                    'total_sales' => $totalSales,
                    'cash_sales' => $cashSales,
                    'card_sales' => $cardSales,
                    'notes' => $data['notes'] ?? null,
                ]);

                // Archive all remaining orders
                if ($orders->isNotEmpty()) {
                    RestaurantOrder::whereIn('id', $orders->pluck('id'))->update(['is_archived' => true]);
                }

                return $closureRecord;
            });

            return response()->json([
                'status' => true,
                'message' => 'Business day closed successfully. All orders have been archived.',
                'data' => $closure,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during closing day: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get list of shift handovers for the specific page.
     */
    public function handovers(Request $request, $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (! $page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $query = RestaurantShiftHandover::where('page_id', $page->id);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->query('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->query('end_date'));
        }

        $handovers = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'data' => $handovers,
        ]);
    }

    /**
     * Get list of day closures for the specific page.
     */
    public function closures(Request $request, $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);
        if (! $page) {
            return response()->json(['status' => false, 'message' => 'Page not found.'], 404);
        }

        $query = RestaurantDayClosure::where('page_id', $page->id);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->query('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->query('end_date'));
        }

        $closures = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'data' => $closures,
        ]);
    }

    /**
     * Update the order details (type, table).
     */
    public function update(Request $request, $pageId, $orderId): JsonResponse
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
            'type' => 'required|string|in:table,takeaway,delivery',
            'table_id' => 'nullable|integer|exists:restaurant_tables,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        // Validate table occupancy if changing table
        if ($request->type === 'table' && $request->table_id) {
            $hasActiveOrder = RestaurantOrder::where('table_id', $request->table_id)
                ->where('id', '!=', $order->id)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->exists();

            if ($hasActiveOrder) {
                return response()->json([
                    'status' => false,
                    'message' => 'The selected table is currently occupied by another active order.',
                ], 422);
            }
        }

        $order->type = $request->type;
        if ($request->type === 'table') {
            $order->table_id = $request->table_id;
        } else {
            $order->table_id = null;
        }
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'Order updated successfully.',
            'data' => $order->load(['items.menuItem', 'items.extras', 'table', 'branch']),
        ]);
    }
}
