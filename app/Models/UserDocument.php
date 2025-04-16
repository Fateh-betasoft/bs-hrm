<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    protected $fillable = [
        'document_type',
        'file_name',
    ];
}
