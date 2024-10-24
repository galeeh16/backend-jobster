<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Storage $disk */
        $disk = Storage::disk('public');

        $photo = $this->photo && $disk->exists($this->photo) ? $disk->url($this->photo) : $disk->url('assets/no-image.jpeg');

        return [
            'id' => $this->id,
            // 'company_id' => $this->company_id,
            'company_name' => $this->company_name,
            'address' => $this->address,
            'location' => $this->location,
            'about_company' => $this->about_company,
            'company_size' => $this->company_size,
            'founded_in' => $this->founded_in,
            'photo' => $photo,
            'website_url' => $this->website_url,
            'facebook_url' => $this->facebook_url,
            'instagram_url' => $this->instagram_url,
            'twitter_url' => $this->twitter_url,
            'linked_in_url' => $this->linked_in_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
