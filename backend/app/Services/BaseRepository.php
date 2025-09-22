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
}
