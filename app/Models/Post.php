<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\PostReport;
class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'text',  
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function comments()
    {
    return $this->hasMany(Comment::class);
    }
    public function likedBy($user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'post_like')->withTimestamps();
    }


    public function post_reports()
    {
        return $this->hasMany(PostReport::class, 'reported_post')->withTimestamps();
    }

}