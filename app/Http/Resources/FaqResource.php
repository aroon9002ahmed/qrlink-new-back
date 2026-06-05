<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
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
            'id'                    => $this->id,
            'question'              => $this->question,
            'answer'                => $this->answer,
            'question_translations' => $this->getTranslations('question'),
            'answer_translations'   => $this->getTranslations('answer'),
            'status'                => (bool) $this->status,
            'order'                 => (int) $this->order,
        ];
    }
}
