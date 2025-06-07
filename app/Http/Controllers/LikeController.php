<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;

class LikeController extends Controller
{
    public function likedOrDisliked($post_id)
    {
        try {
            $post = Post::find($post_id);
            if (!$post) {
                return response()->json([
                    'message' => 'Post not found',
                ], 404);
            }
            $like = Like::where('post_id', $post_id)->where('user_id', Auth::id())->first();
            if (!$like) {
                $like = new Like();
                $like->post_id = $post_id;
                $like->user_id = Auth::id();
                $like->save();
                return response()->json([
                    'message' => 'Like stored successfully',
                ], 200);
            }
            //else dislike comment
            $like->delete();
            return response()->json([
                'message' => 'Dislike stored successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error processing like/dislike',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
                
           

           
        
   
}//end of class
