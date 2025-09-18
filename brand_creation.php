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
$brand_name_error = 0;
$confirm_password_error = 0;


if (isset($_POST["submit"])) {

    $connection = mysqli_connect("localhost", "root", "", "FoodLynk");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $username   		= mysqli_real_escape_string($connection, $_POST['username']);
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
    $image_path = "cover_images/" . basename($image_name);
    move_uploaded_file($image_tmp, $image_path);

	$email_placeholder = $email;
	$brand_name_placeholder = $brand_name;

	if($_POST['password'] == $_POST['confirm_password']){

		$check_query = "SELECT email, brand_name FROM brands WHERE email = '$email'";
		$check_query_result = mysqli_query($connection, $check_query);

		if(mysqli_num_rows($check_query_result) > 0){
			
			$email_error = 1;
			$email_placeholder = "Email already taken";

		}else{

			$brand_name_check_query = "SELECT brand_name FROM brands WHERE brand_name = '$brand_name'";
			$brand_name_check_query_result = mysqli_query($connection, $brand_name_check_query);

			if(mysqli_num_rows($brand_name_check_query_result) > 0){
			
				$brand_name_error = 1;
				$brand_name_placeholder = "Brand Name already taken";
	
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
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/login&signin.css" />

</head>
<body>

	<a href="index.php" class="home-link">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
			<path d="M19 12H5M12 19l-7-7 7-7"/>
		</svg>
		Back to Home
	</a>
		
	<div class="main-container">

		<div class="brand-container">

			<div class="header-section">

				<div class="header-top">
					<div class="brand-icon">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                            <path d="M2 17l10 5 10-5"/>
                            <path d="M2 12l10 5 10-5"/>
                        </svg>
					</div>
					<h1 class="brand-title">Create Your <span>Brand</span></h1>
				</div>

				<p class="brand-subtitle">Join FoodLynk and start reaching customers today</p>

			</div>
			
			<form class="brand-form" method="POST" enctype="multipart/form-data">

				<div class="form-grid">

					<div class="form-group">
						<input type="text" id="first_name" name="first_name" placeholder="<?php echo htmlspecialchars($first_name_placeholder); ?>"
						value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required />
					</div>

					<div class="form-group">
						<input type="text" id="last_name" name="last_name" placeholder="<?php echo htmlspecialchars($last_name_placeholder); ?>" 
						value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" required />
					</div>

					<div class="form-group">
						<input type="text" id="username" name="username" placeholder="<?php echo htmlspecialchars($username_placeholder); ?>"
						value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required />
					</div>

					<div class="form-group">
						<input type="text" id="brand_name" name="brand_name" placeholder="<?php echo htmlspecialchars($brand_name_placeholder); ?>" 
						value="<?php echo isset($_POST['brand_name']) && !$brand_name_error ? htmlspecialchars($_POST['brand_name']) : ''; ?>" 
						class="<?php echo !empty($brand_name_error) ? 'error' : ''; ?>" 
						required/>
						<?php if($brand_name_error): ?>
							<span class="error-message">Brand name already taken</span>
						<?php endif; ?>
					</div>

					<div class="form-group">
						<input type="email" id="email" name="email" placeholder="<?php echo htmlspecialchars($email_placeholder); ?>" 
						value="<?php echo isset($_POST['email']) && !$email_error ? htmlspecialchars($_POST['email']) : ''; ?>" 
						class="<?php echo !empty($email_error) ? 'error' : ''; ?>" 
						required/>
						<?php if($email_error): ?>
							<span class="error-message">Email already taken</span>
						<?php endif; ?>
					</div>

					<div class="form-group">
						<input type="tel" id="phone_number" name="phone_number" placeholder="<?php echo htmlspecialchars($phone_number_placeholder); ?>" 
						value="<?php echo isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number']) : ''; ?>" required />
					</div>

					<div class="form-group">
						<input type="text" id="address" name="address" placeholder="<?php echo htmlspecialchars($address_placeholder); ?>" 
						value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" required />
					</div>

					<div class="form-group">
						<select id="category" name="category" required>
							<option value="">Select Category</option>
							<option value="restaurant" <?php echo (isset($_POST['category']) && $_POST['category'] == 'restaurant') ? 'selected' : ''; ?>>Restaurant</option>
							<option value="supermarket" <?php echo (isset($_POST['category']) && $_POST['category'] == 'supermarket') ? 'selected' : ''; ?>>Supermarket</option>
							<option value="cafe" <?php echo (isset($_POST['category']) && $_POST['category'] == 'cafe') ? 'selected' : ''; ?>>Cafe</option>
							<option value="bakery" <?php echo (isset($_POST['category']) && $_POST['category'] == 'bakery') ? 'selected' : ''; ?>>Bakery</option>
						</select>
					</div>

					<div class="form-group">
						<input type="password" id="password" name="password" placeholder="<?php echo htmlspecialchars($password_placeholder); ?>" required />
					</div>

					<div class="form-group">
						<input type="password" id="confirm_password" name="confirm_password" placeholder="<?php echo htmlspecialchars($confirm_password_placeholder); ?>" 
						class="<?php echo !empty($confirm_password_error) ? 'error' : ''; ?>" 
						required />
						<?php if($confirm_password_error): ?>
							<span class="error-message">Passwords don't match</span>
						<?php endif; ?>
					</div>

					<div class="form-group full-width">
						<div class="file-input-wrapper">
							<input type="file" id="brand_image" name="brand_image" accept="image/*" />
							<div class="file-input-placeholder">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
									<circle cx="8.5" cy="8.5" r="1.5"/>
									<polyline points="21,15 16,10 5,21"/>
								</svg>
								<span>Choose image file</span>
							</div>
						</div>
					</div>

				</div>

				<button type="submit" name="submit" class="submit-btn">

					<span>Create Your Brand</span>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M5 12h14M12 5l7 7-7 7"/>
					</svg>

				</button>

			</form>

			<div class="footer-links">
				<p>Already have a brand? <a href="brand_login.php">Sign in here</a></p>
			</div>

		</div>
		
	</div>
</body>
</html>