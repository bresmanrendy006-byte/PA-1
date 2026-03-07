<footer class="footer">
    <div class="footer-container">

        <!-- Brand -->
        <div class="footer-col">
            <div class="footer-logo">
                <i class="fas fa-mug-hot"></i>
                <span>Kedanku Coffee</span>
            </div>
            <p>
                Tempat terbaik menikmati kopi premium dengan suasana nyaman dan modern di Balige.
            </p>
        </div>

        <!-- Quick Links -->
        <div class="footer-col">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="{{ route('about') }}">About</a></li>
                <li><a href="{{ route('menu') }}">Menu</a></li>
                <li><a href="{{ route('testimoni') }}">Testimoni</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </div>

        <!-- Jam Operasional -->
        <div class="footer-col">
            <h3>Jam Operasional</h3>
            <p>Senin - Jumat : 08.00 - 22.00</p>
            <p>Sabtu - Minggu : 09.00 - 23.00</p>
        </div>

        <!-- Social -->
        <div class="footer-col">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        <p>© {{ date('Y') }} Kedanku Coffee. All Rights Reserved.</p>
    </div>
</footer>