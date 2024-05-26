<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Vote;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VoteController extends Controller
{
    public function upvote($postId = null, $commentId = null)
    {
        $user = Auth::user();

        try
        {
            if ($postId) 
            {
                // Upvote for a post
                $post = Post::findOrFail($postId);
                $vote = Vote::updateOrCreate(
                    ['user_id' => $user->id, 'post_id' => $postId],
                    ['upvote' => true, 'downvote' => false]
                );

                return response()->json(['message' => 'Post upvoted successfully'], 200);
            }
            elseif ($commentId) 
            {
                // Upvote for a comment
                $comment = Comment::findOrFail($commentId);
                $vote = Vote::updateOrCreate(
                    ['user_id' => $user->id, 'comment_id' => $commentId],
                    ['upvote' => true, 'downvote' => false]
                );

                return response()->json(['message' => 'Comment upvoted successfully'], 200);
            }
            else 
            {
                // Neither postId nor commentId provided
                return response()->json(['message' => 'Please provide a postId or commentId'], 400);
            }
        }
        catch (ModelNotFoundException $e){
            return response()->json(['message' => 'Post or comment not found'], 404);
        }
    }

    public function downvote($postId = null, $commentId = null)
    {
        $user = Auth::user();

        try{
            if ($postId) 
            {
                // Upvote for a post
                $post = Post::findOrFail($postId);
                $vote = Vote::updateOrCreate(
                    ['user_id' => $user->id, 'post_id' => $postId],
                    ['upvote' => false, 'downvote' => true]
                );

                return response()->json(['message' => 'Post downvoted successfully'], 200);
            }
            elseif ($commentId) 
            {
                // Upvote for a comment
                $comment = Comment::findOrFail($commentId);
                $vote = Vote::updateOrCreate(
                    ['user_id' => $user->id, 'comment_id' => $commentId],
                    ['upvote' => false, 'downvote' => true]
                );

                return response()->json(['message' => 'Comment downvoted successfully'], 200);
            }
            else 
            {
                // Neither postId nor commentId provided
                return response()->json(['message' => 'Please provide a postId or commentId'], 400);
            }
        }
        catch (ModelNotFoundException $e){
            return response()->json(['message' => 'Post or comment not found'], 404);
        }
    }
}
