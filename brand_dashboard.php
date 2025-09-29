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

$query_meals = "SELECT * FROM mealsnew WHERE brand_name = '$brand_name'";
$result_meals = mysqli_query($connection, $query_meals);

if(isset($_POST['submit'])){

    if(mysqli_num_rows($result) === 1){

        $meal_name = mysqli_real_escape_string($connection, $_POST['meal_name']);
        $meal_description = mysqli_real_escape_string($connection, $_POST['meal_description']);
        $meal_price = mysqli_real_escape_string($connection, $_POST['meal_price']);
        $meal_quantity = isset($_POST['meal_quantity']) ? mysqli_real_escape_string($connection, $_POST['meal_quantity']) : -1;

        $is_vegan 			= isset($_POST['is_vegan']) ? 1 : 0;
        $is_spicy 			= isset($_POST['is_spicy']) ? 1 : 0;
        $is_gluten_free 	= isset($_POST['is_gluten_free']) ? 1 : 0;
		$is_nut_free    	= isset($_POST['is_nut_free']) ? 1 : 0;
		$is_halal       	= isset($_POST['is_halal']) ? 1 : 0;
		$is_low_carb    	= isset($_POST['is_low_carb']) ? 1 : 0;
		$is_low_sugar   	= isset($_POST['is_low_sugar']) ? 1 : 0;
		$category 			= isset($_POST['category']) ? $_POST['category'] : '';

        $image_name = $_FILES['meal_image']['name'];
        $image_tmp  = $_FILES['meal_image']['tmp_name'];
        $image_path = "meal_images/" . basename($image_name);
        move_uploaded_file($image_tmp, $image_path);

        $insert = "INSERT INTO mealsnew 
			(brand_name, email, meal_name, meal_description, meal_image, meal_quantity, meal_price, category, 
			is_vegan, is_spicy, is_gluten_free, is_nut_free, is_halal, is_low_carb, is_low_sugar, status) 
			VALUES 
			('$brand_name', '$email', '$meal_name', '$meal_description', '$image_path', '$meal_quantity', '$meal_price', '$category', 
			'$is_vegan', '$is_spicy', '$is_gluten_free', '$is_nut_free', '$is_halal', '$is_low_carb', '$is_low_sugar', 'Deactivate')";

        if (!mysqli_query($connection, $insert)) {
            die("Error inserting into database: " . mysqli_error($connection));
        }

        header("location:" . $_SERVER['PHP_SELF']);
        exit();
    }

}

if(isset($_POST['delete']) && isset($_POST['confirm_delete'])){

    if(isset($_GET['managed_meal_name'])){

        $managed_meal_name = mysqli_real_escape_string($connection, $_GET['managed_meal_name']);

        if(!empty($managed_meal_name)){
            $delete_query_meals = "DELETE FROM mealsnew WHERE meal_name = '$managed_meal_name' AND brand_name = '$brand_name'";
            mysqli_query($connection, $delete_query_meals);
        }
    }

	header("location:" . $_SERVER['PHP_SELF']);
    exit();
}

