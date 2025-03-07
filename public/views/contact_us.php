<?php
require_once '../config/con.php';



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/d890c03bb3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/css/contact_us_page.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    
    
    <title>Contact Us - Timeless Elegance</title>
</head>
<body>

<?php include './components/navbar.html'; ?>

    <div class="contact_us">
        <div class="contact_us_content">
            <div class="contact_info">
                <h1>Get in Touch</h1>
                <p>Have any questions or concerns? Feel free to reach out to us.</p>
                <div class="contact_details">
                    <div class="contact_item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 10c0 6-8 0-8 0s-8 6-8 0a8 8 0 0 1 16 0Z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span>Jordan, Amman</span>
                    </div>
                    <div class="contact_item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        <span>123-456-7890</span>
                    </div>
                    <div class="contact_item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <a href="mailto:time@watch.com">time@watch.com</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="contact_us_form">
            <form id="contactForm" class="contact_form">
                <h1>Contact Us</h1>
                <p>Fill out the form below and we'll get back to you as soon as possible.</p>
                
                <div id="formErrors" class="error-messages"></div>
                
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>
    </div>

    
    
    <script>
        const form = document.getElementById('contactForm');
        const formErrors = document.getElementById('formErrors');
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(form);
            try {
                const response = await fetch('../controllers/contact_us_handle.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    form.reset();
                    formErrors.innerHTML = '';
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'Cool!',
                        timer: 1500,
                        timerProgressBar: true
                    });
                } else {
                    formErrors.innerHTML = `<p class="error-message">${data.message}</p>`;
                }
            } catch (error) {
                console.error('Error:', error);
                formErrors.innerHTML = '<p class="error-message">An unexpected error occurred</p>';
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/navbar.js"></script>
    <?php include './components/footer.html'; ?>
</body>
</html>