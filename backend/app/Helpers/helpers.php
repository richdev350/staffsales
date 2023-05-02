<?php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use App\Models\Entities\ItemCategory;
use Illuminate\Support\Facades\Cache;

function is_mobile()
{
    $useragents = [
        'iPhone', // iPhone
        'Android(.*)Mobile',
        'BB10'
    ];
    $pattern = '/' . implode('|', $useragents) . '/i';

    return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}

if (! function_exists('current_route_name')) {
    /**
     * 現在のルート名を返す
     *
     * @return string
     */
    function current_route_name() {
        return Route::currentRouteName();
    }
}


if (! function_exists('current_controller_name')) {
    /**
     * 現在のコントローラ名を返す
     *
     * @return string
     */
    function current_controller_name() {
        list($currentControllerName, $currentActionName) = explode('@', class_basename(Route::currentRouteAction()));
        $currentControllerName = Str::snake($currentControllerName, '-');
        $temp = explode('-', $currentControllerName);
        array_pop($temp);
        $currentControllerName = implode('-', $temp);

        return $currentControllerName;
    }
}


if (! function_exists('current_action_name')) {
    /**
     * 現在のアクション名を返す
     *
     * @return string
     */
    function current_action_name() {
        list($currentControllerName, $currentActionName) = explode('@', class_basename(Route::currentRouteAction()));
        $currentActionName = Str::snake($currentActionName, '-');

        return $currentActionName;
    }
}


if (! function_exists('load_php_files')) {
    /**
     * 指定ディレクトリ以下に設置したPHPファイルを読み込む
     *
     * @param  string  $directory
     * @return void
     */
    function load_php_files(string $directory)
    {
        try {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator((string) $directory));
            $file_array = [];
            while ($iterator->valid()) {
                if (! $iterator->isDot() && $iterator->isFile() && $iterator->isReadable() && 'php' === $iterator->current()->getExtension()) {
                    //require_once $iterator->key();
                    $file_array[] = $iterator->key();
                }

                $iterator->next();
            }

            // 環境によってソート順が異なる場合があるのでサブフォルダ優先となるように降順にソート
            rsort($file_array);

            foreach($file_array as $file){
                require_once $file;
            }

        } catch (Exception $exception) {
            report($exception);
        }
    }
}


if (! function_exists('get_client_ip')) {
    /**
     * クライアントIPアドレスを取得して返す
     *
     * @return string
     */
    function get_client_ip()
    {
        $server = request()->server();

        $clientIp = isset($server['REMOTE_ADDR']) ? $server['REMOTE_ADDR'] : null;
        if (! empty($server['HTTP_CLIENT_IP'])) {
            $clientIp = $server['HTTP_CLIENT_IP'];
        } elseif (! empty($server['HTTP_X_FORWARDED_FOR'])) {
            $clientIp = $server['HTTP_X_FORWARDED_FOR'];
            // サーバを経由する場合は "111.222.333.444, 999.888.777.666" のようにコンマ区切りで経由サーバのIPアドレスも一緒に取得される場合があるので、その場合は本来のクライアントIPアドレスのみを取得する
            $temp = explode(',', $clientIp);
            $clientIp = trim(array_shift($temp));
        }

        return $clientIp;
    }
}


if (! function_exists('pagination_slide_range')) {
    /**
     * ページネーションのページ範囲をスライド表示対応で、指定ページ数のみの範囲を返す
     *
     * @param  array  $fullRange
     * @param  int    $currentPage
     * @param  int    $pageCount
     * @return array
     */
    function pagination_slide_range(array $fullRange, int $currentPage, int $pageCount = 10) {
        $pageIndex    = array_search($currentPage, $fullRange);
        $reverseIndex = array_search($currentPage, array_reverse($fullRange));

        $padding = $pageCount / 2;

        $slidingRange = [];
        if ($padding < $pageIndex && $padding <= $reverseIndex) {
            $slidingRange = array_slice($fullRange, ($pageIndex - $padding), $pageCount);
        } elseif ($padding > $reverseIndex) {
            $slidingRange = array_slice($fullRange, ($pageCount * -1), $pageCount);
        } else {
            $slidingRange = array_slice($fullRange, 0, $pageCount);
        }

        return $slidingRange;
    }
}

