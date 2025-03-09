<?php

namespace App\Http\Services;

use App\Http\Requests\Group\CreateGroupRequest;
use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;

class GroupService
{
    public static function list() {}

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
