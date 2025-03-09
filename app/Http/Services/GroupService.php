<?php

namespace App\Http\Services;

use App\Http\Requests\Group\CreateGroupRequest;
use App\Http\Requests\ListGroupRequest;
use App\Http\Resources\GroupShortResource;
use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;

class GroupService
{
    /**
     * Получение списка групп
     * @param ListGroupRequest $request
     */
    public static function list(ListGroupRequest $request) {
        $groups = Group::skip($request['offset'])->take($request['limit'])->get();
        return ['groups' => GroupShortResource::collection($groups)];
    }

    /**
     * Сохранение группы
     * @param CreateGroupRequest $request
     */
    public function save(CreateGroupRequest $request) {
        $data = $request->validated();

        $group = Group::firstOrCreate([
            'name' => $data['name'],
        ]);

        if (isset($data['employees'])) {
            foreach ($data['employees'] as $userData) {
                $userId = $userData['id'];
                $userExists = User::where(['id' => $userId])->exists();
                if ($userExists) {
                    UserGroup::firstOrCreate([
                        'user_id' => $userId,
                        'group_id' => $group->id,
                    ]);
                }
            }
        }

        return $group;
    }

    public function update() {}

    public function delete() {}
}
