<aside class="sidebar">

    {{-- Sidebar Header --}}
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-mug-hot"></i>
            <span>Kedanku Coffee</span>
        </div>
    </div>

    {{-- Sidebar Menu --}}
    <ul class="sidebar-menu">

        {{-- Dashboard --}}
        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- Kelola Home --}}
        <li class="{{ request()->routeIs('admin.home.*') ? 'active' : '' }}">
            <a href="{{ route('admin.home.index') }}">
                <i class="fas fa-home"></i>
                <span>Kelola Home</span>
            </a>
        </li>

        {{-- Kelola Profile --}}
        <li class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
            <a href="{{ route('admin.profile.index') }}">
                <i class="fas fa-user-cog"></i>
                <span>Kelola Profile</span>
            </a>
        </li>

        {{-- Kelola Menu --}}
        <li class="{{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
            <a href="{{ route('admin.menu.index') }}">
                <i class="fas fa-mug-hot"></i>
                <span>Kelola Menu Minuman</span>
            </a>
        </li>

        {{-- Kelola Pesanan --}}
        <li class="{{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
            <a href="{{ route('admin.orders.index') }}">
                <i class="fas fa-clipboard-list"></i>
                <span>Kelola Pesanan</span>
            </a>
        </li>

        {{-- Kelola Testimoni --}}
        <li class="{{ request()->routeIs('admin.testimoni.*') ? 'active' : '' }}">
            <a href="{{ route('admin.testimoni.index') }}">
                <i class="fas fa-comments"></i>
                <span>Kelola Testimoni</span>
            </a>
        </li>

        {{-- Owner Only --}}
        @if(Auth::user()->role === 'owner')

            {{-- History Pembelian --}}
            <li class="{{ request()->routeIs('admin.orders.history') ? 'active' : '' }}">
                <a href="{{ route('admin.orders.history') }}">
                    <i class="fas fa-clock-rotate-left"></i>
                    <span>History Pembelian</span>
                </a>
            </li>

            {{-- Manajemen Stok --}}
            <li class="{{ request()->routeIs('admin.stock.*') ? 'active' : '' }}">
                <a href="{{ route('admin.stock.index') }}">
                    <i class="fas fa-box-open"></i>
                    <span>Manajemen Stok</span>
                </a>
            </li>

        @endif

        {{-- Logout --}}
        <li class="logout-li">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </li>

    </ul>

    {{-- USER INFO DI SUDUT KIRI BAWAH --}}
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