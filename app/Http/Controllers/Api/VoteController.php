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
    public function upvotePost($postId = null, $commentId = null)
    {
        $user = Auth::user();

        try
        {
            if ($postId) 
            {
                $post = Post::findOrFail($postId);
                $vote = Vote::updateOrCreate(
                    ['user_id' => $user->id, 'post_id' => $postId],
                    ['upvote' => true, 'downvote' => false]
                );

                return response()->json(['message' => 'Post upvoted successfully'], 200);
            }
            else 
            {
                return response()->json(['message' => 'Please provide a postId'], 400);
            }
        }
        catch (ModelNotFoundException $e){
            return response()->json(['message' => 'Post not found'], 404);
        }
    }

    public function downvotePost($postId = null, $commentId = null)
    {
        $user = Auth::user();

        try{
            if ($postId) 
            {
                $post = Post::findOrFail($postId);
                $vote = Vote::updateOrCreate(
                    ['user_id' => $user->id, 'post_id' => $postId],
                    ['upvote' => false, 'downvote' => true]
                );

                return response()->json(['message' => 'Post downvoted successfully'], 200);
            }
            else 
            {
                return response()->json(['message' => 'Please provide a postId'], 400);
            }
        }
        catch (ModelNotFoundException $e){
            return response()->json(['message' => 'Post not found'], 404);
        }
    }


    public function upvotePostComment($commentId = null)
    {
        $user = Auth::user();

        try
        {
            if ($commentId) 
            {
                $comment = Comment::findOrFail($commentId);
                $vote = Vote::updateOrCreate(
                    ['user_id' => $user->id, 'comment_id' => $commentId],
                    ['upvote' => true, 'downvote' => false]
                );

                return response()->json(['message' => 'Comment upvoted successfully'], 200);
            }
            else 
            {
                return response()->json(['message' => 'Please provide a  commentId'], 400);
            }
        }
        catch (ModelNotFoundException $e){
            return response()->json(['message' => 'Comment not found'], 404);
        }
    }

    public function downvotePostComment($commentId = null)
    {
        $user = Auth::user();

        try{
         
            if ($commentId) 
            {
                $comment = Comment::findOrFail($commentId);
                $vote = Vote::updateOrCreate(
                    ['user_id' => $user->id, 'comment_id' => $commentId],
                    ['upvote' => false, 'downvote' => true]
                );

                return response()->json(['message' => 'Comment downvoted successfully'], 200);
            }
            else 
            {
                return response()->json(['message' => 'Please provide a commentId'], 400);
            }
        }
        catch (ModelNotFoundException $e){
            return response()->json(['message' => 'Comment not found'], 404);
        }
    }
}
