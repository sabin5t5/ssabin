<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'title',
        'gender',
        'dob',
        'language',
        'born_country',
        'mailing_address',
        'current_address',
        'country',
        'rank',
        'remarks',
        'icon',
        'email',
        'work_email',
        'social_link',
        'timezone',
        'website',
        'work_phone',
        'mobile',
        'home_phone',
        'image',
        'marital_status',
        'religion',
        'nid_ssn',
        'passport_number',
        'disability_status',
        'work_authorization',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
