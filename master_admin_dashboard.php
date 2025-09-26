<?php

session_start();

$connection = mysqli_connect("localhost", "root", "", "foodlynk");

$query = "SELECT id, brand_name, first_name, last_name, email, status FROM brands";

$result = mysqli_query($connection, $query);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    
    $id = $_POST['id'];

    if (isset($_POST['approve'])) {
        $querytwo = "UPDATE brands SET status = 'approve' WHERE id = $id";
    } elseif (isset($_POST['reject'])) {
        $querytwo = "UPDATE brands SET status = 'reject' WHERE id = $id";
    } elseif (isset($_POST['suspend'])) {
        $querytwo = "UPDATE brands SET status = 'suspend' WHERE id = $id";
    }

    if (isset($querytwo)) {

        $resulttwo = mysqli_query($connection, $querytwo);

        if ($resulttwo) {
            $_SESSION['update'] = "✅ Status updated successfully for user ID: $id.";
        } else {
            $_SESSION['update'] = "❌ Error updating status: " . mysqli_error($connection);
        }

		header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Master Admin Dashboard</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/master-admin-dashboard.css" />

</head>

<body>

    <div class="background-gradient"></div>

    <div class="main-container">

        <div class="brand-container">

            <div class="header-section">
                <div class="header-top">
                    <h1 class="brand-title"><span>Master Admin Dashboard</span></h1>
                </div>
                <p class="brand-subtitle">Manage all registered brands efficiently</p>
            </div>

            <div class="table-wrapper">

                <table class="styled-table">

                    <thead>
                        <tr>
                            <th>Brand Name</th>
                            <th>Owner</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php 

                        if(mysqli_num_rows($result) > 0){

                            while($row = mysqli_fetch_assoc($result)){

                                echo '<tr>
                                        <td>' . $row['brand_name'] . '</td>
                                        <td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>
                                        <td>' . $row['email'] . '</td>
                                        <td><span class="status ' . $row['status'] . '">' . $row['status'] . '</span></td>
                                        <td>
                                            <form method="post">
                                                <input type="hidden" name="id" value="' . $row['id'] . '">
                                                <button type="submit" name="approve" class="btn approve">Approve</button>
                                                <button type="submit" name="reject" class="btn reject">Reject</button>
                                                <button type="submit" name="suspend" class="btn suspend">Suspend</button>
                                            </form>
                                        </td>
                                    </tr>';
                            }
                        }

                        ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

	<?php if(isset($_SESSION['update'])): ?>

		<div id="popup-message" class="popup">
			<?php 
				echo $_SESSION['update']; 
				unset($_SESSION['update']); 
			?>
		</div>

		<script>
			const popup = document.getElementById('popup-message');
			if (popup) {
				popup.style.display = 'block';
				setTimeout(() => {
					popup.style.opacity = '0';
					setTimeout(() => popup.remove(), 500);
				}, 3000);
			}
		</script>

	<?php endif; ?>

</body>
</html>