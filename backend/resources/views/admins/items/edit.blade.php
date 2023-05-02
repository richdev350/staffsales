@extends('admins.layouts.app')
@php
    $isEdit = false;
    if (stristr(current_route_name(), 'edit') || stristr(current_route_name(), 'update')) {
        $isEdit = true;
    }

    $max_file = 3;
@endphp
@section('title', '商品'.($isEdit ? '編集' : '新規登録').' | 商品管理')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/min/dropzone.min.css" type="text/css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.2/themes/default/style.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.2/jstree.min.js"></script>
<script>
var myAwesomeDropzone = function(dropzone_id) {
    var max_file = {{ $max_file }};
	Dropzone.autoDiscover = false;
	$('#' + dropzone_id).dropzone({
		url                          : '{{ route('image.upload') }}',
		paramName                    : 'file',
		previewsContainer            : '.dropzone-preview',
		acceptedFiles                : 'image/*',
		maxFilesize                  : 10 , //MB
		addRemoveLinks               : true ,
		thumbnailWidth               : 512 , //px
		thumbnailHeight              : 512 , //px
		thumbnailMethod              : 'contain',
		maxFiles                     : max_file,
		parallelUploads              : 1,
		dictFileTooBig               : "ファイルが大きすぎます。 (@{{filesize}}MB). 最大サイズ: @{{maxFilesize}}MB.",
		dictInvalidFileType          : "画像ファイル以外はアップロード出来ません。",
		dictMaxFilesExceeded         : "一度にアップロード出来るファイルを越えています。",
		dictRemoveFile               :'[×]' ,
		dictCancelUpload             :'キャンセル' ,
		dictCancelUploadConfirmation : 'アップロードをキャンセルします。よろしいですか？' ,
		dictDefaultMessage           : "ドラッグ&ドロップで追加<br />または<br />クリックしてファイルを選択してください",

		success:function(file, rt, xml){
            // ファイルエラーチェック
            if(rt.status == '400'){
                this.removeFile(file);
                alert(rt.data);
                return false;
            }
			// それぞれのファイルアップロードが完了した時の処理
            file.previewElement.classList.add("dz-success");
			$(file.previewElement).find('.dz-success-mark').hide();
			// サーバ上のファイル名をセット
			$(file.previewElement).find('span[data-dz-name]').append('<input type="hidden" name="files[]" value="' + rt.data + '">');
            // ドラッグ禁止エリア指定
            $(file.previewElement).find('.dz-remove').addClass('js-not-sortable');

			// 既存画像がある場合上限を超えるので越えた分を削除
			if($('.dropzone-preview').children().length > max_file){
				$('.dropzone-preview div.dz-preview:last-child').remove();
			}
			if($('.dropzone-preview').children().length == max_file){
				$('#' + dropzone_id).hide();
			}
		},
		processing: function(file){
            file.previewElement.classList.add("mr-2");
			// ファイルサイズ・アイコン非表示
			$(file.previewElement).find('.dz-size').hide();
			$(file.previewElement).find('.dz-success-mark').hide();
			$(file.previewElement).find('.dz-error-mark').hide();
		} ,
		error:function(file, _error_msg){
            this.removeFile(file);
            alert(_error_msg);
		},
		removedfile:function(file){
			var ref;
            $('#' + dropzone_id).show();
			(ref = file.previewElement) != null ? ref.parentNode.removeChild(file.previewElement) : void 0;
		}
	});
	// 最大数に達した場合ドロップエリア非表示
	if($('.dropzone-preview').children().length >= max_file){
		$('#' + dropzone_id).hide();
	}else{
		$('#' + dropzone_id).show();
	}
}

