<?php
ob_start(); 
include("connect.php"); 
include '../connection.php';
if($_SESSION['name']==''){
	header("location:deliverylogin.php");
}
$name = $_SESSION['name'];
$city = $_SESSION['city'];

// Check if Did exists in session before using it
$id = isset($_SESSION['Did']) ? $_SESSION['Did'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Dashboard - Food Donate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 15px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-sizing: border-box;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        
        .content-container {
            max-width: 1200px;
            margin: 80px auto 40px;
            padding: 0 20px;
        }
        
        .welcome-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .welcome-card h2 {
            color: #333;
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        
        .welcome-card p {
            color: #666;
            margin: 0;
            font-size: 18px;
        }
        
        .delivery-image {
            width: 100%;
            max-width: 350px;
            margin: 20px auto;
            display: block;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .card-title {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
            color: #333;
            border-bottom: 3px solid #06C167;
            padding-bottom: 8px;
            display: inline-block;
        }
        
        .table-wrapper {
            overflow-x: auto;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th {
            background-color: #06C167;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: normal;
            font-size: 16px;
        }
        
        .table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-size: 15px;
        }
        
        .table tbody tr:hover {
            background-color: #f9f9f9;
        }
        
        .table tr:last-child td {
            border-bottom: none;
        }
        
        .btn {
            background: #06C167;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            font-size: 14px;
            display: inline-block;
        }
        
        .btn:hover {
            background: #049e54;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(6, 193, 103, 0.3);
        }
        
        #map-container {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        #map {
            height: 400px;
            width: 100%;
        }
        
        .map-legend {
            background: white;
            padding: 15px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .legend-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .legend-text {
            font-size: 15px;
            color: #555;
        }
        
        .donor-color { background-color: #4CAF50; }
        .delivery-color { background-color: #FF9800; }
        .ngo-color { background-color: #2196F3; }
        
        .route-legend {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .route-item {
            display: flex;
            align-items: center;
            margin: 8px 0;
        }
        
        .route-line {
            width: 30px;
            height: 3px;
            margin-right: 10px;
        }
        
        .no-orders-message {
            text-align: center;
            padding: 30px;
            color: #666;
            font-size: 16px;
        }
        
        .status-assigned {
            color: green;
            font-weight: bold;
        }
        
        .status-unavailable {
            color: #d9534f;
            font-weight: bold;
        }
        
        .footer {
            background: #333;
            color: white;
            padding: 30px 20px;
            text-align: center;
            margin-top: 40px;
        }
        
        .footer p {
            margin: 10px 0;
        }
        
        @media (max-width: 768px) {
            .welcome-card h2 {
                font-size: 24px;
            }
            
            .card-title {
                font-size: 20px;
            }
            
            .table th, .table td {
                padding: 12px 10px;
                font-size: 14px;
            }
            
            .delivery-image {
                max-width: 300px;
            }
            
            #map {
                height: 300px;
            }
        }
        
        @media (max-width: 576px) {
            .content-container {
                padding: 0 15px;
                margin-top: 70px;
            }
            
            .welcome-card h2 {
                font-size: 22px;
            }
            
            .welcome-card p {
                font-size: 16px;
            }
            
            .table {
                display: block;
            }
            
            .table thead, .table tbody, .table th, .table td, .table tr {
                display: block;
            }
            
            .table thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            
            .table tr {
                border: 1px solid #eee;
                margin-bottom: 15px;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            }
            
            .table td {
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
                text-align: right;
                background-color: white;
            }
            
            .table td:last-child {
                border-bottom: none;
            }
            
            .table td:before {
                position: absolute;
                top: 15px;
                left: 15px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
                color: #555;
                content: attr(data-label);
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Food <b style="color: #06C167;">Donate</b></div>
    </header>

    <div class="content-container">
        <div class="welcome-card">
            <h2>Welcome, <?php echo $name; ?>!</h2>
            <p>You are serving in <?php echo $city; ?></p>
        </div>

        <img src="../img/delivery.gif" alt="Delivery Animation" class="delivery-image">
        
        <div class="card" id="map-container">
            <div class="card-header">
                <h2 class="card-title">Delivery Routes</h2>
            </div>
            <div id="map"></div>
            <div class="map-legend">
                <div class="legend-title">Map Legend</div>
                <div class="legend-item">
                    <div class="legend-color donor-color"></div>
                    <div class="legend-text">Donor Location (Food Pickup)</div>
                </div>
                <div class="legend-item">
                    <div class="legend-color delivery-color"></div>
                    <div class="legend-text">Your Location (Delivery Person)</div>
                </div>
                <div class="legend-item">
                    <div class="legend-color ngo-color"></div>
                    <div class="legend-text">NGO Location (Food Delivery)</div>
                </div>
                <div class="route-legend">
                    <div class="route-item">
                        <div class="route-line" style="background-color: #4CAF50;"></div>
                        <div class="legend-text">Donor to Delivery Person Route</div>
                    </div>
                    <div class="route-item">
                        <div class="route-line" style="background-color: #2196F3;"></div>
                        <div class="legend-text">Delivery Person to NGO Route</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Available Orders</h2>
            </div>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Date/Time</th>
                            <th>Pickup Address</th>
                            <th>Delivery Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Define the SQL query to fetch unassigned orders
                        $sql = "SELECT fd.Fid AS Fid, fd.location as cure, fd.name, fd.phoneno, fd.date, 
                                fd.delivery_by, fd.address as From_address, fd.location
                                FROM food_donations fd
                                WHERE assigned_to IS NOT NULL AND delivery_by IS NULL AND fd.location='$city'";

                        // Execute the query
                        $result = mysqli_query($connection, $sql);

                        // Check for errors
                        if (!$result) {
                            die("Error executing query: " . mysqli_error($connection));
                        }

                        // Check if there are any records
                        if (mysqli_num_rows($result) > 0) {
                            // If the delivery person has taken an order, update the assigned_to field in the database
                            if (isset($_POST['food']) && isset($_POST['delivery_person_id'])) {
                                $order_id = $_POST['order_id'];
                                $delivery_person_id = $_POST['delivery_person_id'];
                                $check_sql = "SELECT * FROM food_donations WHERE Fid = $order_id AND delivery_by IS NOT NULL";
                                $check_result = mysqli_query($connection, $check_sql);

                                if (mysqli_num_rows($check_result) > 0) {
                                    // Order has already been assigned to someone else
                                    echo "<script>alert('Sorry, this order has already been assigned to someone else.');</script>";
                                } else {
                                    $update_sql = "UPDATE food_donations SET delivery_by = $delivery_person_id WHERE Fid = $order_id";
                                    $update_result = mysqli_query($connection, $update_sql);

                                    if (!$update_result) {
                                        die("Error assigning order: " . mysqli_error($connection));
                                    }

                                    // Reload the page to prevent duplicate assignments
                                    echo "<script>window.location.href = window.location.href;</script>";
                                    ob_end_flush();
                                }
                            }

                            // Display available orders
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                        <td data-label='Name'>{$row['name']}</td>
                                        <td data-label='Phone Number'>{$row['phoneno']}</td>
                                        <td data-label='Date/Time'>{$row['date']}</td>
                                        <td data-label='Pickup Address'>{$row['From_address']}, {$row['location']}</td>
                                        <td data-label='Delivery Address'>Dev Food Donation NGO</td>
                                        <td data-label='Action'>";
                                
                                if ($row['delivery_by'] == null && $id !== null) {
                                    echo "<form method='post' action=''>
                                            <input type='hidden' name='order_id' value='{$row['Fid']}'>
                                            <input type='hidden' name='delivery_person_id' value='$id'>
                                            <button type='submit' name='food' class='btn'>Take Order</button>
                                          </form>";
                                } else if ($row['delivery_by'] == $id) {
                                    echo "<span class='status-assigned'>Order assigned to you</span>";
                                } else {
                                    echo "<span class='status-unavailable'>Order unavailable</span>";
                                }
                                
                                echo "</td>
                                    </tr>";
                            }
                        } else {
                            // No orders found
                            echo "<tr><td colspan='6' class='no-orders-message'>
                                    <i class='fa fa-info-circle' style='font-size: 24px; margin-bottom: 10px; color: #06C167;'></i><br>
                                    No available orders in your city at the moment.<br>
                                    Check back later for new donation requests.
                                  </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Food Donate | Designed with ❤️</p>
        <p>Contact: (+91) 9702251090 | Email: <a href="mailto:Fooddonate@gmail.com" style="color: #06C167;">Fooddonate@gmail.com</a></p>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sample coordinates - you should replace these with actual coordinates from your database
        const donorLocation = [23.075205, 76.860277];
        const deliveryPersonLocation = [23.071325, 76.830797];
        const ngoLocation = [23.173895, 77.061554];
        
        // Calculate the center point
        const centerLat = (donorLocation[0] + deliveryPersonLocation[0] + ngoLocation[0]) / 3;
        const centerLng = (donorLocation[1] + deliveryPersonLocation[1] + ngoLocation[1]) / 3;
        
        // Initialize the map
        const map = L.map('map').setView([centerLat, centerLng], 11);
        
        // Add the base map layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Custom icons for each marker
        const donorIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background-color: #4CAF50; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white;"></div>`,
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
        
        const deliveryIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background-color: #FF9800; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white;"></div>`,
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
        
        const ngoIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background-color: #2196F3; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white;"></div>`,
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
        
        // Add markers with custom icons
        L.marker(donorLocation, {icon: donorIcon}).addTo(map)
            .bindPopup("<b>Donor Location</b><br>Food pickup point").openPopup();
            
        L.marker(deliveryPersonLocation, {icon: deliveryIcon}).addTo(map)
            .bindPopup("<b>Your Location</b><br>Delivery Person");
            
        L.marker(ngoLocation, {icon: ngoIcon}).addTo(map)
            .bindPopup("<b>NGO Location</b><br>Food Donation Center");
        
        // Draw colored lines between points
        // First route: donor to delivery person
        L.polyline([donorLocation, deliveryPersonLocation], {
            color: '#4CAF50',
            weight: 4,
            dashArray: '5, 10',
            opacity: 0.7
        }).addTo(map);
        
        // Second route: delivery person to NGO
        L.polyline([deliveryPersonLocation, ngoLocation], {
            color: '#2196F3',
            weight: 4,
            dashArray: '5, 10',
            opacity: 0.7
        }).addTo(map);
        
        // Fit map to bounds
        const bounds = L.latLngBounds([donorLocation, deliveryPersonLocation, ngoLocation]);
        map.fitBounds(bounds, {
            padding: [50, 50]
        });
    });
    </script>
</body>
</html>