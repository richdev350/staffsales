<?php
declare(strict_types=1);

namespace App\States\Registration;

use App\States\Registration\RegistrationState;

class Activated extends RegistrationState
{
    public static $name = 'activate';

    public function label(): string
    {
        return '有効';
    }
}