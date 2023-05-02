<?php
declare(strict_types=1);

namespace App\Services\Item;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Entities\Item;
use App\Models\Repositories\Contracts\ItemRepositoryInterface;
use App\Services\Traits\Conditionable;

class BatchItemsService
{
    private $repository;
    private $request;

    public function __construct(ItemRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    private function show_item($item, $visible)
    {
        $item = $this->repository->edit($item, ['is_visible' => $visible]);
        $this->repository->persist($item);
    }

    public function batch(): int
    {
        $targetIds = array_map('intval', explode(',', $this->request->input('targets', '')));
        if (0 == count($targetIds)) {
            return 0;
        }

        try {
            return DB::transaction(function () use ($targetIds) {
                $collection = $this->repository->list(['ids' => $targetIds]);
                $count      = $collection->count();

                switch ($this->request->input('action')) {
                    case 'delete':
                        foreach ($collection as $item) {
                            $this->repository->delete($item);
                        }
                        break;
                    case 'show':
                        foreach ($collection as $item) {
                            $this->show_item($item, TRUE);
                        }

                        clearCategoryCache();
                        break;
                    case 'hide':
                        foreach ($collection as $item) {
                            $this->show_item($item, FALSE);
                        }

                        clearCategoryCache();
                        break;
                    default:
                        break;
                }

                return $count;
            });
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
