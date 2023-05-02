@extends('admins.layouts.app')
@section('title', '商品詳細 | 商品管理')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.2/themes/default/style.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.2/jstree.min.js"></script>

    <h1>商品 - 詳細</h1>
    <div class="card p-5">
        <dl class="form-group row">
            <dt class="col-2">商品名</dt>
            <dd class="col-10">
                {{ old('name') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">商品画像</dt>
            <dd class="col-10">
                <div class="dropzone-preview d-flex flex-row flex-wrap">
                @if(old('files'))
                    @foreach(old('files') as $file)
                        <div class="dz-preview mr-2">
                            <img src="{{$file->url}}" class="img-fluid">
                            <input name="files[]" type="hidden" value="{{ $file }}">
                        </div>
                    @endforeach
                @endif
                </div>
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">メーカー</dt>
            <dd class="col-10">
                @php
                    $maker = old('maker_id') ? $makers->filter(function($maker,$key){return $maker->id==old('maker_id');})->first() : null;
                @endphp
                {{ $maker ? $maker->name : "" }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">JAN</dt>
            <dd class="col-10">
                {{ old('jan') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">価格</dt>
            <dd class="col-10">
                {{ old('price') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">最大注文数量</dt>
            <dd class="col-10">
                {{ old('max_amount') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">在庫確認</dt>
            <dd class="col-10">
                @if(1==old('is_stock'))
                    する
                @elseif(0==old('is_stock'))
                    しない
                @endif
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-sm-2">表示・非表示</dt>
            <dd class="col-sm-10">
                @if(1==old('is_visible'))
                    表示
                @elseif(0==old('is_visible'))
                    非表示
                @endif
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">商品カテゴリ</dt>
            <dd class="col-10">
                <div id="item-category-tree"></div>
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-sm-2">一覧用商品要約</dt>
            <dd class="col-sm-10">
            {!! old('abridge') !!}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-sm-2">商品概要</dt>
            <dd class="col-sm-10">
            {!! old('summary') !!}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-sm-2">商品説明のタイトル</dt>
            <dd class="col-sm-10">
            {{ textareaString(old('description_title')) }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-sm-2">商品説明</dt>
            <dd class="col-sm-10">
            {!! old('description') !!}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">特記事項</dt>
            <dd class="col-10">
            {!! old('notes') !!}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">スペック</dt>
            <dd class="col-10">
            @if(old('spec'))
            タイトル / 内容<br>
                @foreach(old('spec') as $spec)
                    {{$spec['title']}} /
                    {{$spec['body']}}
                    <br>
                @endforeach
            @else
            なし
            @endif
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-sm-2">ラベル</dt>
            <dd class="col-sm-10">
                @if(old('labels'))
                    @foreach($labels as $key => $value)
                        @if(in_array($key, (array)old('labels')))
                            {{$value}}
                        @endif
                    @endforeach
                @endif
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-sm-2">タグ</dt>
            <dd class="col-sm-10">
                {{old('tags')}}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-sm-2">セルフメディケーション</dt>
            <dd class="col-sm-10">
                @if(1==old('self_medication'))
                    対象
                @elseif(0==old('self_medication'))
                    非対象
                @endif
            </dd>
        </dl>
        <div class="card-footer">
            @include('admins.components.buttons.edit', ['url' => route('admin.item.edit', $id)])
            <form action="{{ route('admin.item.destroy', $id) }}" method="post" class="d-inline">
                {{ method_field('delete') }}
                {{ csrf_field() }}
                @include('admins.components.buttons.delete', ['name' => old('name')])
            </form>
        </div>
        <script>
            $(function(){
                $(function(){

                    let treeJson  = {!! $tree_json !!};

                    initCategoryTree(treeJson);
                });

                $('button[value="confirm"').click(function(){
                    let form = $('form');
                    let checkedIds = [];
                    $("#item-category-tree").jstree("get_checked",null,true).forEach(function(value, index, ar){
                        checkedIds.push(value);
                    });
                    checkedIds.forEach(function(value, index, ar){
                        $('<input>').attr({
                            'type': 'hidden',
                            'name': 'item_category_ids[]',
                            'value': value
                        }).appendTo(form);
                    });
                });
            });

            function initCategoryTree(treeJson){
                return $('#item-category-tree').jstree({
                    "plugins" : [ "checkbox" ],
                    "checkbox": { cascade: "up+undetermined", three_state: false },
                    'core':{
                        'data' : treeJson.data,
                        "check_callback" : function(operation, node, node_parent, node_position, more){
                                if (operation=="move_node"){
                                    return false;
                                }
                        },
                    },
                }).on('loaded.jstree', function(){
                    $(this).jstree('open_all');
                });
            }
        </script>

</div>
@endsection
