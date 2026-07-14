<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ActivityLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'action', 'message', 'status','panel','panel_id'
    ];


    public static function makeActivity($message, $panel, $panel_id, $action)
    {
    	$data = [
    			'user_id'  => Auth::user()->id,
    			'action'   => $action,
                'message'  => $message,
                'panel'    => $panel,
    			'panel_id' => $panel_id,
    			'status'   => 1,
    	       ];
        return Parent::create($data);
    }
}
