<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $fillable = [
        'user_id',
        'marital_status',
        'gender',
        'date_of_birth',
        'emp_code',
        'position',
        'father_name',
        'email_slug',
        'token',
    ];
}
