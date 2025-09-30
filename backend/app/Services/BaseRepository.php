<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class BaseRepository
{
    public array $dirtyValues = [];

    public array $dirtyKeyValues = [];

    public $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function save($data, $model)
    {
        $this->dirtyValues = [];

        $model->fill($data);

        $this->dirtyValues = $model->getDirty();
        $this->dirtyKeyValues = array_keys($this->dirtyValues);

        $model = $this->afterModelFill($data, $model);
        if ($model->save()) {
            $this->afterSave($data, $model);
            return $model;
        }

        return false;
    }

    public function afterModelFill($data, $model)
    {
        return $model;
    }

    public function afterSave($data, $model)
    {

    }

    public function getDTParams($params)
    {
        $params['limit'] = $params['records_per_page'];

        $params['search_key'] = null;
        if (!empty($params['search'])) {
            $params['search_key'] = trim($params['search']);
        }

        $params['page_num'] = 1;
        if ($params['start'] > 0 && $params['records_per_page'] > 0) {
            $params['page_num'] = abs(floor($params['start'] / $params['records_per_page']) + 1);
        }

        return $params;
    }
}
