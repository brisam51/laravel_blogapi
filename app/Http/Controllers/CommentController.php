<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index($post_id)
    {
        try {
            $comments = Comment::where('post_id', $post_id)->orderBy('created_at', 'desc')->with('user:id,name,image')->get();
            if (!$comments) {
                return response()->json([
                    'message' => 'No comments found',
                ], 404);
            }
            return response()->json([
                'message' => 'Comments fetched successfully',
                'comments' => $comments,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error fetching comments',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    //store comment
    public function store(Request $request, $post_id)
    {
        try {
            $request->validate([
                'comment' => 'required|string|max:255',
            ]);
            $post = Post::find($post_id);
            if (!$post) {
                return response()->json([
                    'message' => 'Post not found',
                ], 404);
            }
            $comment = new Comment();
            $comment->user_id = Auth::id();
            $comment->post_id = $post->id;
            $comment->comment = $request->comment;
            $comment->save();
            return response()->json([
                'message' => 'Comment stored successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error storing comment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}//end of class
