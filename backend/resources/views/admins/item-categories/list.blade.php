@extends('admins.layouts.app')

@section('title', '商品カテゴリ一覧 | 商品カテゴリ管理')
@section('content')
    @include('generals.components.loading')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.2/themes/default/style.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.2/jstree.min.js"></script>
    <div class="mb-2">
        <h1>商品カテゴリ一覧</h1>
    </div>

    <div id="item-category-tree"></div>

    <script>
        $(function(){
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });
            let treeJson  = {!! $tree_json !!};
            initCategoryTree(treeJson);

            $('#item-category-tree').on('create_node.jstree', function (event, categoryData) {
                let request = {
                    parent_id:categoryData.node.parent,
                    name:categoryData.node.text
                };

                $.ajax({
                    url: '{{ route('item-category.store') }}',
                    type: 'POST',
                    datatype: 'json',
                    data: request,
                    beforeSend: function(){
                        dispLoading();
                    }
                })
                .then(
                    function(response){
                        if(response.errors){
                            let errorText =  '';
                            if(response.errors.parent_id){
                                Object.keys(response.errors.parent_id).forEach(function (key) {
                                    errorText += response.errors.parent_id[key] + '\n';
                                });
                            }
                            if(response.errors.name){
                                Object.keys(response.errors.name).forEach(function (key) {
                                    errorText += response.errors.name[key] + '\n';
                                });
                            }
                            if('' !== errorText){
                                alert(errorText);
                            }
                        }

                        let treeJson  = JSON.parse(response.tree_json);
                        setDataCategoryTree(treeJson);
                        removeLoading();
                    },
                    function(XMLHttpRequest, textStatus, errorThrown){
                        alert('保存に失敗しました。');
                        removeLoading();
                        return;
                    }
                );
            });

            $('#item-category-tree').on('rename_node.jstree', function (event, categoryData) {
                if(categoryData.text === categoryData.old){
                    return;
                }
                let request = {
                    id:categoryData.node.id,
                    name:categoryData.node.text
                };

                $.ajax({
                    url: '{{ route('item-category.update') }}',
                    type: 'PUT',
                    datatype: 'json',
                    data: request,
                    beforeSend: function(){
                        dispLoading();
                    }
                })
                .then(
                    function(response){
                        if(response.errors){
                            let errorText =  '';
                            if(response.errors.id){
                                Object.keys(response.errors.id).forEach(function (key) {
                                    errorText += response.errors.id[key] + '\n';
                                });
                            }
                            if(response.errors.name){
                                Object.keys(response.errors.name).forEach(function (key) {
                                    errorText += response.errors.name[key] + '\n';
                                });
                            }
                            if('' !== errorText){
                                alert(errorText);
                            }
                        }

                        let treeJson  = JSON.parse(response.tree_json);
                        setDataCategoryTree(treeJson);
                        removeLoading();
                    },
                    function(XMLHttpRequest, textStatus, errorThrown){
                        alert('保存に失敗しました。');
                        removeLoading();
                        return;
                    }
                );
            });

            $('#item-category-tree').on('delete_node.jstree', function (event, categoryData) {
                let request = {
                    id:categoryData.node.id,
                    name:categoryData.node.text
                };

                $.ajax({
                    url: '{{ route('item-category.destroy') }}',
                    type: 'DELETE',
                    datatype: 'json',
                    data: request,
                    beforeSend: function(){
                        dispLoading();
                    }
                })
                .then(
                    function(response){
                        if(response.errors){
                            let errorText =  '';
                            if(response.errors.id){
                                Object.keys(response.errors.id).forEach(function (key) {
                                    errorText += response.errors.id[key] + '\n';
                                });
                            }
                            if(response.errors.name){
                                Object.keys(response.errors.name).forEach(function (key) {
                                    errorText += response.errors.name[key] + '\n';
                                });
                            }
                            if('' !== errorText){
                                alert(errorText);
                            }
                        }

                        let treeJson  = JSON.parse(response.tree_json);
                        setDataCategoryTree(treeJson);
                        removeLoading();
                        return;
                    },
                    function(XMLHttpRequest, textStatus, errorThrown){
                        alert('削除に失敗しました。');
                        removeLoading();
                        return;
                    }
                );
            });
        });

        function initCategoryTree(treeJson){
            return $('#item-category-tree').jstree({
                'plugins': [ 'contextmenu','dnd' ],
                'core':{
                    'data' : treeJson.data,
                    "check_callback" : function(operation, node, node_parent, node_position, more){
                            if (operation=="move_node"){
                                return false;
                            }
                    },
                },
                "contextmenu":{
                    "items":function($node){
                        return {
                            "createRootCategory":{
                                "separator_before": false,
                                "separator_after": false,
                                "icon": "contextmenu-icon fa fa-folder-plus",
                                "label": "ルートカテゴリ作成",
                                "_disabled": function(data){
                                },
                                "action": function(data){
                                    let inst = $.jstree.reference(data.reference);
                                    inst.create_node('#', { text:'新しいカテゴリ', 'icon':'jstree-folder' }, "last", function(new_node){
                                        try{
                                            inst.edit(new_node);
                                        }catch(ex){
                                            setTimeout(function(){ inst.edit(new_node); },0);
                                        }
                                    });
                                }
                            },
                            "createCategory":{
                                "separator_before": true,
                                "separator_after": false,
                                "icon": "contextmenu-icon fa fa-folder-plus",
                                "label": "カテゴリ作成",
                                "_disabled": function(data){
                                },
                                "action": function(data){
                                    let inst = $.jstree.reference(data.reference), obj = inst.get_node(data.reference);
                                    inst.create_node(obj, { text:'新しいカテゴリ', 'icon':'jstree-folder' }, "last", function(new_node){
                                        try{
                                            inst.edit(new_node);
                                        }catch(ex){
                                            setTimeout(function(){ inst.edit(new_node); },0);
                                        }
                                    });
                                }
                            },
                            "rename":{
                                "separator_before": true,
                                "separator_after": false,
                                "icon": "contextmenu-icon fa fa-edit",
                                "label": "名前の変更",
                                "_disabled": false,
                                "action": function(data){
                                    let inst = $.jstree.reference(data.reference), obj = inst.get_node(data.reference);
                                    inst.edit(obj);
                                }
                            },
                            "remove":{
                                "separator_before": false,
                                "separator_after": false,
                                "icon": "contextmenu-icon fa fa-trash-alt",
                                "label": "削除",
                                "_disabled": function(data){
                                    let inst = $.jstree.reference(data.reference), obj = inst.get_node(data.reference);
                                    if(obj.parent != "#"){
                                        return false;
                                    }
                                    let existSibling = (inst.get_prev_dom(data.reference, true) && 0 != inst.get_prev_dom(data.reference, true).length) || (inst.get_next_dom(data.reference, true) && 0 != inst.get_next_dom(data.reference, true).length);
                                    if(existSibling){
                                        return false;
                                    }
                                    return true;
                                },
                                "action": function(data){
                                    let inst = $.jstree.reference(data.reference), obj = inst.get_node(data.reference);
                                    if (inst.is_selected(obj)){
                                        inst.delete_node(inst.get_selected());
                                    }else{
                                        inst.delete_node(obj);
                                    }
                                }
                            }
                        };
                    }
                }
            }).on('loaded.jstree', function(){
                $(this).jstree('open_all');
            });
        }

        function setDataCategoryTree(treeJson){
            $('#item-category-tree').jstree(true).settings.core.data = treeJson.data;
            $('#item-category-tree').jstree(true).refresh();
        }
    </script>
@endsection
