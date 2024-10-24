<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use App\Http\Resources\BaseResourceCollection;
use App\Http\Resources\Post\PostPaginateResource;

class PostCollection extends BaseResourceCollection
{
    public $collects = PostPaginateResource::class;

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
