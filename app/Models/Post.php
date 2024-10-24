<?php declare(strict_types=1);

namespace App\Models;

use App\Models\User;
use App\Models\JobTitle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    protected $table = 'posts';
    protected $guarded = ['id'];

    public function jobTitle(): HasOne
    {
        return $this->hasOne(JobTitle::class, 'id', 'job_title_id');
    }
    
    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id', 'id');
    }

    public function usersApplies(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_applies', 'post_id', 'user_id')->withTimestamps();
    }
}