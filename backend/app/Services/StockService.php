<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class StockService extends BaseRepository implements ServiceInterface
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
        $select = array_merge(
            Arr::pluck($this->userDTColumns(), 'field'),
            ['product_stocks.unit', DB::raw('products.id AS product_id')]
        );
        $alias = Arr::pluck($this->userDTColumns(), 'alias');

        $qb = DB::table('product_stocks')
            ->select($select)
            ->leftJoin('products', 'product_stocks.product_id', '=', 'products.id')
            ->whereNull('product_stocks.deleted_at')
            ->whereNull('products.deleted_at');

        if (!empty($params['search_key'])) {
            $qb->where('products.name', 'LIKE', $params['search_key'] . '%');
        }

        $qb->orderBy('product_stocks.created_at', 'DESC');

        $result = $qb->paginate($params['limit'], ['*'], 'page', $params['page_num']);

        return [$result->items(), $result->total()];
    }

    private function userDTColumns()
    {
        return [
            [
                'field' => 'product_stocks.id',
                'alias' => 'id',
            ],
            [
                'field' => DB::raw('products.name AS product_name'),
                'alias' => 'product_name',
            ],
            [
                'field' => 'product_stocks.supplier_name',
                'alias' => 'supplier_name',
            ],
            [
                'field' => 'product_stocks.price',
                'alias' => 'price',
            ],
            [
                'field' => 'product_stocks.quantity',
                'alias' => 'quantity',
            ],
            [
                'field' => DB::raw('product_stocks.subtotal AS total'),
                'alias' => 'total',
            ],
            [
                'field' => 'product_stocks.stock_date',
                'alias' => 'stock_date',
            ],
            [
                'field' => 'product_stocks.created_at',
                'alias' => 'created_at',
            ],
        ];
    }
}
