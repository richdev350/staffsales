<?php
declare(strict_types=1);

namespace App\States\Registration;

use App\States\Registration\RegistrationState;

class Registered extends RegistrationState
{
    public static $name = 'resistered';

    public function label(): string
    {
        return '本登録';
    }
}