<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductCategoryService extends BaseRepository implements ServiceInterface
{

    public function createRecord($data, $model)
    {
        $data['created_by'] = $this->user->id;
        $data['updated_by'] = $this->user->id;

        return $this->save($data, $model);
    }

    public function updateRecord($data, $model)
    {
        $data['updated_by'] = $this->user->id;

        return $this->save($data, $model);
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
