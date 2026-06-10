<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class NotificationsController extends Controller
{
    /**
     * Get user notifications.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $query = $request->boolean('unread_only') || $request->query('filter') === 'unread'
            ? $user->unreadNotifications()
            : $user->notifications();

        // Paginate results (default 15 per page)
        $perPage = (int) $request->query('per_page', 15);
        $notifications = $query->paginate($perPage);

        // Map the paginated collection to a clean array
        $mappedData = collect($notifications->items())->map(function ($notification) {
            // Clean type to just the class name or return raw type
            $type = class_basename($notification->type);

            return [
                'id' => $notification->id,
                'type' => $type,
                'data' => $notification->data,
                'read_at' => $notification->read_at?->toIso8601String(),
                'created_at' => $notification->created_at?->toIso8601String(),
            ];
        });

        return response()->json([
            'status' => true,
            'unread_count' => $user->unreadNotifications()->count(),
            'total' => $notifications->total(),
            'data' => $mappedData,
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
            ],
        ], 200);
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->find($id);

        if (!$notification) {
            return response()->json([
                'status' => false,
                'message' => 'Notification not found.',
            ], 404);
        }

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
            $notification->refresh();
        }

        return response()->json([
            'status' => true,
            'message' => 'Notification marked as read successfully.',
            'data' => [
                'id' => $notification->id,
                'type' => class_basename($notification->type),
                'data' => $notification->data,
                'read_at' => $notification->read_at?->toIso8601String(),
                'created_at' => $notification->created_at?->toIso8601String(),
            ],
        ], 200);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json([
            'status' => true,
            'message' => 'All notifications marked as read successfully.',
        ], 200);
    }

    /**
     * Delete a single notification.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->find($id);

        if (!$notification) {
            return response()->json([
                'status' => false,
                'message' => 'Notification not found.',
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'status' => true,
            'message' => 'Notification deleted successfully.',
        ], 200);
    }

    /**
     * Clear all notifications.
     */
    public function clearAll(Request $request): JsonResponse
    {
        $request->user()->notifications()->delete();

        return response()->json([
            'status' => true,
            'message' => 'All notifications cleared successfully.',
        ], 200);
    }
}
