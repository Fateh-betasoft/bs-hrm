<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFamilyMember extends Model
{
    //fillables
    protected $fillable = [
        'user_id',
        'name',
        'relation',
        'age',
        'date_of_birth',
        'occupation',
        'dependency'
    ];
}
