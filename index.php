<!-- <!DOCTYPE html> -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alliance</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+V8GA5R6f5cAwUO580m1frLjvuC4CZ4p4XvCwOaPJ7j8qnp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

 
</head>
<body>
<header class="header">
<div class="brand-name">  
            <h1>Alliance</h1>
            <!-- <p>We will lead your way<p> -->
        </div>
        <!-- <div class="logo">Alliance</div> -->
        <nav class="navigation">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#about">About&nbsp;Us</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
        <div class="user-actions">
    <a class="login-button" href="login.php">Login</a>
    <a class="signup-button" href="Customer.php">Sign Up</a>
</div>

    </header>


    
    <section class="video-section">
    <div class="video-text">
        <h2>Experience The Best Bus Travel</h2>
        <p>Discover the world by bus with Alliance. We offer comfortable and reliable bus services to exciting destinations.</p>
    </div>
        <video autoplay muted loop>
            <source src="bg.mp4" type="video/mp4">
        </video>
        
        <div class="overlay"></div>
        
        <div class="search-form">
            <form id="searchForm" method="post" action="pleaselogin.php">
        <input type="text" id="start" name="start" list="startStops" placeholder="Starting From" required>
        <datalist id="startStops">
            <?php
            include "Dbconnect.php";
            $sql = "SELECT DISTINCT Stop_name FROM tbl_Stop ORDER BY Stop_name ASC"; // Order by stop names in ascending order
            $result = $conn->query($sql);
            $currentLetter = '';

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $stopName = $row['Stop_name'];
                    $firstLetter = strtoupper(substr($stopName, 0, 1));
                    
                    if ($firstLetter !== $currentLetter) {
                        // Display a letter heading
                        echo "<option class='custom-option' value=''>$firstLetter</option>";
                        $currentLetter = $firstLetter;
                    }
                    
                    echo "<option class='custom-option' value='$stopName'>$stopName</option>";
                }
            }
            ?>
        </datalist><br><br>

        <input type="text" id="destination" name="destination" list="destinationStops" placeholder="Destination" required>
        <datalist id="destinationStops">
            <?php
            include "Dbconnect.php";
            $sql = "SELECT DISTINCT Stop_name FROM tbl_Stop ORDER BY Stop_name ASC"; // Order by stop names in ascending order
            $result = $conn->query($sql);
            $currentLetter = '';

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $stopName = $row['Stop_name'];
                    $firstLetter = strtoupper(substr($stopName, 0, 1));
                    
                    if ($firstLetter !== $currentLetter) {
                        // Display a letter heading
                        echo "<option class='custom-option' value=''>$firstLetter</option>";
                        $currentLetter = $firstLetter;
                    }
                    
                    echo "<option class='custom-option' value='$stopName'>$stopName</option>";
                }
            }
            ?>
        </datalist><br><br>

        <input type="date" id="journeyDate" name="journeyDate"  min="<?= date('Y-m-d'); ?>" required placeholder="date"><br><br>
                <button type="submit">Search</button>
            </form>
        </div>
    </section>
    <section class="popular-destinations">
    <h2 class="popular-heading">Popular Destinations</h2>
        <div class="destination">
            <div class="destination-content">
                <div class="destination-info">
                    <img src="img/blr.webp" alt="Bengaluru">
                    <h2>Bengaluru</h2>
                </div>
                <div class="description">
                    <p>A bustling tech hub in India, Bengaluru is known for its innovative spirit, pleasant climate, and diverse culture.</p>
                </div>
            </div>
        </div>
        <div class="destination">
            <div class="destination-content">
                <div class="destination-info">
                    <img src="img/cni.jpg" alt="Chennai">
                    <h2>Chennai</h2>
                </div>
                <div class="description">
                    <p>Nestled on India's southeastern coast, Chennai is a cultural gem with a rich history, beautiful beaches, and a captivating fusion of tradition and modernity.</p>
                </div>
            </div>
        </div>
        <div class="destination">
            <div class="destination-content">
                <div class="destination-info">
                    <img src="img/hyd.jpg" alt="Hyderabad">
                    <h2>Hyderabad</h2>
                </div>
                <div class="description">
                    <p>The "City of Pearls," Hyderabad, boasts a regal past and dynamic present, with a blend of heritage and innovation, and renowned biryani and warm hospitality.</p>
                </div>
            </div>
        </div>
        <div class="destination">
            <div class="destination-content">
                <div class="destination-info">
                    <img src="img/goa.jpg" alt="Goa">
                    <h2>Goa</h2>
                </div>
                <div class="description">
                    <p>India's beach paradise, Goa offers palm-fringed shores, colonial architecture, and a laid-back atmosphere for leisure seekers, alongside a pulsating party scene and vibrant nightlife.</p>
                </div>
            </div>
        </div>
    </section>

   
 <section id="about" class="about-section">
        <div class="about-content">
            <div class="about-text">
                <h2 class="section-title">Discover Our Story</h2>
                <p class="description">Welcome to <span class="highlight">Alliance</span>, your gateway to extraordinary travel experiences. Our team of passionate travelers and industry experts is dedicated to curating the perfect journey for you. With a combined experience of over 10 years, we have explored the far corners of the globe, and now we're here to help you do the same.</p>
                <p class="description">At <span class="highlight">Alliance</span>, we believe that travel is more than just visiting destinations; it's about creating lifelong memories, forging connections with different cultures, and discovering the beauty of the world.</p>
                <div class="feature-list">
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-star"></i></div>
                        <p class="feature-description">Expert Travel Advisors: Our team of knowledgeable travel advisors is committed to understanding your unique preferences and crafting personalized itineraries that match your interests.</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-medal"></i></div>
                        <p class="feature-description">Unparalleled Customer Service: We're proud of our <span class="highlight">5-star rating</span> on TripAdvisor, a testament to our dedication to providing exceptional service at every stage of your journey.</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-award"></i></div>
                        <p class="feature-description">Award-Winning Excellence: We're honored to have been named the <span class="highlight">Best Travel Company</span> by Travel Weekly for three consecutive years.</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-globe"></i></div>
                        <p class="feature-description">Global Network: With partners and connections around the world, we can offer you exclusive experiences and insider access that make your travels extraordinary.</p>
                    </div>
                </div>
            </div>
            <div class="about-image">
                <img src="img/About.jpg" alt="About Us Image">
            </div>
        </div>
    </section>


<section id="contact" class="contact-section">
    <div class="contact-container">
        <h2 class="contact-heading">Let's Connect</h2>
        <p class="contact-subheading">We're thrilled to hear from you!</p>
        <div class="contact-content">
            <p>If you're ready to start a conversation, have questions, or simply want to say hello, don't hesitate to get in touch. Our dedicated team is here to make your experience exceptional.</p>
        </div>
        <div class="contact-details">
            <div class="contact-info">
                <i class="fas fa-envelope"></i>
                <p class="contact-label">Email Us</p>
                <a class="contact-link" href="mailto:info@alliance.com">info@alliance.com</a>
            </div>
            <div class="contact-info">
                <i class="fas fa-phone"></i>
                <p class="contact-label">Call Us</p>
                <a class="contact-link" href="tel:+919999900000">+91 9995993395</a>
            </div>
        </div>
    </div>
</section>


    
<footer>
    <div class="footer-content">
        <div class="footer-logo">Alliance</div>
        <ul class="footer-nav">
            <li><a href="#">Home</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <div class="social-icons">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
    <p class="footer-bottom">&copy; 2023 Alliance. All Rights Reserved.<!-- | <a href="#">Privacy Policy  --></a></p>
</footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="scripts.js"></script> 
</body>
</html>













    

    