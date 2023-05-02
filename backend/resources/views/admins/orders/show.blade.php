@extends('admins.layouts.app')
@section('title', '受注詳細 | 受注管理')

@section('content')

    <h1>受注 - 詳細</h1>
    <div class="card p-5">
        <dl class="form-group row">
            <dt class="col-2">注文番号</dt>
            <dd class="col-10">
                {{ old('id') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">注文日時</dt>
            <dd class="col-10">
                {{ old('created_at')->format('Y-m-d H:i') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">お名前</dt>
            <dd class="col-10">
                {{ old('name') }}
            </dd>
        </dl>
        <dl class="form-group row">
            <dt class="col-2">社員番号</dt>
            <dd class="col-10">
                {{ old('staff_id') }}
            </dd>
        </dl>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>商品名</th>
                <th>数量</th>
                <th>単価</th>
                <th>金額</th>
            </tr>
            </thead>
            <tbody>
            @php $amount_sum = 0; @endphp
            @foreach (old('details') as $detail)
                <tr>
                    <td>
                        {{ $detail->item->name }}
                    </td>
                    <td>
                        {{ $detail->amount }}&nbsp;個
                    </td>
                    <td>
                        {{ number_format($detail->price) }}&nbsp;円
                    </td>
                    <td>
                        {{ number_format(($detail->price) * ($detail->amount)) }}&nbsp;円<span class="tax">（税込）</span>
                    </td>
                    @php $amount_sum += $detail->amount; @endphp
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="row mb-1">
            <div class="col-6"></div>
            <div class="col-3 text-right">合計{{ $amount_sum }}&nbsp;点</div>
            <div class="col-3 text-center">{{ number_format(old('sum')) }}&nbsp;円<span class="tax">（税込）</span></div>
        </div>
        <div class="row mb-1">
            <div class="col-12">
                <p class="img"><img src="data:image/png;base64,{{ DNS1D::getBarcodePNG(generateBarCode(old('id'), old('secure_code')), 'C128', 2, 100) }}" /></p>
            </div>
        </div>
        <div class="card-footer">
            @if(!old('deleted_at') && ($login_admin_user->can('admin_permission')))
                @include('admins.components.buttons.edit', ['url' => route('admin.order.edit', $id)])
            @endif
            @if(!old('deleted_at') && ($login_admin_user->can('admin_permission')))
                <form action="{{ route('admin.order.destroy', $id) }}" method="post" class="d-inline">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    @include('admins.components.buttons.delete', ['name' => '注文番号：' . $id])
                </form>
            @endif

            @include('admins.components.buttons.back_list', ['url' => url('admin/order/list')])
        </div>

    </div>
@endsection
