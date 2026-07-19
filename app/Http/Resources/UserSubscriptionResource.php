<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'billing_cycle'  => $this->billing_cycle,
            'status'         => $this->status,
            'starts_at'      => Carbon::parse($this->starts_at)->format('Y-m-d'),
            'ends_at'        => Carbon::parse($this->ends_at)->format('Y-m-d'),
            'cancelled_at'   => Carbon::parse($this->cancelled_at)->format('Y-m-d'),
            'amount_paid'    => $this->amount_paid,
            'payment_method' => $this->payment_method,
            'transaction_id' => $this->transaction_id,
            'plan'           => $this->relationLoaded('subscriptionPlan') && $this->subscriptionPlan
                ? new SubscriptionPlanResource($this->subscriptionPlan)
                : null,
        ];
    }
}
