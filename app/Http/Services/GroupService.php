<?php

namespace App\Http\Services;

use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Group\CreateGroupRequest;
use App\Http\Requests\Group\GroupCreate;
use App\Http\Requests\Group\GroupUpdate;
use App\Http\Resources\Group\GroupResource;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'groups' => GroupResource::collection($result['items']->get()),
        ];
    }

    /**
     * Сохранение группы
     * @param CreateGroupRequest $request
     */
    public function save(GroupCreate $request)
    {
        $data = $request->validated();

        try {
            DB::transaction(function () use ($data) {
                $group = Group::firstOrCreate(['name' => $data['name']]);
        
                if (isset($data['employeeIds']) && filled($data['employeeIds'])) {
                    $group->users()->sync($data['employeeIds']);
                }
            });

            return ['message' => 'Группа создана'];
        } catch (\Exception $e) {
            return ['message' => 'Произошла ошибка: ' . $e->getMessage()];
        }
    }

    /**
     * Обновление группы
     * @param GroupUpdate $request
     */
    public function update(string $uuid, GroupUpdate $request)
    {
        $data = $request->validated();

        try {
            DB::transaction(function () use ($uuid, $data) {
                $group = Group::findOrFail($uuid);
                
                if (isset($data['employeeIds']) && filled($data['employeeIds'])) {
                    $group->users()->sync($data['employeeIds']);
                    unset($data['employeeIds']);
                }
        
                $group->update($data);
            });
    
            return ['message' => 'Группа обновлена'];
        } catch (\Exception $e) {
            return ['message' => 'Произошла ошибка: ' . $e->getMessage()];
        }
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
