@extends('admins.layouts.app')

@section('title', '時間帯一覧 | 時間帯管理')
@section('content')
    <div class="mb-2">
        <h1>時間帯一覧</h1>
    </div>

    <div class="mb-2 mt-2">
        @include('admins.components.buttons.add', ['url' => route('admin.desired-time.create')])
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>時間帯</th>
                <th class="list-table__thead__heading">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paginator as $desired_time)
            <tr>
                <td>
                    {{ $desired_time->period }}
                </td>
                <td>
                    @include('admins.components.buttons.show', ['url' => route('admin.desired-time.show', $desired_time->id)])
                    @include('admins.components.buttons.edit', ['url' => route('admin.desired-time.edit', $desired_time->id)])
                    <form action="{{ route('admin.desired-time.destroy', $desired_time->id) }}" method="post" class="d-inline">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}
                        @include('admins.components.buttons.delete', ['name' => $desired_time->period])
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @include('admins.components.pagination', ['paginator' => $paginator, 'position' => 'bottom'])

@endsection
