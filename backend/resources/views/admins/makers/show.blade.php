@extends('admins.layouts.app')
@section('title', 'メーカー詳細 | メーカー管理')
@section('content')
    <h1>メーカー - 詳細</h1>
    <div class="card p-5">
        <dl class="form-group row">
            <dt class="col-2">名称</dt>
            <dd class="col-10">
                {{ old('name') }}
            </dd>
        </dl>
        <div class="card-footer">
            @include('admins.components.buttons.edit', ['url' => route('admin.maker.edit', $id)])
            <form action="{{ route('admin.maker.destroy', $id) }}" method="post" class="d-inline">
                {{ method_field('delete') }}
                {{ csrf_field() }}
                @include('admins.components.buttons.delete', ['name' => old('name')])
            </form>
            @include('admins.components.buttons.back_list', ['url' => url('admin/maker')])
        </div>
</div>
@endsection
