<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Services\Publish\ListPublishService;
use App\Services\Publish\CreatePublishService;
use App\Services\Publish\UpdatePublishService;
use App\Services\DesiredTime\ListDesiredTimesService;
use DB;
use DateTime;


class PublishController extends Controller
{
    public function list(
        ListPublishService $listPublishService,
        $condition = null
    ) {
        $conditions = $listPublishService->conditionQueryToArray($condition);
        $publish = $listPublishService->list($conditions);
        $id = null;
        $name = null;
        $exhibit_date = null;
        $sales_start_date = null;
        $end_of_sale_date = null;
        $visible = 1;
        $emergency_flag = 0;
        if (count($publish) > 0) {
            $id = $publish->first()->id;
            $name = $publish->first()->name;
            $exhibit_date = $publish->first()->exhibit_date->format('Y-m-d H:i:s');
            $sales_start_date = $publish->first()->sales_start_date->format('Y-m-d H:i:s');
            $end_of_sale_date = $publish->first()->end_of_sale_date->format('Y-m-d H:i:s');
            $visible = $publish->first()->is_end_of_sale_date_visible;
            $emergency_flag = $publish->first()->emergency_flag;
        }
        return response()->view('admins.publish.list',compact(
            'exhibit_date',
            'sales_start_date',
            'end_of_sale_date',
            'name',
            'id',
            'visible',
            'emergency_flag'
        ));
    }

    public function create() {
        return response()->view('admins.publish.edit');
    }

    public function Date($year, $month, $day, $hour, $minute) {
        $value = $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute.':00';
        return $value;
    }

    public function insert(
        CreatePublishService $createPublishService,
        ListPublishService $listPublishService,
        Request $request) {

        $name = $request['title'];
        $exhibit_date     = $this->Date($request['exhibit_year'],
                                        $request['exhibit_month'],
                                        $request['exhibit_day'],
                                        $request['exhibit_hour'],
                                        $request['exhibit_minute']
                                        );
        $sales_start_date = $this->Date($request['sales_start_year'],
                                        $request['sales_start_month'],
                                        $request['sales_start_day'],
                                        $request['sales_start_hour'],
                                        $request['sales_start_minute']
                                        );
        $end_of_sale_date = $this->Date($request['end_of_sale_year'],
                                        $request['end_of_sale_month'],
                                        $request['end_of_sale_day'],
                                        $request['end_of_sale_hour'],
                                        $request['end_of_sale_minute']
                                        );
        $is_end_of_sale_date_visible = 0;

        if ($request['visible']) {
            $is_end_of_sale_date_visible = 1;
        }

        $data = array(
              'name'             => $name,
              'exhibit_date'     => $exhibit_date,
              'sales_start_date' => $sales_start_date,
              'end_of_sale_date' => $end_of_sale_date,
              'is_end_of_sale_date_visible' => $is_end_of_sale_date_visible,
              'emergency_flag' => 0,
            );

        $request['name'] = $name;

        if (DateTime::createFromFormat('Y-m-d H:i:s', $exhibit_date)) {
            $request['exhibit_date'] = $exhibit_date;
        }
        if (DateTime::createFromFormat('Y-m-d H:i:s', $sales_start_date)) {
            $request['sales_start_date'] = $sales_start_date;
        }
        if (DateTime::createFromFormat('Y-m-d H:i:s', $end_of_sale_date)) {
            $request['end_of_sale_date'] = $end_of_sale_date;
        }

        $errors = new MessageBag;
        $action = $request->input('action');
        if (false === array_search($action, ['ok'])) {
            abort(500, sprintf('Invalid action. action: %s', $action));
        }

        if ($createPublishService->passesValidation()) {

            $publish = $createPublishService->create($data);

            $conditions = $listPublishService->conditionQueryToArray(null);
            $publish = $listPublishService->list($conditions);
            $request->flash();

            return redirect()->route('admin.publish.list');

        } else {
            $request->flash();
            $publish = null;
            $errors = $createPublishService->getValidationMessages();
            return response()->view('admins.publish.edit',compact(
                'publish',
                'errors'
            ));
        }

    }

