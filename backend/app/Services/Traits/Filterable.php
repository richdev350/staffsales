<?php
declare(strict_types=1);

namespace App\Services\Traits;

use InvalidArgumentException;
use Illuminate\Http\Request;
use App\Http\Requests\RequestFilter;

/**
 * フィルタリング用トレイト
 */
trait Filterable
{
    /**
     * @var RequestFilter  $requestFilter
     */
    protected $requestFilter;

    /**
     * リクエストフィルターインスタンスをセットする
     *
     * @param  RequestFilter  $formRequest
     * @return void
     */
    public function setRequestFilter(RequestFilter $requestFilter)
    {
        $this->requestFilter = $requestFilter;
    }

    /**
     * 入力をフィルタリングしてリクエストにセットし直す
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function filterInputs()
    {
        if (! $this->request instanceof Request) {
            throw new InvalidArgumentException('This service must have $request parameter of the \Illuminate\Http\Request instance.');
        }

        $this->request->replace(
            $this->requestFilter->filterInputs($this->request->input())
        );
    }

    public function getRequestFilter()
    {
        return $this->requestFilter;
    }
}
