<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/25/2020
 * Time: 1:50 PM
 */

namespace App\Enums\Payment;

use MyCLabs\Enum\Enum;

class Reason
{
    public const NOT_PAID            = 0;
    public const PAID                = 1;
    public const CANCELED            = 2;
    public const INVALID_SHOP        = 3;
    public const INVALID_ORDER_NO    = 4;
    public const INVALID_SECURE_CODE = 5;
    public const INVALID_STATUS      = 6;

}