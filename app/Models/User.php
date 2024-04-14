<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\PostReport;
use App\Models\CommentReport;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'isAdmin'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function posts()
    {
    return $this->hasMany(Post::class);
    }
    public function comments()
    {
    return $this->hasMany(Comment::class);
    }
    public function likes()
    {
        return $this->belongsToMany(Post::class, 'post_like',)->withTimestamps();
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'follower_user','follower_id','user_id')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follower_user','user_id','follower_id')->withTimestamps();
    }

    public function follows(User $user)
    {
        return $this->followings()->where('user_id', $user->id)->withTimestamps();
    }

    public function post_reports()
    {
        return $this->hasMany(PostReport::class)->withTimestamps();
    }

    public function comment_reports()
    {
        return $this->hasMany(CommentReport::class)->withTimestamps();
    }


}
