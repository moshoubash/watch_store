document.querySelectorAll('.featured-products-carousel').forEach(carousel => {
    const carouselTrack = carousel.querySelector('.carousel-list');
    const carouselItems = carousel.querySelectorAll('.carousel-item');
    const prevButton = carousel.querySelector('.arrow-button--prev');
    const nextButton = carousel.querySelector('.arrow-button--next');

    const slideWidth = 350; // Width of each carousel item (matches CSS)
    const gap = 30; // Gap between items (matches CSS)
    const visibleSlides = 3; // Number of items visible at once
    const totalSlides = carouselItems.length;
    let slideIndex = 0;

    // Set container width to fit visible slides and prevent overflow
    const containerWidth = (slideWidth + gap) * visibleSlides - gap;
    carousel.querySelector('.carousel-content').style.maxWidth = `${containerWidth}px`;
    carouselTrack.style.width = `${(slideWidth + gap) * totalSlides - gap}px`;

    // Update carousel position and button states
    function updateCarousel() {
        const maxIndex = totalSlides - visibleSlides;
        slideIndex = Math.max(0, Math.min(slideIndex, maxIndex)); // Clamp index within bounds

        const offset = -slideIndex * (slideWidth + gap);
        carouselTrack.style.transform = `translateX(${offset}px)`;

        // Toggle button states
        prevButton.disabled = slideIndex === 0;
        nextButton.disabled = slideIndex >= maxIndex;
    }

    // Previous button click handler
    prevButton.addEventListener('click', () => {
        if (slideIndex > 0) {
            slideIndex--;
            updateCarousel();
        }
    });

    // Next button click handler
    nextButton.addEventListener('click', () => {
        if (slideIndex < totalSlides - visibleSlides) {
            slideIndex++;
            updateCarousel();
        }
    });

    // Initialize carousel
    updateCarousel();
});