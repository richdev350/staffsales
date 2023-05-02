<?php
namespace App\Enums\Mode;

use MyCLabs\Enum\Enum;

class Modes extends Enum
{
    public const BROWSING      = 0; // 閲覧モード
    public const SALES         = 1; // 販売モード
    public const MAINTENANCE   = 2; // メンテナンスモード
}
