<?php

namespace App\Http\Controllers;

use App\Events\CommentedEvent;
use App\Http\Requests\PostRequest;
use App\Mail\Commented;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Facade\FlareClient\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$this->authorize('viewAny', Post::class);
        // Retrieve all posts

        //$posts = Post::withCount('comments')->with(['user'])->orderByDesc('created_at')->get();
        //$posts = Post::onlyTrashed()->withCount('comments')->with(['user'])->orderByDesc('created_at')->get();
        if(Auth::check() && $request->user()->is_admin){
            $posts = Post::withTrashed()->withCount(['comments'=> function ($query) {$query->withTrashed();}])->with(['user', 'tags'])->orderByDesc('created_at')->get();
        }else{
            $posts = Post::withCount('comments')->with(['user', 'tags'])->orderByDesc('created_at')->get();
        }
        //Debugbar::info($posts);

        //Debugbar::info($value);
        return View('posts.index', [
            'posts' => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Post::class);
        // Get tags
        $tags = Tag::orderByDesc('created_at')->get();
        //Debugbar::info($tags);
        return View('posts.create', [
            'tags' => $tags,
            'isActive' => 'create_post'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $this->authorize('create', Post::class);
        $validatedData = $request->validated();
        $validatedData['user_id'] = $request->user()->id;
        $tagsArr = $request->input('tags') ?? [];
        $tags = [];
        foreach($tagsArr as $tag){
            array_push($tags, $tag);
        }
        //dd($tags);
        $post = Post::create($validatedData);
        $post->tags()->sync($tags);
        $hasImageFile = $request->hasFile('image');
        if($hasImageFile){
            $path = $request->file('image')->store('posts');
            $image = new Image(['path' => $path]);
            $post->image()->save($image);
        }

        return redirect()->route('posts.show', ['post' => $post->id])->with('status', 'Post successfuly created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        if(Auth::check() && $request->user()->is_admin){
            $post = Post::withTrashed()->with(['user', 'comments', 'tags'])->findOrFail($id);
        }else{
            $post = Post::with(['user', 'comments', 'tags'])->findOrFail($id);
        }
        $this->authorize('view', $post);
        //Debugbar::info($post);
        return View('posts.show', [
            'post' => $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, int $id)
    {
        $post = Post::with(['user', 'tags'])->findOrFail($id);
        $this->authorize('update', $post);
        $tags = Tag::orderByDesc('created_at')->get();
        return View('posts.edit', [
            'tags' => $tags,
            'post' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, int $id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('update', $post);
        /*if(Gate::denies('post.update', $post)){
            abort(403);
        }*/
        $hasImageFile = $request->hasFile('image');
        if($hasImageFile){
            $path = $request->file('image')->store('posts');
            if($post->image){
                Storage::delete($post->image->path);
                $post->image->path = $path;
                $post->image->save();
            }else{
                $image = new Image(['path' => $path]);
                $post->image()->save($image);
            }
        }
        $validatedData = $request->validated();
        $tagsArr = $request->input('tags');
        $tags = [];
        foreach($tagsArr as $tag){
            array_push($tags, $tag);
        }
        //dd($tags);
        $post->fill($validatedData);
        $post->save();
        $post->tags()->sync($tags);

        return redirect()->route('posts.show', ['post' => $post->id])->with('status', 'Post successfuly edited!');
        //return redirect()->back()->with('status', 'Post successfuly edited!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('delete', $post);
        $post->delete();
        //return redirect()->route('posts.index')->with('status', 'Post successfuly deleted!');
        return redirect()->back()->with('status', 'Post successfuly deleted!');
    }
    public function restore(Request $request, $id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $post);
        $post->restore();
        //return redirect()->route('posts.index')->with('status', 'Post successfuly restored!');
        return redirect()->back()->with('status', 'Post successfuly restored!');
    }
    public function forceDestroy(Request $request, $id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $post);
        $post->forceDelete();
        //return redirect()->route('posts.index')->with('status', 'Post successfuly completely deleted!');
        return redirect()->back()->with('status', 'Post successfuly completely deleted!');
    }
    public function addComment(Request $request, int $id)
    {
        $post = Post::findOrFail($id);
        $validated = $request->validate([
            'content' => 'required|min:2'
        ]);
        $commentValues = new Comment([
            'content' => $validated['content'],
            'user_id' => Auth::id()
        ]);
        $comment = $post->comments()->save($commentValues);

        event(new CommentedEvent($comment));
        //Mail::to($post->user->email)->send(new Commented($comment));
        //Mail::to($post->user->email)->queue(new Commented($comment));
        //Mail::to($post->user->email)->later(now()->addMinutes(1), new Commented($comment));
        return redirect()->back()->with('status', 'Comment successfuly added!');
    }
}
