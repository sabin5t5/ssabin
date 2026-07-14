<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blogs extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','user_id','title', 'description', 'image','blog_category', 'published_at','slug','status','is_highlight','archive_at'
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'deleted_at' => 'datetime',
            'archive_at' => 'datetime',
        ];
    }

    /**
     * News belongs to one NewsCategory
     * @return relation many to one
     */
    public function blogCategory()
    {
        return $this->belongsTo('App\Models\Admin\BlogCategory', 'blog_category', 'id');
    }

    /**
     * News belongs to one User
     * @return relation many to one
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * news belogs to many tags
     * @return relation many to many
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Admin\Tag', 'tag_blogs', 'blog_id', 'tag_id')->withTimestamps();
    }

    public function getReadTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->description));

        return max(1, ceil($wordCount / 200));
    }
}
