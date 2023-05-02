<?php use App\Enums\Mode\Modes; ?>
@extends('generals.layouts.app')
@if($current_mode == Modes::MAINTENANCE && AllowIpsOnMaintenance())
@include('generals.components.maintenance_mode')
@endif
@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/locale/ja.js"></script>
    <div id="top" class="order-form">
        <div class="top_content cake_list active">
            <h2 class="title_border_bottom"><span class="label">お客様情報入力</span></h2>
            <form
                action="{{ route('order.confirm') }}"
                method="post" class="register-form">
                {{ csrf_field() }}
                <div class="">
                    <div class="row row__entry justify-content-center">
                        <div class="col-2">
                            <span class="require-box">入力必須</span>
                        </div>
                        <div class="col-2">
                            <dt>お名前</dt>
                        </div>
                        <div class="col-8">
                            <dd>
                                <span class="input"><input name="name" type="text" placeholder="例：山田 太郎"  value="{{ old('name') }}"></span>
                                @foreach ($errors->get('name') as $error)
                                    <p class="alert alert-danger">{{ $error }}</p>
                                @endforeach
                            </dd>
                        </div>
                    </div>
                    <div class="row row__entry justify-content-center">
                        <div class="col-2">
                            <span class="require-box">入力必須</span>
                        </div>
                        <div class="col-2">
                            <dt>社員番号</dt>
                        </div>
                        <div class="col-8">
                            <dd>
                                <span class="input"><input name="staff_id" type="text" placeholder="例：12345"  value="{{ old('staff_id') }}"></span>
                                @foreach ($errors->get('staff_id') as $error)
                                    <p class="alert alert-danger">{{ $error }}</p>
                                @endforeach
                            </dd>
                        </div>
                    </div>                            
                    <div class="row row__btn justify-content-center">
                        <div class="col text-center mt-2">
                            @include('generals.components.buttons.back_cart')
                            @include('generals.components.buttons.confirm')
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(function(){
            $('.btn-confirm').click(function(){
                if($('#desired-date').val() != ''){
                    let desiredDate = $('#desired-date').val();
                    let m = moment(desiredDate, "YYYY年MM月DD日");
                    $('#desired-date-hidden').val(m.format('YYYY-MM-DD'));
                }else{
                    $('#desired-date-hidden').val('');
                }
            });
        });
    </script>
@endsection
