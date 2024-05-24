<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','title','content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function votesCount()
    {
        return $this->votes()->count();
    }

    public function commentsCount()
    {
        return $this->comments()->count();
    }

    public function upvotesCount()
    {
        return $this->votes()->where('upvote', true)->count();
    }

    public function downvotesCount()
    {
        return $this->votes()->where('downvote', true)->count();
    }
}
