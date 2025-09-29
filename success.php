<?php

session_start();

$subtotal = 0;
$delivery_fee = 3.99;

if (!empty($_SESSION['cart'])) {

    foreach ($_SESSION['cart'] as $meal_name => $item) {

        $line_total = $item['qty'] * $item['price'];
        $subtotal += $line_total;
    }
}

$tax = $subtotal * 0.088;
$total = $subtotal + $delivery_fee + $tax;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/success.css">

</head>

<body>

    <div class="background-gradient"></div>

    <div class="success-container">

        <h1>🎉 Order Placed Successfully!</h1>

        <div class="confirmation-details">

            <h2>Order Summary</h2>

            <?php if (!empty($_SESSION['cart'])): ?>

                <?php foreach ($_SESSION['cart'] as $meal_name => $item): 
                    $line_total = $item['qty'] * $item['price'];
                ?>

                    <div class="detail-row">
                        <span class="label"><?php echo htmlspecialchars($item['qty']); ?> × <?php echo htmlspecialchars($meal_name); ?></span>
                        <span class="value">$<?php echo number_format($line_total, 2); ?></span>
                    </div>

                <?php endforeach; ?>

                <div class="detail-row">
                    <span class="label">Delivery Fee</span>
                    <span class="value">$<?php echo number_format($delivery_fee, 2); ?></span>
                </div>

                <div class="detail-row">
                    <span class="label">Tax</span>
                    <span class="value">$<?php echo number_format($tax, 2); ?></span>
                </div>

                <div class="detail-row total">
                    <span class="label">Total</span>
                    <span class="value">$<?php echo number_format($total, 2); ?></span>
                </div>

            <?php else: ?>

                <p class="no-items">No items in cart.</p>

            <?php endif; ?>

        </div>

        <a href="index.php" class="btn-home">Back to Home</a>

    </div>

</body>
</html>

