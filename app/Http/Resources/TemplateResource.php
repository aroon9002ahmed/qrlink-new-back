<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $previewImageUrl = null;
        $thumbnailUrl = null;

        if ($this->preview_image) {
            if (str_starts_with($this->preview_image, 'images/templates/cache/')) {
                $previewImageUrl = Storage::disk('public')->url($this->preview_image);
                $thumbnailPath = str_replace('images/templates/cache/', 'images/templates/thumbnail/', $this->preview_image);
                $thumbnailUrl = Storage::disk('public')->url($thumbnailPath);
            } elseif (str_starts_with($this->preview_image, 'templates/')) {
                // Seeded path templates/profile1.png
                $previewImageUrl = Storage::disk('public')->url($this->preview_image);
                $thumbnailUrl = Storage::disk('public')->url($this->preview_image);
            } else {
                $previewImageUrl = Storage::disk('public')->url('images/templates/cache/' . $this->preview_image);
                $thumbnailUrl = Storage::disk('public')->url('images/templates/thumbnail/' . $this->preview_image);
            }
        }

        return [
            'id'                     => $this->id,
            'page_type_id'           => (int) $this->page_type_id,
            'name'                   => $this->name,
            'slug'                   => $this->slug,
            'description'            => $this->description,
            'name_translations'      => $this->getTranslations('name'),
            'description_translations' => $this->getTranslations('description'),
            'preview_image'          => $this->preview_image,
            'preview_image_url'      => $previewImageUrl,
            'thumbnail_url'          => $thumbnailUrl,
            'status'                 => (bool) $this->status,
            'created_at'             => $this->created_at?->toIso8601String(),
            'updated_at'             => $this->updated_at?->toIso8601String(),
        ];
    }
}
