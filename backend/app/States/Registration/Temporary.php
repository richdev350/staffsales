<?php
declare(strict_types=1);

namespace App\States\Registration;

use App\States\Registration\RegistrationState;

class Temporary extends RegistrationState
{
    public static $name = 'temporary';

    public function label(): string
    {
        return '仮登録';
    }
}