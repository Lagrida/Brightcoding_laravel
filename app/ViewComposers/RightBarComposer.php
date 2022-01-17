<?php

namespace App\ViewComposers;

use App\Models\Post;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class RightBarComposer
{
    public function compose(View $view)
    {
        //$postsMostCommented = Post::withCount('comments')->with(['user'])->orderByDesc('comments_count')->take(5)->get();
        $postsMostCommented = Cache::remember('postsMostCommented', now()->addMinutes(1), function(){
            return Post::withCount('comments')->with(['user'])->orderByDesc('comments_count')->take(5)->get();
        });
        //$usersActiveLastMonth = User::withCount(['posts' => function ($query) {$query->whereBetween('created_at', [now()->subMonths(1), now()]);}])->orderByDesc('posts_count')->take(5)->get();
        $usersActiveLastMonth = Cache::remember('usersActiveLastMonth', now()->addMinutes(1), function(){
            return User::withCount(['posts' => function ($query) {$query->whereBetween('created_at', [now()->subMonths(1), now()]);}])->orderByDesc('posts_count')->take(5)->get();
        });
        $view->with([
            'postsMostCommented' => $postsMostCommented,
            'usersActiveLastMonth' => $usersActiveLastMonth
        ]);
    }
}