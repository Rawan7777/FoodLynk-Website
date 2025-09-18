<?php

session_start();

$connection = mysqli_connect("localhost", "root", "", "foodlynk");

if(isset($_GET['brand_name']) || isset($_GET['refresh'])){

    $brand_name = $_GET['brand_name'];
    $query_meals = "SELECT * FROM meals WHERE brand_name = '$brand_name'";
    $result_meals = mysqli_query($connection, $query_meals);
}

if(isset($_SESSION['client_email']) && isset($_GET['brand_name']) && isset($_POST['buy'])){

    $client_email = $_SESSION['client_email'];
    $brand_name = $_GET['brand_name'];
    $bougth_meal_name = $_GET['bougth_meal_name'];

    $bougth_meal_name = $_GET['bougth_meal_name'];
    $query_bougth_meals = "INSERT INTO client_meals (client_email, brand_name, meal_name)
                            VALUES ('$client_email', '$brand_name', '$bougth_meal_name')";
    $result_bougth_meals = mysqli_query($connection, $query_bougth_meals);

    header("location:" . 'menu.php?bougth_meal_name=' . $meal['meal_name'] .'&brand_name=' . $_GET['brand_name']);
    exit();
}

if(isset($_POST['logout'])){

    session_destroy();
    header("location: brand_login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Menu</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/menu.css" />

</head>

<body>

    <header class="navbar">

        <div class="nav-container">

            <h1 class="logo" style="cursor:pointer;" onclick="window.location.href='index.php';">FoodLynk</h1>
            
            <!-- <nav class="nav_links">

                <?php if (isset($_SESSION['client_email'])): ?>
                    <a href="client_account.php">My account</a>

                <?php else: ?>
                    <a href="client_login.php">Login</a>

                <?php endif; ?>

                <?php if (isset($_SESSION['brand_email'])): ?>
                    <a href="brand_dashboard.php">My Brand</a>

                <?php else: ?>
                    <a href="brand_creation.php">Make Your Brand</a>

                <?php endif; ?> -->
        </div>

    </header>

    <h1 class="section-title">
        <?php echo $brand_name ?> Menu
        <span class="title-underline"></span>
    </h1>

    <div class="container">

        <div class="menu-grid">

            <?php 

            if(mysqli_num_rows($result_meals) > 0){

                $meal_counter = 1;

                while($meal = mysqli_fetch_assoc($result_meals)){
                    
                    echo '
                    <div class="meal-card">
                        <div class="meal-image">
                            <img src="' . $meal['meal_image'] . '" alt="Meal ' . $meal_counter++ . '">
                        </div>
                        <div class="meal-info">
                            <h4 class="meal-name">' . $meal['meal_name'] . '</h4>
                            <p class="meal-description">' . $meal['meal_description'] . '</p>
                            <div class="meal-meta">
                                <span>Qty: ' . ($meal['meal_quantity'] > 0 ? $meal['meal_quantity'] : 'Unlimited') . '</span>
                                <span>$' . $meal['meal_price'] . '</span>
                            </div>
                            <form method="post" action="menu.php?bougth_meal_name=' . $meal['meal_name'] .'&brand_name=' . $_GET['brand_name'] . '">
                                <button name="buy" class="btn">Buy Now</button>
                            </form>
                        </div>
                    </div>';
                }
            }
            ?>

            <!-- Example Card -->
            <div class="meal-card">

                <div class="meal-image">
                    <img src="meal_images/spaghetti-bolognese.jpg" alt="Meal 1">
                </div>
                <div class="meal-info">
                    <h4 class="meal-name">Spaghetti</h4>
                    <p class="meal-description">A classic Italian dish with tomato sauce and cheese.</p>
                    <div class="meal-meta">
                        <span>Qty: 10</span>
                        <span>$8.99</span>
                    </div>
                    <form method="post">
                        <button name="buy" class="btn">Buy Now</button>
                    </form>
                </div>
                
            </div>

        </div>
    </div>

</body>
</html>
