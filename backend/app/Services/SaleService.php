<?php

namespace App\Services;

use App\Models\ProductSale;

class SaleService extends BaseRepository implements ServiceInterface
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

    public function afterSave($data, $model)
    {
        $productSaleService = new ProductSaleService();

        foreach ($data['items'] as $item) {
            $productSaleModel = new ProductSale();

            $productSaleData = [
                'sale_id' => $model->id,
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
