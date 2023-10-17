<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Owner extends BaseModel
{
    protected $fillable = [
        'email',
        'name',
        'phone',
    ];
}
