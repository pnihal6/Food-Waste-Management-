<?php
include("login.php");

// Check if admin is logged in
// You should implement proper admin authentication
if(!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
    // Redirect non-admins - you'll need to create an admin login page
    header("location: admin_login.php");
    exit;
}

// Handle status update
if(isset($_POST['update_status'])) {
    $donation_id = $_POST['donation_id'];
    $new_status = $_POST['status'];
    
    $update_query = "UPDATE food_donations SET status = '$new_status' WHERE id = $donation_id";
    if(mysqli_query($connection, $update_query)) {
        $success_message = "Status updated successfully!";
    } else {
        $error_message = "Error updating status: " . mysqli_error($connection);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Donations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 50px auto; padding: 20px; }
        .heading { font-size: 24px; font-weight: bold; margin-bottom: 20px; color: #333; border-bottom: 3px solid #06C167; padding-bottom: 8px; display: inline-block; }
        .table-container { margin: 20px 0; border-radius: 10px; overflow: hidden; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .table { width: 100%; border-collapse: collapse; }
        .table th { background-color: #06C167; color: white; padding: 12px 15px; text-align: left; }
        .table td { padding: 12px 15px; border-bottom: 1px solid #eee; }
        .table tbody tr:hover { background-color: #f5f5f5; }
        .status-form { display: flex; align-items: center; }
        .status-select { padding: 8px; border-radius: 4px; border: 1px solid #ddd; margin-right: 10px; }
        .status-btn { background: #06C167; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; }
        .status-btn:hover { background: #05a057; }
        .message { padding: 10px; margin-bottom: 20px; border-radius: 4px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .navbar { background: #333; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .navbar .logo { font-size: 24px; font-weight: bold; }
        .navbar .nav-links a { color: white; text-decoration: none; margin-left: 20px; }
        .navbar .nav-links a:hover { color: #06C167; }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">Food <b style="color: #06C167;">Donate</b> Admin</div>
        <div class="nav-links">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_donations.php" style="color: #06C167;">Donations</a>
            <a href="admin_users.php">Users</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <h1 class="heading">Manage Food Donations</h1>
        
        <?php if(isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if(isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Donor Email</th>
                        <th>Food</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Date/Time</th>
                        <th>Current Status</th>
                        <th>Update Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM food_donations ORDER BY date DESC";
                    $result = mysqli_query($connection, $query);
                    
                    if($result && mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            $status = isset($row['status']) ? $row['status'] : 'pending';
                            
                            echo "<tr>";
                            echo "<td>{$row['id']}</td>";
                            echo "<td>{$row['email']}</td>";
                            echo "<td>{$row['food']}</td>";
                            echo "<td>{$row['type']}</td>";
                            echo "<td>{$row['category']}</td>";
                            echo "<td>{$row['date']}</td>";
                            echo "<td><span style='color: ".
                                 ($status=='delivered' ? 'green' : 
                                 ($status=='picked_up' ? 'orange' : 'red'))."'>".
                                 ucwords(str_replace('_',' ', $status))."</span></td>";
                            echo "<td>
                                    <form method='POST' class='status-form'>
                                        <input type='hidden' name='donation_id' value='{$row['id']}'>
                                        <select name='status' class='status-select'>
                                            <option value='pending' ".($status == 'pending' ? 'selected' : '').">Pending</option>
                                            <option value='picked_up' ".($status == 'picked_up' ? 'selected' : '').">Picked Up</option>
                                            <option value='delivered' ".($status == 'delivered' ? 'selected' : '').">Delivered</option>
                                        </select>
                                        <button type='submit' name='update_status' class='status-btn'>Update</button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' style='text-align: center;'>No donations found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>