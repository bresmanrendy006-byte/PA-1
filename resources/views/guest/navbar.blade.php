<nav class="navbar">
    <div class="nav-container">
        <div class="logo">
            <i class="fas fa-mug-hot"></i>
            <span>Kedanku Coffee</span>
        </div>

        <div class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </div>

        <ul class="nav-links" id="navLinks">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Profile</a></li>
            <li><a href="{{ route('guest.menu') }}" class="{{ request()->routeIs('guest.menu') ? 'active' : '' }}">Menu Minuman</a></li>
            <li><a href="{{ route('testimoni') }}" class="{{ request()->routeIs('testimoni') ? 'active' : '' }}">Testimoni</a></li>
            
            {{-- Admin Menu - Only for admin/owner --}}
            @auth
                @if(Auth::user()->role == 'owner' || Auth::user()->role == 'karyawan')
                    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">Dashboard</a></li>
                @endif
            @endauth
        </ul>

        <div class="nav-right">
            {{-- GUEST --}}
            @guest
                <a href="{{ route('login') }}" class="order-btn">
                    <span>Order Now</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            @endguest

            {{-- AUTHENTICATED USER --}}
            @auth
                <div class="user-view" id="userView">
                    <div class="user-profile" id="userProfile">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                        </div>
                        <span class="username">{{ Auth::user()->username }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>

                    <a href="{{ route('cart.index') }}" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count" id="cartCount">
                            {{ Auth::user()->carts()->count() ?? 0 }}
                        </span>
                    </a>

                    <div class="dropdown-menu">
                        @if(Auth::user()->role == 'customer')
                            <a href="{{ route('orders.index') }}">
                                <i class="fas fa-receipt"></i> Pesanan Saya
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>