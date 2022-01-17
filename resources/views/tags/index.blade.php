@extends('layouts.app')
@section('title') | {{ __('site.tags') }} @endsection
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
            @can('create', \App\Models\Tag::class)
            <div>
                <a href="{{ route('tags.create') }}">
                    <button type="button" @class([
                        'btn',
                        'btn-primary'
                    ])>
                    <i class="fas fa-plus"></i> 
                    {{ __('site.add_tags') }}
                    </button>
                </a>
            </div>
            @endcan
            @endauth
            @forelse ($tags as $tag)
                <div @class([
                    "p-2",
                    "my-2",
                    "border",
                    "rounded"
                ])>
                    <div @class([
                        "d-flex",
                        "justify-content-between",
                        "align-items-center"
                    ])>
                        <div>
                            <h2><a href="{{ route('tags.show', ['tag' => $tag->id]) }}">#{{ $tag->name }}</a></h2>
                            <span class="badge bg-info text-dark">{{ $tag->created_at->diffForHumans() }}</span><br />
                            <span class="badge bg-warning text-dark">{{ trans_choice('site.posts_many', $tag->posts_count, ['count' => $tag->posts_count]) }}</span>
                        </div>
                        <div>
                            <a title="Edit" href="{{ route('tags.edit', ['tag' => $tag->id]) }}"><button type="button" class="btn btn-success"><i class="fas fa-pencil-alt"></i></button></a> 
                            <form class="d-inline-block" method="post" action="{{ route('tags.destroy', ['tag' => $tag->id]) }}">
                                @csrf
                                @method('DELETE')
                                <button title="Delete" type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
            <div class="p-3 mb-2 bg-danger text-white bg-gradient border border-danger rounded font-weight-bold">
                There are no tags !
            </div>
            @endforelse
        </div>
        <div class="col-md-4 col-sm-12">
            @include('layouts.right-bar')
        </div>
    </div>
</div>
@endsection