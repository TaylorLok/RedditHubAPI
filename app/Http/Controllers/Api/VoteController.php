<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Vote;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function upvote($postId, $commentId)
    {
        $user = Auth::user();

        $vote = Vote::where('user_id', $user->id)
                    ->where('post_id', $postId)
                    ->where('comment_id', $commentId)
                    ->first();

        if ($vote) {
           
            $vote->update(['upvote' => true, 'downvote' => false]);
        } else {
        
            Vote::create([
                'user_id' => $user->id,
                'post_id' => $postId,
                'comment_id' => $commentId,
                'upvote' => true,
                'downvote' => false,
            ]);
        }

        return response()->json(['message' => 'Comment upvoted successfully'], 200);
    }

    public function downvote($postId, $commentId)
    {
        $user = Auth::user();

    
        $vote = Vote::where('user_id', $user->id)
                    ->where('post_id', $postId)
                    ->where('comment_id', $commentId)
                    ->first();

        if ($vote) {
           
            $vote->update(['upvote' => false, 'downvote' => true]);
        } else {
            
            Vote::create([
                'user_id' => $user->id,
                'post_id' => $postId,
                'comment_id' => $commentId,
                'upvote' => false,
                'downvote' => true,
            ]);
        }

        return response()->json(['message' => 'Comment downvoted successfully'], 200);
    }
}
