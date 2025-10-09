<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductCategoryService extends BaseRepository implements ServiceInterface
{
    CONST IMAGE_BASE_PATH = "uploads/product_categories/image/";

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
            Storage::disk('public')->putFileAs(self::IMAGE_BASE_PATH, $image, $filename);
        }

        if (!empty($this->dirtyValues['image_name'])
            && !empty($this->dirtyValues['image_extension'])
            && !$model->wasRecentlyCreated
        ) {
            $oldFilename = $this->oldValues['image_name'] . '.' . $this->oldValues['image_extension'];
            if (in_array('image_name', $this->dirtyKeyValues)
                && in_array('image_extension', $this->dirtyKeyValues)
                && Storage::disk('public')->exists(self::IMAGE_BASE_PATH . $oldFilename)
            ) {
                Storage::disk('public')->delete(self::IMAGE_BASE_PATH . $oldFilename);
            }
        }
    }

    public function getDTList($params)
    {
        $params = $this->getDTParams($params);
        $select = Arr::pluck($this->userDTColumns(), 'field');
        $alias = Arr::pluck($this->userDTColumns(), 'alias');

        $qb = DB::table('product_categories')
            ->select($select)
            ->whereNull('deleted_at');

        if (!empty($params['search_key'])) {
            $qb->where('product_categories.name', 'LIKE', $params['search_key'] . '%');
        }

        $qb->orderBy('product_categories.created_at', 'DESC');

        $result = $qb->paginate($params['limit'], ['*'], 'page', $params['page_num']);

        return [$result->items(), $result->total()];
    }

    private function userDTColumns()
    {
        return [
            [
                'field' => 'product_categories.id',
                'alias' => 'id',
            ],
            [
                'field' => 'product_categories.name',
                'alias' => 'name',
            ],
            [
                'field' => 'product_categories.description',
                'alias' => 'description',
            ],
            [
                'field' => 'product_categories.created_at',
                'alias' => 'created_at',
            ],
        ];
    }
}
