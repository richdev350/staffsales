<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Services\Maker\ListMakersService;
use App\Services\Maker\BatchMakersService;
use App\Services\Maker\CreateMakerService;
use App\Services\Maker\UpdateMakerService;
use App\Services\Maker\DeleteMakerService;

class MakerController extends Controller
{
    public function list(
        ListMakersService $listMakersService,
        $condition = null
    ) {
        $conditions = $listMakersService->conditionQueryToArray($condition);
        $paginator = $listMakersService->pagination($conditions);

        return response()->view('admins.makers.list', compact(
            'conditions',
            'paginator'
        ));
    }

    public function select(ListMakersService $listMakersService)
    {
        $condition = $listMakersService->conditionsToQuery([
            'name',
        ]);

        return redirect()->route('admin.maker.list', ['condition' => $condition]);
    }

    public function create(
        CreateMakerService $createMakerService,
        Request $request
    )
    {
        $request->flash();

        return response()->view('admins.makers.edit');
    }

    public function store(
        CreateMakerService $createMakerService,
        Request $request
    )
    {
        $errors = new MessageBag;

        $action = $request->input('action');
        if (false === array_search($action, ['confirm', 'save', 'return'])) {
            abort(500, sprintf('Invalid action. action: %s', $action));
        }

        $viewPath = 'admins.makers.edit';
        if ($createMakerService->passesValidation()) {
            if ('confirm' === $action) {
                $viewPath = 'admins.makers.confirm';
            } elseif ('save' === $action) {
                try {
                    $maker = $createMakerService->create();
                    $request->session()->flash('message', 'メーカーを保存しました。');
                } catch (Throwable $exception) {
                    throw $exception;
                }

                return redirect()->route('admin.maker.list');
            }
        } else {
            $errors = $createMakerService->getValidationMessages();
        }

        $request->flash();

        return response()->view($viewPath, compact(
            'errors'
        ));
    }

    public function show(
        UpdateMakerService $updateMakerService,
        Request $request,
        int $id
    ) {

        $request->flash();

        return response()->view('admins.makers.show', compact(
            'id'
        ));
    }

    public function edit(
        UpdateMakerService $updateMakerService,
        Request $request,
        int $id
    ) {

        $request->flash();

        return response()->view('admins.makers.edit', compact(
            'id',
        ));
    }

    public function update(
        UpdateMakerService $updateMakerService,
        Request $request,
        int $id
    ) {
        $errors = new MessageBag;

        $action = $request->input('action');
        if (false === array_search($action, ['confirm', 'save', 'return'])) {
            abort(500, sprintf('Invalid action. action: %s', $action));
        }

        $viewPath = 'admins.makers.edit';
        if ($updateMakerService->passesValidation()) {
            if ('confirm' === $action) {
                $viewPath = 'admins.makers.confirm';
            } elseif ('save' === $action) {
                try {
                    $maker = $updateMakerService->update();
                    $request->session()->flash('message', 'メーカーを保存しました。');
                } catch (Throwable $exception) {
                    throw $exception;
                }

                return redirect()->route('admin.maker.list');
            }
        } else {
            $errors = $updateMakerService->getValidationMessages();
        }

        $request->flash();

        return response()->view($viewPath, compact(
            'id',
            'errors'
        ));
    }

    public function destroy(
        DeleteMakerService $deleteMakerService,
        Request $request,
        int $id
    ) {
        try {
            $maker = $deleteMakerService->delete();
            $request->session()->flash('message', sprintf('メーカー「%s」を削除しました。', $maker->name));
        } catch (Throwable $exception) {
            throw $exception;
        }

        return redirect()->route('admin.maker.list');
    }
}
