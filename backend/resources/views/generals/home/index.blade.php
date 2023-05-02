<?php use App\Enums\Mode\Modes; ?>
@extends('generals.layouts.app')
@if($current_mode == Modes::MAINTENANCE && AllowIpsOnMaintenance())
@include('generals.components.maintenance_mode')
@endif
@section('content')
<div class="top-empty">
  <div class="notice">
    <h2 class="title">コスモス薬品従業員販売専用サイトです。</h2>
    <hr />

    @if (count($barcode_datas) > 0)
      <div class="content">
        <p class="pl-5">●支払いが完了しているバーコードは削除してご利用してください。</p>
        <ul>
        @foreach($barcode_datas as $barcode_data)
          <li class="mt-1">
            <div class="d-inline-block">
              <span>◼ {{ $barcode_data['payment_datetime'] }} 購入</span>
              <div class="d-block">
                <img id="barcode" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode_data['barcode'], 'C128', 2, 100) }}" />
              </div>
              <form action="{{ route('home.barcode.destroy', $barcode_data['barcode']) }}" method="post" class="mt-2" onsubmit="return confirm('バーコードを削除してもよろしいですか？')">
                {{ method_field('delete') }}
                {{ csrf_field() }}
                <button class="d-block ml-auto btn btn-danger">削除</button>
              </form>
            </div>
          </li>
        @endforeach
        </ul>
      </div>
    @endif

    <div class="content">
      <p style="color:red; font-weight:bold;">下記注意点確認の上、サイトを利用ください。</p>
      <ul style="list-style:disc; padding:20px;">
        <li> ECサイトで商品選択・バーコード発行完了後、店頭にて支払いをおこなわないと注文完了にはなりません。ご注意ください。</li>
        <li>一類医薬品・コンタクトレンズはEC従業員販売サイトに<span style="color: #f00;">掲載がありません。</span><br/>
        一類医薬品・コンタクトレンズ取扱い店舗：精算シートから商品の追加をおこなってください。<br/>
        その他店舗：取扱店舗で購入　もしくは　自店で購入ができる現行の運用を利用してください。<br/>
        <span style="color: #f00;">※わからない場合は店長にご確認ください。</span><br/></li>
      </ul>
        <p style="text-align:center" class="topbtn"><a href="{{ route('item.list') }}" style="font-weight:bold;font-size:14pt;">商品一覧</a></p>
      </div>
  </div>
</div>
@endsection
