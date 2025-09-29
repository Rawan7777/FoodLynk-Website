<?php

session_start();

$first_name_value = "";
$last_name_value = "";
$card_id_value = "";
$email_value = "";
$phone_number_value = "";
$address_value = "";

$readonly = "";

if (isset($_SESSION['brand_name']))
{
	$brand_name = $_SESSION['brand_name'];
}

if (isset($_SESSION['client_email']))
{

	$readonly = "readonly";

	$connection = mysqli_connect("localhost", "root", "", "foodlynk");

	if (!$connection) {
		die("Connection failed: " . mysqli_connect_error());
	}

	$client_email = $_SESSION['client_email'] ?? null;
	$query = "SELECT * FROM clients WHERE email = '$client_email'";
	$result = mysqli_query($connection, $query);
	$fetch = mysqli_fetch_assoc($result);

	if($fetch){

		$first_name_value = $fetch['first_name'];
		$last_name_value = $fetch['last_name'];
		$card_id_value = $fetch['card_id'];
		$email_value = $fetch['email'];
		$phone_number_value = $fetch['phone_number'];
		$address_value = $fetch['address'];
	}

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Location: checkout.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - FoodLynk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/checkout&confirm.css">

</head>

<body>

    <div class="background-gradient"></div>

    <a href="menu.php?brand_name=<?php echo htmlspecialchars($brand_name); ?>" class="home-link">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
			<path d="M19 12H5M12 19l-7-7 7-7"/>
		</svg>
		Back to Menu
	</a>

    <div class="checkout-container">

        <div class="progress-bar">

            <div class="progress-step completed">
                <div class="step-circle">1</div>
                <span>Cart</span>
            </div>

            <div class="progress-step active">
                <div class="step-circle">2</div>
                <span>Checkout</span>
            </div>

            <div class="progress-step">
                <div class="step-circle">3</div>
                <span>Confirm</span>
            </div>

        </div>

        <h1 class="checkout-title">Complete Your Order</h1>

        <div class="checkout-grid">

            <div class="checkout-form">

				<form method="get" action="confirm.php">

					<div class="form-section">

						<h2>Personal Information</h2>

						<div class="input-row">

							<div class="input-group">
								<label>First Name</label>
								<input type="text" name="first_name" placeholder="Enter your first name" value="<?php echo $first_name_value; ?>" <?php echo $readonly; ?> required>
							</div>

							<div class="input-group">
								<label>Last Name</label>
								<input type="text" name="last_name" placeholder="Enter your last name" value="<?php echo $last_name_value; ?>" <?php echo $readonly; ?> required>
							</div>

							<div class="input-group">
								<label>Card ID</label>
								<input type="text" name="card_id" placeholder="Enter your card ID" value="<?php echo $card_id_value; ?>" <?php echo $readonly; ?> required>
							</div>

						</div>

						<div class="input-row">

							<div class="input-group">
								<label>Email Address</label>
								<input type="email" name="email" placeholder="you@email.com" value="<?php echo $email_value; ?>" <?php echo $readonly; ?> required>
							</div>

							<div class="input-group">
								<label>Phone Number</label>
								<input type="tel" name="phone_number" placeholder="+1 (555) 123-4567" value="<?php echo $phone_number_value; ?>" required>
							</div>

						</div>

						<div class="input-group">
							<label>Delivery Address</label>
							<textarea name="address" rows="3" placeholder="Enter delivery address" required><?php echo $address_value; ?></textarea>
						</div>

					</div>

					<div class="form-section">

						<h2>Payment Method</h2>

						<div class="payment-methods">

							<label class="payment-option">
								<input type="radio" name="payment" value="card" checked>
								<span>Credit/Debit Card</span>
							</label>

							<label class="payment-option">
								<input type="radio" name="payment" value="paypal">
								<span>PayPal</span>
							</label>

							<label class="payment-option">
								<input type="radio" name="payment" value="cod">
								<span>Cash on Delivery</span>
							</label>

						</div>

						<div class="card-info">

							<div class="input-group">
								<label>Card Number</label>
								<input type="text" name="card_number" placeholder="1234 5678 9012 3456" required>
							</div>

							<div class="input-row">

								<div class="input-group">
									<label>Expiry</label>
									<input type="text" name="expiry" placeholder="MM/YY" required>
								</div>

								<div class="input-group">
									<label>CVV</label>
									<input type="password" name="cvv" placeholder="123" required>
								</div>

							</div>

						</div>

					</div>

					<button type="submit" class="submit-btn">Complete Order</button>

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
