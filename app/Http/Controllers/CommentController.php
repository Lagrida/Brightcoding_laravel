<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|min:2'
        ]);
        $post_id = $request->input('post_id') ?? 0;
        $post = Post::findOrFail($post_id);
        $user_id = Auth::id();



        $comment = $post->comments()->create([
            'content' => $request->content,
            'post_id' => $post_id,
            'user_id' => $user_id
        ]);
        return redirect()->back();
    }
}
