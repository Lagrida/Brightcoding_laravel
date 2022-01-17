@extends('layouts.app')
@section('title') | {{ $tag->name }} @endsection
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
                    <h1 style="margin: 0; padding: 0;">#{{ $tag->name }}</h1> 
                    <span class="badge bg-info text-dark">{{ $tag->created_at->diffForHumans() }}</span><br />
                    <span class="badge bg-warning text-dark">{{ trans_choice('site.posts_many', $tag->posts_count, ['count' => $tag->posts_count]) }}</span>
                </div>
                <div>
                    <a href="{{ route('tags.edit', ['tag' => $tag->id]) }}"><button type="button" class="btn btn-success"><i class="fas fa-pencil-alt"></i></button></a> 
                    <form class="d-inline-block" method="post" action="{{ route('tags.destroy', ['tag' => $tag->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                    </form>
                </div>
            </div>
            <hr style="margin: 8px 0px" />
            <div>
                @forelse ($tag->posts as $post)
                <h4><a href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a></h4>
                @empty
                <div class="p-3 mb-2 bg-danger text-white bg-gradient border border-danger rounded font-weight-bold">
                    There are no posts !
                </div>
                @endforelse
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            @include('layouts.right-bar')
        </div>
    </div>
</div>
@endsection