    public function update(
        ListPublishService $listPublishService,
        UpdatePublishService $updatePublishService,
        Request $request) {

        $id = $request['id'];
        $name = $request['title'];
        $action = $request->input('action');
        $is_end_of_sale_date_visible = 0;
        $conditions = $listPublishService->conditionQueryToArray(null);
        $publish = $listPublishService->list($conditions);
        $exhibit_date     = $this->Date($request['exhibit_year'],
                                        $request['exhibit_month'],
                                        $request['exhibit_day'],
                                        $request['exhibit_hour'],
                                        $request['exhibit_minute']
                                        );
        $sales_start_date = $this->Date($request['sales_start_year'],
                                        $request['sales_start_month'],
                                        $request['sales_start_day'],
                                        $request['sales_start_hour'],
                                        $request['sales_start_minute']
                                        );
        $end_of_sale_date = $this->Date($request['end_of_sale_year'],
                                    $request['end_of_sale_month'],
                                    $request['end_of_sale_day'],
                                    $request['end_of_sale_hour'],
                                    $request['end_of_sale_minute']
                                    );

        $emergency_flag_value = 0;
        if ($action == 'termination' || $action == 'termination_stop'){
            $id = 0;
            $name = '';
            $visible = 1;
            if ($action == 'termination') {
                $emergency_flag_value = 1;
            }
            if ($action == 'termination_stop') {
                $emergency_flag_value = 0;
            }
            if (count($publish) > 0) {
                $id = $publish->first()->id;
                $name = $publish->first()->name;
                $exhibit_date = $publish->first()->exhibit_date->format('Y-m-d H:i:s');
                $sales_start_date = $publish->first()->sales_start_date->format('Y-m-d H:i:s');
                $end_of_sale_date = $publish->first()->end_of_sale_date->format('Y-m-d H:i:s');
                $visible = $publish->first()->is_end_of_sale_date_visible;
            }
            $data = array(
                'id'               => $id,
                'name'             => $name,
                'exhibit_date'     => $exhibit_date,
                'sales_start_date' => $sales_start_date,
                'end_of_sale_date' => $end_of_sale_date,
                'is_end_of_sale_date_visible' => $visible,
                'emergency_flag' => $emergency_flag_value,
              );
        } else {
            if (count($publish) > 0) {
                $emergency_flag_value = $publish->first()->emergency_flag;
            }
            if ($request['visible']) {
                $is_end_of_sale_date_visible = 1;
            }
            $data = array(
                'id'               => $id,
                'name'             => $name,
                'exhibit_date'     => $exhibit_date,
                'sales_start_date' => $sales_start_date,
                'end_of_sale_date' => $end_of_sale_date,
                'is_end_of_sale_date_visible' => $is_end_of_sale_date_visible,
                'emergency_flag' => $emergency_flag_value,
              );
        }

        $request['name'] = $name;
        $request['id'] = $id;

        if (DateTime::createFromFormat('Y-m-d H:i:s', $exhibit_date)) {
            $request['exhibit_date'] = $exhibit_date;
        }
        if (DateTime::createFromFormat('Y-m-d H:i:s', $sales_start_date)) {
            $request['sales_start_date'] = $sales_start_date;
        }
        if (DateTime::createFromFormat('Y-m-d H:i:s', $end_of_sale_date)) {
            $request['end_of_sale_date'] = $end_of_sale_date;
        }

        $errors = new MessageBag;
        if (false === array_search($action, ['ok', 'termination', 'termination_stop'])) {
            abort(500, sprintf('Invalid action. action: %s', $action));
        }

        if ($updatePublishService->passesValidation()) {
            $publish = $updatePublishService->update($data);
            return redirect()->route('admin.publish.list');
        } else {
            $request->flash();
            $errors = $updatePublishService->getValidationMessages();
            $id = 0;
            $name = '';
            $exhibit_date = 0;
            $sales_start_date = 0;
            $end_of_sale_date = 0;
            $visible = 1;
            $emergency_flag = 0;
            if (count($publish) > 0) {
                $id = $publish->first()->id;
                $name = $publish->first()->name;
                $exhibit_date = $publish->first()->exhibit_date->format('Y-m-d H:i:s');
                $sales_start_date = $publish->first()->sales_start_date->format('Y-m-d H:i:s');
                $end_of_sale_date = $publish->first()->end_of_sale_date->format('Y-m-d H:i:s');
                $visible = $publish->first()->is_end_of_sale_date_visible;
                $emergency_flag = $publish->first()->emergency_flag;
            }
            foreach ($errors->get('name') as $error){
                $name = '';
            }
            foreach ($errors->get('exhibit_date') as $error){
                $exhibit_date = 0;
            }
            foreach ($errors->get('sales_start_date') as $error){
                $sales_start_date = 0;
            }
            foreach ($errors->get('end_of_sale_date') as $error){
                $end_of_sale_date = 0;
            }
            return response()->view('admins.publish.list',compact(
                'errors',
                'exhibit_date',
                'sales_start_date',
                'end_of_sale_date',
                'name',
                'id',
                'visible',
                'emergency_flag'
            ));
        }

    }
}

