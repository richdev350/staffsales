@extends('admins.layouts.app')
@section('title', 'メーカ一一覧 | メーカー管理')
@section('content')
    <div class="mb-2">
        <h1>メーカー一覧</h1>
    </div>
    <div class="card">
    <form action="{{ route('admin.maker.select') }}" method="post" class="js-search-form">
      {{ csrf_field() }}
      <div class="card-body">
        @include('admins.components.labels.search-title')
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">名称</label>
          <div class="col-sm-10">
            <input type="text" name="name" class="form-control" value="{{ isset($conditions['name'])?$conditions['name']:'' }}">
          </div>
        </div>
      </div>
      <div class="card-footer">
        @include('admins.components.buttons.search')
        @include('admins.components.buttons.clear_condition')
      </div>
    </form>
  </div>
    <div class="mb-2 mt-2">
        @include('admins.components.buttons.add', ['url' => route('admin.maker.create')])
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>メーカー名</th>
                <th class="list-table__thead__heading">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paginator as $maker)
            <tr>
                <td>
                    {{ $maker->name }}
                </td>
                <td>
                    @include('admins.components.buttons.show', ['url' => route('admin.maker.show', $maker->id)])
                    @include('admins.components.buttons.edit', ['url' => route('admin.maker.edit', $maker->id)])
                    <form action="{{ route('admin.maker.destroy', $maker->id) }}" method="post" class="d-inline">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}
                        @include('admins.components.buttons.delete', ['name' => $maker->name])
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @include('admins.components.pagination', ['paginator' => $paginator, 'position' => 'bottom'])
@endsection
