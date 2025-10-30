<?php

namespace App\Services;

use App\Models\ProductSale;
use Carbon\Carbon;

class SaleService extends BaseRepository implements ServiceInterface
{
    public function createRecord($data, $model)
    {
        $data['created_by'] = $this->user->id;
        $data['updated_by'] = $this->user->id;
        $data['sale_date'] = Carbon::now();

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
}
