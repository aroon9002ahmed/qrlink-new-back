<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class PageTypeResource extends JsonResource
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
            'id'                     => $this->id,
            'name'                   => $this->name,
            'slug'                   => $this->slug,
            'description'            => $this->description,
            'icon'                   => $this->icon,
            'icon_url'               => $this->icon
                ? (str_starts_with($this->icon, 'images/pageTypes/cache/')
                    ? Storage::disk('public')->url($this->icon)
                    : Storage::disk('public')->url('images/pageTypes/cache/' . $this->icon))
                : null,
            'name_translations'      => $this->getTranslations('name'),
            'description_translations' => $this->getTranslations('description'),
            'status'                 => (bool) $this->status,
            'created_at'             => $this->created_at?->toIso8601String(),
            'updated_at'             => $this->updated_at?->toIso8601String(),
        ];
    }
}
