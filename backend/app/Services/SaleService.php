<?php

namespace App\Services;

use App\Models\ProductSale;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SaleService extends BaseRepository implements ServiceInterface
{
    public function createRecord($data, $model)
    {
        $today = Carbon::now();
        $saleCountToday = Sale::whereDate('sale_date', $today->copy()->format('Y-m-d'))->count() + 1;

        $data['created_by'] = $this->user->id;
        $data['updated_by'] = $this->user->id;
        $data['sale_date'] = $today;
        $data['invoice_number'] = 'INV-' . $today->copy()->format('Ymd') . '-' . $saleCountToday;

        return $this->save($data, $model);
    }

    public function updateRecord($data, $model)
    {

    }

    public function afterSave($data, $model)
    {
        if ($model->wasRecentlyCreated) {
            $productSaleService = new ProductSaleService();

            foreach ($data['items'] as $item) {
                $productSaleModel = new ProductSale();

                $productSaleData = [
                    'sale_id' => $model->id,
                    'name' => $item['name'],
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'created_by' => $this->user->id,
                    'updated_by' => $this->user->id,
                ];

                $productSaleService->createRecord($productSaleData, $productSaleModel);
            }
        }
    }

    public function getDTList($params)
    {
        $params = $this->getDTParams($params);
        $select = Arr::pluck($this->userDTColumns(), 'field');
        $alias = Arr::pluck($this->userDTColumns(), 'alias');

        $qb = DB::table('sales')
            ->select(array_merge($select, ['subtotal_amount', 'discount_amount', 'tax_amount',]))
            ->whereNull('sales.deleted_at');

        if (!empty($params['search_key'])) {
            $qb->where('sales.invoice_number', 'LIKE', $params['search_key'] . '%');
        }

        $qb->orderBy('sales.created_at', 'DESC');

        $result = $qb->paginate($params['limit'], ['*'], 'page', $params['page_num']);

        return [$result->items(), $result->total()];
    }

    private function userDTColumns()
    {
        return [
            [
                'field' => 'sales.id',
                'alias' => 'id',
            ],
            [
                'field' => 'sales.invoice_number',
                'alias' => 'invoice_number',
            ],
            [
                'field' => 'sales.sale_date',
                'alias' => 'sale_date',
            ],
            [
                'field' => 'sales.total_amount',
                'alias' => 'total_amount',
            ],
            [
                'field' => 'sales.cash_amount',
                'alias' => 'cash_amount',
            ],
            [
                'field' => 'sales.change_amount',
                'alias' => 'change_amount',
            ],
            [
                'field' => 'sales.created_at',
                'alias' => 'created_at',
            ],
        ];
    }
}
