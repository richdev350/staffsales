@extends('generals.layouts.app')

@section('title', '権限がありません')

@section('content')
    <h1>
        権限がありません
    </h1>
    <a class="btn" href="{{ route('home.index') }}">
        ホームへ戻る
    </a>
@endsection
