<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    use HasUuids;

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
}
