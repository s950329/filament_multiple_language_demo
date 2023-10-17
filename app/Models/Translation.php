<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends BaseModel
{
    protected $fillable = [
        'translatable_type',
        'translatable_id',
        'locale',
        'column',
        'value'
    ];

    public function translatable()
    {
        return $this->morphTo();
    }
}
