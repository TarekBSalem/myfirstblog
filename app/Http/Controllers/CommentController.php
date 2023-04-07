<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{


    public function store(CommentRequest $request,$id)
{

  Comment::create([
        'id' => $request->id,
        'content' => $request->content,
        'user_id' =>  Auth::user()->id,
        'post_id' => $id
    ]);

    return back();
}

public function edit(Comment $comment){
        return view('admin.comments.edit', compact('comment'));
    }
    public function update(Request $request, Comment $comment){
        $request->validate([
            'content' => 'required'
        ]);
        $comment = Comment::findorFail($request->id);

        $comment->update([
            'content' => $request->content
        ]);
        return redirect()->route('admin.comments.index');
    }
    public function delete(Comment $comment){
        $comment->delete();
        return back();
    }
}
