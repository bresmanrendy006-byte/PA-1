<div class="admin-layout">

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
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- Kelola Home --}}
            <li>
                <a href="{{ route('admin.kelolahome') }}">
                    <i class="fas fa-home"></i>
                    <span>Kelola Home</span>
                </a>
            </li>

            {{-- Kelola Profile --}}
            <li>
                <a href="{{ route('admin.profile.index') }}">
                    <i class="fas fa-user-cog"></i>
                    <span>Kelola Profile</span>
                </a>
            </li>

            {{-- Kelola Menu --}}
            <li>
                <a href="{{ route('admin.menu.index') }}">
                    <i class="fas fa-mug-hot"></i>
                    <span>Kelola Menu Minuman</span>
                </a>
            </li>

            {{-- Kelola Pesanan --}}
            <li>
                <a href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Kelola Pesanan</span>
                </a>
            </li>

            {{-- Owner Only --}}
            @if(Auth::user()->role == 'owner')

                {{-- History Pembelian --}}
                <li>
                    <a href="{{ route('admin.orders.history') }}">
                        <i class="fas fa-clock-rotate-left"></i>
                        <span>History Pembelian</span>
                    </a>
                </li>

                {{-- Manajemen Stok --}}
                <li>
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

    </aside>

</div>