<?php
declare(strict_types=1);

namespace App\States\Payment;

use App\States\Payment\PaymentState;

class Cancel extends PaymentState
{
    public static $name = 'cancel';

    public function label(): string
    {
        return 'キャンセル';
    }
    public function code(): int
    {
        return 9;
    }
}
