@extends('admins.layouts.app')
@php
    $isEdit = false;
    if (stristr(current_route_name(), 'edit') || stristr(current_route_name(), 'update')) {
        $isEdit = true;
    }
@endphp
@section('title', '時間帯'.($isEdit ? '編集' : '登録').'確認 | 時間帯管理')

@section('content')

    <h1>時間帯 - {{ $isEdit ? '編集' : '登録' }}確認</h1>
    <div class="card p-5">
        <form
            @if ($isEdit)
                action="{{ route('admin.desired-time.update', ['id' => $id]) }}"
            @else
                action="{{ route('admin.desired-time.store') }}"
            @endif
            method="post" class="register-form">
            @if ($isEdit)
                {{ method_field('patch') }}
            @endif
            {{ csrf_field() }}
            <dl class="form-group row">
                <dt class="col-2">FROM</dt>
                <dd class="col-10">
                    {{ old('from') }}時
                    <input name="from" type="hidden" value="{{ old('from') }}">
                </dd>
            </dl>

            <dl class="form-group row">
                <dt class="col-2">TO</dt>
                <dd class="col-10">
                    {{ old('to') }}時
                    <input name="to" type="hidden" value="{{ old('to') }}">
                </dd>
            </dl>
            <div class="card-footer">
                @include('admins.components.buttons.regist')
                @include('admins.components.buttons.back')
            </div>

        </form>
    </div>
@endsection
