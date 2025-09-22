<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case Admin = 'admin';
    case Staff = 'staff';
    case Customer = 'customer';
}
