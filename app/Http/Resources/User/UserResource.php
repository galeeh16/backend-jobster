<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use App\Http\Resources\User\UserProfileResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\CompanyProfileResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'user_type' => $this->user_type,
            'created_at' => $this->created_at,
            'user_profile' => new UserProfileResource($this->whenLoaded('userProfile')),
            'company_profile' => new CompanyProfileResource($this->whenLoaded('companyProfile')),
        ];
    }
}
