<?php
declare(strict_types=1);

namespace App\States\Payment;

use App\States\Payment\PaymentState;

class Failed extends PaymentState
{
    public static $name = 'failed';

    public function label(): string
    {
        return '失敗';
    }
    public function code(): int
    {
        return 2;
    }
}
