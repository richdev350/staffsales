<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AdminUsersShops extends Pivot
{
    public function hello(): string
    {
        return 'Hello, world.';
    }
}
