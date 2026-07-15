<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortCodeAnalyticResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'shortCodeId' => $this->short_code_id,
            'ipAddress'   => $this->ip_address,
            'userAgent'   => $this->user_agent,
            'country'     => $this->country,
            'city'        => $this->city,
            'createdAt'   => $this->created_at?->toIso8601String(),
            'updatedAt'   => $this->updated_at?->toIso8601String(),
            'shortCode'   => $this->whenLoaded('shortCode', fn() => [
                'id' => $this->shortCode->id,
                'code' => $this->shortCode->code,
                'clicks' => $this->shortCode->clicks,
            ]),
        ];
    }
}
