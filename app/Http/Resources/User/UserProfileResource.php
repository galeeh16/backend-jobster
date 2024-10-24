<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            // 'user_id' => $this->user_id,
            'job_title_id' => $this->job_title_id,
            'location' => $this->location,
            'full_address' => $this->full_address,
            'about_me' => $this->about_me,
            'phone' => $this->phone,
            'photo' => $photo,
            'cv' => $this->cv ? $disk->url($this->cv) : null,
            'portfolio' => $this->portfolio ? $disk->url($this->portfolio) : null,
            'experience_year' => $this->experience_year,
            'availibilty_for_work' => $this->availibilty_for_work,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
