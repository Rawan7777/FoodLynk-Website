<?php

session_start();

$connection = mysqli_connect("localhost", "root", "", "foodlynk");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$client_email = $_SESSION['client_email'] ?? null;
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    header('Content-Type: application/json');
    
    $action = $_POST['action'];
    
    switch ($action) {

        case 'update_personal':

            $first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
            $last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
            $phone = mysqli_real_escape_string($connection, $_POST['phone']);
            
            $sql = "UPDATE clients SET first_name='$first_name', last_name='$last_name', phone_number='$phone' WHERE email='$client_email'";
            
            if (mysqli_query($connection, $sql)) {
                $response = ['success' => true, 'message' => 'Personal information updated successfully!'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to update personal information.'];
            }
            break;
            
        case 'update_contact':

            $new_email = mysqli_real_escape_string($connection, $_POST['email']);
            $card_id = mysqli_real_escape_string($connection, $_POST['card_id']);
            
            $check_email = "SELECT email FROM clients WHERE email='$new_email' AND email!='$client_email'";
            $email_result = mysqli_query($connection, $check_email);
            
            if (mysqli_num_rows($email_result) > 0) {

                $response = ['success' => false, 'message' => 'Email already exists!'];
            } else {

                $sql = "UPDATE clients SET email='$new_email', card_id='$card_id' WHERE email='$client_email'";
                
                if (mysqli_query($connection, $sql)) {

                    $_SESSION['client_email'] = $new_email;
                    $response = ['success' => true, 'message' => 'Contact information updated successfully!'];
                
                } else {
                    $response = ['success' => false, 'message' => 'Failed to update contact information.'];
                }
            }
            break;
            
        case 'update_address':

            $address = mysqli_real_escape_string($connection, $_POST['address']);
            
            $sql = "UPDATE clients SET address='$address' WHERE email='$client_email'";
            
            if (mysqli_query($connection, $sql)) {
                $response = ['success' => true, 'message' => 'Address updated successfully!'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to update address.'];
            }
            break;
            
        case 'update_password':

            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            if ($new_password !== $confirm_password) {
                $response = ['success' => false, 'message' => 'New passwords do not match!'];
                break;
            }
            
            if (strlen($new_password) < 0) {
                $response = ['success' => false, 'message' => 'Password must be at least 6 characters long!'];
                break;
            }
            
            $query_check = "SELECT password FROM clients WHERE email = '$client_email'";
            $result_check = mysqli_query($connection, $query_check);
            $client_data = mysqli_fetch_assoc($result_check);
            
            if ($client_data && password_verify($current_password, $client_data['password'])) {

                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE clients SET password='$hashed_password' WHERE email='$client_email'";
                
                if (mysqli_query($connection, $sql)) {
                    $response = ['success' => true, 'message' => 'Password updated successfully!'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to update password.'];
                }

            } else {
                $response = ['success' => false, 'message' => 'Current password is incorrect!'];
            }
            break;
            
        case 'update_profile_image':

            if (!empty($_FILES['profile_image']['name'])) {

                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                $file_type = $_FILES['profile_image']['type'];
                
                if (!in_array($file_type, $allowed_types)) {
                    $response = ['success' => false, 'message' => 'Only JPG, PNG and GIF files are allowed!'];
                    break;
                }
                
                if ($_FILES['profile_image']['size'] > 10 * 1024 * 1024) {
                    $response = ['success' => false, 'message' => 'File size must be less than 10MB!'];
                    break;
                }
                
                $upload_dir = "profile_images/";
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                $new_filename = uniqid() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {

                    $sql = "UPDATE clients SET profile_image='$upload_path' WHERE email='$client_email'";
                    
                    if (mysqli_query($connection, $sql)) {
                        $response = ['success' => true, 'message' => 'Profile image updated successfully!', 'image_path' => $upload_path];
                    } else {
                        $response = ['success' => false, 'message' => 'Failed to update profile image.'];
                    }

                } else {
                    $response = ['success' => false, 'message' => 'Failed to upload image.'];
                }

            } else {
                $response = ['success' => false, 'message' => 'No image selected!'];
            }
            break;
    }
    
    echo json_encode($response);
    exit();
}

$client = null;

if ($client_email) {

    $query = "SELECT * FROM clients WHERE email = '$client_email'";
    $result = mysqli_query($connection, $query);
    $client = mysqli_fetch_assoc($result);
}

if (!$client) {
    header("Location: login.php");
    exit();
}

$profile_image = $client['profile_image'] ?: "images/default_avatar.jpg";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - FoodLynk</title>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/client_edit_profile.css">
</head>
<body>
    <div class="background-gradient"></div>

    <div class="top-bar">
        <a href="client_account.php" class="home-link">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Back to Home
        </a>
        
        <div class="nav-right">
            <span class="client-name"><?php echo htmlspecialchars($client['first_name']); ?></span>
            <div class="nav-avatarr">
                <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="User Avatar" id="nav-avatar">
            </div>
        </div>
    </div>

    <div class="main-container">

        <div class="edit-container">

            <div class="header-section">

                <div class="header-top">

                    <div class="brand-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>

                    <h1 class="brand-title">Edit Profile</h1>

                </div>

                <p class="brand-subtitle">Manage your account settings and preferences</p>

            </div>

            <div class="form-section">

                <div class="section-header">
                    <h3 class="section-title">Profile Picture</h3>
                </div>
                
                <form id="profileImageForm" enctype="multipart/form-data">

                    <div class="profile-picture-section">

                        <div class="current-avatar">
                            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile" id="profile-preview">
                        </div>
                        
                        <div class="file-input-wrapper">

                            <input type="file" name="profile_image" accept="image/*" id="profile-image-input">
                            
                            <div class="file-input-placeholder">
                                <span>Click to upload new image</span>
                                <small>JPG, PNG or GIF (Max 2MB)</small>
                            </div>

                        </div>
                        
                        <button type="submit" class="btn" id="upload-btn">
                            <span class="btn-text">Upload Image</span>
                            <div class="spinner" style="display: none;"></div>
                        </button>

                    </div>

                    <div class="alert" id="image-alert"></div>

                </form>

            </div>

            <div class="form-section">

                <div class="section-header">
                    <h3 class="section-title">Personal Information</h3>
                </div>
                
                <form id="personalForm">

                    <div class="form-grid">

                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" value="<?php echo htmlspecialchars($client['first_name']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" value="<?php echo htmlspecialchars($client['last_name']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" value="<?php echo htmlspecialchars($client['phone_number']); ?>">
                        </div>

                    </div>
                    
                    <button type="submit" class="btn">
                        <span class="btn-text">Update Personal Info</span>
                        <div class="spinner" style="display: none;"></div>
                    </button>

                    <div class="alert" id="personal-alert"></div>

                </form>

            </div>

            <div class="form-section">

                <div class="section-header">
                    <h3 class="section-title">Contact Information</h3>
                </div>
                
                <form id="contactForm">

                    <div class="form-grid">

                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($client['email']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Card ID</label>
                            <input type="text" name="card_id" value="<?php echo htmlspecialchars($client['card_id']); ?>" required>
                        </div>

                    </div>
                    
                    <button type="submit" class="btn">
                        <span class="btn-text">Update Contact Info</span>
                        <div class="spinner" style="display: none;"></div>
                    </button>

                    <div class="alert" id="contact-alert"></div>

                </form>

            </div>

            <div class="form-section">

                <div class="section-header">
                    <h3 class="section-title">Address</h3>
                </div>
                
                <form id="addressForm">

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Full Address</label>
                            <input type="text" name="address" value="<?php echo htmlspecialchars($client['address']); ?>" placeholder="Enter your complete address">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn">
                        <span class="btn-text">Update Address</span>
                        <div class="spinner" style="display: none;"></div>
                    </button>

                    <div class="alert" id="address-alert"></div>

                </form>

            </div>

            <div class="form-section">

                <div class="section-header">
                    <h3 class="section-title">Change Password</h3>
                </div>
                
                <form id="passwordForm">

                    <div class="form-grid">

                        <div class="form-group">
                            <label>Current Password</label>
                            <input type="password" name="current_password" required placeholder="Enter current password">
                        </div>

                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="new_password" required placeholder="Enter new password">
                        </div>

                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" name="confirm_password" required placeholder="Confirm new password">
                        </div>

                    </div>
                    
                    <button type="submit" class="btn">
                        <span class="btn-text">Change Password</span>
                        <div class="spinner" style="display: none;"></div>
                    </button>

                    <div class="alert" id="password-alert"></div>

                </form>

            </div>
        </div>
    </div>

    <script>

        function showAlert(alertId, message, type) {
            const alert = document.getElementById(alertId);
            alert.textContent = message;
            alert.className = `alert ${type} show`;
            
            setTimeout(() => {
                alert.classList.remove('show');
            }, 5000);
        }

        function handleFormSubmit(formId, action, alertId, successCallback = null) {

            const form = document.getElementById(formId);
            const btn = form.querySelector('button[type="submit"]');
            const btnText = btn.querySelector('.btn-text');
            const spinner = btn.querySelector('.spinner');
            
            form.addEventListener('submit', async (submitformevent) => {
                submitformevent.preventDefault();
                
                btn.disabled = true;
                btnText.style.display = 'none';
                spinner.style.display = 'block';
                
                const formData = new FormData(form);
                formData.append('action', action);
                
                try {
                    const response = await fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();

                    if (result.success) {

                        showAlert(alertId, result.message, 'success');

                        if (successCallback) {
                            successCallback(result);
                        }

                    } else {

                        showAlert(alertId, result.message, 'error');
                    }

                } catch (error) {

                    showAlert(alertId, 'An error occurred. Please try again.', 'error');

                } finally {

                    btn.disabled = false;
                    btnText.style.display = 'inline';
                    spinner.style.display = 'none';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {

            handleFormSubmit('profileImageForm', 'update_profile_image', 'image-alert', (result) => {

                if (result.image_path) {

                    document.getElementById('profile-preview').src = result.image_path;
                    document.getElementById('nav-avatar').src = result.image_path;
                }
            });

            handleFormSubmit('personalForm', 'update_personal', 'personal-alert', (result) => {

                const firstName = document.querySelector('input[name="first_name"]').value;
                document.querySelector('.client-name').textContent = firstName;
            });

            handleFormSubmit('contactForm', 'update_contact', 'contact-alert');
            handleFormSubmit('addressForm', 'update_address', 'address-alert');
            handleFormSubmit('passwordForm', 'update_password', 'password-alert', () => {

                document.querySelector('input[name="current_password"]').value = '';
                document.querySelector('input[name="new_password"]').value = '';
                document.querySelector('input[name="confirm_password"]').value = '';
            });

            document.getElementById('profile-image-input').addEventListener('change', function(profileimageinput) {

                const file = profileimageinput.target.files[0];

                if (file) {

                    if (file.size > 10 * 1024 * 1024) {

                        showAlert('image-alert', 'File size must be less than 10MB!', 'error');
                        return;
                    }
                    
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

                    if (!allowedTypes.includes(file.type)) {

                        showAlert('image-alert', 'Only JPG, and PNG files are allowed!', 'error');
                        return;
                    }
                    
                    const reader = new FileReader();

                    reader.onload = function(e) {

                        document.getElementById('profile-preview').src = e.target.result;
                    };

                    reader.readAsDataURL(file);
                    
                    document.getElementById('upload-btn').style.display = 'inline-flex';
                }
            });
        });
    </script>
</body>
</html>
