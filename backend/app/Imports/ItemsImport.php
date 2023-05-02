<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/30/2020
 * Time: 2:36 PM
 */

namespace App\Imports;

use App\Exceptions\ImportValidationException;
use Log;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Services\Item\CreateItemService;
use App\Services\Item\UpdateItemService;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

HeadingRowFormatter::default('none');

class ItemsImport implements ToCollection, WithHeadingRow, WithEvents
{
    use Importable, RegistersEventListeners;

    public $validation_errors = [];
    public $unique_errors = [];
    public $upsert_datas = [];

    public function __construct(
        CreateItemService $createItemService,
        UpdateItemService $updateItemService,
        Request $request,
        $numcols
    ) {
        $this->createItemService = $createItemService;
        $this->updateItemService = $updateItemService;
        $this->request = $request;
        $this->numcols = $numcols;
    }

    private function addValidationError($row_no, $errors) {
        array_push($this->validation_errors, ['row_no' => $row_no, 'errors' => $errors]);
    }

    private function addUniqueError($row_no, $errors) {
        array_push($this->unique_errors, ['row_no' => $row_no, 'column' => $errors]);
    }

    private function addData($input) {
        array_push($this->upsert_datas, $input);
    }

    public function getImportJans() {
        $jans = [];
        foreach($this->upsert_datas as $data) {
            array_push($jans, $data['jan']);
        }
        return $jans;
    }

    public function collection(Collection $rows)
    {
        $row_no = 1;
        $maxcolumn = 58; //最大カラム数
        foreach($rows as $row) {
            $item_category_ids = $row["カテゴリID"];
            $item_category_ids = explode(' ', $item_category_ids);

            $labels = $row["ラベル"];
            if($labels){
                $labels = explode(' ', $labels);
            }else{
                $labels = [];
            }

            $title_list = [];
            $body_list = [];

            foreach($row as $key=>$value) {
                if (!$value)
                    continue;

                if (strpos($key, 'スペックタイトル') === 0) {
                    array_push($title_list, $value);
                } elseif (strpos($key, 'スペック本文') === 0) {
                    array_push($body_list, $value);
                }
            }

            $spec = [];
            if (count($title_list) >= count($body_list)) {
                foreach($title_list as $index => $value) {
                    if (isset($body_list[$index])){
                        array_push($spec, ['title' => (string)$value, 'body' => (string)$body_list[$index]]);
                    } else{
                        array_push($spec, ['title' => (string)$value, 'body' => '']);
                    }
                }
            } elseif(count($title_list) < count($body_list)) {
                foreach($body_list as $index => $value) {
                    if (isset($title_list[$index])){
                        array_push($spec, ['title' => (string)$title_list[$index], 'body' => (string)$value]);
                    } else{
                        array_push($spec, ['title' => '', 'body' => (string)$value]);
                    }
                }
            }
            $input = [
                'id'                     => $row["ID"],
                'item_category_ids'      => $item_category_ids,
                'maker_id'               => $row["メーカーID"],
                'jan'                    => (string)$row["JANコード"],
                'name'                   => $row["商品名"],
                'abridge'                => $row["一覧用商品要約"],
                'summary'                => $row["商品概要"],
                'description_title'      => $row["商品説明のタイトル"],
                'description'            => $row["商品説明"],
                'labels'                 => $labels,
                'notes'                  => $row["特記事項"],
                'tags'                   => $row["タグ"],
                'self_medication'        => $row["セルフメディケーション"],
                'price'                  => $row["価格"],
                'is_stock'               => $row["在庫確認"],
                'is_visible'             => $row["表示・非表示"],
                'max_amount'             => $row["購入上限数"],
                'sort_no'                => $row["ソート順"],
                'spec'                   => $spec,
            ];

            $input['is_csv_import'] = true;

            $input = $this->createItemService->getRequestFilter()->filterInputs($input);
            $error = '必須項目の登録が無いCSVです。';

            $err_row = [];
            if ($item_category_ids[0] == null) {
                array_push($err_row, '「カテゴリID」');
            }
            if ($input['name'] == '') {
                array_push($err_row, '「商品名」');
            }
            if ($input['price'] == '') {
                array_push($err_row, '「価格」');
            }
            if ($input['is_stock'] === '') {
                array_push($err_row, '「在庫確認」');
            }
            if ($input['max_amount'] == '') {
                array_push($err_row, '「購入上限数」');
            }
            if ($input['jan'] == '') {
                array_push($err_row, '「JANコード」');
            }
            for($i = 0; $i < count($spec); $i++){
                if ($spec[$i]['title'] == '' && $spec[$i]['body'] != '') {
                    $i1 = $i + 1;
                    array_push($err_row, '「スペックタイトル'.$i1.'」');
                }
                if ($spec[$i]['title'] != '' && $spec[$i]['body'] == '') {
                    $i1 = $i + 1;
                    array_push($err_row, '「スペック本文'.$i1.'」');
                }
            }
            if($err_row) {
                array_push($this->validation_errors, ['row_no' => $row_no, 'errors' => $error.'('.implode(",", $err_row).')']);
            }
            if ($this->numcols[$row_no] !== $maxcolumn) {
                $error = "CSVのカラム数が違います";
                array_push($this->validation_errors, ['row_no' => $row_no, 'errors' => $error]);
            } elseif ($input['id']) {
                $this->request->merge(['id' => $input['id']]);
                if ($this->updateItemService->passesValidation($input)) {
                    $this->addData($input);
                } else {
                    $errors = $this->updateItemService->getValidationMessages();
                    $this->addValidationError($row_no, $errors);
                }
            } else {
                // 新規挿入時はsort_no除外してdefault:0とする
                // ※バリデーションエラー回避のためバリデーション前に除外（sometimesで回避される）
                unset($input['sort_no']);
                $this->request->merge(['id' => null]);
                if ($this->createItemService->passesValidation($input)) {
                    // ダブルクオートが無い場合にnull判定される場合があるので空文字をセット
                    $input['id'] = '';
                    $this->addData($input);
                } else {
                    $errors = $this->createItemService->getValidationMessages();
                    $this->addValidationError($row_no, $errors);
                }
            }

            $row_no++;
        }

    }

