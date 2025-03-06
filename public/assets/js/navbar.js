const hamburger = document.querySelector('.hamburger');
const leftItems = document.querySelector('.left-items');
const rightItems = document.querySelector('.right-items');
const dropdownToggle = document.querySelector('.dropdown-toggle');
const dropdown = document.querySelector('.dropdown');

hamburger.addEventListener('click', () => {
  leftItems.classList.toggle('active');
  rightItems.classList.toggle('active');
});

// Toggle dropdown in mobile view
dropdownToggle.addEventListener('click', (e) => {
  if (window.innerWidth <= 992) { // Only in mobile view
    e.preventDefault(); // Prevent link navigation
    dropdown.classList.toggle('active');
  }
});