<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Http\Resources\TemplateResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TemplatesController extends Controller
{
    /**
     * Display a listing of active templates, optionally filtered by page_type_id.
     *
     * GET /api/templates
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Template::where('status', true);

        if ($request->has('page_type_id')) {
            $query->where('page_type_id', $request->query('page_type_id'));
        }

        $templates = $query->get();

        return response()->json([
            'status' => true,
            'data'   => TemplateResource::collection($templates),
        ], 200);
    }

    /**
     * Display a listing of active templates for a specific page type ID.
     *
     * GET /api/templates/page-type/{page_type_id}
     *
     * @param  int  $pageTypeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function byPageType(int $pageTypeId): JsonResponse
    {
        $templates = Template::where('status', true)
            ->where('page_type_id', $pageTypeId)
            ->get();

        return response()->json([
            'status' => true,
            'data'   => TemplateResource::collection($templates),
        ], 200);
    }

    /**
     * Display the specified active template.
     *
     * GET /api/templates/{id}
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $template = Template::where('status', true)->find($id);

        if (!$template) {
            return response()->json([
                'status'  => false,
                'message' => 'Template not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => new TemplateResource($template),
        ], 200);
    }
}

