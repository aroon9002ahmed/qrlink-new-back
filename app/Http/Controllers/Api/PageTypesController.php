<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PageType;
use App\Http\Resources\PageTypeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageTypesController extends Controller
{
    /**
     * Display a listing of active page types.
     *
     * GET /api/page-types
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $pageTypes = PageType::where('status', true)->get();

        return response()->json([
            'status' => true,
            'data'   => PageTypeResource::collection($pageTypes),
        ], 200);
    }

    /**
     * Display the specified active page type.
     *
     * GET /api/page-types/{id}
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $pageType = PageType::where('status', true)->find($id);

        if (!$pageType) {
            return response()->json([
                'status'  => false,
                'message' => 'Page type not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => new PageTypeResource($pageType),
        ], 200);
    }
}
