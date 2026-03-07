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
            <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ url('/about') }}" class="{{ request()->is('about') ? 'active' : '' }}">About</a></li>
            <li><a href="{{ url('/menu') }}" class="{{ request()->is('menu') ? 'active' : '' }}">Menu Minuman</a></li>
            <li><a href="{{ url('/testimoni') }}" class="{{ request()->is('testimoni') ? 'active' : '' }}">Testimoni</a></li>
            <li><a href="{{ url('/contact') }}" class="{{ request()->is('contact') ? 'active' : '' }}">Contact Us</a></li>
        </ul>

        <div class="nav-right">
            <!-- Guest View (Belum Login) -->
            <div class="guest-view">
                <a href="{{ route('login') }}" class="order-btn">
                    <span>Order Now</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <!-- User View (Sudah Login) - Akan ditampilkan dengan JS -->
            {{-- <div class="user-view" style="display: none;">
                <div class="user-profile">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="username">John Doe</span>
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </div>
                
                <a href="#" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">3</span>
                </a>
            </div> --}}
        </div>
    </div>
</nav>