if(isset($_POST['disable']) && isset($_POST['confirm_disable'])){

    if(isset($_GET['managed_meal_name'])){

        $managed_meal_name = mysqli_real_escape_string($connection, $_GET['managed_meal_name']);

        if(!empty($managed_meal_name)){

            $current_status_query = "SELECT status FROM mealsnew WHERE meal_name = '$managed_meal_name' AND brand_name = '$brand_name'";
            $current_status_result = mysqli_query($connection, $current_status_query);
            $current_status = mysqli_fetch_assoc($current_status_result);
            
            $new_status = ($current_status['status'] === 'Deactivate') ? 'Activate' : 'Deactivate';

			$updateQuery = "UPDATE mealsnew SET status = '$new_status' WHERE meal_name = '$managed_meal_name' AND brand_name = '$brand_name'";
			mysqli_query($connection, $updateQuery);
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Brand Dashboard</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/brand_dashboard.css">

</head>

<body>

    <div class="top-bar">

        <a href="index.php" class="home-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to Home
        </a>

		<h1>Brand Admin Dashboard</h1>

		<form method="post" style="margin:0;">
			<button name="logout" class="btn">Logout</button>
		</form>

    </div>

    <div class="dashboard-container">

        <div class="dashboard-header">
            <h2>Welcome, <?php echo $brand_name ?></h2>
            <button class="btn" onclick="showPopup()">➕ Add New Meal</button>
        </div>

        <div class="card-container">

            <?php 

            if(mysqli_num_rows($result_meals) > 0){

                $meal_counter = 1;

                while($meal = mysqli_fetch_assoc($result_meals)){

                    $mealStatusButton = ($meal['status'] === 'Deactivate') ? 'Activate' : 'Deactivate';

					echo '
					<div class="meal-card">

						<div class="card-top-buttons">

							<form method="post" action="brand_dashboard.php?managed_meal_name=' . urlencode($meal['meal_name']) . '" id="delete-form-' . $meal_counter . '">
								<button type="button" class="btn-delete" onclick="showDeleteConfirm(\'' . htmlspecialchars($meal['meal_name'], ENT_QUOTES) . '\', ' . $meal_counter . ')">Delete</button>
								<input type="hidden" name="confirm_delete" value="1">
							</form>

							<form method="post" action="brand_dashboard.php?managed_meal_name=' . urlencode($meal['meal_name']) . '" id="disable-form-' . $meal_counter . '">
								<button type="button" class="btn-disable" onclick="showStatusConfirm(\'' . htmlspecialchars($meal['meal_name'], ENT_QUOTES) . '\', \'' . $mealStatusButton . '\', ' . $meal_counter . ')">' . $mealStatusButton . '</button>
								<input type="hidden" name="confirm_disable" value="1">
							</form>
                            
						</div>

						<img src="' . $meal['meal_image'] . '" alt="Meal ' . $meal_counter++ . ' image" />
						<h4>' . $meal['meal_name'] . '</h4>
						<p>' . $meal['meal_description'] . '</p>

						<div class="info">
							<span>Qty: ' . ($meal['meal_quantity'] > 0 ? $meal['meal_quantity'] : 'Unlimited') . '</span>
							<span>$' . $meal['meal_price'] . '</span>
						</div>

					</div>';
                }
            }

            ?>

        </div>

    </div>

    <div class="popup-overlay" id="deleteConfirmPopup" style="display: none;">

        <div class="popup-container confirm-popup">

            <div class="popup-header">
                <h2>Confirm Delete</h2>
                <button type="button" class="close-icon" onclick="hideDeleteConfirm()">✕</button>
            </div>

            <div class="confirm-content">
                <p>Are you sure you want to delete "<span id="deleteMealName"></span>"?</p>
                <p><strong>This action cannot be undone.</strong></p>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="hideDeleteConfirm()">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete</button>
            </div>

        </div>
    </div>

    <div class="popup-overlay" id="statusConfirmPopup" style="display: none;">

        <div class="popup-container confirm-popup">

            <div class="popup-header">
                <h2>Confirm Status Change</h2>
                <button type="button" class="close-icon" onclick="hideStatusConfirm()">✕</button>
            </div>

            <div class="confirm-content">
                <p>Are you sure you want to <span id="statusAction"></span> "<span id="statusMealName"></span>"?</p>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="hideStatusConfirm()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmStatusChange()">Confirm</button>
            </div>

        </div>

    </div>

    <div class="popup-overlay" id="popup">

		<div class="popup-container">

			<div class="popup-header">
				<h2>Add New Meal</h2>
				<button type="button" class="close-icon" onclick="hidePopup()">✕</button>
			</div>

			<form method="post" enctype="multipart/form-data" class="popup-form">

				<div class="popup-grid">
				
					<div class="left-column">

						<div class="image-upload-area">
							<input type="file" name="meal_image" id="meal_image" accept="image/*" hidden required>
							<label for="meal_image" class="image-upload-label">
							<span class="upload-icon">📷</span>
							<span class="upload-text">Click to upload meal image</span>
							<span class="upload-subtext">PNG, JPG up to 10MB</span>
							</label>
						</div>

						<div class="form-group">
							<label for="meal_description">Description</label>
							<textarea name="meal_description" id="meal_description" rows="5" maxlength="200" placeholder="Describe your meal (max 200 characters)" required></textarea>
						</div>

					</div>

					<div class="right-column">

						<div class="form-group">
							<label for="meal_name">Meal Name</label>
							<input type="text" name="meal_name" id="meal_name" maxlength="150" placeholder="Enter meal name" required>
						</div>

						<div class="form-group">
							<label for="category">Category</label>
							<select name="category" id="category" required>
							<option value="">Select category</option>
							<option value="starter">Starter</option>
							<option value="main">Main Course</option>
							<option value="drink">Drink</option>
							</select>
						</div>

						<div class="form-group">
							<label for="meal_price">Price</label>
							<input type="number" name="meal_price" id="meal_price" step="0.01" placeholder="0.00" required>
						</div>

						<div class="form-group">
							<label for="meal_quantity">Quantity</label>
							<input type="number" name="meal_quantity" id="meal_quantity" placeholder="Leave empty for unlimited">
						</div>

					</div>

				</div>

				<div class="form-group full-width">

					<label>Dietary Options</label>

					<div class="checkbox-row">
						<label><input type="checkbox" name="is_gluten_free" value="1"> Gluten-Free</label>
						<label><input type="checkbox" name="is_vegan" value="1"> Vegan</label>
						<label><input type="checkbox" name="is_spicy" value="1"> Spicy</label>
						<label><input type="checkbox" name="is_nut_free" value="1"> Nut-Free</label>
						<label><input type="checkbox" name="is_halal" value="1"> Halal</label>
						<label><input type="checkbox" name="is_low_carb" value="1"> Low-Carb</label>
						<label><input type="checkbox" name="is_low_sugar" value="1"> Low-Sugar</label>
					</div>

				</div>

				<div class="form-actions">
					<button type="button" class="btn btn-secondary" onclick="hidePopup()">Cancel</button>
					<button type="submit" name="submit" class="btn btn-primary">Add Meal</button>
				</div>

			</form>
		</div>
	</div>

    <script>

        function showPopup(){ document.getElementById("popup").style.display="flex"; }
        function hidePopup(){ document.getElementById("popup").style.display="none"; }

        let currentDeleteForm = null;
        let currentStatusForm = null;

        function showDeleteConfirm(mealName, formId) {

            document.getElementById("deleteMealName").textContent = mealName;
            document.getElementById("deleteConfirmPopup").style.display = "flex";
            currentDeleteForm = formId;
        }

        function hideDeleteConfirm() {

            document.getElementById("deleteConfirmPopup").style.display = "none";
            currentDeleteForm = null;
        }

        function confirmDelete() {

            if (currentDeleteForm) {

                const form = document.getElementById("delete-form-" + currentDeleteForm);
                const deleteButton = document.createElement('input');
                deleteButton.type = 'hidden';
                deleteButton.name = 'delete';
                deleteButton.value = '1';
                form.appendChild(deleteButton);
                form.submit();
            }
        }

        function showStatusConfirm(mealName, action, formId) {

            document.getElementById("statusMealName").textContent = mealName;
            document.getElementById("statusAction").textContent = action.toLowerCase();
            document.getElementById("statusConfirmPopup").style.display = "flex";
            currentStatusForm = formId;
        }

        function hideStatusConfirm() {

            document.getElementById("statusConfirmPopup").style.display = "none";
            currentStatusForm = null;
        }

        function confirmStatusChange() {

            if (currentStatusForm) {

                const form = document.getElementById("disable-form-" + currentStatusForm);
                const disableButton = document.createElement('input');
                disableButton.type = 'hidden';
                disableButton.name = 'disable';
                disableButton.value = '1';
                form.appendChild(disableButton);
                form.submit();
            }
        }
		
    </script>

</body>
</html>