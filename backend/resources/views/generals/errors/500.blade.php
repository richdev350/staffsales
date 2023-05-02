@extends('generals.layouts.app')

@section('title', 'エラーが発生しました')

@section('content')
    <h1>
        エラーが発生しました
    </h1>
    <a class="btn" href="{{ route('home.index') }}">
        ホームへ戻る
    </a>
@endsection
