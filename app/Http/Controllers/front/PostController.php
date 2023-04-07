<?php

namespace App\Http\Controllers\front;

use App\Models\Post;
use App\Models\Comment;

use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user', 'comments')->latest()->get();
        return view('front.posts.index', compact('posts'));
    }

    public function show(Post $post)
    {

        $comments = Comment::where('post_id', $post->id)->with('user')
        ->latest()
        ->get();
        return view('front.posts.post', compact('post'),compact('comments'));
    }
}
