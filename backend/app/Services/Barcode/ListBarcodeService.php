<?php

declare(strict_types=1);

namespace App\Services\Barcode;

use App\Enums\Barcode\BarcodeCookie;
use Illuminate\Support\Facades\Cookie;

class ListBarcodeService
{
    public function list(): array
    {
        return json_decode(Cookie::get(BarcodeCookie::NAME, '[]'), true);
    }

    public function isLimited(): bool
    {
        $barcodes = $this->list();
        if (count($barcodes) < BarcodeCookie::LIMIT) {
            return true;
        }

        return false;
    }
}
