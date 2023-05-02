<section class="sidebar col-2 font-weight-bold text-light">
    <nav class="sidebar-menu">
        <ul class="nav flex-column nav-pills nav-fill">
            <li class="nav-item text-left"><a href="{{ url('admin/home') }}" class="nav-link{{ request()->is('admin/home', 'admin') ? ' active' : '' }}"><i class="fas fa-tachometer-alt"></i> ダッシュボード</a></li>
            @if($login_admin_user->can('admin_permission'))
            <li class="nav-item text-left pl-3"><a href="{{ url('admin/admin-user/list') }}" class="nav-link{{ request()->is('admin/admin-user', 'admin/admin-user/*') ? ' active' : '' }}"><i class="fa fa-user"></i> ユーザ管理</a></li>
            <li class="nav-item text-left pl-3"><a href="{{ url('admin/maker/list') }}" class="nav-link{{ request()->is('admin/maker', 'admin/maker/*') ? ' active' : '' }}"><i class="fas fa-building"></i> メーカー管理</a></li>
            <li class="nav-item text-left pl-3"><a href="{{ url('admin/shop/list') }}" class="nav-link{{ request()->is('admin/shop', 'admin/shop/*') ? ' active' : '' }}"><i class="fa fa-store"></i> 店舗管理</a></li>
            <li class="nav-item text-left pl-3"><a href="{{ url('admin/desired-time/list') }}" class="nav-link{{ request()->is('admin/desired-time', 'admin/desired-time/*') ? ' active' : '' }}"><i class="fa fa-clock"></i> 時間帯管理</a></li>
            <li class="nav-item text-left pl-3"><a href="{{ url('admin/item-category/list') }}" class="nav-link{{ request()->is('admin/item-category', 'admin/item-category/*') ? ' active' : '' }}"><i class="fas fa-folder-open"></i> 商品カテゴリ管理</a></li>
            <li class="nav-item text-left pl-3"><a href="{{ url('admin/item/list') }}" class="nav-link{{ request()->is('admin/item', 'admin/item/*') ? ' active' : '' }}"><i class="fa fa-gifts"></i> 商品管理</a></li>
            <li class="nav-item text-left pl-3"><a href="{{ url('admin/publish/list') }}" class="nav-link{{ request()->is('admin/publish', 'admin/publish/*') ? ' active' : '' }}"><i class="fa fa-cog"></i> 公開日時設定</a></li>

            @endif
            @if($login_admin_user->can('manager_permission'))
            <li class="nav-item text-left pl-3"><a href="{{ url('admin/order/list') }}" class="nav-link{{ request()->is('admin/order', 'admin/order/*') ? ' active' : '' }}"><i class="fa fa-shopping-cart"></i> 受注管理</a></li>
            @endif
        </ul>
    </nav>
</section>
