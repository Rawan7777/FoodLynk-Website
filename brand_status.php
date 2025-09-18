<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Account Status</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/login&signin.css" />

</head>

<body>

    <div class="confirmation-box">

        <?php
        if(isset($_SESSION['brand_status'])) {

            if($_SESSION['brand_status'] == 'reject'){

                echo '<h2>Request Rejected</h2>
                <p>We’re sorry, but your request has been reviewed and unfortunately it has been rejected at this time. Please contact support for more information or reapply with updated details.</p>';
            
            }
            else if($_SESSION['brand_status'] == 'suspend'){

                echo '<h2>Account Suspended</h2>
                <p>We’re sorry, but your account has been suspended due to a violation of our policies. Please contact support for further clarification or to appeal this decision.</p>';
            
            }
            else {

                echo '<h2>Status Unknown</h2>
                <p>Please contact support for more information.</p>';
            }

        } else {

            echo '<h2>No Status Found</h2>
            <p>Please login to check your account status.</p>';
        }

        ?>

        <button class="button" onclick="window.location.href='index.php'">Go Back Home</button>
        
    </div>

</body>
</html>
