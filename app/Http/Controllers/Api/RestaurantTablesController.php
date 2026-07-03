<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestaurantTablesController extends Controller
{
    /**
     * Get all tables for a specific page.
     *
     * GET /api/pages/{pageId}/tables
     */
    public function index(Request $request, int $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);

        if (! $page) {
            return response()->json([
                'status' => false,
                'message' => 'Page not found.',
            ], 404);
        }

        $tables = $page->tables()
            ->orderByRaw('CAST(table_number AS UNSIGNED) ASC')
            ->orderBy('table_number')
            ->get()
            ->map(function ($table) {
                $activeOrder = $table->orders()
                    ->whereNotIn('status', ['completed', 'cancelled'])
                    ->first();

                $table->active_order = $activeOrder ? [
                    'id' => $activeOrder->id,
                    'order_number' => $activeOrder->id,
                    'customer_name' => $activeOrder->customer_name,
                    'status' => $activeOrder->status,
                    'total' => $activeOrder->total,
                ] : null;

                return $table;
            });

        return response()->json([
            'status' => true,
            'data' => $tables,
        ], 200);
    }

    /**
     * Create a new table.
     *
     * POST /api/pages/{pageId}/tables
     */
    public function store(Request $request, int $pageId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);

        if (! $page) {
            return response()->json([
                'status' => false,
                'message' => 'Page not found.',
            ], 404);
        }

        $request->validate([
            'table_number' => 'required|string|max:255',
            'seating_capacity' => 'required|integer|min:1',
            'type' => 'nullable|string|in:table,delivery',
            'status' => 'nullable|string|in:available,not_available,occupied,reserved',
        ]);

        // Check if table number already exists for this page
        $exists = $page->tables()->where('table_number', $request->input('table_number'))->exists();
        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'A table with this number already exists.',
            ], 422);
        }

        $table = $page->tables()->create([
            'table_number' => $request->input('table_number'),
            'seating_capacity' => $request->input('seating_capacity', 4),
            'type' => $request->input('type', 'table'),
            'status' => $request->input('status', 'available'),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Table created successfully.',
            'data' => $table,
        ], 201);
    }

    /**
     * Update an existing table.
     *
     * PUT/PATCH /api/pages/{pageId}/tables/{tableId}
     */
    public function update(Request $request, int $pageId, int $tableId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);

        if (! $page) {
            return response()->json([
                'status' => false,
                'message' => 'Page not found.',
            ], 404);
        }

        $table = $page->tables()->find($tableId);

        if (! $table) {
            return response()->json([
                'status' => false,
                'message' => 'Table not found.',
            ], 404);
        }

        $request->validate([
            'table_number' => 'sometimes|required|string|max:255',
            'seating_capacity' => 'sometimes|required|integer|min:1',
            'type' => 'sometimes|required|string|in:table,delivery',
            'status' => 'sometimes|required|string|in:available,not_available,occupied,reserved',
        ]);

        if ($request->has('table_number') && $request->input('table_number') !== $table->table_number) {
            // Check if new table number already exists for this page
            $exists = $page->tables()
                ->where('table_number', $request->input('table_number'))
                ->where('id', '!=', $table->id)
                ->exists();
            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'A table with this number already exists.',
                ], 422);
            }
        }

        $table->update($request->only([
            'table_number',
            'seating_capacity',
            'type',
            'status',
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Table updated successfully.',
            'data' => $table,
        ], 200);
    }

    /**
     * Delete a table.
     *
     * DELETE /api/pages/{pageId}/tables/{tableId}
     */
    public function destroy(Request $request, int $pageId, int $tableId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);

        if (! $page) {
            return response()->json([
                'status' => false,
                'message' => 'Page not found.',
            ], 404);
        }

        $table = $page->tables()->find($tableId);

        if (! $table) {
            return response()->json([
                'status' => false,
                'message' => 'Table not found.',
            ], 404);
        }

        // Check if there are active, non-finalized orders on this table
        $hasActiveOrders = $table->orders()
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->exists();

        if ($hasActiveOrders) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete this table because it currently has active orders.',
            ], 422);
        }

        $table->delete();

        return response()->json([
            'status' => true,
            'message' => 'Table deleted successfully.',
        ], 200);
    }
}
