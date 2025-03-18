<?php

namespace App\Http\Services;

use Illuminate\Http\Request;

class BaseService
{
    public static function paginateQuery($query, Request $request)
    {
        return [
            'total' => $query->count(),
            'items' => $query
                ->offset($request['offset'])
                ->limit($request['limit']),
        ];
    }
}
