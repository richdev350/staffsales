<?php

declare(strict_types=1);

namespace App\Services\Barcode;

use App\Enums\Barcode\BarcodeCookie;
use Illuminate\Support\Facades\Cookie;

class DeleteBarcodeService
{
    private $listBarcodeService;

    public function __construct(
        ListBarcodeService $listBarcodeService
    ){
        $this->listBarcodeService = $listBarcodeService;
    }

    public function delete($barcode)
    {
        $barcodeDatas = $this->listBarcodeService->list();

        foreach ($barcodeDatas as $key => $barcodeData) {
            if ($barcodeData['barcode'] == $barcode) {
                unset($barcodeDatas[$key]);
                array_values($barcodeDatas);
                break;
            }
        }

        if (count($barcodeDatas) > 0) {
            $barcodeDatas = json_encode($barcodeDatas);
            Cookie::queue(BarcodeCookie::NAME, $barcodeDatas, BarcodeCookie::MINUTES_PER_MONTH);
        } else {
            Cookie::queue(Cookie::forget(BarcodeCookie::NAME));
        }
    }
}
