<?php

$connection = mysqli_connect("localhost", "root", "", "foodlynk");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

$brand_email = $_SESSION['brand_email'];

$query = "SELECT username, first_name, last_name, email, phone_number, address, brand_name, category, status 
          FROM brands WHERE email = '$brand_email'";

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
    <link rel="stylesheet" href="css/login&signin.css" />

    <style>

        .confirmation-box {
            max-width: 900px;
            margin: 1rem auto;
            padding: 3.5rem 3rem;
        }

        .info-box {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            text-align: left;
            background: var(--gray-50);
            padding: 2rem;
            border-radius: var(--radius-lg);
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
        }

        .info-box p {
            margin: 0.5rem 0;
            color: var(--text-primary);
            font-size: 0.95rem;
        }

    </style>

</head>

<body>

    <a href="index.php" class="home-link">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Back to Home
    </a>

    <form method="post" class="confirmation-box">

        <h2>Brand Submitted Successfully</h2>
        <p>Thank you for submitting your brand details. Please wait for master-admin confirmation.</p>

        <div class="info-box">
            <p><strong>First Name: </strong> <?php echo $brand['first_name']; ?></p>
            <p><strong>Last Name: </strong> <?php echo $brand['last_name']; ?></p>
            <p><strong>Brand Name: </strong> <?php echo $brand['brand_name']; ?></p>
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