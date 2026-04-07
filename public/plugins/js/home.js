// home.js
document.addEventListener('DOMContentLoaded', function() {
    initHomeSwiper();
    initHomeEvents();
});

function initHomeSwiper() {
    // Cek apakah elemen swiper ada
    const swiperElement = document.querySelector('.home-swiper');
    if (!swiperElement) return;

    const swiper = new Swiper('.home-swiper', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        speed: 1500,
    });
}

function initHomeEvents() {

    // Parallax effect (optional)
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const heroSection = document.querySelector('.home-section');
        if (heroSection && scrolled < window.innerHeight) {
            heroSection.style.transform = `translateY(${scrolled * 0.1}px)`;
        }
    });
}