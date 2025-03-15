<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function getTable()
    {
        if (!empty($this->table)) {
            return $this->table;
        }

        $reflection = new ReflectionClass($this);
        $className = $reflection->getShortName();

        return Str::snake(Str::plural($className));
    }

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
