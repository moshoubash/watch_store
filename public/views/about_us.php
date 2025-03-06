<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/about_us.css">
    <title>Timeless Elegance</title>
</head>
<body>
    <!-- Navigation -->
    <nav class="navigation">
        <div class="logo">Timeless Elegance</div>
        <div class="burger-menu">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <ul class="nav-links">
            <li><a href="#story">Our Story</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#mission">Our Mission</a></li>
            <li><a href="#team">Our Team</a></li>
            <li><a href="#services">Services</a></li>
        </ul>
    </nav>

    <!-- Our Story Section -->
    <section id="story" class="our_story_head">
        <div class="sub_head our_story">
            <h1>Our Story</h1>
            <p>Founded with a passion for horology, Timeless Elegance brings together classic and contemporary designs, celebrating precision and artistry.</p>
        </div>
        <div class="sub_head story_img"></div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="about_us">
        <video autoplay muted loop playsinline>
            <source src="../assets/video/about_us_vid/4d50e20badaa40a5b9672069332cc166.HD-1080p-4.8Mbps-42922285.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="about_us_content">
            <h1>About Us</h1>
            <p>Welcome to Timeless Elegance, a watch store where tradition meets innovation. Though this is a training project, our goal is to deliver an authentic experience for watch enthusiasts. We believe every timepiece tells a story — of craftsmanship, style, and timeless beauty.</p>
        </div>
    </section>

    <!-- Our Mission Section -->
    <section id="mission" class="our_mission">
        <div class="sub_head mission_content">
            <h1>Our Mission</h1>
            <p>We aim to inspire elegance through high-quality timepieces, helping every customer find a watch that suits their style and personality. Our mission is to offer not just watches, but lasting memories and symbols of sophistication.</p>
        </div>
        <div class="sub_head mission_img"></div>
    </section>

    <!-- Our Team Section -->
    <section id="team" class="our_team">
        <div class="team_content">
            <h1>Our Team</h1>
            <p>Our team is made up of watch enthusiasts who are dedicated to providing the best service to our customers. We are passionate about horology and are committed to helping you find the perfect timepiece.</p>
        </div>
        <div class="team_info">
            <div class="team">
                <div class="team_member">
                    <img src="../assets/images/about_us_img/team1.png" alt="John Doe">
                    <h3>John Doe</h3>
                    <p>Founder</p>
                </div>
                <div class="team_member">
                    <img src="../assets/images/about_us_img/team2.png" alt="Jane Doe">
                    <h3>Jane Doe</h3>
                    <p>Marketing Manager</p>
                </div>
                <div class="team_member">
                    <img src="../assets/images/about_us_img/team3.webp" alt="Michael Doe">
                    <h3>Michael Doe</h3>
                    <p>Customer Service</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Services Section -->
    <section id="services" class="our_services">
        <h1>Our Services</h1>
        <div class="services_content">
            <div class="service">
                <h3>Watch Repair</h3>
                <p>Our skilled technicians provide expert watch repair services, ensuring your timepiece is in top condition.</p>
            </div>
            <div class="service">
                <h3>Customization</h3>
                <p>Personalize your watch with custom engravings and unique designs to make it truly yours.</p>
            </div>
            <div class="service">
                <h3>Appraisals</h3>
                <p>Get a professional appraisal of your watch's value for insurance or resale purposes.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2024 Timeless Elegance. All Rights Reserved.</p>
            <div class="social-links">
                <a href="#">Instagram</a>
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const burgerMenu = document.querySelector('.burger-menu');
            const navLinks = document.querySelector('.nav-links');

            burgerMenu.addEventListener('click', () => {
                burgerMenu.classList.toggle('active');
                navLinks.classList.toggle('active');
            });

            // Close menu when a link is clicked
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.addEventListener('click', () => {
                    burgerMenu.classList.remove('active');
                    navLinks.classList.remove('active');
                });
            });
        });
    </script>
</body>
</html>