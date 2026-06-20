<aside class="sidebar">

    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-mug-hot"></i>
            <span>Kedanku Coffee</span>
        </div>
    </div>

    <div class="sidebar-menu-wrapper">
        <ul class="sidebar-menu">

            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            @if (Auth::user()->role === 'owner')
            <li class="{{ request()->routeIs('admin.home.*') ? 'active' : '' }}">
                <a href="{{ route('admin.home.index') }}">
                    <i class="fas fa-home"></i>
                    <span>Kelola Home</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <a href="{{ route('admin.profile.index') }}">
                    <i class="fas fa-user-cog"></i>
                    <span>Kelola Profile</span>
                </a>
            </li>
            @endif
            <li class="{{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                <a href="{{ route('admin.menu.index') }}">
                    <i class="fas fa-mug-hot"></i>
                    <span>Kelola Menu Minuman</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                <a href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Kelola Pesanan</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.testimoni.*') ? 'active' : '' }}">
                <a href="{{ route('admin.testimoni.index') }}">
                    <i class="fas fa-comments"></i>
                    <span>Kelola Testimoni</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.orders.history') ? 'active' : '' }}">
                <a href="{{ route('admin.orders.history') }}">
                    <i class="fas fa-clock-rotate-left"></i>
                    <span>History Pembelian</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('admin.stock.*') ? 'active' : '' }}">
                <a href="{{ route('admin.stock.index') }}">
                    <i class="fas fa-box-open"></i>
                    <span>Manajemen Stok</span>
                </a>
            </li>

        </ul>
    </div>

    <a href="{{ route('admin.userProfile') }}"
       class="sidebar-user-info {{ request()->routeIs('admin.userProfile') ? 'active' : '' }}">
        <div class="sidebar-user-avatar">
            {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
        </div>
        <div class="sidebar-user-detail">
            <span class="sidebar-user-name">{{ Auth::user()->username }}</span>
            <span class="sidebar-user-role">{{ ucfirst(Auth::user()->role) }}</span>
        </div>
        <i class="fas fa-chevron-right sidebar-user-arrow"></i>
    </a>

</aside>