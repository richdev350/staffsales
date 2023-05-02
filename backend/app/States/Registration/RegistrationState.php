<?php
declare(strict_types=1);

namespace App\States\Registration;

use Spatie\ModelStates\State;
use App\States\Registration\Unregistered;
use App\States\Registration\Temporary;
use App\States\Registration\Registered;
use App\States\Registration\Activated;
use App\States\Registration\Deactivated;
use App\States\Registration\Suspended;

abstract class RegistrationState extends State
{
    public static $states = [
        Unregistered::class,
        Temporary::class,
        Registered::class,
        Activated::class,
        Deactivated::class,
        Suspended::class,
    ];

    abstract public function label(): string;
}