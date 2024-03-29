<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\PostRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();

        $posts = Post::where('user_id', $user_id)
                     ->latest()
                     ->get();
        return view('admin.posts.index', compact('posts'));
    }


        public function create()
        {
            return view('admin.posts.create');
        }
        public function store(PostRequest $request)
         {

            $avataName = '/uploads/'.time().'.'.$request->image->getClientOriginalExtension();
            Image::make($request->image)->save(public_path().$avataName, 60);

            $user_id = Auth::user()->id;

            Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => $user_id,
                'image' => $avataName
            ]);

            return redirect()->route('admin.posts.index');
        }
        public function edit( Post $post)
        {
            return view('admin.posts.edit',compact('post'));
        }


        public function update(PostRequest $request)
        {
            $post = Post::findorFail($request->id);

            $avataName = '/uploads/'.time().'.'.$request->image->getClientOriginalExtension();
            Image::make($request->image)->save(public_path().$avataName, 60);

            $post->update([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $avataName
            ]);
            return redirect()->route('admin.posts.index');
        }



        public function delete(Post $post)

        {
            $post = Post::find($post->id);
            $post->delete();
            File::delete(public_path($post->image));

            return redirect()->route('admin.posts.index');
        }

}
