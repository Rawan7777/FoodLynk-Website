<?php

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

		$_SESSION['update'] = "Status updated successfully for user ID: $id.";

		if ($resulttwo) {

			echo $_SESSION['update'];
			unset($_SESSION['update']);

		} else {

			echo "Error updating status: " . mysqli_error($connection);
		}
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

	<h1>Master Admin Dashboard</h1>

	<table>
		<tr>
			<th>Brand Name</th>
			<th>Owner</th>
			<th>Email</th>
			<th>Status</th>
			<th>Actions</th>
		</tr>

		<tr>
			<td>McDonald's</td>
			<td>john wick</td>
			<td>john.wick@gmail.com</td>
			<td>pending</td>
			<td>
				<form method="post">

				<button type="submit" class="approve">Approve</button>
				<button type="submit" class="reject">Reject</button>
				<button type="submit" class="suspend">Suspend</button>

				</form>
			</td>
		</tr>

      <?php 
      
      if(mysqli_num_rows($result) > 0){

        while($row = mysqli_fetch_assoc($result)){

          echo '<tr>
                  <td>' . $row['brand_name'] . '</td>
                  <td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>
                  <td>' . $row['email'] . '</td>
                  <td>' . $row['status'] . '</td>
                  <td>
                  <form method="post">

                    <input type="hidden" name="id" value="' . $row['id'] . '">

                    <button type="submit" name="approve" class="approve">Approve</button>
					<button type="submit" name="reject" class="reject">Reject</button>
					<button type="submit" name="suspend" class="suspend">Suspend</button>

                  </form>
                  </td>
          </tr>';

        }
      }
      ?>

  </table>

</body>
</html>
