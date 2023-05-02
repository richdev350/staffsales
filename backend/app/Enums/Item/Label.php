<?php
namespace App\Enums\Item;

use MyCLabs\Enum\Enum;

class Label extends Enum
{
    public const NEW                = 'NEW';
    public const ONLY_A_FEW         = '残りわずか';
    public const RECOMMEND          = 'オススメ';
    public const SOLD_OUT_SOON      = 'まもなく販売終了';
    public const SELF_MEDICATION    = 'セルフメディケーション税制対象';
    public const DRINK_WATER        = '飲用水';
}