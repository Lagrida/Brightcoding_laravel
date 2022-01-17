<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Policies\PostPolicy;
use App\Policies\TagPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Post::class => PostPolicy::class,
        Tag::class => TagPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        /*Gate::define('post.update', function (User $user, Post $post) {
            //return $user->id === $post->user_id;
            //return Response::deny('You must be an administrator.');
            return $user->id === $post->user_id
            ? Response::allow()
            : Response::deny('You do not own this post.');
            //return false;
        });*/
        Gate::before(function(User $user, $ability){
            if($user->is_admin){
                return true;
            }
        });
    }
}
