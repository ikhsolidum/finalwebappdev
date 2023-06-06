<link rel="stylesheet" href="../css/edit-user.css">

<?php
require_once 'class.user.php';

$user = new User();
$password_regex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/";
$email_regex = "/^[a-zA-Z]+@[a-zA-Z]+\.[a-zA-Z]{2,}$/";

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $user_status = isset($_POST['user_status']) ? $_POST['user_status'] : '';

    // Get the existing user details
    $user_details = $user->get_user_details($id);
    $existing_firstname = $user_details['user_firstname'];
    $existing_lastname = $user_details['user_lastname'];
    $existing_email = $user_details['user_email'];
    $existing_password = $user_details['user_password'];
    $existing_user_status = $user_details['user_status'];

    // Check if each field is changed or edited
    $update_fields = array();
    if ($firstname != $existing_firstname) {
        $update_fields['user_firstname'] = $firstname;
    }
    if ($lastname != $existing_lastname) {
        $update_fields['user_lastname'] = $lastname;
    }
    if ($email != $existing_email) {
        // Email validation
if (!preg_match($email_regex, $email)) {
    echo '<script>';
    echo 'if(confirm("Email should only contain letters with no symbols and numbers."))';
    echo '{';
    echo '   window.history.back();';
    echo '}';
    echo '</script>';
    exit();
}

        $update_fields['user_email'] = $email;
    }
    if ($user_status != $existing_user_status) {
        $update_fields['user_status'] = $user_status;
    }

    // Check if the password field is provided and not empty
    if (isset($_POST['password']) && trim($_POST['password']) !== '') {
        $new_password = $_POST['password'];
        // Password validation
        if (!preg_match($password_regex, $new_password)) {
            echo '<script>';
            echo 'if(confirm("Invalid password format. Password should be at least 8 characters with a mix of uppercase and lowercase letters and numbers."))';
            echo '{';
            echo '   window.history.back();';
            echo '}';
            echo '</script>';
            exit();
        }
        // Apply MD5 hashing to the password
        $new_password = md5($new_password);
        $update_fields['user_password'] = $new_password;
    }

    // Update the user details if any field is changed
    if (!empty($update_fields)) {
        if ($user->update_user($update_fields, $id)) {
            echo "User details updated successfully.";
            // Redirect to users page
            header("Location: ../index.php?page=users");
            exit();
        } else {
            echo "Failed to update user details.";
        }
    } else {
        echo "No changes were made.";
    }
}

// Check if the delete button is clicked
if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    // Delete the user
    if ($user->delete_user($id)) {
        echo "User deleted successfully.";
        // Redirect to users page
        header("Location: ../index.php?page=users");
        exit();
    } else {
        echo "Failed to delete user.";
    }
}

// Check if the user ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get user details
    $user_details = $user->get_user_details($id);
    $firstname = $user_details['user_firstname'];
    $lastname = $user_details['user_lastname'];
    $email = $user_details['user_email'];
    $user_status = $user_details['user_status'];
    $existing_password = $user_details['user_password'];
?>

<!-- Remaining HTML code -->


<div class="container">
  <form method="POST" action="" class="form-container">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <label for="firstname" class="form-label">First Name:</label>
    <input type="text" id="firstname" name="firstname" value="<?php echo $firstname; ?>" class="form-input" required>
    <label for="lastname" class="form-label">Last Name:</label>
    <input type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>" class="form-input" required>
    <label for="email" class="form-label">Email:</label>
    <input type="text" id="email" name="email" value="<?php echo $email; ?>" class="form-input" required>
    <label for="password" class="form-label">Password:</label>
    <input type="password" id="password" name="password" class="form-input">
    <input type="hidden" name="existing_password" value="<?php echo $existing_password; ?>" required>
    <label for="user_status" class="form-label">Status:</label>
    <select id="user_status" name="user_status" class="form-select">
        <option value="Staff"<?php if ($user_status == 'Staff') echo ' selected'; ?>>Staff</option>
        <option value="Manager"<?php if ($user_status == 'Manager') echo ' selected'; ?>>Manager</option>
    </select>
    <div class="form-submit">
    <input type="submit" name="submit" value="Update">
    <button type="button" onclick="goBack()" class="cancel-button">Cancel</button>
    <input type="submit" name="delete" value="Delete">
</div>
</form>
</div>
<?php
} else {
    echo "User ID not provided.";
}
?>
<script>
    function goBack() {
        window.history.back();
    }

    // ...
</script>