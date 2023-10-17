<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends BaseModel
{
    protected $fillable = [
        'date_of_birth',
        'name',
        'owner_id',
        'type',
    ];
}
