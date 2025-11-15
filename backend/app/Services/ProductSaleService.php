<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductSaleService extends BaseRepository implements ServiceInterface
{
    public function createRecord($data, $model)
    {
        $data['created_by'] = $this->user->id;
        $data['updated_by'] = $this->user->id;

        return $this->save($data, $model);
    }

    public function updateRecord($data, $model)
    {

    }

    public function getDTList($params)
    {
        $params = $this->getDTParams($params);
        $select = Arr::pluck($this->userDTColumns(), 'field');
        $alias = Arr::pluck($this->userDTColumns(), 'alias');

        $qb = DB::table('product_sales')
            ->select(array_merge($select, ['products.unit']))
            ->leftJoin('products', 'product_sales.product_id', '=', 'products.id')
            ->whereNull('product_sales.deleted_at')
            ->whereNull('products.deleted_at');

        if (!empty($params['sale_id'])) {
            $qb->where('product_sales.sale_id', $params['sale_id']);
        }

        if (!empty($params['search_key'])) {
            $qb->where('product_sales.invoice_number', 'LIKE', $params['search_key'] . '%');
        }

        $qb->orderBy('product_sales.created_at', 'DESC');

        $result = $qb->paginate($params['limit'], ['*'], 'page', $params['page_num']);

        return [$result->items(), $result->total()];
    }

    private function userDTColumns()
    {
        return [
            [
                'field' => 'product_sales.id',
                'alias' => 'id',
            ],
            [
                'field' => DB::raw('products.name AS product_name'),
                'alias' => 'product_name',
            ],
            [
                'field' => 'product_sales.price',
                'alias' => 'price',
            ],
            [
                'field' => 'product_sales.quantity',
                'alias' => 'quantity',
            ],
            [
                'field' => 'product_sales.subtotal',
                'alias' => 'subtotal',
            ],
            [
                'field' => 'product_sales.created_at',
                'alias' => 'created_at',
            ],
        ];
    }
}
