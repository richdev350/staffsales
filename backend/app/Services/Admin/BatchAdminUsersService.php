<?php
declare(strict_types=1);

namespace App\Services\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Entities\AdminUser;
use App\Models\Repositories\Contracts\AdminUserRepositoryInterface;
use App\Services\Traits\Conditionable;

/**
 * 管理ユーザー一括処理一覧サービス
 */
class BatchAdminUsersService
{
    /**
     * リポジトリ
     */
    private $repository;

    /**
     * @var Request
     */
    private $request;

    /**
     * コンストラクタ
     *
     */
    public function __construct(AdminUserRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    /**
     * 一括処理を行って処理件数を返す
     *
     */
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
                        foreach ($collection as $admn_user) {
                            $this->repository->delete($admn_user);
                        }
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
