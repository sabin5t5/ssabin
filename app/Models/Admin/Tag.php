<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['user_id', 'name', 'slug' ];

	/**
	 * get the words associated with the given tag.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */

	public function blogs()
	{
		return $this->belongsToMany('App\Models\Admin\Blogs', 'tag_blogs', 'tag_id', 'blogs_id');
	}

	public function User()
    {
        return $this->belongsTo('App\Models\User');
    }
}
