<?php

session_start();

$connection = mysqli_connect("localhost", "root", "", "foodlynk");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$first_name_value   = mysqli_real_escape_string($connection, $_GET['first_name'] ?? '');
$last_name_value    = mysqli_real_escape_string($connection, $_GET['last_name'] ?? '');
$card_id_value      = mysqli_real_escape_string($connection, $_GET['card_id'] ?? '');
$email_value        = mysqli_real_escape_string($connection, $_GET['email'] ?? '');
$phone_number_value = mysqli_real_escape_string($connection, $_GET['phone_number'] ?? '');
$address_value      = mysqli_real_escape_string($connection, $_GET['address'] ?? '');
$payment_method     = mysqli_real_escape_string($connection, $_GET['payment'] ?? '');
$card_number        = mysqli_real_escape_string($connection, $_GET['card_number'] ?? '');
$expiry             = mysqli_real_escape_string($connection, $_GET['expiry'] ?? '');
$cvv                = mysqli_real_escape_string($connection, $_GET['cvv'] ?? '');
$brand_name         = mysqli_real_escape_string($connection, $_SESSION['brand_name'] ?? '');

if(isset($_POST['confirm'])){

    if (!empty($_SESSION['cart'])) {

        foreach ($_SESSION['cart'] as $meal_name => $item) {

            $meal_name = mysqli_real_escape_string($connection, $meal_name);
            $price     = (float)$item['price'];
            $quantity  = (int)$item['qty'];

            $sql = "INSERT INTO orders (first_name, last_name, card_id, email, phone_number, address, 
                    payment_method, card_number, expiry, cvv, brand_name, meal_name, price, quantity) 
                    VALUES ('$first_name_value', '$last_name_value', '$card_id_value', '$email_value',
                    '$phone_number_value', '$address_value', '$payment_method', '$card_number',
                    '$expiry', '$cvv', '$brand_name', '$meal_name', '$price', '$quantity')";

            mysqli_query($connection, $sql) or die("Error: " . mysqli_error($connection));
        }

        header('Location: success.php');
        exit();
    }
}

mysqli_close($connection);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Confirm Order - FoodLynk</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/checkout&confirm.css">
</head>
<body>

    <style>

        @keyframes progressFill {
            0% {
                width: 0%;
            }
            100% {
                width: 82%;
            }
        }

    </style>

    <div class="background-gradient"></div>

    <a href="checkout.php" class="home-link">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Back to Checkout
    </a>

    <div class="checkout-container">
        <div class="progress-bar">
            <div class="progress-step completed">
                <div class="step-circle">1</div>
                <span>Cart</span>
            </div>
            <div class="progress-step completed">
                <div class="step-circle">2</div>
                <span>Checkout</span>
            </div>
            <div class="progress-step active">
                <div class="step-circle">3</div>
                <span>Confirm</span>
            </div>
        </div>

        <h1 class="checkout-title">Confirm Your Order</h1>

        <div class="checkout-grid">
            <div class="checkout-form">
            <div class="form-section confirmation-details">
    <h2>Contact Information</h2>
    <div class="detail-row">
        <span class="label">First Name</span>
        <span class="value"><?php echo htmlspecialchars($first_name_value); ?></span>
    </div>
    <div class="detail-row">
        <span class="label">Last Name</span>
        <span class="value"><?php echo htmlspecialchars($last_name_value); ?></span>
    </div>
    <div class="detail-row">
        <span class="label">Card ID</span>
        <span class="value"><?php echo htmlspecialchars($card_id_value); ?></span>
    </div>
    <div class="detail-row">
        <span class="label">Email</span>
        <span class="value"><?php echo htmlspecialchars($email_value); ?></span>
    </div>
    <div class="detail-row">
        <span class="label">Phone</span>
        <span class="value"><?php echo htmlspecialchars($phone_number_value); ?></span>
    </div>
    <div class="detail-row">
        <span class="label">Address</span>
        <span class="value"><?php echo htmlspecialchars($address_value); ?></span>
    </div>
    <div class="detail-row">
        <span class="label">Card Number</span>
        <span class="value"><?php echo htmlspecialchars($card_number); ?></span>
    </div>
    <div class="detail-row">
        <span class="label">Expiry</span>
        <span class="value"><?php echo htmlspecialchars($expiry); ?></span>
    </div>
    <div class="detail-row">
        <span class="label">CVV</span>
        <span class="value"><?php echo htmlspecialchars($cvv); ?></span>
    </div>
</div>


                <form method="post">
                    <button name="confirm" type="submit" class="submit-btn">Confirm Order</button>
                </form>
            </div>

            <div class="checkout-summary">
                <h2>Order Summary</h2>
                <div class="summary-items">
                    <?php
                    $subtotal = 0;
                    if (!empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $meal_name => $item) {
                            $line_total = $item['qty'] * $item['price'];
                            $subtotal += $line_total;
                            echo '<div class="summary-item">';
                            echo '<span>' . $item['qty'] . ' ' . htmlspecialchars($meal_name) . '</span>';
                            echo '<span>$' . number_format($line_total, 2) . '</span>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No items found in cart.</p>';
                    }

                    $delivery_fee = 3.99;
                    $tax = $subtotal * 0.088;
                    $total = $subtotal + $delivery_fee + $tax;
                    ?>
                </div>

                <div class="summary-breakdown">
                    <div><span>Subtotal</span><span>$<?= number_format($subtotal, 2) ?></span></div>
                    <div><span>Delivery Fee</span><span>$<?= number_format($delivery_fee, 2) ?></span></div>
                    <div><span>Tax</span><span>$<?= number_format($tax, 2) ?></span></div>
                    <div class="total"><span>Total</span><span>$<?= number_format($total, 2) ?></span></div>
                </div>

                <div class="delivery-time">Estimated delivery: 25-35 min</div>
            </div>
        </div>
    </div>
</body>
</html>
