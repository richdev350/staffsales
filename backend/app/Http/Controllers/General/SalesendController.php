<?php
declare(strict_types=1);

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ItemCategory\ListItemCategoriesService;
use Illuminate\Support\Facades\Redirect;
use App\Services\Publish\ListPublishService;
use DateTime;

class SalesendController extends BaseGeneralController
{
    public function end() {
        return response()->view('generals.salesend.end');
    }

}
