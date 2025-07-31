<?php 

$first_name_placeholder 		= "First Name";
$last_name_placeholder 			= "Last Name";
$brand_name_placeholder 		= "Brand Name";
$username_placeholder 			= "Username";
$phone_number_placeholder 		= "Phone Number";
$email_placeholder 				= "Email";
$address_placeholder 			= "Address";
$password_placeholder 			= "Password";
$confirm_password_placeholder 	= "Confirm Password";
$category_placeholder 			= "Category";

$email_error = 0;
$confirm_password_error = 0;


if (isset($_POST["submit"])) {

    $connection = mysqli_connect("localhost", "root", "", "FoodLynk");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $username   	= mysqli_real_escape_string($connection, $_POST['username']);
    $first_name   		= mysqli_real_escape_string($connection, $_POST['first_name']);
    $last_name    		= mysqli_real_escape_string($connection, $_POST['last_name']);
    $brand_name   		= mysqli_real_escape_string($connection, $_POST['brand_name']);
    $email        		= mysqli_real_escape_string($connection, $_POST['email']);
    $address      		= mysqli_real_escape_string($connection, $_POST['address']);
    $phone_number 		= mysqli_real_escape_string($connection, $_POST['phone_number']);
    $category     		= mysqli_real_escape_string($connection, $_POST['category']);
    $password     		= password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Handle image upload
    $image_name = $_FILES['brand_image']['name'];
    $image_tmp  = $_FILES['brand_image']['tmp_name'];
    $image_path = "uploads/" . basename($image_name);
    move_uploaded_file($image_tmp, $image_path);

	$email_placeholder = $email;

	if($_POST['password'] == $_POST['confirm_password']){

		$check_query = "SELECT email FROM brands WHERE email = '$email'";
		$check_query_result = mysqli_query($connection, $check_query);

		if(mysqli_num_rows($check_query_result) > 0){
			
			$email_error = 1;
			$email_placeholder = "Email already taken";

		}else{

			$query = "INSERT INTO brands 
            (username, first_name, last_name, phone_number, email, address, brand_name, brand_image, password, category, status) VALUES 
            ('$username', '$first_name', '$last_name', '$phone_number', '$email', '$address', '$brand_name', '$image_path', '$password', '$category', 'pending')";

			if (!mysqli_query($connection, $query)) {
				die("Error inserting into database: " . mysqli_error($connection));
			}

			session_start();
            
            $_SESSION['brand_email'] = $email;
            
            header("Location: brand_waiting.php");
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

	<title>Create Brand - FoodLynk</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="css/create_account.css" />

</head>
<body>

	<a href="index.php" class="home-arrow">&#8592; Home</a>

	<div class="brand-container">

		<h2 class="brand-title">Create Your <span>Brand</span></h2>
		
		<form class="brand-form" method="POST" enctype="multipart/form-data">

			<div class="form-column">

				<input type="text" name="first_name" placeholder="<?php echo $first_name_placeholder; ?>"
				value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required />

				<input type="text" name="brand_name" placeholder="<?php echo $brand_name_placeholder; ?>"
				value="<?php echo isset($_POST['brand_name']) ? htmlspecialchars($_POST['brand_name']) : ''; ?>" required />

				<input type="tel" name="phone_number" placeholder="<?php echo $phone_number_placeholder; ?>" 
				value="<?php echo isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number']) : ''; ?>"required />

				<input type="password" name="password" placeholder="<?php echo $password_placeholder; ?>" 
				value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>"required />

				<input type="text" name="username" placeholder="<?php echo $username_placeholder; ?>"
				value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required />

			</div>
			
			<div class="form-column">
				<input type="text" name="last_name" placeholder="<?php echo $last_name_placeholder; ?>" 
				value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" required />

				<input type="email" name="email" placeholder="<?php echo $email_placeholder; ?>" 
				value="<?php echo isset($_POST['email']) && !$email_error ? htmlspecialchars($_POST['email']) : ''; ?>" 
				style="<?php echo !empty($email_error) ? 'border: 2px solid red; color: red;' : ''; ?>" 
				required/>

				<input type="text" name="address" placeholder="<?php echo $address_placeholder; ?>" 
				value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" required />

				<input type="password" name="confirm_password" placeholder="<?php echo $confirm_password_placeholder; ?>" 
				value="<?php echo isset($_POST['confirm_password']) && !$confirm_password_error ? htmlspecialchars($_POST['confirm_password']) : ''; ?>" 
				style="<?php echo !empty($confirm_password_error) ? 'border: 2px solid red; color: red;' : ''; ?>" 
				required />

				<input type="category" name="category" placeholder="<?php echo $category_placeholder; ?>" 
				value="<?php echo isset($_POST['category']) ? htmlspecialchars($_POST['category']) : ''; ?>" required />
			</div>

			<div style="width: 100%;">
				<label for="brand_image" class="file-label">Upload Brand Image (1000×600)</label>
				<input type="file" id="brand_image" name="brand_image" accept="image/*" />
			</div>

			<button type="submit" name="submit">Create Your Brand</button>

		</form>

		<a href="brand_login.php" class="already-link">Already have a brand?</a>

	</div>

</body>
</html>
