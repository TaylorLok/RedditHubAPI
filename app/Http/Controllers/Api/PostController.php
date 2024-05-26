<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $posts = Post::where('user_id', $user->id)
                        ->with('user', 'comments', 'votes')
                        ->paginate(100);

            if ($posts->isEmpty()) {
                return response()->json(['message' => 'No posts found for the authenticated user'], 404);
            }

            return response()->json(['message' => 'Posts retrieved successfully', 'posts' => $posts], 200); 
        } 
        catch (ModelNotFoundException $e) 
        {
            return response()->json(['message' => 'No posts found for the authenticated user'], 404);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::create([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'user_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Post created successfully', 'post' => $post], 201);
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
        try 
        {
            $user = User::where('name', $username)->firstOrFail();
        } 
        catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Post with username ' . $username . ' not found'], 404);
        }

        $posts = Post::where('user_id', $user->id)->with('user', 'comments', 'votes')->paginate(100);
        
        if ($posts->isEmpty()) {
            return response()->json(['message' => 'No posts found for the specified username'], 404);
        }

        return response()->json(['message' => 'Posts retrieved successfully', 'posts' => $posts], 200);
    }

    public function show($id)
    {
        try 
        {
            $post = Post::findOrFail($id);
        } 
        catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Post with ID ' . $id . ' not found'], 404);
        }

        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $post->load('user', 'comments', 'votes');

        return response()->json(['message' => 'Post retrieved successfully', 'post' => $post], 200);
    }

    public function update(Request $request, $id)
    {
        try 
        {
            $post = Post::findOrFail($id);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Post with ID ' . $id . ' not found'], 404);
        }

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
        try 
        {
            $post = Post::findOrFail($id);
        } 
        catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Post with ID ' . $id . ' not found'], 404);
        }

        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}