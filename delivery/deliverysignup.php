<?php
session_start();
include '../connection.php'; // Database connection

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $location = mysqli_real_escape_string($connection, $_POST['location']);

    // Check if email already exists
    $check_email = mysqli_query($connection, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $msg = "<p class='text-red-600 text-center'>Email already exists.</p>";
    } else {
        // Insert user into the database (no hashing)
        $query = "INSERT INTO users (username, email, password, location) VALUES ('$username', '$email', '$password', '$location')";
        if (mysqli_query($connection, $query)) {
            $_SESSION['success'] = "Registration successful! You can now log in.";
            header("Location: deliverylogin.php"); // Redirect to login page
            exit();
        } else {
            $msg = "<p class='text-red-600 text-center'>Error: Could not register user.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen grid place-items-center bg-gradient-to-br from-green-50 to-emerald-100 p-4">
    <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-6">
        <div class="text-center">
            <div class="mx-auto bg-green-100 w-16 h-16 rounded-full grid place-items-center">
                <svg class="w-8 h-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 00-7.2 17.2c.3.3.8.3 1.1 0L12 16l6.1 3.2c.3.2.8.2 1.1 0A10 10 0 0012 2z" />
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-green-800 mt-2">Register</h2>
            <p class="text-green-600">Join us in reducing food waste and creating a sustainable future</p>
        </div>

        <!-- Show error messages -->
        <?php if ($msg != "") echo $msg; ?>

        <form action="" method="POST" class="space-y-4 mt-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" required placeholder="Enter your username"
                       class="w-full mt-1 p-2 border rounded-lg focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" required placeholder="Enter your email"
                       class="w-full mt-1 p-2 border rounded-lg focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required placeholder="Create a password"
                       class="w-full mt-1 p-2 border rounded-lg focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Location</label>
                <input type="text" name="location" required placeholder="Enter your city"
                       class="w-full mt-1 p-2 border rounded-lg focus:ring-green-500 focus:border-green-500">
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg">
                Register
            </button>

            <p class="text-center text-sm text-gray-600">
                Already a member?
                <a href="deliverylogin.php" class="text-green-600 hover:text-green-700 font-medium">Sign in</a>
            </p>
        </form>
    </div>
</body>
</html>
