<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        try 
        {
            $user = Auth::user();

            $post = Post::findOrFail($postId); 

            $validated = $request->validate([
                'content' => 'required|string',
            ]);
           
            //it's a regular comment
            $comment = Comment::create([
                'user_id' => $user->id,
                'post_id' => $postId,
                'parent_comment_id' => null, // i don't have much time i wanted to create reply to comment and rest of crud :(.
                'content' => $validated['content'],
            ]);

            return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 201);
            
        }
        catch (ModelNotFoundException $e) 
        {
            return response()->json(['message' => 'Error: Post  not found'], 404);
        } 
    }

}
