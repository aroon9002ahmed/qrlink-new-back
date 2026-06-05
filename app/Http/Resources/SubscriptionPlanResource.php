<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionPlanResource extends JsonResource
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
            'id'                  => $this->id,
            'name'                => $this->getTranslations('name'),
            'slug'                => $this->slug,
            'description'         => $this->getTranslations('description'),
            'price_monthly'       => $this->price_monthly,
            'price_yearly'        => $this->price_yearly,
            'features'            => [
                'max_links'           => $this->max_links,
                'max_qrcodes'         => $this->max_qrcodes,
                'max_pages'           => $this->max_pages,
                'max_items'           => $this->max_items,
                'max_blocks_per_page' => $this->max_blocks_per_page,
                'custom_domain'       => (bool) $this->custom_domain,
                'branches'            => (bool) $this->branches,
                'customization_templates'            => (bool) $this->customization_templates,
                'page_qrcode'         => (bool) $this->qr_code,
                'banners'             => (bool) $this->banners,
                'restaurant_table'    => (bool) $this->restaurant_table,
                'delivery'            => (bool) $this->delivery,
                'takeaway' => (bool) $this->takeaway,
            ],
            'turn_off_Branding' => (bool) $this->turn_off_Branding,
            'analytics'           => (bool) $this->analytics,
            'priority_support'    => (bool) $this->priority_support,
            'is_active'           => (bool) $this->is_active,
            'sort_order'          => $this->sort_order,
        ];
    }
}
