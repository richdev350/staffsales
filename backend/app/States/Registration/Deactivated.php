<?php
declare(strict_types=1);

namespace App\States\Registration;

use App\States\Registration\RegistrationState;

class Deactivated extends RegistrationState
{
    public static $name = 'deactivated';

    public function label(): string
    {
        return '無効';
    }
}