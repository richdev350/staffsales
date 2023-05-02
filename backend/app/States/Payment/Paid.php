<?php
declare(strict_types=1);

namespace App\States\Payment;

use App\States\Payment\PaymentState;

class Paid extends PaymentState
{
    public static $name = 'paid';

    public function label(): string
    {
        return '入金済';
    }

    public function code(): int
    {
        return 1;
    }
}
