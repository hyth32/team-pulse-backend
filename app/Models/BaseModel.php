<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class BaseModel extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();

            $model->{$model->getCreatedAtColumn()} = now();

            $model->{$model->getUpdatedAtColumn()} = now();
        });

        static::updating(function ($model) {
            $model->{$model->getUpdatedAtColumn()} = now();
        });
    }
}
