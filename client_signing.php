<?php 

$first_name_placeholder 		= "First Name";
$last_name_placeholder 			= "Last Name";
$card_id_placeholder 			= "Card ID";
$phone_number_placeholder 		= "Phone Number";
$email_placeholder 				= "Email";
$address_placeholder 			= "Address";
$password_placeholder 			= "Password";
$confirm_password_placeholder 	= "Confirm Password";

$email_error = 0;
$confirm_password_error = 0;

if (isset($_POST["submit"])) {

    $connection = mysqli_connect("localhost", "root", "", "FoodLynk");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $first_name		= mysqli_real_escape_string($connection, $_POST['first_name']);
    $last_name 		= mysqli_real_escape_string($connection, $_POST['last_name']);
    $card_id 		= mysqli_real_escape_string($connection, $_POST['card_id']);
    $email     		= mysqli_real_escape_string($connection, $_POST['email']);
    $address   		= mysqli_real_escape_string($connection, $_POST['address']);
    $phone_number	= mysqli_real_escape_string($connection, $_POST['phone_number']);
    $password  		= password_hash($_POST['password'], PASSWORD_DEFAULT);

	$email_placeholder = $email;

	if($_POST['password'] == $_POST['confirm_password']){

		$check_query = "SELECT email FROM clients WHERE email = '$email'";
		$check_query_result = mysqli_query($connection, $check_query);

		if(mysqli_num_rows($check_query_result) > 0){

			$email_error = 1;
			$email_placeholder = "Email already taken";

		}else{

			$query = "INSERT INTO clients 
            (first_name, last_name, phone_number, email, address, card_id, password) VALUES 
            ('$first_name', '$last_name', '$phone_number', '$email', '$address', '$card_id', '$password')";

			if (!mysqli_query($connection, $query)) {
				die("Error inserting into database: " . mysqli_error($connection));
			}

			session_start();
            
            $_SESSION['client_email'] = $email;
            
            header("Location: client_account.php");
            exit();
		}
	}else{

		$confirm_password_error = 1;
		$confirm_password_placeholder = "Confirm Password Doesn't match";
	}    

    mysqli_close($connection);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

	<title>Sign-in - FoodLynk</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="css/create_account.css" />

</head>

<body>

  <a href="index.php" class="home-arrow">&#8592; Home</a>

	<div class="brand-container">

		<h2 class="brand-title">Sign In <span>FoodLynk</span></h2>

		<form class="brand-form" method="POST" enctype="multipart/form-data">

			<div class="form-column">

				<input type="text" name="first_name" placeholder="<?php echo $first_name_placeholder; ?>"
				value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required />
				
				<input type="text" name="card_id" placeholder="<?php echo $card_id_placeholder; ?>"
				value="<?php echo isset($_POST['card_id']) ? htmlspecialchars($_POST['card_id']) : ''; ?>" required />

				<input type="tel" name="phone_number" placeholder="<?php echo $phone_number_placeholder; ?>" 
				value="<?php echo isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number']) : ''; ?>"required />

				<input type="password" name="password" placeholder="<?php echo $password_placeholder; ?>" 
				value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>"required />

			</div>

			<div class="form-column">

				<input type="text" name="last_name" placeholder="<?php echo $last_name_placeholder; ?>" 
				value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" required />

				<input type="email" name="email" placeholder="<?php echo $email_placeholder; ?>" 
				value="<?php echo isset($_POST['email']) && !$email_error ? htmlspecialchars($_POST['email']) : ''; ?>" 
				style="<?php echo !empty($email_error) ? 'border: 2px solid red; color: red;' : ''; ?>" 
				required />

				<input type="text" name="address" placeholder="<?php echo $address_placeholder; ?>" 
				value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" required />

				<input type="password" name="confirm_password" placeholder="<?php echo $confirm_password_placeholder; ?>" 
				value="<?php echo isset($_POST['confirm_password']) && !$confirm_password_error ? htmlspecialchars($_POST['confirm_password']) : ''; ?>" 
				style="<?php echo !empty($confirm_password_error) ? 'border: 2px solid red; color: red;' : ''; ?>" 
				required />

			</div>

			<button type="submit" name="submit">Sign In</button>
		
		</form>

		<a href="client_login.php" class="already-link">Already have an account? Login</a>

	</div>

</body>
</html>
