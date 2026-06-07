document.addEventListener('DOMContentLoaded', function () {
    const burger = document.querySelector('.burger');
    const mobileMenu = document.querySelector('.menu-mobile');

    if (burger && mobileMenu) {
        burger.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
        });
    }

    const cardLinks = document.querySelectorAll('.card-link');
    cardLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            const card = this.closest('.card');
            if (!card) {
                return;
            }

            card.classList.toggle('expanded');
            this.textContent = card.classList.contains('expanded') ? 'Zwiń ↑' : 'Rozwiń →';
        });
    });

    const galleryImages = document.querySelectorAll('.zdjecia img');
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');

    if (galleryImages.length && lightbox && lightboxImg) {
        galleryImages.forEach(img => {
            img.addEventListener('click', () => {
                lightbox.style.display = 'flex';
                lightboxImg.src = img.src;
            });
        });

        lightbox.addEventListener('click', () => {
            lightbox.style.display = 'none';
        });
    }
});

