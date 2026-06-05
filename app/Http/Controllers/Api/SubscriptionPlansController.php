<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Http\Resources\SubscriptionPlanResource;
use Illuminate\Http\JsonResponse;

class SubscriptionPlansController extends Controller
{
    /**
     * Display a listing of active subscription plans.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'status' => true,
            'data'   => SubscriptionPlanResource::collection($plans),
        ], 200);
    }

    /**
     * Display the specified subscription plan.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $plan = SubscriptionPlan::where('is_active', true)->find($id);

        if (!$plan) {
            return response()->json([
                'status'  => false,
                'message' => 'Subscription plan not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => new SubscriptionPlanResource($plan),
        ], 200);
    }
}
