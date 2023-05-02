<?php
declare(strict_types=1);

namespace App\States\Payment;

use Spatie\ModelStates\State;
use App\States\Payment\Pending;
use App\States\Payment\Paid;
use App\States\Payment\Failed;
use App\States\Payment\Cancel;

abstract class PaymentState extends State
{
    public static $states = [
        Pending::class,
        Paid::class,
        Failed::class,
        Cancel::class,
    ];

    abstract public function label(): string;
}
