<?php

namespace App\Models;

use App\Models\Traits\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends BaseModel
{
    use Translatable;

    const TYPE_DOG = 'dog';
    const TYPE_CAT = 'cat';
    const TYPE_RABBIT = 'rabbit';

    protected $fillable = [
        'owner_id',
        'date_of_birth',
        'name',
        'type',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    public static function typeMap()
    {
        return [
            self::TYPE_DOG => __('Dog'),
            self::TYPE_CAT => __('Cat'),
            self::TYPE_RABBIT => __('Rabbit'),
        ];
    }

    public static function getTranslatableColumns()
    {
        return [
            'name' => __('Name'),
        ];
    }
}
