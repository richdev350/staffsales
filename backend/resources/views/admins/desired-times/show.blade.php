@extends('admins.layouts.app')
@section('title', '時間帯詳細 | 時間帯管理')
@section('content')

    <h1>時間帯 - 詳細</h1>
    <div class="card p-5">
        <dl class="form-group row">
            <dt class="col-2">FROM</dt>
            <dd class="col-10">
                {{ old('from') }}
            </dd>
        </dl>

        <dl class="form-group row">
            <dt class="col-2">TO</dt>
            <dd class="col-10">
                {{ old('to') }}
            </dd>
        </dl>
        <div class="card-footer">
            @include('admins.components.buttons.edit', ['url' => route('admin.desired-time.edit', $id)])
            <form action="{{ route('admin.desired-time.destroy', $id) }}" method="post" class="d-inline">
                {{ method_field('delete') }}
                {{ csrf_field() }}
                @include('admins.components.buttons.delete', ['name' => old('period')])
            </form>

        </div>

</div>
@endsection
