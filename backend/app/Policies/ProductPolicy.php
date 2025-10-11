<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    public function store(User $user, Product $model)
    {
        return ($user->role === 'admin')
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }

    public function update(User $user, Product $model)
    {
        return ($user->role === 'admin')
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }

    public function destroy(User $user, Product $model)
    {
        return ($user->role === 'admin')
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }
}
