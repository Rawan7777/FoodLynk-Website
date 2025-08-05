<?php

$connection = mysqli_connect("localhost", "root", "", "foodlynk");

session_start();

$email = $_SESSION['brand_email'];

$query = "SELECT brand_name FROM brands WHERE email = '$email'";

$result = mysqli_query($connection, $query);

$brand = mysqli_fetch_assoc($result);

$brand_name = $brand['brand_name'];

if(isset($_POST['logout'])){

    session_destroy();
    header("location: brand_login.php");
    exit();
}

// --------------------------------------------------------------------------------------------------

$query_meals = "SELECT * FROM meals WHERE brand_name = '$brand_name'";

$result_meals = mysqli_query($connection, $query_meals);

if(isset($_POST['submit'])){
    
    if(mysqli_num_rows($result) === 1){
        
        $meal_name   	    = mysqli_real_escape_string($connection, $_POST['meal_name']);
        $meal_description   = mysqli_real_escape_string($connection, $_POST['meal_description']);
        $meal_price    		= mysqli_real_escape_string($connection, $_POST['meal_price']);

        if(isset($_POST['meal_quantity'])){

            $meal_quantity   	= mysqli_real_escape_string($connection, $_POST['meal_quantity']);

        }else{
            
            $meal_quantity = -1;
        }
        
        $image_name = $_FILES['meal_image']['name'];
        $image_tmp  = $_FILES['meal_image']['tmp_name'];
        $image_path = "meal_images/" . basename($image_name);
        move_uploaded_file($image_tmp, $image_path);
        
        $insert = "INSERT INTO meals 
				(brand_name, meal_name, meal_description, meal_price, email, meal_quantity, meal_image, status) VALUES 
				('$brand_name', '$meal_name', '$meal_description', '$meal_price', '$email', '$meal_quantity', '$image_path', 'not_active')";

				if (!mysqli_query($connection, $insert)) {
					die("Error inserting into database: " . mysqli_error($connection));
				}

                header("location:" . $_SERVER['PHP_SELF']);
                exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <title>Brand Dashboard</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/brand_dashboard.css" />

</head>
<body>

    <form method="post">
        <button name="logout" class="btn">Log out</button>
    </form>

    <h1>Brand Admin Dashboard</h1>

    <div class="dashboard-container">

        <div class="dashboard-header">
            <h2>Welcome to <?php echo $brand_name?></h2>
            <button class="btn" onclick="showPopup()">Add New Meal</button>
        </div>

        <div class="card-container">

            <?php 
        
            if(mysqli_num_rows($result_meals) > 0){

                $meal_counter = 1;

                while($meal = mysqli_fetch_assoc($result_meals)){

                    echo '  <div class="meal-card">
                                <img src="' . $meal['meal_image'] . ' " alt="Meal ' . $meal_counter++ . ' image" />
                                <h4>' . $meal['meal_name'] . '</h4>
                                <p>' . $meal['meal_description'] . '.</p>
                                <div class="info">
                                    <span>Qty: ' . ($meal['meal_quantity'] > 0 ? $meal['meal_quantity'] : 'Unlimited') . '</span>
                                    <span>$' . $meal['meal_price'] . '</span>
                                </div>
                            </div>';
                }
            }
            ?>

            <div class="meal-card">
                <img src="meal_images/spaghetti-bolognese.jpg" alt="Meal 1" />
                <h4>Spaghetti</h4>
                <p>A classic Italian dish with tomato sauce and cheese.</p>
                <div class="info">
                    <span>Qty: 10</span>
                    <span>$8.99</span>
                </div>
            </div>

    </div>

    <div class="popup-form" id="popup">

        <div class="popup-content">
        <h3>Add New Meal</h3>

        <form method="post" enctype="multipart/form-data">
            <label for="brand_image" class="file-label">Upload Meal Image (407×160)</label>
            <input type="file" name="meal_image" accept="image/*" required />
            <input type="text" name="meal_name" placeholder="Meal Name" maxlength="50" required />
            <textarea name="meal_description" rows="3" placeholder="Description (200 character maximum)" maxlength="200" required></textarea>
            <input type="number" name="meal_price" placeholder="Price" step="0.01" required />
            <input type="number" name="meal_quantity" placeholder="Quantity (Unlimited default)" />
            <button type="submit" name="submit">Submit</button>
            <button type="button" class="close-btn" onclick="hidePopup()">Cancel</button>
        </form>

        </div>
    </div>

    <script>
        function showPopup() {
            document.getElementById("popup").style.display = "flex";
        }

        function hidePopup() {
            document.getElementById("popup").style.display = "none";
        }
    </script>

</body>
</html>
