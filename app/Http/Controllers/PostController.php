<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    //adding post
    public function addNewPost(Request $request){
        $validated=validator($request->all(),[
            'title'=>'required|string',
            'content'=>'required|string',
        ]);
        if($validated->fails()){
            return response()->json($validated->errors(),403);
        };

        try {
            $post=new Post();
            $post->title=$request->title;
            $post->content=$request->content;
            $post->user_id=auth()->user()->id;
            $post->save();

            return response()->json([
                'message'=>'Post added Successfully',
            ]);
        } catch (\Exception $th) {
            return response()->json([
               'message'=>'Error Occurred',
                'error'=>$th->getMessage()
            ],500);
        }
    }

    //edit post first approach
    public function editPost(Request $request){
        $validated=validator($request->all(),[
            'title'=>'required|string',
            'content'=>'required|string',
            'post_id'=>'required|integer'
        ]);
        if($validated->fails()){
            return response()->json($validated->errors(),403);
        };

        try {
            $post_data=Post::find($request->post_id);
            $updated_post=$post_data->update([
                'title'=>$request->title,
                'content'=>$request->content,
            ]);

            return response()->json([
                'message'=>'Post updated Successfully',
            ]);
        } catch (\Exception $th) {
            return response()->json([
               'message'=>'Error Occurred',
                'error'=>$th->getMessage()
            ],status: 403);
        }
    }

    //edit post approach 2
    public function editPost2(Request $request, Post $post_id){
        $validated=validator($request->all(),[
            'title'=>'required|string',
            'content'=>'required|string',
        ]);
        if($validated->fails()){
            return response()->json($validated->errors(),403);
        };

        try {
            $post_data=Post::find($post_id);
            $updated_post=$post_data->update([
                'title'=>$request->title,
                'content'=>$request->content,
            ]);

            return response()->json([
                'message'=>'Post updated Successfully',
            ]);
        } catch (\Exception $th) {
            return response()->json([
               'message'=>'Error Occurred',
                'error'=>$th->getMessage()
            ],status: 403);
        }
    }

    //retrieve all posts
    public function getAllPosts(){
        try {
            $posts=Post::all();
            return response()->json([
                'posts'=>$posts,
            ],200);
        } catch (\Exception $th) {
            return response()->json([
               'message'=>'Error Occurred',
                'error'=>$th->getMessage()
            ],403);
        }
    }

    //fetch single post
    public function getSinglePost($post_id){
        try {
            $post=Post::with('user')->find($post_id);
            // Or $post=Post::where('id',$post_id)->first();
            return response()->json([
                'post'=>$post,
            ],200);
        } catch (\Exception $th) {
            return response()->json([
               'message'=>'Error Occurred',
                'error'=>$th->getMessage()
            ],403);
        }
    }

    //delete post
    public function deletePost(Request $request, $post_id){
        // $validated=validator($request->all(),[
        //     'post_id'=>'required|integer'
        // ]);
        // if($validated->fails()){
        //     return response()->json($validated->errors(),403);
        // };

        try {
            $post=Post::find($post_id);
            $post->delete();
            return response()->json([
               'message'=>'Post deleted Successfully',
            ],200);
        } catch (\Exception $th) {
            return response()->json([
               'message'=>'Error Occurred',
                'error'=>$th->getMessage()
            ],403);
        }
    }

}
