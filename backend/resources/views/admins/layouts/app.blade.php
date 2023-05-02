<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('admins.components.head')
    <style>
        .alert {
            margin-top: 5px;
        }
    </style>
</head>
<body>
    @if ($is_admin_login)
    <div class="container-fluid">
        @include('admins.components.navbar_header')
        <div class="row">
        @include('admins.components.sidebar')
        <main class="col-10 pl-5 pt-3">
            @if (session('message'))
                <div class="alert alert-primary">
                    <p>{{ session('message') }}</p>
                </div>
            @endif
            @if (isset($errors) && count($errors->all()))
                <div class="alert alert-danger">
                    <p>入力内容を確認してください。</p>
                    @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

    @else
    <div class="container">
        <div class="row">
            <main class="col-12">
    @endif

            @yield('content')

            </main>
        </div>
    </div>
    <script>
    $(function(){
        $(".js-delete_btn").on('click', function(){
            var name = $(this).data("name");
            if(!confirm(name + "を削除してもよろしいですか？")){
                return false;
            }
        });
    });
    </script>
</body>
</html>
