<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::withCount('posts')->latest()->get();
        return View('tags.index', [
            'tags' => $tags
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Tag::class);
        return View('tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagRequest $request)
    {
        $this->authorize('create', Tag::class);
        $validatedData = $request->validated();
        $tag = Tag::create($validatedData);
        return redirect()->route('tags.show', ['tag' => $tag->id])->with('status', 'Tag successfuly created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        $tag = Tag::withCount('posts')->with('posts')->findOrFail($id);
        $this->authorize('view', $tag);
        return View('tags.show', [
            'tag' => $tag
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, int $id)
    {
        $tag = Tag::findOrFail($id);
        $this->authorize('update', $tag);
        return View('tags.edit', [
            'tag' => $tag
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(TagRequest $request, int $id)
    {
        $tag = Tag::findOrFail($id);
        $this->authorize('update', $tag);
        $validatedData = $request->validated();
        $tag->fill($validatedData);
        $tag->save();
        return redirect()->route('tags.show', ['tag' => $tag->id])->with('status', 'Tag successfuly edited!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        $tag = Tag::findOrFail($id);
        $this->authorize('delete', $tag);
        $tag->delete();
        return redirect()->route('tags.index')->with('status', 'Tag successfuly deleted!');
    }
}
