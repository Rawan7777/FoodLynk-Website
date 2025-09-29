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

$query_orders = "SELECT * FROM orders WHERE email = '$client_email' ORDER BY created_at DESC";
$result_orders = mysqli_query($connection, $query_orders);

if (mysqli_num_rows($result_orders) == 0) {
    $_SESSION['no_meals'] = '<div style="background-color:#fff5f1; padding:12px 16px; border-radius:8px; border:1px solid #ffe0d8; margin:10px 0; display:flex; justify-content:center">
                                <p style="color:#eb5a3c; font-weight:bold; margin:0;">No orders found.</p>
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
                            <button type="submit" name="logout" class="btn ghost small">Log Out</button>
                        </form>
                    </div>

                </div>

                <div class="orders-card">

                    <div class="orders-header">
                        <h2>My Orders</h2>
                    </div>

                    <div class="orders-list">

                        <?php

                        if (isset($_SESSION['no_meals'])) {

                            echo $_SESSION['no_meals'];
                            unset($_SESSION['no_meals']);

                        } else {

                            $grouped_orders = [];
                            mysqli_data_seek($result_orders, 0);
                            
                            while ($order = mysqli_fetch_assoc($result_orders)) {

                                $key = $order['brand_name'] . '|' . $order['meal_name'];

                                if (!isset($grouped_orders[$key])) {

                                    $grouped_orders[$key] = [
                                        'brand_name' => $order['brand_name'],
                                        'meal_name' => $order['meal_name'],
                                        'price' => $order['price'],
                                        'total_quantity' => 0,
                                        'latest_date' => $order['created_at']
                                    ];
                                }

                                $grouped_orders[$key]['total_quantity'] += $order['quantity'];

                                if (strtotime($order['created_at']) > strtotime($grouped_orders[$key]['latest_date'])) {
                                    $grouped_orders[$key]['latest_date'] = $order['created_at'];
                                }
                            }

                            foreach ($grouped_orders as $grouped_order) {

                                $brand_name = $grouped_order['brand_name'];
                                $meal_name = $grouped_order['meal_name'];
                                
                                $query_meal_details = "SELECT meal_image, meal_description FROM mealsnew WHERE brand_name = '$brand_name' AND meal_name = '$meal_name'";
                                $result_meal_details = mysqli_query($connection, $query_meal_details);
                                $meal_details = mysqli_fetch_assoc($result_meal_details);

                                $meal_image = $meal_details['meal_image'] ?? 'images/default_meal.jpg';
                                $meal_description = $meal_details['meal_description'] ?? 'No description available';

                                $meal_title = $grouped_order['total_quantity'] > 1 
                                    ? $grouped_order['total_quantity'] . 'x ' . htmlspecialchars($meal_name)
                                    : htmlspecialchars($meal_name);

                                echo '<div class="order-row">
                                        <div class="order-thumb">
                                            <img src="' . htmlspecialchars($meal_image) . '" alt="' . htmlspecialchars($meal_name) . '">
                                        </div>
                                        <div class="order-info">
                                            <div class="order-title">' . $meal_title . '</div>
                                            <div class="order-sub">' . htmlspecialchars($meal_description) . '</div>
                                            <div class="order-meta">
                                                <span>Total Qty: ' . htmlspecialchars($grouped_order['total_quantity']) . '</span>
                                                <span>•</span>
                                                <span>$' . htmlspecialchars($grouped_order['price']) . ' each</span>
                                                <span>•</span>
                                                <span>From: ' . htmlspecialchars($grouped_order['brand_name']) . '</span>
                                            </div>
                                            <div class="order-date">
                                                <small>Last ordered: ' . date('M j, Y g:i A', strtotime($grouped_order['latest_date'])) . '</small>
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