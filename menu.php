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

    <div class="nav_bar">

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

        </nav>

    </div>

    <h1 class="h1"><?php echo $brand_name?> Menu</h1>

    <div class="dashboard-container">

        <div class="card-container">

            <?php 
        
            if(mysqli_num_rows($result_meals) > 0){

                $meal_counter = 1;

                while($meal = mysqli_fetch_assoc($result_meals)){

                    echo '  <div class="meal-card">
                                <img src="' . $meal['meal_image'] . ' " alt="Meal ' . $meal_counter++ . ' image" />
                                <h4>' . $meal['meal_name'] . '</h4>
                                <p>' . $meal['meal_description'] . '.</p>
                                <div class="info">
                                    <span>Qty: ' . ($meal['meal_quantity'] > 0 ? $meal['meal_quantity'] : 'Unlimited') . '</span>
                                    <span>$' . $meal['meal_price'] . '</span>
                                </div>
                                <form method="post" action="menu.php?bougth_meal_name=' . $meal['meal_name'] .'&brand_name=' . $_GET['brand_name'] . '">
                                    <button name="buy" class="btn buy-btn">Buy Now</button>
                                </form>
                            </div>';
                }
            }
            ?>

            <div class="meal-card">
                <img src="meal_images/spaghetti-bolognese.jpg" alt="Meal 1" />
                <h4>Spaghetti</h4>
                <p>A classic Italian dish with tomato sauce and cheese.</p>
                <div class="info">
                    <span>Qty: 10</span>
                    <span>$8.99</span>
                </div>
				<form method="post">
                    <button name="buy" class="btn buy-btn">Buy Now</button>
                </form>
            </div>

    </div>

</body>
</html>
