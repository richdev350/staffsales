@extends('admins.layouts.app')
@php
    $isEdit = false;
    if (stristr(current_route_name(), 'edit') || stristr(current_route_name(), 'update')) {
        $isEdit = true;
    }
@endphp
@section('title', '時間帯'.($isEdit ? '編集' : '登録').' | 時間帯管理')

@section('content')

    <h1>時間帯 - {{ $isEdit ? '編集' : '登録' }}</h1>
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
                    <div class="row">
                        <select name="from" class="form-control col-2">
                            @foreach($range_of_times as $time)
                                <option value="{{$time}}" @if($time===old('from')) selected @endif>{{$time}}</option>
                            @endforeach
                        </select>
                        <span class="col-1">時</span>
                    </div>
                    @foreach ($errors->get('from') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>

            <dl class="form-group row">
                <dt class="col-2">TO</dt>
                <dd class="col-10">
                    <div class="row">
                        <select name="to" class="form-control col-2">
                            @foreach($range_of_times as $time)
                                <option value="{{$time}}" @if($time===old('to')) selected @endif>{{$time}}</option>
                            @endforeach
                        </select>
                        <span class="col-1">時</span>
                    </div>
                    @foreach ($errors->get('to') as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <div class="card-footer">
                @include('admins.components.buttons.confirm')
                @include('admins.components.buttons.back_list', ['url' => url('admin/desired-time/list')])
            </div>

        </form>
    </div>
@endsection