if (! function_exists('trim_ascii_control_chars')) {
    /**
     * ASCII制御文字を取り除いて返す
     *
     * @param  string  $string
     * @param  array   $except  変換対象外にする文字のプレースホルダ名と値のリスト
     * @return string
     */
    function trim_ascii_control_chars(string $string, array $except = ['CRLF' => "\r\n", 'CR' => "\r", 'LF' => "\n"]) {
        if (! is_string($string)) {
            return $string;
        }

        $exceptPlaceholders = array_map(function($val) {
            return '{{%' . $val . '%}}';
        }, array_keys($except));
        $exceptValues = array_values($except);

        $string = str_replace($exceptValues, $exceptPlaceholders, $string);
        $string = preg_replace('/[\x00-\x1F\x7F]/', '', $string);
        $string = str_replace($exceptPlaceholders, $exceptValues, $string);

        return $string;
    }
}


if (! function_exists('is_json')) {
    /**
     * JSON形式文字列かどうかを返す
     *
     * @param  string  $string
     * @return string
     */
    function is_json($string) {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}


if (! function_exists('csv2array')) {
    /**
     * CSVを読み込んで配列でデータを返す
     *
     * @param  string  $csv  CSVファイルのパスまたはCSV文字列
     * @param  string  $outputEncoding
     * @param  string  $csvEncoding
     * @param  string  $delimiter
     * @return array
     */
    function csv2array(string $csv, string $outputEncoding = 'UTF-8', string $csvEncoding = 'UTF-8', string $delimiter = ',') {
        setlocale(LC_ALL, 'ja_JP');

        if (is_file($csv)) {
            $tmpData = file_get_contents($csv);
        } else {
            $tmpData = $csv;
        }
        if ($outputEncoding != $csvEncoding) {
            $tmpData = mb_convert_encoding($tmpData, $outputEncoding, $csvEncoding);
        }

        $tmpFile = tmpfile();
        $data    = [];

        fwrite($tmpFile, $tmpData);
        rewind($tmpFile);

        while (false !== ($tmpData = fgetcsv($tmpFile, 0, $delimiter))) {
            $data[] = $tmpData;
        }
        fclose($tmpFile);

        return $data;
    }
}


if (! function_exists('output_download_contents')) {
    /**
     * ダウンロード用に出力をバッファリングして指定byteずつ出力する(大容量ファイル対応)
     *
     * @param  string  $downloadFile  ダウンロードするファイルのパス
     * @return void
     */
    function output_download_contents(string $downloadFile) {
        // 出力バッファリングが有効な場合は一旦すべてのネストにおいて無効にする
        while (0 < ob_get_level()) {
            ob_end_clean();
        }
        // 改めて出力バッファリングを有効にして、指定byteずつ出力する
        ob_start();
        if ($fileHandle = fopen($downloadFile, 'rb')) {
            while (! feof($fileHandle) && 0 == connection_status()) {
                echo fread($fileHandle, 4096);
                ob_flush();
            }
            ob_flush();
            fclose($fileHandle);
        }
        ob_end_clean();
    }
}


if (! function_exists('safe_mix')) {
    /**
     * バージョン付けしたMixファイル、または見つからなかった場合は指定アセットファイルのパス（ルート相対パス）を返す
     *
     * @param  string  $path
     * @param  string  $manifestDirectory
     * @return string
     */
    function safe_mix($path, $manifestDirectory = '') {
        try {
            return mix($path, $manifestDirectory);
        } catch (\Exception $exception) {
            if (! Str::startsWith($path, '/')) {
                $path = "/{$path}";
            }

            if ($manifestDirectory && ! Str::startsWith($manifestDirectory, '/')) {
                $manifestDirectory = "/{$manifestDirectory}";
            }

            return new HtmlString($manifestDirectory . $path);
        }
    }
}


if (! function_exists('asset_mix')) {
    /**
     * バージョン付けしたMixファイル、または見つからなかった場合は指定アセットファイルのURLを返す
     *
     * @param  string  $path
     * @param  string  $manifestDirectory
     * @param  bool    $secure
     * @return string
     */
    function asset_mix($path, $manifestDirectory = '', $secure = null) {
        return asset(safe_mix($path, $manifestDirectory), $secure);
    }
}


if (! function_exists('secure_asset_mix')) {
    /**
     * バージョン付けしたMixファイル、または見つからなかった場合は指定アセットファイルのセキュアURLを返す
     *
     * @param  string  $path
     * @param  string  $manifestDirectory
     * @return string
     */
    function secure_asset_mix($path, $manifestDirectory = '') {
        return secure_asset(safe_mix($path, $manifestDirectory));
    }
}


if (! function_exists('path_to_asset_url')) {
    /**
     * ファイルのパスをURLに変換して返す
     *
     * @param  string  $path
     * @return string
     */
    function path_to_asset_url(string $path) {
        return asset(str_replace(public_path(), '', $path));
    }
}


if (! function_exists('get_resized_image_path')) {
    /**
     * リサイズ画像のファイルパスを返す
     *
     * @param  \App\Model\Entities\Storage\File|string  $originalFile  モデルインスタンス or ファイルパス
     * @param  string                                   $criterion  リサイズ基準  width|height|long_side
     * @param  int                                      $length
     * @param  bool                                     $autoGenerate  リサイズ済み画像ファイルが存在しない場合に自動生成するかどうか
     * @return string
     * @throws Exception
     */
    function get_resized_image_path($originalFile, string $criterion, int $length, bool $autoGenerate = true) {
        if ($originalFile instanceof \App\Model\Entities\Storage\File) {
            $originalFilePath = $originalFile->path;
        } elseif (is_string($originalFile)) {
            $originalFilePath = $originalFile;
        } else {
            throw new Exception(sprintf('Type error: get_resized_image_url() expects parameter 1 to be instanceof \App\Model\Entities\Storage\File or string, %s given.', gettype($originalFile)));
        }

        if (! is_file($originalFilePath)) {
            throw new Exception(sprintf('No such file. file: %s', $originalFilePath));
        } elseif (! preg_match('/^image\//', mime_content_type($originalFilePath))) {
            throw new Exception(sprintf('The file is not image. file: %s', $originalFilePath));
        }

        if (false === array_search($criterion, ['width', 'height', 'long_side'])) {
            throw new Exception(sprintf('Invalid criterion. criterion: %s', $criterion));
        }

        $pathInfo = pathinfo($originalFilePath);
        list($originalWidth, $originalHeight) = getimagesize($originalFilePath);

        // 長辺基準のリサイズの場合は、縦横どちらが長辺かを調べてリサイズ基準をセットし直す
        if ('long_side' == $criterion) {
            $originalLongSide = $originalWidth > $originalHeight ? $originalWidth : $originalHeight;
            $criterion        = $originalLongSide == $originalHeight ? 'height' : 'width';
        }

        // リサイズ基準に基づいて画像ファイル名を決定する（リサイズ画像サイズが元画像サイズ以上の場合は元画像のパスを返す）
        if ('width' == $criterion) {
            if ($originalWidth <= $length) {
                return $originalFilePath;
            }

            $resizedFileName = $pathInfo['filename'] . '-w' . $length . '.' . $pathInfo['extension'];
        } elseif ('height' == $criterion) {
            if ($originalHeight <= $length) {
                return $originalFilePath;
            }

            $resizedFileName = $pathInfo['filename'] . '-h' . $length . '.' . $pathInfo['extension'];
        }

        $resizedFilePath = $pathInfo['dirname'] . DIRECTORY_SEPARATOR . $resizedFileName;

        if ($autoGenerate) {
            if (! is_file($resizedFilePath)) {
                $result = resize_image($originalFilePath, $resizedFilePath, $criterion, $length);
            } elseif (filemtime($resizedFilePath) < filemtime($originalFilePath)) {
                $result = resize_image($originalFilePath, $resizedFilePath, $criterion, $length);
            } else {
                $result = true;
            }

            if (! $result || ! is_file($resizedFilePath)) {
                throw new Exception('Failure in resize image.');
            }
        }

        return $resizedFilePath;
    }
}

if (! function_exists('weekday2youbi')) {
    /**
     * 日付文字列に含まれる英語表記の曜日を日本語表記の曜日に変換して返す
     *
     * @param  string  $string
     * @return string
     */
    function weekday2youbi($string) {
        $mapping = [
            'Sun' => '日',
            'Mon' => '月',
            'Tue' => '火',
            'Web' => '水',
            'Thu' => '木',
            'Fri' => '金',
            'Sat' => '土',
        ];
        return str_replace(array_keys($mapping), array_values($mapping), $string);
    }
}


/** ==================== 以下デバッグ用 ==================== **/

if (! function_exists('d')) {
    /**
     * デバッグ表示用 var_dump() のエイリアス
     * HTMLの<pre>タグで整形して表示
     *
     * @param  mixed  $arg
     * @return void
     */
    function d($arg = null) {
        echo PHP_EOL;
        echo '<pre style="clear: both; box-sizing: border-box; width: 100%; max-width: 100%; padding: 5px; border: 1px solid #666; background-color: #fff; color: #444; font-family: monospace; font-size: 12px; text-align: left; overflow-x: auto;">';
        echo PHP_EOL;
        var_dump($arg);
        echo '</pre>';
        echo PHP_EOL;
    }
}

if (! function_exists('p')) {
    /**
     * デバッグ表示用 print_r() のエイリアス
     * HTMLの<pre>タグで整形して表示
     *
     * @param  mixed  $arg
     * @return void
     */
    function p($arg = null) {
        echo PHP_EOL;
        echo '<pre style="clear: both; box-sizing: border-box; width: 100%; max-width: 100%; padding: 5px; border: 1px solid #666; background-color: #fff; color: #444; font-family: monospace; font-size: 12px; text-align: left; overflow-x: auto;">';
        echo PHP_EOL;
        print_r($arg);
        echo '</pre>';
        echo PHP_EOL;
    }
}

if (! function_exists('addJanCodeCheckDigit')) {
    function addJanCodeCheckDigit($value) {
        $numbers = str_split($value);
        $odd_sum = 0;
        $even_sum = 0;
        foreach($numbers as $index => $number){
           if($index % 2 == 0) {
               $odd_sum += intval($number);
           } else {
               $even_sum += intval($number);
           }
        }
        $check_digit = 10 - intval(substr((string) (($even_sum * 3) + $odd_sum),-1));
        return $value . ($check_digit === 10 ? 0 : $check_digit);
    }
}

if (! function_exists('generateBarCode')) {
    function generateBarCode($order_id, $secure_code) {
        //【EcType ： 数字2桁】【OrderNo ： 数字8桁】【ReceiveStoreNo ： 数字5桁従販では「00000」固定】【SecureCode : 数字8桁】【チェックデジット：数字1桁】計24桁
        $bar_code = "03" . sprintf("%08d", $order_id) . "00000" . $secure_code;
        $bar_code =  addJanCodeCheckDigit($bar_code);
        return $bar_code;
    }
}

if (! function_exists('items_count_of_maker')) {
    function items_count_of_maker($maker) {
        if(!Cache::has('items_count_of_maker.'.$maker->id)){
            $count = count($maker->visible_items);
            Cache::put('items_count_of_maker.'.$maker->id, $count, config('cache.lifetime'));
            return $count;
        }else{
            return Cache::get('items_count_of_maker.'.$maker->id);
        }
    }
}

if (! function_exists('price_search_list')) {
    function price_search_list()
    {
        $prices = [
            [
                "min" => null,
                "max" => 1000,
                "label" => "￥1,000未満"
            ],
            [
                "min" => 1000,
                "max" => 2000,
                "label" => "￥1,000 ～ ￥1,999"
            ],
            [
                "min" => 2000,
                "max" => 4000,
                "label" => "￥2,000 ～ ￥3,999"
            ],
            [
                "min" => 4000,
                "max" => 7000,
                "label" => "￥4,000 ～ ￥6,999"
            ],
            [
                "min" => 7000,
                "max" => 10000,
                "label" => "￥7,000 ～ ￥9,999"
            ],
            [
                "min" => 10000,
                "max" => null,
                "label" => "￥10,000以上"
            ]
        ];
        return $prices;
    }
}

if (! function_exists('star_mark')) {
    function star_mark($level) {
        $star = "";
        for($i=0; $i<$level; $i++) {
            $star .= "★";
        }

        return $star;
    }
}

if (! function_exists('file_url')) {
    function file_url($file) {
        $url = "/img/no_image.png";

        if (isset($file->url)) {
            $url = $file->url;
        }

        return $url;
    }
}

if (! function_exists('escape_filter')) {
    function escape_filter($keyword) {
        $keyword = str_replace("%", "\\%", $keyword);
        $keyword = str_replace("'", "\'", $keyword);
        return $keyword;
    }
}

if (! function_exists('get_keyword_array')) {
    function get_keyword_array($keyword)
    {
        $words = preg_split("/( |　)+/", trim($keyword));
        return $words;
    }
}

if (! function_exists('change_special_char')) {
    function change_special_char($keyword, $is_encode = true) {
        $change_list = [["/", "!!!!^^^^"], ["\\", "^^^^!!!!"]];
        foreach($change_list as $change) {
            if ($is_encode) {
                $keyword = str_replace($change[0], $change[1], $keyword);
            } else {
                $keyword = str_replace($change[1], $change[0], $keyword);
            }

        }
        return $keyword;
    }
}

if (! function_exists('category_tree')) {
    function category_tree($categories, $depth = 1) {
        $html = "";
        foreach($categories as $category) {
            if ($category->items_count == 0) {
                continue;
            }

            $html .= '<li class="level1">';
            $html .= '<p class="menu1 parent-category">';
            $html .= '<a href="javascript:void(0);" data="' . $category->id . '"  class="category-link">' . $category->name . '(' . $category->items_count . ')</a>';
            $html .= '</p>';

            if(!empty($category->children)) {
                $html .= '<ul class="child" style="max-height: unset; padding-left: ' . $depth * 20 . 'px;">';
                $html .= category_tree($category->children, $depth + 1);
                $html .= '</ul>';
            }
            $html .= '</li>';
        }
        return $html;
    }
}

if (! function_exists('category_tree_select')) {
    function category_tree_select($categories) {
        $result = [];
        foreach($categories as $category) {
            $node = [
                "id" => $category->id,
                "title" => $category->name,
            ];

            if($category->children && $category->children->count() > 0) {
                $node['subs'] = category_tree_select($category->children);
            }
            $result[] = $node;
        }
        return $result;
    }
}

if (! function_exists('get_category_tree_data')) {
    function get_category_tree_data() {
        $category_tree_data_string = "";
        $category_tree_data_key = "category_tree_data_key";
        if(Cache::has($category_tree_data_key)){
            $category_tree_data_string = Cache::get($category_tree_data_key);
        }else{
            $category_tree_data_string = category_tree_select(ItemCategory::getTree());
            Cache::put($category_tree_data_key, $category_tree_data_string, config('cache.lifetime'));
        }
        return json_encode($category_tree_data_string);
    }
}

if (! function_exists('textareaString')) {
    function textareaString($string) {
        return new HtmlString(nl2br(htmlspecialchars($string, ENT_QUOTES, 'UTF-8')));
    }
}

if (! function_exists('clearCategoryCache')) {
    function clearCategoryCache()
    {
        $cache_keys = [
            "items_count_of_category"
        ];

        foreach($cache_keys as $cache_key) {
            Cache::forget($cache_key);
        }
    }
}
if (! function_exists('AllowIpsOnMaintenance')) {
    function AllowIpsOnMaintenance()
    {
        $res = config('app.allow_ips_on_maintenance');
        $data = [];
        $main = false;
        $ip = explode(',', $res);
        for($i = 0; $i < count($ip); $i++) {
            if (strpos($ip[$i], '/')) {
                $slash = explode('/', $ip[$i]);
                $value = explode('.', $slash[0]);
                for($k = $value[3]; $k < $slash[1] + 1; $k++) {
                    $ip_address = $value[0] . '.' . $value[1] . '.' . $value[2] . '.' . $k;
                    array_push($data, $ip_address);
                }
            } else {
                array_push($data, $ip[$i]);
            }
        }
        
        if(in_array(request()->getClientIp(), $data)) {
            $main = true;
        }
        return $main;
    }
}