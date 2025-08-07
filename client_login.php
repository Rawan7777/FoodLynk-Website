<?php

$email_placeholder = "Email";
$password_placeholder = "Password";

$email_error = false;
$password_error = false;

if(isset($_POST['login'])){

	$connection = mysqli_connect("localhost", "root", "", "FoodLynk");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

	$email = $_POST['email'];
	$password = $_POST['password'];

	$query = "SELECT * FROM clients WHERE email = '$email'";

	$result = mysqli_query($connection, $query);

	if(mysqli_num_rows($result) === 1){

		$client = mysqli_fetch_assoc($result);

		if(password_verify($password, $client['password'])){
			
			session_start();
            
            $_SESSION['client_email'] = $email;
            
            header("Location: client_account.php");
            exit();
			
		} else {

            $password_error = true;
            $password_placeholder = "Incorrect password";
        }

	} else {

        $email_error = true;
        $email_placeholder = "Email not found";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

	<title>Login - FoodLynk</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="css/login.css" />

</head>

<body>

  <a href="index.php" class="home-arrow">&#8592; Home</a>

  <div class="login-container">

		<h2 class="login-title">Welcome Back to <span>FoodLynk</span></h2>
		
		<form class="login-form" method="POST">
			
			
			<input type="email" name="email" placeholder="<?php echo $email_placeholder; ?>"
			style="<?php echo $email_error ? 'border: 2px solid red; color: red;' : ''; ?>"
			value="<?php echo isset($_POST['email']) && !$email_error ? htmlspecialchars($_POST['email']) : ''; ?>"
            required />

			<input type="password" name="password" placeholder="<?php echo $password_placeholder; ?>"
			style="<?php echo $password_error ? 'border: 2px solid red; color: red;' : ''; ?>"
			value="<?php echo !$password_error && isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>" required />
			
			<button type="submit" name="login">Login</button>
		
		</form>

		<a href="client_signing.php" class="signup-link">Don't have an account? Sign up</a>
		
  </div>

</body>
</html>
