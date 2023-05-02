@extends('generals.layouts.app')

@section('title', 'ページが見つかりません')

@section('content')
    <h1>
        ページが見つかりません
    </h1>
    <a class="btn" href="{{ route('home.index') }}">
        ホームへ戻る
    </a>
@endsection
