<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $posts = Post::where('user_id', $user->id)
                    ->with('user', 'comments', 'votes')
                    ->paginate(100);

        if ($posts->isEmpty()) {
            return response()->json(['message' => 'No posts found for the authenticated user'], 404);
        }

        return response()->json(['message' => 'Posts retrieved successfully', 'posts' => $posts], 200);
    }

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

    //A user should be able to query all the posts that they have upvoted or downvoted.
    public function postByVoted()
    {
        $user = Auth::user();

        $postIds = Vote::where('user_id', $user->id)->pluck('post_id');

        $posts = Post::whereIn('id', $postIds)->with('user', 'comments', 'votes')->paginate(100);
        
        if ($posts->isEmpty()) {
            return response()->json(['message' => 'No voted posts found for the authenticated user'], 404);
        }

        return response()->json(['message' => 'Voted posts retrieved successfully', 'posts' => $posts], 200);
    }

    //A user should be able to see all the posts created by a specific user by using their username.
    public function postsByUsername($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        $posts = Post::where('user_id', $user->id)->with('user', 'comments', 'votes')->paginate(100);
        
        if ($posts->isEmpty()) {
            return response()->json(['message' => 'No posts found for the specified username'], 404);
        }

        return response()->json(['message' => 'Posts retrieved successfully', 'posts' => $posts], 200);
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $post->load('user', 'comments', 'votes');

        return response()->json(['message' => 'Post retrieved successfully', 'post' => $post], 200);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->update([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
        ]);

        return response()->json(['message' => 'Post updated successfully', 'post' => $post], 200);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}