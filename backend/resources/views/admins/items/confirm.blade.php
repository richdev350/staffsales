@extends('admins.layouts.app')
@php
    $isEdit = false;
    if (stristr(current_route_name(), 'edit') || stristr(current_route_name(), 'update')) {
        $isEdit = true;
    }

@endphp
@section('title', '商品'.($isEdit ? '編集' : '登録').'確認 | 商品管理')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/min/dropzone.min.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.2/themes/default/style.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.2/jstree.min.js"></script>

    <h1>商品 - {{ $isEdit ? '編集' : '登録' }}確認</h1>
    <div class="card p-5">
        <form
            @if ($isEdit)
                action="{{ route('admin.item.update', ['id' => $id]) }}"
            @else
                action="{{ route('admin.item.store') }}"
            @endif
            method="post" class="register-form">
            @if ($isEdit)
                {{ method_field('patch') }}
            @endif
            {{ csrf_field() }}
            <dl class="form-group row">
                <dt class="col-2">商品名</dt>
                <dd class="col-10">
                    {{ old('name') }}
                    <input name="name" type="hidden" value="{{ old('name') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">商品画像</dt>
                <dd class="col-10">
                    <div class="dropzone-preview d-flex flex-row flex-wrap">
                    @if(old('files'))
                        @foreach(old('files') as $file)
                            <div class="dz-preview mr-2">
                                @if(preg_match('/\/tmp\//', $file))
                                <img src="{{ config('app.root_path').$file }}" class="img-fluid">
                                @else
                                <img src="{{ asset($file) }}" class="img-fluid">
                                @endif
                                <input name="files[]" type="hidden" value="{{ $file }}">
                            </div>
                        @endforeach
                    @endif
                    </div>
                    @if(old('old_file_ids'))
                        @foreach(old('old_file_ids') as $old_file_id)
                        <input name="old_file_ids[]" type="hidden" value="{{ $old_file_id }}">
                        @endforeach
                    @endif
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">メーカー</dt>
                <dd class="col-10">
                    @php
                        $maker = old('maker_id') ? $makers->filter(function($maker,$key){return $maker->id==old('maker_id');})->first() : null;
                    @endphp
                    {{ $maker ? $maker->name : "" }}
                    <input name="maker_id" type="hidden" value="{{ old('maker_id') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">JAN</dt>
                <dd class="col-10">
                    {{ old('jan') }}
                    <input name="jan" type="hidden" value="{{ old('jan') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">価格</dt>
                <dd class="col-10">
                    {{ old('price') }}
                    <input name="price" type="hidden" value="{{ old('price') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">最大注文数量</dt>
                <dd class="col-10">
                    {{ old('max_amount') }}
                    <input name="max_amount" type="hidden" value="{{ old('max_amount') }}">
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
                    <input name="is_stock" type="hidden" value="{{ old('is_stock') }}">
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
                    <input name="is_visible" type="hidden" value="{{ old('is_visible') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">商品カテゴリ</dt>
                <dd class="col-10">
                    <div id="item-category-tree"></div>
                    @foreach(old('item_category_ids') as $item_category_id)
                    <input name="item_category_ids[]" type="hidden" value="{{ $item_category_id }}">
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-sm-2">一覧用商品要約</dt>
                <dd class="col-sm-10">
                    {!! old('abridge') !!}
                    <input name="abridge" type="hidden" value="{{ old('abridge') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-sm-2">商品説明のタイトル</dt>
                <dd class="col-sm-10">
                    {{ textareaString(old('description_title')) }}
                    <input name="description_title" type="hidden" value="{{ old('description_title') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-sm-2">商品概要</dt>
                <dd class="col-sm-10">
                    {!! old('summary') !!}
                    <input name="summary" type="hidden" value="{{ old('summary') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-sm-2">商品説明</dt>
                <dd class="col-sm-10">
                    {!! old('description') !!}
                    <input name="description" type="hidden" value="{{ old('description') }}">
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">特記事項</dt>
                <dd class="col-10">
                    {!! old('notes') !!}
                    <input name="notes" type="hidden" value="{{ old('notes') }}">
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
                        <input type="hidden" name="spec[{{$loop->index}}][title]" value="{{$spec['title']}}" />
                        <input type="hidden" name="spec[{{$loop->index}}][body]" value="{{$spec['body']}}" />
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
                                <input type="hidden" name="labels[]" class="" value="{{$key}}">
                            @endif
                        @endforeach
                    @endif
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-sm-2">タグ</dt>
                <dd class="col-sm-10">
                    {{old('tags')}}
                    <input type="hidden" name="tags" value="{{old('tags')}}">
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
                    <input name="self_medication" type="hidden" value="{{ old('self_medication') }}">
                </dd>
            </dl>
            <div class="card-footer">
                @include('admins.components.buttons.regist')
                @include('admins.components.buttons.back')
            </div>

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
@endsection
