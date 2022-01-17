<div class="mt-2">
    <div class="border rounded mb-2 p-2">
        <h2>{{ __('site.most_commented') }}</h2>
        <hr style="margin: 0" />
        @forelse ($postsMostCommented as $postMostCommented)
            <h5><a href="{{ route('posts.show', ['post' => $postMostCommented->id]) }}">{{ $postMostCommented->title }}</a></h5>
            <span class="badge bg-warning text-dark">{{ $postMostCommented->comments_count }} comments</span><br />
            {{ __('site.by') }} {{ $postMostCommented->user->name }}
            <hr style="margin: 0" />
        @empty
        <div class="p-3 text-center">
            There are no posts !
        </div>
        @endforelse
    </div>
    <div class="border rounded mb-2 p-2">
        <h2>{{ __('site.most_active_users') }}</h2>
        <hr style="margin: 0" />
        @forelse ($usersActiveLastMonth as $userActiveLastMonth)
            <h5><a href="">{{ $userActiveLastMonth->name }}</a></h5>
            <span class="badge bg-warning text-dark">{{ $userActiveLastMonth->posts_count }} posts</span>
            <hr style="margin: 0" />
        @empty
        <div class="p-3 text-center">
            There are no posts !
        </div>
        @endforelse
    </div>
</div>