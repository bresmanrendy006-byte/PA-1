<div class="admin-layout">

    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-mug-hot"></i>
                <span>Kedanku Coffee</span>
            </div>
        </div>
        
        <ul class="sidebar-menu">

            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('admin.kelolahome') }}">
                    <i class="fas fa-home"></i> Kelola Home
                </a>
            </li>

            <li>
                <a href="{{ route('admin.profile.index') }}">
                    <i class="fas fa-user-cog"></i> Kelola Profile
                </a>
            </li>

            <li>
                <a href="{{ route('admin.menu.index') }}">
                    <i class="fas fa-mug-hot"></i> Kelola Menu Minuman
                </a>
            </li>

            <li>
                <a href="#">
                    <i class="fas fa-clipboard-list"></i> Kelola Pesanan
                </a>
            </li>

            @if(Auth::user()->role == 'owner')
            <li>
                <a href="#">
                    <i class="fas fa-users"></i> Kelola Karyawan
                </a>
            </li>
            @endif

            <li class="logout-li">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>

        </ul>
    </aside>

</div>