</script>

    <h1>商品- {{ $isEdit ? '編集' : '新規登録' }}</h1>
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
                    <input name="name" type="text" class="form-control" value="{{ old('name') }}">
                    @foreach ($errors->get('name') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">商品画像<small class="ml-3">※画像について：最大{{ $max_file }}枚、1枚あたり10MBまで</small></dt>
                <dd class="col-10">
                    <div class="dropzone-preview d-flex flex-row flex-wrap"></div>
                    <div class="dropzone" id="js-dropzone"></div>
                    @if(old('files'))
                        @php $count = 0; @endphp
                        @foreach(old('files') as $file)
                            @if(isset($file->id))
                            <input type="hidden" value="{{ $file->id }}" name="old_file_ids[]">
                            <script>
                            $('.dropzone-preview').append('<div class="dz-preview dz-image-preview dz-success dz-complete mr-2"><div class="dz-image"><img data-dz-thumbnail="" alt="" src="{{ $file->url }}"></div><div class="dz-details"><div class="dz-filename"><span data-dz-name=""><input type="hidden" name="files[]" value="{{ $file->from_public_path }}"><input type="hidden" name="image_x[]" value=""><input type="hidden" name="image_y[]" value=""><input type="hidden" name="image_w[]" value=""><input type="hidden" name="image_h[]" value=""></span></div></div><div class="dz-success-mark"></div><a class="dz-remove-item js-not-sortable" href="javascript:undefined;" data-dz-remove="">[×]</a></div>');
                            </script>
                            @else
                                @php $file_url = config('app.root_path').$file; @endphp
                                <script>
                                $('.dropzone-preview').append('<div class="dz-preview dz-image-preview dz-success dz-complete mr-2"><div class="dz-image"><img data-dz-thumbnail="" alt="" src="{{ $file_url }}"></div><div class="dz-details"><div class="dz-filename"><span data-dz-name=""><input type="hidden" name="files[]" value="{{ $file }}"><input type="hidden" name="image_x[]" value=""><input type="hidden" name="image_y[]" value=""><input type="hidden" name="image_w[]" value=""><input type="hidden" name="image_h[]" value=""></span></div></div><div class="dz-success-mark"></div><a class="dz-remove-item js-not-sortable" href="javascript:undefined;" data-dz-remove="">[×]</a></div>');
                                </script>
                            @endif
                            @php $count ++; @endphp
                        @endforeach
                        @if(old('old_file_ids'))
                            @foreach(old('old_file_ids') as $old_file_id)
                            <input name="old_file_ids[]" type="hidden" value="{{ $old_file_id }}">
                            @endforeach
                        @endif
                    @endif
                    <script>
                    myAwesomeDropzone('js-dropzone');
                    </script>
                    @foreach ($errors->get('files') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">メーカー</dt>
                <dd class="col-10">
                    <select name="maker_id" class="form-control">
                        <option value="">選択してください</option>
                        @foreach($makers as $maker)
                            <option value="{{$maker->id}}" @if($maker->id===old('maker_id')) selected @endif>{{$maker->name}}</option>
                        @endforeach
                    </select>
                    @foreach ($errors->get('maker_id') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">JAN</dt>
                <dd class="col-10">
                    <input name="jan" type="text" class="form-control" value="{{ old('jan') }}">
                    @foreach ($errors->get('jan') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">価格</dt>
                <dd class="col-10">
                    <input name="price" type="text" class="form-control" value="{{ old('price') }}">
                    @foreach ($errors->get('price') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">最大注文数量</dt>
                <dd class="col-10">
                    <input name="max_amount" type="text" class="form-control" value="{{ old('max_amount') }}">
                    @foreach ($errors->get('max_amount') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">在庫確認</dt>
                <dd class="col-10">
                    <div class="form-check">
                        <input name="is_stock" type="radio" class="form-check-input" value="1" @if(is_null(old('is_stock')) || 1===old('is_stock')) checked @endif>
                        <label class="form-check-label">する</label>
                    </div>
                    <div class="form-check">
                        <input name="is_stock" type="radio" class="form-check-input" value="0" @if(0===old('is_stock')) checked @endif>
                        <label class="form-check-label">しない</label>
                    </div>
                    @foreach ($errors->get('is_stock') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-sm-2 required">表示・非表示</dt>
                <dd class="col-sm-10">
                    <div class="form-check">
                        <input name="is_visible" type="radio" class="form-check-input" value="1" @if(is_null(old('is_visible')) || 1===old('is_visible')) checked @endif>
                        <label class="form-check-label">表示</label>
                    </div>
                    <div class="form-check">
                        <input name="is_visible" type="radio" class="form-check-input" value="0" @if(0===old('is_visible')) checked @endif>
                        <label class="form-check-label">非表示</label>
                    </div>
                    @foreach ($errors->get('is_visible') as $error)
                        <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">商品カテゴリ</dt>
                <dd class="col-10">
                    <div id="item-category-tree"></div>
                    @foreach ($errors->get('item_category_ids') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-sm-2">一覧用商品要約</dt>
                <dd class="col-sm-10">
                    <textarea class="form-control ckeditor" name="abridge">{{ old('abridge') }}</textarea>
                    @foreach ($errors->get('abridge') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-sm-2">商品概要</dt>
                <dd class="col-sm-10">
                    <textarea class="form-control ckeditor" name="summary">{{ old('summary') }}</textarea>
                    @foreach ($errors->get('summary') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-sm-2">商品説明のタイトル</dt>
                <dd class="col-sm-10">
                    <input name="description_title" type="text" class="form-control" value="{{ old('description_title') }}">
                    @foreach ($errors->get('description_title') as $error)
                        <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-sm-2">商品説明</dt>
                <dd class="col-sm-10">
                    <textarea class="form-control ckeditor" name="description">{{ old('description') }}</textarea>
                    @foreach ($errors->get('description') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">特記事項</dt>
                <dd class="col-10">
                    <textarea class="form-control" name="notes">{{ old('notes') }}</textarea>
                    @foreach ($errors->get('notes') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">スペック</dt>
                <dd class="col-10">
                    <p>
                        タイトル / 内容
                    </p>
                    <div id="spec-items" class="container-fluid mb-2">
                    @php $count = 0; @endphp
                    @if(old('spec'))
                        @foreach(old('spec') as $spec)
                            @if($loop->first)
                                @php $count = $loop->count; @endphp
                            @endif
                            <div class="spec-item row ml-2">
                                <input class="form-control col-sm-4 mr-2" name="spec[{{$loop->index}}][title]" value="{{$spec['title']}}" />
                                <input class="form-control col-sm-4 mr-2" name="spec[{{$loop->index}}][body]" value="{{$spec['body']}}" />
                                <button type="button" class="btn-remove-spec btn btn-outline-secondary">-</button>
                            </div>
                            @foreach ($errors->get('spec.'.$loop->index.'.title') as $error)
                            <p class="alert alert-danger">{{ $error }}</p>
                            @endforeach
                            @foreach ($errors->get('spec.'.$loop->index.'.body') as $error)
                            <p class="alert alert-danger">{{ $error }}</p>
                            @endforeach
                        @endforeach
                    @endif
                    </div>
                    <button type="button" id='btn-add-spec' class="btn btn-outline-secondary">+</button>
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-sm-2">ラベル</dt>
                <dd class="col-sm-10">
                    @foreach($labels as $key => $value)
                        <input type="checkbox" name="labels[]" class="" value="{{$key}}" @if(in_array($key, (array)old('labels'))) checked @endif>
                        <label class="form-check-label">{{$value}}</label>
                        @foreach ($errors->get('labels.'.$key) as $error)
                            <p class="alert alert-danger">{{ $error }}</p>
                        @endforeach
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-sm-2">タグ</dt>
                <dd class="col-sm-10">
                    <input type="text" name="tags" class="form-control" value="{{old('tags')}}" placeholder="タグを複数入力する場合は半角・全角スペースで入力してください">
                    @foreach ($errors->get('tags.'.$key) as $error)
                        <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-sm-2 required">セルフメディケーション</dt>
                <dd class="col-sm-10">
                    <div class="form-check">
                        <input name="self_medication" type="radio" class="form-check-input" value="1" @if(1==old('self_medication')) checked @endif>
                        <label class="form-check-label">対象</label>
                    </div>
                    <div class="form-check">
                        <input name="self_medication" type="radio" class="form-check-input" value="0" @if(is_null(old('self_medication')) || 0==old('self_medication')) checked @endif>
                        <label class="form-check-label">非対象</label>
                    </div>
                    @foreach ($errors->get('self_medication') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <div class="card-footer">
                @include('admins.components.buttons.confirm')
                @include('admins.components.buttons.back_list', ['url' => url('admin/item/list')])
            </div>

        </form>
    </div>
    <script>
    $(function(){
        $(function(){
            let itemCategoryIds = [];
            @if(old('item_category_ids[]'))
                itemCategoryIds = {{ old('item_category_ids[]') }};
            @endif
            let treeJson  = {!! $tree_json !!};
            initCategoryTree(treeJson);
        });

        $('.dz-remove-item').click(function(){
            $('#js-dropzone').show();
            $(this).parent().remove();
        });

        let count = {{$count}};
        $('#btn-add-spec').click(function(){
            $template = '<div class="spec-item row ml-2"><input class="form-control col-sm-4 mr-2" name="spec['+count+'][title]" value="" /><input class="form-control col-sm-4 mr-2" name="spec['+count+'][body]" value="" /><button type="button" class="btn-remove-spec btn btn-outline-secondary">-</button></div>';
            $('#spec-items').append($template);
            count++;
        });
        $(document).on("click", ".btn-remove-spec", function(){
            $(this).parent().remove();
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
