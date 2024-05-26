<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $postId, $parentCommentId = null)
    {
        $user = Auth::user();

        $post = Post::findOrFail($postId); 

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        // If $parentCommentId is provided, it means this is a reply to a comment
        if (!is_null($parentCommentId)) {
            // Check if the parent comment exists and belongs to the same post
            $parentComment = Comment::where('id', $parentCommentId)
                                    ->where('post_id', $postId)
                                    ->firstOrFail();

            $comment = Comment::create([
                'user_id' => $user->id,
                'post_id' => $postId,
                'parent_comment_id' => $parentCommentId,
                'content' => $validated['content'],
            ]);
        } else {
            // Otherwise, it's a regular comment
            $comment = Comment::create([
                'user_id' => $user->id,
                'post_id' => $postId,
                'content' => $validated['content'],
            ]);
        }

        return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 201);
    }

    public function update(Request $request, $commentId)
    {
        $user = Auth::user();
        $comment = Comment::where('user_id', $user->id)->findOrFail($commentId);

        $comment->update(['content' => $request->content]);

        return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment], 200);
    }

    public function updateReply(Request $request, $parentCommentId, $replyId)
    {
        $user = Auth::user();
        $comment = Comment::where('user_id', $user->id)
                          ->where('parent_comment_id', $parentCommentId)
                          ->findOrFail($replyId);

        $comment->update(['content' => $request->content]);

        return response()->json(['message' => 'Reply updated successfully', 'comment' => $comment], 200);
    }

    public function destroy($commentId)
    {
        $user = Auth::user();
        $comment = Comment::where('user_id', $user->id)->findOrFail($commentId);
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }

    public function destroyReply($parentCommentId)
    {
        $user = Auth::user();
        $replyId = $parentCommentId; 
        $comment = Comment::where('user_id', $user->id)
                    ->where('id', $replyId) 
                    ->whereNotNull('parent_comment_id')
                    ->firstOrFail();

        $comment->delete();

        return response()->json(['message' => 'Reply deleted successfully'], 200);
    }

}
