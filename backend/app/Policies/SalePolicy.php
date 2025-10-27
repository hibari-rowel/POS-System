<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SalePolicy
{
    public function store(User $user, Sale $model)
    {
        return ($user->role === 'admin' || $user->role === 'staff')
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }

    public function destroy(User $user, User $model)
    {
        return ($user->role === 'admin' || $user->role === 'staff')
            ? Response::allow()
            : Response::deny('You do not have the necessary permissions to access this resource. Please contact support for assistance.');
    }
}
