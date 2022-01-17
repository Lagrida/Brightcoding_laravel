@extends('layouts.app')
@section('title') | {{ $post->title }} @endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-sm-12">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="margin: 0; padding: 0;">{{ $post->title }} 
                    @if($post->deleted_at != null)
                    <span class="badge bg-secondary">Deleted</span>
                    @endif
                    </h1> 
                    <span class="badge bg-info text-dark">{{ $post->created_at->diffForHumans() }}</span><br />
                    @foreach ($post->tags as $tag)
                    <a href="{{ route('tags.show', ['tag' => $tag->id]) }}"><span class="badge bg-dark text-white">#{{ $tag->name }}</span></a>
                    @endforeach
                    @if($post->tags->count() > 0)
                    <br />
                    @endif
                    <span>{{ __('site.by') }} : {{ $post->user->name }}</span>
                </div>
                <div>
                    @auth
                    @can('update', $post)
                    <a href="{{ route('posts.edit', ['post' => $post->id]) }}"><button type="button" class="btn btn-success"><i class="fas fa-pencil-alt"></i></button></a> 
                    @endcan
                    @if($post->deleted_at == null)
                    @can('delete', $post)
                    <form class="d-inline-block" method="post" action="{{ route('posts.destroy', ['post' => $post->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                    </form>
                    @endcan
                    @else
                    <form class="d-inline-block" method="post" action="{{ route('posts.restore', ['post' => $post->id]) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary"><i class="fas fa-trash-restore"></i></button>
                    </form>
                    
                    <form class="d-inline-block" method="post" action="{{ route('posts.force_destroy', ['post' => $post->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="background: #CC0000"><i class="fas fa-trash"></i></button>
                    </form>
                    @endif
                    @endauth
                </div>
            </div>
            <hr style="margin: 8px 0px" />
            <div>
                @if($post->image)
                <div class="text-center"><img class="img-fluid rounded" src="{{ $post->image->url() ?? '' }}" /></div>
                @endif
                {{ $post->content }}
            </div>
            <hr />
            <div>
                <h3>{{ __('site.show_comments') }}</h3>
                @auth
                @if ($errors->any())
                <div class="alert alert-danger">
                    Errors, please fix them.
                </div>
                @endif
                <form method="post" action="{{ route('posts.add_comment', ['post' => $post->id]) }}">
                    @csrf
                    <input type="hidden" value="{{ $post->id }}" name="post_id" />
                    <textarea name="content" placeholder="Enter content" id="content" rows="3" @class([
                        'form-control',
                        'is-invalid' => $errors->has('content')
                    ])>{{ old('content') }}</textarea>
                    @error('content')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <div class="d-grid gap-2 my-2">
                        <button class="btn btn-primary" type="submit">{{ __('site.submit') }}</button>
                    </div>
                </form>
                <div class="my-2"></div>
                @endauth
                @forelse ($post->comments as $comment)
                <div class="my-3 p-3" style="border: 1px dotted #333">
                    {{ $comment->content }}, {{ __('site.by') }} {{ $comment->user->name }}
                </div>
                @empty
                <div class="text-center my-2">No Comments !</div>
                @endforelse
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            @include('layouts.right-bar')
        </div>
    </div>
</div>
@endsection