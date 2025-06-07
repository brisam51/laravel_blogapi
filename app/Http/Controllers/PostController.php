<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::orderBy('created_at', 'desc')->with('user:id,name,image')->withCount('comments', 'likes')
            ->with('likes', function ($query) {
                $query->select('id', 'post_id', 'user_id')->with('user:id,name,image');
            })
            ->with('comments', function ($query) {
                $query->select('id', 'post_id', 'user_id', 'comment', 'created_at')->with('user:id,name,image');
            })
            ->get();
            if ($posts->isEmpty()) {
                return response()->json([
                    'message' => 'No posts found',
                ], 404);
            }

            return response()->json([
                'message' => 'Posts fetched successfully',
                'posts' => $posts
              
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching posts',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //show post
    public function show($id)
    {
        try {
            $post = Post::where('id', $id)->with('user:id,name,image')->withCount('comments', 'likes')->first();
            if (!$post) {
                return response()->json([
                    'message' => 'Post not found',
                ], 404);
            }
            return response()->json([
                'message' => 'Post fetched successfully',
                'post' => $post,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //create post
    public function store(Request $request)
    {
        try {
            $request->validate([
                'body' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $image = $this->saveImage($request->image, 'posts');
            $post = new Post();
            $post->user_id = Auth::id();
            $post->body = $request->body;
            $post->image = $image;
            $post->save();
            return response()->json([
                'message' => 'Post created successfully',
                'post' => $post,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //update post
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'body' => 'required|string|max:255',

            ]);
            $post = Post::find($id);
            if (!$post) {
                return response()->json([
                    'message' => 'Post not found',
                ], 404);
            }
            if ($post->user_id !== Auth::id()) {
                return response()->json([
                    'message' => 'You are not authorized to update this post',
                ], 403);
            }
            $post->update($request->all());
            return response()->json([
                'message' => 'Post updated successfully',
                'post' => $post,
            ], 200);
        } catch (Exception $e) {
            return response()->json([

                'message' => 'Error updating post',
                'error' => $e->getMessage(),
            ], 500);
        }
    } //end of update
    //delete post
    public function destroy($id)
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return response()->json([
                    'message' => 'Post not found',
                ], 404);
            }
            if (!$post->user_id !== Auth::id()) {
                return response()->json([
                    'message' => 'You are not authorized to delete this post',
                ], 403);
            }
            $post->comments()->delete();
            $post->likes()->delete();
            $post->delete();
            return response()->json([
                'message' => 'Post deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error deleting post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
