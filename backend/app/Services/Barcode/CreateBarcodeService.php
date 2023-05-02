<?php

declare(strict_types=1);

namespace App\Services\Barcode;

use App\Enums\Barcode\BarcodeCookie;
use App\Models\Entities\Order;
use Illuminate\Support\Facades\Cookie;

class CreateBarcodeService
{
    private $listBarcodeService;

    public function __construct(
        ListBarcodeService $listBarcodeService
    ){
        $this->listBarcodeService = $listBarcodeService;
    }

    public function create(Order $order)
    {
        $barcodeData = '';
        $data = [
            'barcode' => generateBarCode($order->id, $order->secure_code),
            'payment_datetime' => now()->format('Y-m-d H:i'),
        ];

        $oldDatas = $this->listBarcodeService->list();
        $oldDatas[] = $data;
        $barcodeData = json_encode($oldDatas);

        Cookie::queue(BarcodeCookie::NAME, $barcodeData, BarcodeCookie::MINUTES_PER_MONTH);
    }
}
