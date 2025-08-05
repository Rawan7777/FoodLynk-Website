<?php

$email_placeholder = "Email";
$password_placeholder = "Password";

$email_error = false;
$password_error = false;

if (isset($_POST['submit'])) {

    $connection = mysqli_connect("localhost", "root", "", "FoodLynk");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM brands WHERE email = '$email'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) === 1) {

        $brand = mysqli_fetch_assoc($result);

        if (password_verify($password, $brand['password'])) {

            session_start();
            
            $_SESSION['brand_email'] = $email;

            if($brand['status'] == "pending"){

                header("Location: brand_waiting.php");
                exit();

            }else if($brand['status'] == "approve"){

                header("Location: brand_dashboard.php");
                exit();

            }else if($brand['status'] == "reject" || $brand['status'] == "suspend"){

                header("Location: brand_waiting.php");
                exit();
            }
            

        } else {

            $password_error = true;
            $password_placeholder = "Incorrect password";
        }

    } else {

        $email_error = true;
        $email_placeholder = "Email not found";
    }

    mysqli_close($connection);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

	<title>Brand Login - FoodLynk</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="css/login.css" />

</head>
<body>

	<a href="index.php" class="home-arrow">&#8592; Home</a>

	<div class="login-container">

		<h2 class="login-title">Brand <span>Login</span></h2>

		<form class="login-form" method="POST">

			<input type="email" name="email" placeholder="<?php echo $email_placeholder; ?>"
			style="<?php echo $email_error ? 'border: 2px solid red; color: red;' : ''; ?>"
			value="<?php echo isset($_POST['email']) && !$email_error ? htmlspecialchars($_POST['email']) : ''; ?>"
            required />

			<input type="password" name="password" placeholder="<?php echo $password_placeholder; ?>"
			style="<?php echo $password_error ? 'border: 2px solid red; color: red;' : ''; ?>"
			value="<?php echo !$password_error && isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>" required />

			<button type="submit" name="submit">Login</button>

		</form>

		<a href="brand_creation.php" class="signup-link">Don't have a brand? Create one</a>
	
    </div>

</body>
</html>
