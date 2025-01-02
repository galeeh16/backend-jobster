<?php

namespace App\Http\Resources\JobTitle;

use Illuminate\Http\Request;
use App\Http\Resources\BaseResourceCollection;
use App\Http\Resources\JobTitle\JobTitleResource;

class JobTitleCollection extends BaseResourceCollection
{
    public $collects = JobTitleResource::class;
    
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