    public function hasValidationErrors() {
        if (count($this->validation_errors) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function hasUniqueErrors() {
        if (count($this->unique_errors) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkUnique() {

        $check_list = [[
            'key' => 'id',
            'label' => 'ID',
        ], [
            'key' => 'jan',
            'label' => 'JANコード',
        ], [
            'key' => 'sort_no',
            'label' => 'ソート順',
        ]];

        foreach($check_list as &$temp) {
            $item_column = array_column($this->upsert_datas, $temp['key']);

            $filtered_column = array_filter($item_column, function($value) {
                return !is_null($value);
            });
            $temp['count_array'] = array_count_values($filtered_column);
        }

        $row_no = 1;

        foreach($this->upsert_datas as $data) {
            foreach($check_list as $check_item) {
                // 新規追加時にsort_noが存在しない場合があるのでisset追加
                if(isset($data[$check_item['key']])){
                    $value = $data[$check_item['key']];
                    if ($value) {
                        if ($check_item['count_array'][$value] > 1) {
                            $this->addUniqueError($row_no, $check_item['label']);
                        }
                    }
                }
            }
            $row_no++;
        }
    }

    public function upsert() {
        foreach($this->upsert_datas as $data) {
            if ($data['id']) {
                try {
                    $item = $this->updateItemService->update($data);
                } catch (Throwable $exception) {
                    throw $exception;
                }
            } else {
                try {
                    $item = $this->createItemService->create($data);
                } catch (Throwable $exception) {
                    throw $exception;
                }
            }
        }
    }

    public function getValidationErrorMessages() {
        $error_messages = [];

        foreach($this->validation_errors as $temp) {
            $row_no = $temp['row_no'];
            $errors = $temp['errors'];

            if(is_string($errors)) {
                $message = "{$row_no}行目の{$errors}";
                array_push($error_messages, $message);
            } else {
                foreach($errors->all() as $error) {
                    $message = "{$row_no}行目の{$error}";
                    array_push($error_messages, $message);
                }
            }
        }

        return new HTMLString(implode("<br/>", $error_messages));
    }

    public function getUniqueErrorMessages() {
        $error_messages = [];

        foreach($this->unique_errors as $temp) {
            $row_no = $temp['row_no'];
            $column = $temp['column'];

            $message = "{$row_no}行目の{$column}の重複があります。";
            array_push($error_messages, $message);
        }

        return new HTMLString(implode("<br/>", $error_messages));
    }

    public static function beforeImport(BeforeImport $event)
    {
        //Log::info("beforeImport");
    }

    public static function afterImport(AfterImport $event)
    {
//        Log::info("AfterImport");
        throw new ImportValidationException();
    }

    public static function beforeSheet(BeforeSheet $event)
    {
        //Log::info("BeforeSheet");
    }

    public static function afterSheet(AfterSheet $event)
    {
        //Log::info("afterSheet");
    }

}
