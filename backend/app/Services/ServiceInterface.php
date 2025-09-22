<?php

namespace App\Services;

interface ServiceInterface
{
    public function createRecord($data, $model);

    public function updateRecord($data, $model);
}
