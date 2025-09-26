<?php
$connection = mysqli_connect("localhost", "root", "", "foodlynk");

if (!$connection) {
  die("Connection failed: " . mysqli_connect_error());
}

session_start();

$client_email = $_SESSION['client_email'] ?? null;

if (!$client_email) {
    header("location: client_login.php");
    exit();
}

$query_client = "SELECT first_name, email, phone_number, address, profile_image FROM clients WHERE email = '$client_email'";
$result_client = mysqli_query($connection, $query_client);

if ($client = mysqli_fetch_assoc($result_client)) {

    $client_name  = $client['first_name'];
    $client_email = $client['email'];
    $client_phone = $client['phone_number'];
    $client_addr  = $client['address'];
    $client_img   = $client['profile_image'] ?: "images/default_avatar.jpg";

} else {

    session_destroy();
    header("location: client_login.php");
    exit();
}

$query_client_meals = "SELECT * FROM client_meals WHERE client_email = '$client_email'";
$result_client_meals = mysqli_query($connection, $query_client_meals);

if ($row = mysqli_fetch_assoc($result_client_meals)) {

    $brand_name = $row['brand_name'];
    $meal_name  = $row['meal_name'];

    $query_meals = "SELECT * FROM mealsnew WHERE brand_name = '$brand_name' AND meal_name = '$meal_name'";
    $result_meals = mysqli_query($connection, $query_meals);

} else {

    $_SESSION['no_meals'] = '<div style="background-color:#fff5f1; padding:12px 16px; border-radius:8px; border:1px solid #ffe0d8; margin:10px 0; display:flex; justify-content:center">
                                <p style="color:#eb5a3c; font-weight:bold; margin:0;">No purchased meals found.</p>
                             </div>';
}

if (isset($_POST['edit_profile'])) {

    header("location: client_edit_profile.php");
    exit();
}

if (isset($_POST['logout'])) {

    session_destroy();
    header("location: client_login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - FoodLynk</title>
    <link rel="stylesheet" href="css/client_account.css">
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans:wght@400;600;700&display=swap" rel="stylesheet">

</head>

<body>

    <div class="background-gradient"></div>

    <div class="top-bar">

        <a href="index.php" class="home-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to Home
        </a>
            
        <div class="nav-right">
            <span class="client-name"><?php echo htmlspecialchars($client_name); ?></span>
            <div class="nav-avatarr">
                <img src="<?php echo htmlspecialchars($client_img); ?>" alt="User Avatar">
            </div>
        </div>

    </div>

    <div class="main-container">

        <div class="brand-container">

            <div class="header-section">
                <div class="header-top">
                    <div>
                        <h1 class="brand-title">Client Account</h1>
                    </div>
                </div>
            </div>

            <div class="account-grid">

                <div class="profile-card">

                    <div class="profile-top">
                        <img src="<?php echo htmlspecialchars($client_img); ?>" alt="Profile Picture" class="profile-avatar">
                        <h3 class="profile-name"><?php echo htmlspecialchars($client_name); ?></h3>
                        <p class="profile-email"><?php echo htmlspecialchars($client_email); ?></p>
                    </div>
                    
                    <div class="profile-details">
                        <div><strong>Phone:</strong> <?php echo htmlspecialchars($client_phone); ?></div>
                        <div><strong>Address:</strong> <?php echo htmlspecialchars($client_addr); ?></div>
                    </div>

                    <div class="profile-actions">
                        <form method="post">
                            <button type="submit" name="edit_profile" class="btn small edit-btn">Edit Profile</button>
                            <button type="submit" name="logout" class="btn ghost small">Sign Out</button>
                        </form>
                    </div>

                </div>

                <div class="orders-card">

                    <div class="orders-header">
                        <h2>My Meals</h2>
                    </div>

                    <div class="orders-list">

                        <?php

                        if (isset($_SESSION['no_meals'])) {

                            echo $_SESSION['no_meals'];
                            unset($_SESSION['no_meals']);

                        } elseif (isset($result_meals)) {

                            $counter = 1;

                            while ($meal = mysqli_fetch_assoc($result_meals)) {

                                echo '<div class="order-row">
                                        <div class="order-thumb">
                                            <img src="' . htmlspecialchars($meal['meal_image']) . '" alt="Meal ' . $counter++ . '">
                                        </div>
                                        <div class="order-info">
                                            <div class="order-title">' . htmlspecialchars($meal['meal_name']) . '</div>
                                            <div class="order-sub">' . htmlspecialchars($meal['meal_description']) . '</div>
                                            <div class="order-meta">
                                                <span>Qty: ' . ($meal['meal_quantity'] > 0 ? $meal['meal_quantity'] : 'Unlimited') . '</span>
                                                <span>•</span>
                                                <span>$' . htmlspecialchars($meal['meal_price']) . '</span>
                                            </div>
                                        </div>
                                    </div>';
                            }
                        }

                        ?>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

</body>
</html>
