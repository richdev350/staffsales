<?php
declare(strict_types=1);

namespace App\States\Registration;

use App\States\Registration\RegistrationState;

class Unregistered extends RegistrationState
{
    public static $name = 'unregistered';

    public function label(): string
    {
        return '未登録';
    }
}