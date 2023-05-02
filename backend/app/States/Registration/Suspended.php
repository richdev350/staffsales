<?php
declare(strict_types=1);

namespace App\States\Registration;

use App\States\Registration\RegistrationState;

class Suspended extends RegistrationState
{
    public static $name = 'suspended';

    public function label(): string
    {
        return '停止';
    }
}