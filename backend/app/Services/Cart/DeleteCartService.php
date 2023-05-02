<?php
declare(strict_types=1);

namespace App\Services\Cart;

use Throwable;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\Cart\DeleteCartRequestFilter;
use App\Http\Requests\Cart\DeleteCartRequest;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;

class DeleteCartService
{
    use Filterable,
        Validatable;

    private $request;

    public function __construct(
        Request $request
    ){
        $this->request = $request;

        $this->setRequestFilter(new DeleteCartRequestFilter());
        $this->setFormRequest(new DeleteCartRequest());
        // $this->init();
    }

    // public function init()
    // {
    //     if (! $this->request->isMethod('GET')) {
    //         $this->filterInputs();
    //         return;
    //     }

    //     $this->request->flush();
    // }

}
