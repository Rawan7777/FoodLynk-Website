<?php

$connection = mysqli_connect("localhost", "root", "", "foodlynk");

if (!$connection) {
  die("Connection failed: " . mysqli_connect_error());
}

session_start();

$brand_email = $_SESSION['brand_email'];

$query = "SELECT username, first_name, last_name, email, phone_number, address, brand_name, category, status 
        FROM brands WHERE email = '$brand_email' ";

$result = mysqli_query($connection, $query);
$brand = mysqli_fetch_assoc($result);

if($brand['status'] == 'approve'){

	header("location: brand_dashboard.php");
	exit();

} else if($brand['status'] == 'suspend' || $brand['status'] == 'reject'){

	$_SESSION['brand_status'] = $brand['status'];
	header("location: brand_status.php");
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

	<title>Brand Submission - FoodLynk</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="css/create_account.css" />

</head>

<body>

	<a href="index.html" class="home-arrow">&#8592; Home</a>

	<form method="post" class="confirmation-box">

		<h2>Brand Submitted Successfully</h2>

		<p>Thank you for submitting your brand details. Please wait for master-admin confirmation.</p>

		<div class="info-box">
			<p><strong>First Name: </strong> <?php echo $brand['first_name']; ?></p>
			<p><strong>Last Name: </strong> <?php echo $brand['last_name']; ?></p>
			<p><strong>The Brand: </strong> <?php echo $brand['brand_name']; ?></p>
			<p><strong>Username: </strong> <?php echo $brand['username']; ?></p>
			<p><strong>Category: </strong> <?php echo $brand['category']; ?></p>
			<p><strong>Email: </strong> <?php echo $brand['email']; ?></p>
			<p><strong>Phone Number: </strong> <?php echo $brand['phone_number']; ?></p>
			<p><strong>Address: </strong> <?php echo $brand['address']; ?></p>
		</div>

		<button class="button" type="submit" name="logout">Logout</button>
	</form>

</body>
</html>