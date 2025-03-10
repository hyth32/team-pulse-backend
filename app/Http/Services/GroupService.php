<?php

namespace App\Http\Services;

use App\Http\Requests\Group\CreateGroupRequest;
use App\Http\Requests\Group\ListGroupRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
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
    public static function list(ListGroupRequest $request)
    {
        $groups = Group::skip($request['offset'])->take($request['limit'])->get();
        return ['groups' => GroupShortResource::collection($groups)];
    }

    /**
     * Сохранение группы
     * @param CreateGroupRequest $request
     */
    public function save(CreateGroupRequest $request)
    {
        $data = $request->validated();

        $group = Group::firstOrCreate([
            'name' => $data['name'],
        ]);

//        if (isset($data['employees'])) {
//            foreach ($data['employees'] as $userData) {
//                $userId = $userData['id'];
//               $userExists = User::where(['id' => $userId])->exists();
//                if ($userExists) {
//                    UserGroup::firstOrCreate([
//                        'user_id' => $userId,
//                        'group_id' => $group->id,
//                    ]);
//                }
//            }
//        }

        return ['message' => 'Группа создана'];
    }

    /**
     * Обновление группы
     * @param UpdateGroupRequest $request
     */
    public function update(string $uuid, UpdateGroupRequest $request)
    {
        $data = $request->validated();

        $group = Group::findOrFail($uuid);
        $group->update($data);

        return $group;
    }

    /**
     * Удаление группы
     * @param string $uuid
     */
    public function delete(string $uuid)
    {
        $group = Group::findOrFail($uuid);
        if (count($group->users) > 0) {
            $message = 'Невозможно удалить группу с активными сотрудниками.';
        } else {
            $group->delete();
            $message = 'Группа удалена';
        }

        return ['message' => $message];
    }
}
