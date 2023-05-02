@extends('admins.layouts.app')
@php
    $isEdit = false;
    if (stristr(current_route_name(), 'edit') || stristr(current_route_name(), 'update')) {
        $isEdit = true;
    }
@endphp

@section('title', '受注'.($isEdit ? '編集' : '登録').' | 受注管理')

@section('content')

    <h1>受注 - {{ $isEdit ? '編集' : '登録' }}</h1>
    <div class="card p-5">
        <form
            @if ($isEdit)
            action="{{ route('admin.order.update', ['id' => $id]) }}"
            @else
            action="{{ route('admin.order.store') }}"
            @endif
            method="post" class="register-form">
            @if ($isEdit)
                {{ method_field('patch') }}
            @endif
            {{ csrf_field() }}
            <dl class="form-group row">
                <dt class="col-2">注文番号</dt>
                <dd class="col-10">
                    {{ $id }}
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">注文日時</dt>
                <dd class="col-10">
                    @if(is_string(old('created_at')))
                        {{ old('created_at') }}
                        <input name="created_at" type="hidden" class="form-control" value="{{ old('created_at') }}">
                    @else
                        {{ old('created_at')->format('Y-m-d H:i') }}
                        <input name="created_at" type="hidden" class="form-control" value="{{ old('created_at')->format('Y-m-d H:i') }}">
                    @endif
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">お名前</dt>
                <dd class="col-10">
                    <input name="name" type="text" class="form-control" value="{{ old('name') }}">
                    @foreach ($errors->get('name') as $error)
                        <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            <dl class="form-group row">
                <dt class="col-2">社員番号</dt>
                <dd class="col-10">
                    <input name="staff_id" type="text" class="form-control" placeholder="" value="{{ old('staff_id') }}">
                    @foreach ($errors->get('staff_id') as $error)
                        <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                </dd>
            </dl>
            
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>商品</th>
                        <th>価格</th>
                        <th>数量</th>
                        <th>小計</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach (old('details') as $detail)
                        <tr>
                            <td>
                                {{ $detail->item->name }}
                            </td>
                            <td>
                                {{ number_format($detail->price) }}&nbsp;円
                            </td>
                            <td>
                                {{ $detail->amount }}
                            </td>
                            <td>
                                {{ number_format(($detail->price) * ($detail->amount)) }}&nbsp;円
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="row mb-1">
                    <div class="col-6"></div>
                    <div class="col-3 text-right">合計</div>
                    <div class="col-3 text-center">{{ number_format(old('sum')) }}&nbsp;円</div>
                </div>

            <div class="card-footer">
                @include('admins.components.buttons.confirm')
                @include('admins.components.buttons.back_list', ['url' => url('admin/order/list')])
            </div>
        </form>
    </div>
@endsection
