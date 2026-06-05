<?php

namespace App\Http\Resources\public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CodeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            // 'id'        => $this->id,
            'user'      => $this->whenLoaded('user', fn() => [
                // 'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
            ]),
            'shortCode'   => $this->short_code,   // via HasShortCode accessor
            'originalUrl' => $this->original_url,
            'title'       => $this->title,
            'clicks'      => $this->clicks,        // via HasShortCode accessor
            'isActive'    => $this->is_active,
            'expiresAt'   => $this->expires_at,
        ];
    }
}
