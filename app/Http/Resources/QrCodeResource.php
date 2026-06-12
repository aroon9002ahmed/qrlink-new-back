<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QrCodeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'user'        => $this->whenLoaded('user', fn() => [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
            ]),
            'shortCode'   => $this->short_code,   // via HasShortCode accessor
            'title'        => $this->title,
            'url'          => $this->original_url,
            'originalUrl'  => $this->original_url,
            'original_url' => $this->original_url,
            'totalScans'   => $this->clicks,        // via HasShortCode accessor (unified clicks)
            'isActive'     => $this->is_active,
            'expiresAt'    => $this->expires_at,
            'titleShow'    => $this->title_show,
            'fastRedirect' => $this->fast_redirect,
            'createdAt'    => $this->when($request->routeIs('api.qrcodes.show'), fn() => $this->created_at?->toIso8601String()),
            'updatedAt'    => $this->when($request->routeIs('api.qrcodes.show'), fn() => $this->updated_at?->toIso8601String()),
        ];
    }
}
