<?php

namespace App\Http\Resources\Post;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\JobTitle\JobTitleResource;

class PostResource extends JsonResource
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
            'job_title' => new JobTitleResource($this->whenLoaded('jobTitle')),
            'post_title' => $this->post_title,
            'location' => $this->location,
            'overview' => $this->overview,
            'responsablities' => $this->responsabilities,
            'requirements' => $this->requirements,
            'skills' => $this->skills,
            'experience_year' => $this->experience_year,
            'employment_type' => $this->employment_type,
            'level_type' => Str::headline($this->level_type),
            'employment_type' => Str::headline($this->employment_type),
            'salary' => $this->salary,
            'total_applied' => $this->total_applied,
            'created_at' => $this->created_at,
            'created_at_human' => $this->created_at?->diffForHumans(),
            'updated_at' => $this->updated_at,
            // 'company_id' => $this->company_id,
            'company' => new UserResource($this->whenLoaded('company')),
        ];
    }
}