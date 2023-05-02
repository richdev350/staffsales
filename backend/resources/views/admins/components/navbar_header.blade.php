        <header class="font-weight-bold">
            <nav class="navbar">
                <span class="navbar-brand text-light">{{ config('app.name', '') }}</span>
                <div>
                    <span class="text-light">{{ $login_admin_user->name }}さんログイン中</span>&nbsp;
                    <a href="{{ route('admin.auth.logout') }}" class="text-light">ログアウト <i class="fa fa-user-circle"></i></a>
                </div>
            </nav>
        </header>
