<?php

namespace App\Http\Services;

class BaseService
{
    public static function paginateQuery($query, $request)
    {
        return [
            'total' => $query->count(),
            'items' => $query
                ->offset($request['offset'])
                ->limit($request['limit']),
        ];
    }
}
