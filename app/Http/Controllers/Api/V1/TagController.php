<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\ListTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Http\Services\TagService;

class TagController extends Controller
{
    public function list(ListTagRequest $request) {
        return TagService::list($request);
    }

    public function create(CreateTagRequest $request) {
        return (new TagService)->save($request);
    }

    public function update(string $uuid, UpdateTagRequest $request) {
        return (new TagService)->update($uuid, $request);
    }

    public function delete(string $uuid) {
        return (new TagService)->delete($uuid);
    }
}
