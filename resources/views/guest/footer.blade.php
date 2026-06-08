<footer class="footer">

    <div class="footer-container">

        {{-- BRAND --}}
        <div class="footer-col">
            <div class="footer-logo">
                <i class="fas fa-mug-hot"></i>
                <span>Kedanku Coffee</span>
            </div>

            <p>
                Tempat terbaik menikmati kopi premium dengan suasana nyaman
                dan modern di Balige.
            </p>
        </div>


        {{-- QUICK LINKS --}}
        <div class="footer-col">
            <h3>Quick Links</h3>

            <ul>
                <li>
                    <a href="{{ route('about') }}">Profile</a>
                </li>

                <li>
                    <a href="{{ route('guest.menu') }}">Menu</a>
                </li>

                <li>
                    <a href="{{ route('testimoni') }}">Testimoni</a>
                </li>
            </ul>
        </div>


        {{-- JAM OPERASIONAL --}}
        <div class="footer-col">
            <h3>Jam Operasional</h3>

            <p>Senin - Jumat : 08.00 - 22.00</p>
            <p>Sabtu - Minggu : 09.00 - 23.00</p>
        </div>


        {{-- SOCIAL MEDIA --}}
        <div class="footer-col">
            <h3>Contact Us</h3>

            <div class="social-icons">

                <a href="https://www.instagram.com/kedanku_balige/" target="_blank" aria-label="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>


                <a href="https://wa.me/6281262424173" target="_blank" aria-label="WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>

            </div>
        </div>

    </div>


    {{-- COPYRIGHT --}}
    <div class="footer-bottom">
        <p>© {{ date('Y') }} Kedanku Coffee. All Rights Reserved.</p>
    </div>

</footer>