@extends('admins.layouts.app')
@php
    $isEdit = false;
    if (stristr(current_route_name(), 'edit') || stristr(current_route_name(), 'update')) {
        $isEdit = true;
    }
@endphp
@section('title', 'メーカー'.($isEdit ? '編集' : '登録').' | メーカー管理')
@section('content')
    <h1>メーカー - {{ $isEdit ? '編集' : '登録' }}</h1>
    <div class="card p-5">
        <form
            @if ($isEdit)
                action="{{ route('admin.maker.update', ['id' => $id]) }}"
            @else
                action="{{ route('admin.maker.store') }}"
            @endif
            method="post" class="register-form">
            @if ($isEdit)
                {{ method_field('patch') }}
            @endif
            {{ csrf_field() }}
            <dl class="form-group row">
                <dt class="col-2">名称</dt>
                <dd class="col-10">
                    <input name="name" type="text" class="form-control" value="{{ old('name') }}">
                    @foreach ($errors->get('name') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <div class="card-footer">
                @include('admins.components.buttons.confirm')
                @include('admins.components.buttons.back_list', ['url' => url('admin/maker')])
            </div>
        </form>
    </div>
@endsection
