<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

//    protected $hidden = ['title'];
//    protected $appends = ['title_upper_case'];
    protected $fillable = [
        'title', 'body',
    ];
//    protected $guarded = ['title'];
    protected $casts = [
        'body' => 'array',  // body response is in json
    ];

    public function getTitleUpperCaseAttribute()
    {
        return strtoupper($this->title);
    }

    public function setTitleAttribute(string $value)
    {
        return $this->attributes['title'] = strtolower($value);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_user', 'post_id', 'user_id');
    }
}
