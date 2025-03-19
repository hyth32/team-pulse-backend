<?php

namespace App\Http\Services;

use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Group\CreateGroupRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
use App\Http\Resources\GroupShortResource;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupService extends BaseService
{
    /**
     * Получение списка групп
     * @param BaseListRequest $request
     */
    public static function list(BaseListRequest $request)
    {
        $query = Group::query();
        $result = self::paginateQuery($query, $request);

        return [
            'total' => $result['total'],
            'groups' => GroupShortResource::collection($result['items']->get()),
        ];
    }

    /**
     * Сохранение группы
     * @param CreateGroupRequest $request
     */
    public function save(CreateGroupRequest $request)
    {
        $data = $request->validated();
        $group = Group::firstOrCreate(['name' => $data['name']]);

        if (isset($data['employees']) && filled($data['employees'])) {
            $group->users()->sync($data['employees']);
        }

        return ['message' => 'Группа создана'];
    }

    /**
     * Обновление группы
     * @param UpdateGroupRequest $request
     */
    public function update(string $uuid, UpdateGroupRequest $request)
    {
        Group::findOrFail($uuid)
            ->update($request->validated());

        return ['message' => 'Группа обновлена'];
    }

    /**
     * Удаление группы
     * @param string $uuid
     * @param Request $request
     */
    public function delete(string $uuid, Request $request)
    {
        $group = Group::findOrFail($uuid);
        if ($group->users()->exists()) {
            $message = 'Невозможно удалить группу с активными сотрудниками.';
        } else {
            $group->delete();
            $message = 'Группа удалена';
        }

        return ['message' => $message];
    }
}
