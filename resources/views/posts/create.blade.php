@extends('layouts.app')
@section('title') | {{ __('site.add_posts') }} @endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center"><h3>{{ __('site.add_posts') }}</h3></div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        Errors, please fix them.
                        <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                    @endif
                    @include('posts.form', [
                        'tags' => $tags,
                        'action' => route('posts.store'),
                        'type' => 'add'
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection