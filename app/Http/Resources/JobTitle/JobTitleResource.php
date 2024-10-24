<?php

namespace App\Http\Resources\JobTitle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobTitleResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title_name' => $this->title_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}