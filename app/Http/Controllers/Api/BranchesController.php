<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RestaurantBranch;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchesController extends Controller
{
    /**
     * Get all branches for a specific page.
     *
     * GET /api/pages/{pageId}/branches
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

        $branches = $page->branches()->orderBy('main', 'desc')->orderBy('name')->get()->map(function ($branch) {
            return [
                'id'        => $branch->id,
                'page_id'   => $branch->page_id,
                'name'      => $branch->name,
                'address'   => $branch->address,
                'latitude'  => $branch->latitude,
                'longitude' => $branch->longitude,
                'main'      => (bool) $branch->main,
                'status'    => (bool) $branch->status,
                'created_at'=> $branch->created_at,
                'updated_at'=> $branch->updated_at,
            ];
        });

        return response()->json([
            'status' => true,
            'data'   => $branches,
        ], 200);
    }

    /**
     * Create a new branch.
     *
     * POST /api/pages/{pageId}/branches
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
            'name'      => 'required|string|max:255',
            'address'   => 'nullable|string|max:500',
            'latitude'  => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'main'      => 'nullable|boolean',
            'status'    => 'nullable|boolean',
        ]);

        $isMain = filter_var($request->input('main', false), FILTER_VALIDATE_BOOLEAN);
        $status = filter_var($request->input('status', true), FILTER_VALIDATE_BOOLEAN);

        // If it is the first branch, it must be main
        if ($page->branches()->count() === 0) {
            $isMain = true;
        }

        $branch = DB::transaction(function () use ($page, $request, $isMain, $status) {
            if ($isMain) {
                // Reset all other branches to main = false
                $page->branches()->update(['main' => 0]);
            }

            return $page->branches()->create([
                'name'      => $request->input('name'),
                'address'   => $request->input('address'),
                'latitude'  => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'main'      => $isMain ? 1 : 0,
                'status'    => $status ? 1 : 0,
            ]);
        });

        return response()->json([
            'status'  => true,
            'message' => 'Branch created successfully.',
            'data'    => array_merge($branch->toArray(), [
                'main'   => (bool) $branch->main,
                'status' => (bool) $branch->status,
            ]),
        ], 201);
    }

    /**
     * Update an existing branch.
     *
     * PUT/PATCH /api/pages/{pageId}/branches/{branchId}
     */
    public function update(Request $request, int $pageId, int $branchId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);

        if (!$page) {
            return response()->json([
                'status'  => false,
                'message' => 'Page not found.',
            ], 404);
        }

        $branch = $page->branches()->find($branchId);

        if (!$branch) {
            return response()->json([
                'status'  => false,
                'message' => 'Branch not found.',
            ], 404);
        }

        $request->validate([
            'name'      => 'sometimes|required|string|max:255',
            'address'   => 'sometimes|nullable|string|max:500',
            'latitude'  => 'sometimes|nullable|string|max:255',
            'longitude' => 'sometimes|nullable|string|max:255',
            'main'      => 'sometimes|required|boolean',
            'status'    => 'sometimes|required|boolean',
        ]);

        $updateData = [];
        if ($request->has('name')) $updateData['name'] = $request->input('name');
        if ($request->has('address')) $updateData['address'] = $request->input('address');
        if ($request->has('latitude')) $updateData['latitude'] = $request->input('latitude');
        if ($request->has('longitude')) $updateData['longitude'] = $request->input('longitude');
        if ($request->has('status')) $updateData['status'] = filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

        DB::transaction(function () use ($page, $branch, $request, &$updateData) {
            if ($request->has('main')) {
                $isMain = filter_var($request->input('main'), FILTER_VALIDATE_BOOLEAN);
                
                // If trying to unset main, check if this is the only branch. If so, keep it main.
                if (!$isMain && $branch->main && $page->branches()->count() === 1) {
                    $isMain = true;
                }

                if ($isMain) {
                    $page->branches()->where('id', '!=', $branch->id)->update(['main' => 0]);
                    $updateData['main'] = 1;
                } else {
                    // If they turn off main for this branch, we must assign main to another branch
                    $updateData['main'] = 0;
                    $otherBranch = $page->branches()->where('id', '!=', $branch->id)->first();
                    if ($otherBranch) {
                        $otherBranch->update(['main' => 1]);
                    } else {
                        // No other branch, must remain main
                        $updateData['main'] = 1;
                    }
                }
            }

            $branch->update($updateData);
        });

        return response()->json([
            'status'  => true,
            'message' => 'Branch updated successfully.',
            'data'    => array_merge($branch->toArray(), [
                'main'   => (bool) $branch->main,
                'status' => (bool) $branch->status,
            ]),
        ], 200);
    }

    /**
     * Delete a branch.
     *
     * DELETE /api/pages/{pageId}/branches/{branchId}
     */
    public function destroy(Request $request, int $pageId, int $branchId): JsonResponse
    {
        $page = $request->user()->pages()->find($pageId);

        if (!$page) {
            return response()->json([
                'status'  => false,
                'message' => 'Page not found.',
            ], 404);
        }

        $branch = $page->branches()->find($branchId);

        if (!$branch) {
            return response()->json([
                'status'  => false,
                'message' => 'Branch not found.',
            ], 404);
        }

        $wasMain = $branch->main;

        DB::transaction(function () use ($page, $branch, $wasMain) {
            $branch->delete();

            // If we deleted the main branch, set the first remaining branch to main
            if ($wasMain) {
                $nextMain = $page->branches()->first();
                if ($nextMain) {
                    $nextMain->update(['main' => 1]);
                }
            }
        });

        return response()->json([
            'status'  => true,
            'message' => 'Branch deleted successfully.',
        ], 200);
    }
}
