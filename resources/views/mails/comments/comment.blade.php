<p>
    Your post : <a href="{{ route('posts.show', ['post' => $comment->commentable->id]) }}">{{ $comment->commentable->title }}</a> 
    is commented by : <a href="">{{ $comment->user->name }}</a>
</p>
<p style="background: #C3C3C3">
    {{ $comment->content }}
</p>