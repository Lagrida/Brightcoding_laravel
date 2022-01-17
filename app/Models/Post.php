<?php

namespace App\Models;

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    /*public function comments(){
        return $this->hasMany(Comment::class)->orderByDesc(static::CREATED_AT);
    }*/
    /**
     * Get all of the post's comments.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->orderByDesc(static::CREATED_AT);
    }
    public function tags(){
        return $this->belongsToMany(Tag::class)->withTimestamps()->as('pivot_tag')->orderByDesc(static::CREATED_AT);
    }
    /*public function image(){
        return $this->hasOne(Image::class);
    }*/
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public static function boot(){
        parent::boot();
        /*
        static::deleting(function(Post $post){
            if($post->isForceDeleting()){
                $post->comments()->forceDelete();
            }else{
                $post->comments()->delete();
            }
        });
        static::restoring(function(Post $post){
            $post->comments()->restore();
        });
        */
    }
}
