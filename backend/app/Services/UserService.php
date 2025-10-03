<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserService extends BaseRepository implements ServiceInterface
{
    CONST PROFILE_IMAGE_BASE_PATH = "uploads/users/profile_image/";

    public function createRecord($data, $model)
    {
        $data['created_by'] = $this->user->id;
        $data['updated_by'] = $this->user->id;

        $this->getImageParams($data);

        return $this->save($data, $model);
    }

    public function updateRecord($data, $model)
    {
        $data['updated_by'] = $this->user->id;

        $this->getImageParams($data);

        return $this->save($data, $model);
    }

    public function afterSave($data, $model)
    {
        $image = !empty($data['image']) ? $data['image'] : null;
        if (!empty($image) && $image instanceof UploadedFile && $image->isValid()) {
            $filename = $data['image_name'] . '.' . $data['image_extension'];
            Storage::disk('public')->putFileAs(self::PROFILE_IMAGE_BASE_PATH, $image, $filename);
        }

        if (!empty($this->dirtyValues['image_name']) && !empty($this->dirtyValues['image_extension'])) {
            $oldFilename = $this->dirtyValues['image_name'] . '.' . $this->dirtyValues['image_extension'];
            if (!$model->wasRecentlyCreated
                && in_array('image_name', $this->dirtyKeyValues)
                && in_array('image_extension', $this->dirtyKeyValues)
                && Storage::disk('public')->exists(self::PROFILE_IMAGE_BASE_PATH . $oldFilename)
            ) {
                Storage::disk('public')->delete(self::PROFILE_IMAGE_BASE_PATH . $oldFilename);
            }
        }
    }

    public function getDTList($params)
    {
        $params = $this->getDTParams($params);
        $select = Arr::pluck($this->userDTColumns(), 'field');
        $alias = Arr::pluck($this->userDTColumns(), 'alias');

        $qb = DB::table('users')
            ->select($select)
            ->where('id', '!=', $this->user->id)
            ->whereNull('deleted_at');

        if (!empty($params['search_key'])) {
            $qb->where(function ($query) use ($params){
                $query->where('users.first_name', 'LIKE', $params['search_key'] . '%');
                $query->orWhere('users.last_name', 'LIKE', $params['search_key'] . '%');
            });
        }

        $qb->orderBy('users.created_at', 'DESC');

        $result = $qb->paginate($params['limit'], ['*'], 'page', $params['page_num']);

        return [$result->items(), $result->total()];
    }

    private function userDTColumns()
    {
        return [
            [
                'field' => 'users.id',
                'alias' => 'id',
            ],
            [
                'field' => DB::raw('CONCAT_WS(" ", users.first_name, users.middle_name, users.last_name) AS name'),
                'alias' => 'name',
            ],
            [
                'field' => 'users.email',
                'alias' => 'email',
            ],
            [
                'field' => 'users.status',
                'alias' => 'status',
            ],
            [
                'field' => 'users.role',
                'alias' => 'role',
            ],
            [
                'field' => 'users.created_at',
                'alias' => 'created_at',
            ],
        ];
    }
}
