<?php 

$email_placeholder    = "Email";
$password_placeholder = "Password";

$email_error = 0;
$password_error = 0;

if (isset($_POST['login'])) {

    $connection = mysqli_connect("localhost", "root", "", "FoodLynk");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $email    = mysqli_real_escape_string($connection, $_POST['email']);
    $password = $_POST['password'];

    $query  = "SELECT * FROM clients WHERE email = '$email'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) === 1) {

        $client = mysqli_fetch_assoc($result);

        if (password_verify($password, $client['password'])) {

            session_start();
            $_SESSION['client_email'] = $email;
            header("Location: client_account.php");
            exit();

        } else {

            $password_error = 1;
            $password_placeholder = "Incorrect password";
        }

    } else {

        $email_error = 1;
        $email_placeholder = "Email not found";
    }

    mysqli_close($connection);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Client Login - FoodLynk</title>
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
                    <h1 class="brand-title">Welcome <span>Back</span></h1>
                </div>

                <p class="brand-subtitle">Sign in to your FoodLynk account</p>
            
            </div>

            <form class="brand-form" method="POST">

                <div class="form-grid">

                    <div class="form-group full-width">
                        <input type="email" id="email"name="email" placeholder="<?php echo htmlspecialchars($email_placeholder); ?>"
                        value="<?php echo isset($_POST['email']) && !$email_error ? htmlspecialchars($_POST['email']) : ''; ?>"
                        class="<?php echo !empty($email_error) ? 'error' : ''; ?>" required />
                    </div>

                    <div class="form-group full-width">
                        <input type="password" id="password"name="password" placeholder="<?php echo htmlspecialchars($password_placeholder); ?>"
                        class="<?php echo !empty($password_error) ? 'error' : ''; ?>" required />
                    </div>

                </div>

                <button type="submit" name="login" class="submit-btn">
                    <span>Sign In</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </form>

            <div class="footer-links">
                <p>Don’t have an account? <a href="client_signing.php">Sign up here</a></p>
            </div>

        </div>
    </div>
    
</body>
</html>
