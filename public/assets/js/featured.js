document.querySelectorAll('.featured-products-carousel').forEach(carousel => {
    const carouselTrack = carousel.querySelector('.carousel-list');
    const carouselItems = carousel.querySelectorAll('.carousel-item');
    const prevButton = carousel.querySelector('.arrow-button--prev');
    const nextButton = carousel.querySelector('.arrow-button--next');

    const slideWidth = 350; // Width of each carousel item
    const gap = 30; // Gap between items
    const visibleSlides = 3; // Number of items visible at once
    const totalSlides = carouselItems.length;
    let currentIndex = 0;

    // Set initial state
    updateButtonState();

    // Previous button click handler
    prevButton.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        }
    });

    // Next button click handler
    nextButton.addEventListener('click', () => {
        if (currentIndex < totalSlides - visibleSlides) {
            currentIndex++;
            updateCarousel();
        }
    });

    function updateCarousel() {
        // Calculate the position to move the carousel
        const position = -currentIndex * (slideWidth + gap);
        carouselTrack.style.transform = `translateX(${position}px)`;
        updateButtonState();
    }

    function updateButtonState() {
        // Enable/disable buttons based on current position
        prevButton.disabled = currentIndex === 0;
        nextButton.disabled = currentIndex >= totalSlides - visibleSlides;
    }

    // Initialize
    updateCarousel();
});