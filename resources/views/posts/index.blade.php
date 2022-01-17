@extends('layouts.app')
@section('title') | {{ __('site.posts') }} @endsection
@section('content')
<div class="container">
    <div class="row">
        @if (session('status'))
        <div class="col">
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        </div>
    @endif
    </div>
    <div class="row">
        <div class="col-md-8 col-sm-12">
            @auth
            <div>
                <a href="{{ route('posts.create') }}">
                    <button type="button" @class([
                        'btn',
                        'btn-primary'
                    ])>
                    <i class="fas fa-plus"></i> 
                    {{ __('site.add_posts') }}
                    </button>
                </a>
            </div>
            @endauth
            @forelse ($posts as $post)
                <div @class([
                    "p-2",
                    "my-2",
                    "border",
                    "rounded",
                    "trashed-post" => $post->deleted_at != null
                ])>
                    <div @class([
                        "d-flex",
                        "justify-content-between",
                        "align-items-center"
                    ])>
                        <div>
                            <h2><a href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a></h2>
                            <span class="badge bg-info text-dark">{{ $post->created_at->diffForHumans() }}</span><br />
                            <span class="badge bg-warning text-dark">{{ trans_choice('site.comments', $post->comments_count, ['count' => $post->comments_count]) }}</span><br />
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
                            <a title="Edit" href="{{ route('posts.edit', ['post' => $post->id]) }}"><button type="button" class="btn btn-success"><i class="fas fa-pencil-alt"></i></button></a> 
                            @endcan
                            @if($post->deleted_at == null)
                            @can('delete', $post)
                            <form class="d-inline-block" method="post" action="{{ route('posts.destroy', ['post' => $post->id]) }}">
                                @csrf
                                @method('DELETE')
                                <button title="Delete" type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
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
                </div>
            @empty
            <div class="p-3 mb-2 bg-danger text-white bg-gradient border border-danger rounded font-weight-bold">
                There are no posts !
            </div>
            @endforelse
        </div>
        <div class="col-md-4 col-sm-12">
            @include('layouts.right-bar')
        </div>
    </div>
</div>
@endsection