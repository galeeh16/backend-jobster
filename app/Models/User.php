<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Post;
use App\Models\UserProfile;
use App\Models\CompanyProfile;
use App\Traits\HasAuthenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model implements Authenticatable
{
    use HasAuthenticatable;
    
    protected $table = 'users';
    protected $guarded = ['id'];

    protected $hidden = [
        'password',
    ];

    public function userProfile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    public function companyProfile(): HasOne 
    {
        return $this->hasOne(CompanyProfile::class, 'company_id', 'id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'company_id', 'id');
    }

    public function jobBeenApply(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_applies', 'user_id', 'post_id')->withTimestamps();
    }
}
