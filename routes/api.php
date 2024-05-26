<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [App\Http\Controllers\Api\Auth\RegisterController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\Auth\LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () 
{
    Route::get('posts', [App\Http\Controllers\Api\PostController::class, 'index']);
    Route::post('posts/create', [App\Http\Controllers\Api\PostController::class, 'store']);
    Route::put('posts/update/{id}', [App\Http\Controllers\Api\PostController::class, 'update']);
    Route::delete('posts/delete/{id}', [App\Http\Controllers\Api\PostController::class, 'destroy']);
    Route::get('posts/voted-posts', [App\Http\Controllers\Api\PostController::class, 'postByVoted']);
    Route::get('user/{username}/posts', [App\Http\Controllers\Api\PostController::class, 'postsByUsername']);
    Route::get('posts/{id}', [App\Http\Controllers\Api\PostController::class, 'show']);

    Route::post('posts/{post}/upvote', [App\Http\Controllers\Api\VoteController::class, 'upvote']);
    Route::post('posts/{post}/downvote', [App\Http\Controllers\Api\VoteController::class, 'downvote']);

    Route::post('posts/comments/{comment}/upvoteComment', [App\Http\Controllers\Api\VoteController::class, 'upvotePostComment']);
    Route::post('posts/comments/{comment}/downvoteComment', [App\Http\Controllers\Api\VoteController::class, 'downvotePostComment']);


    Route::post('posts/{post}/comments', [App\Http\Controllers\Api\CommentController::class, 'store']);

    Route::get('posts/{postId}/view', [App\Http\Controllers\Api\PostController::class, 'viewPostWithComments']);


    //i remove due to lack of time to test
    // Route::put('comments/{comment}', [App\Http\Controllers\Api\CommentController::class, 'update']);
    // Route::put('comments/{parentComment}/replies/{reply}', [App\Http\Controllers\Api\CommentController::class, 'updateReply']);
    // Route::delete('comments/{comment}', [App\Http\Controllers\Api\CommentController::class, 'destroy']);
    // Route::delete('comments/replies/{parentComment}', [App\Http\Controllers\Api\CommentController::class, 'destroyReply']);
});