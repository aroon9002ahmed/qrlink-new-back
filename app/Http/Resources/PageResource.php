<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'title'       => $this->title,
            'slug'        => $this->slug,
            'description' => $this->description,
            'image_path'  => $this->image_path,
            'status'      => $this->status,
            'language'    => $this->language,
            'copyright'   => $this->copyright,

            // Type information (PageType relation)
            'type' => $this->whenLoaded('pageType', fn() => [
                'id'          => $this->pageType->id,
                'slug'        => $this->pageType->slug,
                'name'        => $this->pageType->name,
                'description' => $this->pageType->description,
                'icon'        => $this->pageType->icon
                    ? (str_starts_with($this->pageType->icon, 'images/pageTypes/cache/')
                        ? Storage::disk('public')->url($this->pageType->icon)
                        : Storage::disk('public')->url('images/pageTypes/cache/' . $this->pageType->icon))
                    : null,
            ], $this->type), // fallback to raw type value if not loaded

            // Template information
            'template' => $this->whenLoaded('template', fn() => [
                'id'            => $this->template->id,
                'name'          => $this->template->name,
                'slug'          => $this->template->slug,
                'description'   => $this->template->description,
                'preview_image' => $this->template->preview_image
                    ? (str_starts_with($this->template->preview_image, 'images/templates/cache/')
                        ? Storage::disk('public')->url($this->template->preview_image)
                        : Storage::disk('public')->url('images/templates/cache/' . $this->template->preview_image))
                    : null,
                'is_active'     => $this->template->is_active,
            ]),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
