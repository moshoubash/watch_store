// This script handles the Add to Bag and Wishlist functionality

document.addEventListener("DOMContentLoaded", function () {
  // Get the required elements
  const addToBagBtn = document.querySelector(".add-to-bag-btn");
  const wishlistBtn = document.querySelector(".wishlist-btn");
  const quantityInput = document.getElementById("stock_count");

  // Get the product ID from the URL
  const urlParams = new URLSearchParams(window.location.search);
  const productId = urlParams.get("id") || 1;

  // Add to Bag functionality
  addToBagBtn.addEventListener("click", function () {
    console.log("Add to bag button clicked");
    const quantity = parseInt(quantityInput.value);
    console.log("Quantity:", quantity);
    // Validate quantity
    if (isNaN(quantity) || quantity < 1) {
      alert("Please enter a valid quantity");
      return;
    }

    // Send AJAX request to add to cart
    fetch("../controllers/product_page.php", {
      // Updated path
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `product_id=${productId}&quantity=${quantity}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Product added to bag successfully!");
          // Optional: Update cart counter in the UI
        } else {
          alert("Error: " + data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Something went wrong. Please try again.");
      });
  });

  // Wishlist functionality

  // Wishlist functionality
  wishlistBtn.addEventListener("click", function () {
    // Toggle wishlist icon
    const wishlistIcon = wishlistBtn.querySelector("i");
    wishlistIcon.classList.toggle("far");
    wishlistIcon.classList.toggle("fas");

    // Send AJAX request to add/remove from wishlist
    fetch("../controllers/toggle_wishlist.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `product_id=${productId}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          if (data.added) {
            alert("Product added to wishlist!");
          } else {
            alert("Product removed from wishlist!");
          }
        } else {
          alert("Error: " + data.message);
          // Revert the icon change if there was an error
          wishlistIcon.classList.toggle("far");
          wishlistIcon.classList.toggle("fas");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Something went wrong. Please try again.");
        // Revert the icon change if there was an error
        wishlistIcon.classList.toggle("far");
        wishlistIcon.classList.toggle("fas");
      });
  });
});
