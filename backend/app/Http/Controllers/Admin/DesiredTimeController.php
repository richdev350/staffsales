<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Models\Entities\DesiredTime;
use App\Services\DesiredTime\ListDesiredTimesService;
use App\Services\DesiredTime\BatchDesiredTimesService;
use App\Services\DesiredTime\CreateDesiredTimeService;
use App\Services\DesiredTime\UpdateDesiredTimeService;
use App\Services\DesiredTime\DeleteDesiredTimeService;

class DesiredTimeController extends Controller
{
    public function list(
        ListDesiredTimesService $listDesiredTimesService,
        $condition = null
    ) {
        $paginator = $listDesiredTimesService->paginate($condition, 10);
        $conditions = $listDesiredTimesService->conditionQueryToArray($condition);

        return response()->view('admins.desired-times.list', compact(
            'conditions',
            'paginator'
        ));
    }

    public function select(ListDesiredTimesService $listDesiredTimesService)
    {
        $condition = $listDesiredTimesService->conditionsToQuery([
        ]);

        return redirect()->route('admin.desired-time.list', ['condition' => $condition]);
    }

    public function batch(
        BatchDesiredTimesService $batchDesiredTimesService,
        Request $request,
        $condition = null
    ) {
        try {
            $count = $batchDesiredTimesService->batch();

            switch ($request->input('action')) {
                case 'delete':
                    $request->session()->flash('message', sprintf('%s 件のアカウントを削除しました。', number_format($count)));
                    break;
                default:
                    break;
            }
        } catch (Throwable $exception) {
            throw $exception;
        }

        return redirect()->route('admin.desired-time.list', ['condition' => $condition]);
    }

    public function create(
        CreateDesiredTimeService $createDesiredTimeService,
        Request $request
    )
    {
        $request->flash();
        $range_of_times = DesiredTime::RANGE_OF_TIMES;

        return response()->view('admins.desired-times.edit', compact(
            'range_of_times',
        ));
    }

    public function store(
        CreateDesiredTimeService $createDesiredTimeService,
        Request $request
    )
    {
        $errors = new MessageBag;

        $action = $request->input('action');
        if (false === array_search($action, ['confirm', 'save', 'return'])) {
            abort(500, sprintf('Invalid action. action: %s', $action));
        }

        $viewPath = 'admins.desired-times.edit';
        if ($createDesiredTimeService->passesValidation()) {
            if ('confirm' === $action) {
                $viewPath = 'admins.desired-times.confirm';
            } elseif ('save' === $action) {
                try {
                    $desiredTime = $createDesiredTimeService->create();
                    $request->session()->flash('message', '時間帯を保存しました。');
                } catch (Throwable $exception) {
                    throw $exception;
                }

                return redirect()->route('admin.desired-time.list');
            }
        } else {
            $errors = $createDesiredTimeService->getValidationMessages();
        }

        $request->flash();
        $range_of_times = DesiredTime::RANGE_OF_TIMES;

        return response()->view($viewPath, compact(
            'errors',
            'range_of_times',
        ));
    }

    public function show(
        UpdateDesiredTimeService $updateDesiredTimeService,
        Request $request,
        int $id
    ) {
        $request->flash();
        return response()->view('admins.desired-times.show', compact(
            'id',
        ));
    }

    public function edit(
        UpdateDesiredTimeService $updateDesiredTimeService,
        Request $request,
        int $id
    ) {
        $request->flash();
        $range_of_times = DesiredTime::RANGE_OF_TIMES;

        return response()->view('admins.desired-times.edit', compact(
            'id',
            'range_of_times',
        ));
    }

    public function update(
        UpdateDesiredTimeService $updateDesiredTimeService,
        Request $request,
        int $id
    ) {
        $errors = new MessageBag;

        $action = $request->input('action');
        if (false === array_search($action, ['confirm', 'save', 'return'])) {
            abort(500, sprintf('Invalid action. action: %s', $action));
        }

        $viewPath = 'admins.desired-times.edit';
        if ($updateDesiredTimeService->passesValidation()) {
            if ('confirm' === $action) {
                $viewPath = 'admins.desired-times.confirm';
            } elseif ('save' === $action) {
                try {
                    $desired_time = $updateDesiredTimeService->update();
                    $request->session()->flash('message', '時間帯を保存しました。');
                } catch (Throwable $exception) {
                    throw $exception;
                }

                return redirect()->route('admin.desired-time.list');
            }
        } else {
            $errors = $updateDesiredTimeService->getValidationMessages();
        }

        $request->flash();
        $range_of_times = DesiredTime::RANGE_OF_TIMES;

        return response()->view($viewPath, compact(
            'id',
            'errors',
            'range_of_times',
        ));
    }

    public function destroy(
        DeleteDesiredTimeService $deleteDesiredTimeService,
        Request $request,
        int $id
    ) {
        try {
            $desired_time = $deleteDesiredTimeService->delete();
            $request->session()->flash('message', sprintf('「%s」を削除しました。', $desired_time->period));

        } catch (Throwable $exception) {
            throw $exception;
        }

        return redirect()->route('admin.desired-time.list');
    }
}
