<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faqs;
use App\Http\Resources\FaqResource;
use Illuminate\Http\JsonResponse;

class FaqsController extends Controller
{
    /**
     * Display a listing of active FAQs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $faqs = Faqs::where('status', true)
            ->orderBy('order', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data'   => FaqResource::collection($faqs),
        ], 200);
    }
}
