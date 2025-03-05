const slides = document.querySelector('.slides');
const dotsContainer = document.querySelector('.dots');
const prevArrow = document.querySelector('.prev-arrow');
const nextArrow = document.querySelector('.next-arrow');
let currentSlide = 0;
const totalSlides = 3; // Number of slides

// Create dots dynamically
for (let i = 0; i < totalSlides; i++) {
    const dot = document.createElement('span');
    dot.classList.add('dot');
    if (i === 0) dot.classList.add('active');
    dot.addEventListener('click', () => goToSlide(i));
    dotsContainer.appendChild(dot);
}

const dots = document.querySelectorAll('.dot');

// Slide navigation functions
function goToSlide(index) {
    currentSlide = index;
    slides.style.transform = `translateX(-${currentSlide * 33.33}%)`;
    updateDots();
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    goToSlide(currentSlide);
}

function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    goToSlide(currentSlide);
}

// Update active dot
function updateDots() {
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === currentSlide);
    });
}

// Event listeners for arrows
prevArrow.addEventListener('click', prevSlide);
nextArrow.addEventListener('click', nextSlide);

// Auto-slide every 5 seconds
let slideInterval = setInterval(nextSlide, 4000);

// Pause auto-slide on hover
slides.addEventListener('mouseover', () => clearInterval(slideInterval));
slides.addEventListener('mouseleave', () => slideInterval = setInterval(nextSlide, 4000));

