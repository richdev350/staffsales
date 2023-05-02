<?php
declare(strict_types=1);

namespace App\States\Payment;

use App\States\Payment\PaymentState;

class Pending extends PaymentState
{
    public static $name = 'pending';

    public function label(): string
    {
        return '未入金';
    }

    public function code(): int
    {
        return 0;
    }
}
