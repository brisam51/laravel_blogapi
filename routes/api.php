<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;





// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
//public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/me', [AuthController::class, 'me']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
Route::post('/send-verification-email', [AuthController::class, 'sendVerificationEmail']);
//protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('/send-verification-email', [AuthController::class, 'sendVerificationEmail']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/update-image', [AuthController::class, 'updateImage']);
    Route::post('/delete-account', [AuthController::class, 'deleteAccount']);
    Route::post('/delete-image', [AuthController::class, 'deleteImage']);
    Route::post('/delete-account', [AuthController::class, 'deleteAccount']);
    Route::post('/delete-image', [AuthController::class, 'deleteImage']);
    Route::post('/delete-account', [AuthController::class, 'deleteAccount']);
    Route::post('/delete-image', [AuthController::class, 'deleteImage']);
    // user info
    Route::get('/users', [AuthController::class, 'userInfo']);
     Route::get('/users', [AuthController::class, 'update']);
    //post Route
    Route::apiResource('posts', PostController::class);
    //comment route
    //Route::apiResource('comments', CommentController::class);
    Route::get('/posts/{post_id}/comments', [CommentController::class, 'index']);//get comments for a post
    Route::post('/posts/{post_id}/comments', [CommentController::class, 'store']);//store a comment for a post
    Route::put('/comments/{comment_id}', [CommentController::class, 'update']);//update a comment
    Route::delete('/comments/{comment_id}', [CommentController::class, 'destroy']);//delete a comment
    //like Rout
    Route::post('/posts/{post_id}/like',[LikeController::class, 'likedOrDisliked']);
});
