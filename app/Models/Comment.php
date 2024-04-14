<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\CommentReport;

class Comment extends Model
{
    protected $fillable = [
        'comment_text',
        'post_id',
        'user_id',
    ];
    use HasFactory;

    public function user()
    {
    return $this->belongsTo(User::class);
    }


    public function comment_reports()
    {
        return $this->hasMany(CommentReport::class)->withTimestamps();
    }

    public function post()
    {
    return $this->belongsTo(Post::class);
    }


    

}
