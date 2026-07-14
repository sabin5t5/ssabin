<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SiteConfig extends Model
{
    protected  $table = 'site_configs';
    protected $fillable = ['config_keys', 'config_values'];
}
