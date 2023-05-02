<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/27/2020
 * Time: 3:58 AM
 */

namespace App\Exports;

use Log;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Services\Item\ListItemsService;

class ItemsExport implements FromCollection, Responsable, WithHeadings, WithMapping
{
    use Exportable;

    private $fileName = 'items.csv';
    private $writerType = Excel::CSV;
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    public function __construct(ListItemsService $listItemsService, $condition)
    {
        $this->listItemsService = $listItemsService;
        $this->condition = $condition;
    }

    public function collection()
    {
        return $this->listItemsService->list($this->condition);
    }

    private function getMaxSpecCount() {
        $items = $this->collection();
        $max_count = 0;
        foreach($items as $item) {
            $temp_count = count($item->spec);
            if ($temp_count > $max_count) {
                $max_count =$temp_count;
            }
        }

        return $max_count;
    }

    public function headings(): array
    {
        $header = [
            'ID',
            'カテゴリID',
            'メーカーID',
            'JANコード',
            '商品名',
            '一覧用商品要約',
            '商品概要',
            '商品説明のタイトル',
            '商品説明',
            'ラベル',
            '特記事項',
            'タグ',
            'セルフメディケーション',
            '価格',
            '在庫確認',
            '表示・非表示',
            '購入上限数',
            'ソート順',
        ];

        for($i=1; $i<=20; $i++) {
            array_push($header, "スペックタイトル{$i}", "スペック本文{$i}");
        }

        return $header;
    }

    public function map($item): array
    {
        $row = [$item->id];

        //TODO: カテゴリIDが複数存在する場合は親子関係がある場合最下層のみ、親子関係が異なる場合はスペース区切りで複数出力
        $category_ids = [];
        foreach($item->item_categories as $category) {
            array_push($category_ids, $category->id);
        }

        array_push($row, implode(' ', $category_ids));

        $labels = [];
        if($item->labels){
            $labels = $item->labels;
        }
        array_push($row,
            $item->maker_id,
            $item->jan,
            $item->name,
            $item->abridge,
            $item->summary,
            $item->description_title,
            $item->description,
            implode(' ', $labels),
            $item->notes,
            $item->tags_text,
            $item->self_medication?'1':'0',
            $item->price,
            $item->is_stock?'1':'0',
            $item->is_visible?'1':'0',
            $item->max_amount,
            $item->sort_no,
        );
        if($item->spec){
            foreach($item->spec as $temp) {
                array_push($row, $temp['title'], $temp['body']);
            }
        }
        return $row;
    }

}
