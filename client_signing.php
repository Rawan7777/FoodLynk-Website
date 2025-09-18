<?php 

$first_name_placeholder         = "First Name";
$last_name_placeholder          = "Last Name";
$card_id_placeholder            = "Card ID";
$phone_number_placeholder       = "Phone Number";
$email_placeholder              = "Email";
$address_placeholder            = "Address";
$password_placeholder           = "Password";
$confirm_password_placeholder   = "Confirm Password";

$email_error = 0;
$confirm_password_error = 0;

if (isset($_POST["submit"])) {

    $connection = mysqli_connect("localhost", "root", "", "FoodLynk");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $first_name     = mysqli_real_escape_string($connection, $_POST['first_name']);
    $last_name      = mysqli_real_escape_string($connection, $_POST['last_name']);
    $card_id        = mysqli_real_escape_string($connection, $_POST['card_id']);
    $email          = mysqli_real_escape_string($connection, $_POST['email']);
    $address        = mysqli_real_escape_string($connection, $_POST['address']);
    $phone_number   = mysqli_real_escape_string($connection, $_POST['phone_number']);
    $password       = password_hash($_POST['password'], PASSWORD_DEFAULT);

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

    <title>Sign Up - FoodLynk</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/login&signin.css"/>

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
                    <h1 class="brand-title">Create Your <span>Account</span></h1>
                </div>

                <p class="brand-subtitle">Join FoodLynk and discover amazing food</p>

            </div>

            <form class="brand-form" method="POST">

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
                        <input type="text" id="card_id" name="card_id" placeholder="<?php echo htmlspecialchars($card_id_placeholder); ?>"
                        value="<?php echo isset($_POST['card_id']) ? htmlspecialchars($_POST['card_id']) : ''; ?>" required />
                    </div>

                    <div class="form-group">
                        <input type="tel" id="phone_number" name="phone_number" placeholder="<?php echo htmlspecialchars($phone_number_placeholder); ?>"
                        value="<?php echo isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number']) : ''; ?>" required />
                    </div>

                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="<?php echo htmlspecialchars($email_placeholder); ?>"
                        value="<?php echo isset($_POST['email']) && !$email_error ? htmlspecialchars($_POST['email']) : ''; ?>"
                        class="<?php echo !empty($email_error) ? 'error' : ''; ?>" required />
                    </div>

                    <div class="form-group">
                        <input type="text" id="address" name="address" placeholder="<?php echo htmlspecialchars($address_placeholder); ?>"
                        value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" required />
                    </div>

                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="<?php echo htmlspecialchars($password_placeholder); ?>" required />
                    </div>

                    <div class="form-group">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="<?php echo htmlspecialchars($confirm_password_placeholder); ?>"
                        class="<?php echo !empty($confirm_password_error) ? 'error' : ''; ?>" required />
                    </div>
                </div>

                <button type="submit" name="submit" class="submit-btn">
                    <span>Create Your Account</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>

            </form>

            <div class="footer-links">
                <p>Already have an account? <a href="client_login.php">Sign in here</a></p>
            </div>

        </div>
    </div>
    
</body>
</html>