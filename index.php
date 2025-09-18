<?php 

session_start();

$connection = mysqli_connect("localhost", "root", "", "foodlynk");

$query = "SELECT * FROM brands WHERE status = 'approve'";

$result = mysqli_query($connection, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>FoodLynk - Your Gateway to Flavor</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

    <header class="navbar">

        <div class="nav-container">
            <h1 class="logo">FoodLynk</h1>
		
		    <nav class="nav_links">

			<?php if (isset($_SESSION['client_email'])): ?>
				<a href="client_account.php">My account</a>

			<?php else: ?>
				<a href="client_login.php">Login</a>

			<?php endif; ?>

			<?php if (isset($_SESSION['brand_email'])): ?>
				<a href="brand_dashboard.php">My Brand</a>

			<?php else: ?>
				<a href="brand_creation.php">Make Your Brand</a>

			<?php endif; ?>
        </div>

    </header>

    <section class="hero">

        <div class="landing_text">

            <h1 class="more">More Than Just Food</h1>

            <p class="text">
                FoodLynk is your gateway to a world of flavor, where restaurants, brands and even supermarkets build their own pages to 
                showcase unique menus. Whether you crave familiar favorites or want to discover something new, FoodLynk 
                connects you to the meals you love quickly, simply, and deliciously.
            </p>

            <div class="hero-stats">

                <div class="stat">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Restaurants</span>
                </div>

                <div class="stat">
                    <span class="stat-number">50+</span>
                    <span class="stat-label">Supermarkets</span>
                </div>

                <div class="stat">
                    <span class="stat-number">10k+</span>
                    <span class="stat-label">Happy Customers</span>
                </div>

            </div>

        </div>

        <div class="img_container">
            <div class="image-placeholder">
                <img src="images/background.jpg" alt="Delicious food spread" />
            </div>
        </div>

    </section>

    <section class="brands-section">

        <div class="container">

            <h2 class="section-title">

                <span class="title-icon">🍕</span>
                Popular Restaurants
                <span class="title-underline"></span>
                
            </h2>
            
            <div class="brands-grid">

                <div class="brand-card">

                    <a href="#" class="brand-link" title="Visit McDonald's">
                        <div class="brand-image">
                            <img src="images/mc.png" alt="McDonald's logo" />
                        </div>
                        <div class="brand-info">
                            <h3 class="brand-name">McDonald's</h3>
                            <p class="brand-category">Fast Food</p>
                        </div>
                    </a>

                </div>

                <div class="brand-card">

                    <a href="#" class="brand-link" title="Visit KFC">
                        <div class="brand-image">
                            <img src="images/kfc.png" alt="KFC logo" />
                        </div>
                        <div class="brand-info">
                            <h3 class="brand-name">KFC</h3>
                            <p class="brand-category">Fried Chicken</p>
                        </div>
                    </a>

                </div>

                <div class="brand-card">

                    <a href="#" class="brand-link" title="Visit Burger King">
                        <div class="brand-image">
                            <img src="images/burgerking.png" alt="Burger King logo" />
                        </div>
                        <div class="brand-info">
                            <h3 class="brand-name">Burger King</h3>
                            <p class="brand-category">Burgers</p>
                        </div>
                    </a>

                </div>

                <div class="brand-card">

                    <a href="#" class="brand-link" title="Visit Pizza Hut">
                        <div class="brand-image">
                            <img src="images/pizzahut.png" alt="Pizza Hut logo" />
                        </div>
                        <div class="brand-info">
                            <h3 class="brand-name">Pizza Hut</h3>
                            <p class="brand-category">Pizza</p>
                        </div>
                    </a>

                </div>

                <?php 

                $counter = 1;
                if(mysqli_num_rows($result) > 0 && $counter < 2){
                    while($row = mysqli_fetch_assoc($result)){
                        $client_email_param = '';
                        if (isset($_SESSION['client_email'])) {
                            $client_email_param = '&client_email=' . $_SESSION['client_email'];
                        }
                        
                        echo '<div class="brand-card">
                                <a href="menu.php?brand_name=' . htmlspecialchars($row['brand_name']) . $client_email_param . '" 
                                   class="brand-link" 
                                   title="Visit ' . htmlspecialchars($row['brand_name']) . '">
                                    <div class="brand-image">
                                        <img src="' . htmlspecialchars($row['brand_image']) . '" alt="' . htmlspecialchars($row['brand_name']) . ' logo" />
                                    </div>
                                    <div class="brand-info">
                                        <h3 class="brand-name">' . htmlspecialchars($row['brand_name']) . '</h3>
                                        <p class="brand-category">Restaurant</p>
                                    </div>
                                </a>
                              </div>';
                    }
                    $counter++;
                }
                ?>

                <div class="brand-card">

                    <a href="#" class="brand-link" title="Visit Subway">
                        <div class="brand-image">
                            <img src="images/subway.png" alt="Subway logo" />
                        </div>
                        <div class="brand-info">
                            <h3 class="brand-name">Subway</h3>
                            <p class="brand-category">Sandwiches</p>
                        </div>
                    </a>

                </div>

                <div class="brand-card">

                    <a href="#" class="brand-link" title="Visit Starbucks">
                        <div class="brand-image">
                            <img src="images/starbucks.png" alt="Starbucks logo" />
                        </div>
                        <div class="brand-info">
                            <h3 class="brand-name">Starbucks</h3>
                            <p class="brand-category">Coffee</p>
                        </div>
                    </a>

                </div>

                <div class="brand-card">

                    <a href="#" class="brand-link" title="Visit Domino's">
                        <div class="brand-image">
                            <img src="images/dominos.png" alt="Domino's logo" />
                        </div>
                        <div class="brand-info">
                            <h3 class="brand-name">Domino's</h3>
                            <p class="brand-category">Pizza</p>
                        </div>
                    </a>

                </div>

                <div class="brand-card">

                    <a href="#" class="brand-link" title="Visit Popeyes">
                        <div class="brand-image">
                            <img src="images/popeyes.png" alt="Popeyes logo" />
                        </div>
                        <div class="brand-info">
                            <h3 class="brand-name">Popeyes</h3>
                            <p class="brand-category">Fried Chicken</p>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <section class="brands-section supermarkets">

        <div class="container">

            <h2 class="section-title">
                <span class="title-icon">🛒</span>
                Supermarkets
                <span class="title-underline"></span>
            </h2>
            
            <div class="brands-grid">

                <div class="brand-card">
                    <a href="#" class="brand-link" title="Visit Marjane">
                        <div class="brand-image">
                            <img src="images/marjane.png" alt="Marjane logo" />
                        </div>
                        <div class="brand-info">
                            <h3 class="brand-name">Marjane</h3>
                            <p class="brand-category">Hypermarket</p>
                        </div>
                    </a>

                </div>

                <div class="brand-card">

                    <a href="#" class="brand-link" title="Visit Carrefour">
                        <div class="brand-image">
                            <img src="images/carrefour.png" alt="Carrefour logo" />
                        </div>
                        <div class="brand-info">
                            <h3 class="brand-name">Carrefour</h3>
                            <p class="brand-category">Supermarket</p>
                        </div>
                    </a>

                </div>

                <div class="brand-card">

                    <a href="#" class="brand-link" title="Visit Ikea">
                        <div class="brand-image">
                            <img src="images/ikea.png" alt="Ikea logo" />
                        </div>
                        <div class="brand-info">
                            <h3 class="brand-name">Ikea</h3>
                            <p class="brand-category">Furniture & Food</p>
                        </div>
                    </a>

                </div>

                <div class="brand-card">

                    <a href="#" class="brand-link" title="Visit Atacadão">
                        <div class="brand-image">
                            <img src="images/atacadao.png" alt="Atacadão logo" />
                        </div>
                        <div class="brand-info">
                            <h3 class="brand-name">Atacadão</h3>
                            <p class="brand-category">Wholesale</p>
                        </div>
                    </a>

                </div>

            </div>
        </div>
    </section>

    <footer class="footer">

        <div class="container">

            <div class="footer-content">

                <div class="footer-section">
                    <h3>🍽️ FoodLynk</h3>
                    <p>Your gateway to a world of flavor</p>
                </div>

                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">Help Center</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>For Businesses</h4>
                    <ul>
                        <li><a href="brand_creation.php">Join as Restaurant</a></li>
                        <li><a href="#">Partner with Us</a></li>
                        <li><a href="#">Business Support</a></li>
                    </ul>
                </div>

            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 FoodLynk. All rights reserved.</p>
            </div>

        </div>
    </footer>
</body>
</html>
