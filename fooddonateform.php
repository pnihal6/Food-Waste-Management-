<?php
include("login.php"); 
if ($_SESSION['name'] == '') {
    header("location: signin.php");
}
$emailid = $_SESSION['email'];
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, 'demo');

if (isset($_POST['submit'])) {
    $foodname = mysqli_real_escape_string($connection, $_POST['foodname']);
    $meal = mysqli_real_escape_string($connection, $_POST['meal']);
    $category = $_POST['image-choice'];
    $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
    $phoneno = mysqli_real_escape_string($connection, $_POST['phoneno']);
    $district = mysqli_real_escape_string($connection, $_POST['district']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $latitude = mysqli_real_escape_string($connection, $_POST['latitude']);
    $longitude = mysqli_real_escape_string($connection, $_POST['longitude']);

    $query = "INSERT INTO food_donations(email, food, type, category, phoneno, location, address, name, quantity, latitude, longitude) 
              VALUES('$emailid', '$foodname', '$meal', '$category', '$phoneno', '$district', '$address', '$name', '$quantity', '$latitude', '$longitude')";

    $query_run = mysqli_query($connection, $query);
    if ($query_run) {
        echo '<script type="text/javascript">alert("Data saved")</script>';
        header("location: delivery.html");
    } else {
        echo '<script type="text/javascript">alert("Data not saved")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Food Donate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loginstyle.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body style="background-color: #06C167;">
    <div class="container">
        <div class="regformf">
            <form action="" method="post">
                <p class="logo">Food <b style="color: #06C167;">Donate</b></p>

                <div class="input">
                    <label for="foodname">Food Name:</label>
                    <input type="text" id="foodname" name="foodname" required />
                </div>

                <div class="radio">
                    <label for="meal">Meal type:</label>
                    <br><br>
                    <input type="radio" name="meal" id="veg" value="veg" required />
                    <label for="veg" style="padding-right: 40px;">Veg</label>
                    <input type="radio" name="meal" id="Non-veg" value="Non-veg" />
                    <label for="Non-veg">Non-veg</label>
                </div>
                <br>

                <div class="input">
                    <label for="food">Select the Category:</label>
                    <br><br>
                    <div class="image-radio-group">
                        <input type="radio" id="raw-food" name="image-choice" value="raw-food">
                        <label for="raw-food">
                            <img src="img/raw-food.png" alt="raw-food">
                        </label>
                        <input type="radio" id="cooked-food" name="image-choice" value="cooked-food" checked>
                        <label for="cooked-food">
                            <img src="img/cooked-food.png" alt="cooked-food">
                        </label>
                        <input type="radio" id="packed-food" name="image-choice" value="packed-food">
                        <label for="packed-food">
                            <img src="img/packed-food.png" alt="packed-food">
                        </label>
                    </div>
                    <br>
                </div>

                <div class="input">
                    <label for="quantity">Quantity (number of person /kg):</label>
                    <input type="text" id="quantity" name="quantity" required />
                </div>

                <b><p style="text-align: center;">Contact Details</p></b>

                <div class="input">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo $_SESSION['name']; ?>" required />

                    <label for="phoneno">Phone No:</label>
                    <input type="text" id="phoneno" name="phoneno" maxlength="10" pattern="[0-9]{10}" required />
                </div>

                <div class="input">
                    <label for="district">District:</label>
                    <select id="district" name="district" style="padding:10px;">
                      <option value="agar malwa">Agar Malwa</option>
                      <option value="alirajpur">Alirajpur</option>
                      <option value="anjlav">Anuppur</option>
                      <option value="ashoknagar">Ashoknagar</option>
                      <option value="balaghat">Balaghat</option>
                      <option value="barwani">Barwani</option>
                      <option value="betul">Betul</option>
                      <option value="bhind">Bhind</option>
                      <option value="bhopal">Bhopal</option>
                      <option value="burhanpur">Burhanpur</option>
                      <option value="chhatarpur">Chhatarpur</option>
                      <option value="chhindwara">Chhindwara</option>
                      <option value="damoh">Damoh</option>
                      <option value="datia">Datia</option>
                      <option value="dewas">Dewas</option>
                      <option value="dhar">Dhar</option>
                      <option value="dindori">Dindori</option>
                      <option value="guna">Guna</option>
                      <option value="gwalior">Gwalior</option>
                      <option value="harda">Harda</option>
                      <option value="hoshangabad">Hoshangabad</option>
                      <option value="indore">Indore</option>
                      <option value="jabalpur">Jabalpur</option>
                      <option value="jhabua">Jhabua</option>
                      <option value="katni">Katni</option>
                      <option value="khandwa">Khandwa</option>
                      <option value="khargone">Khargone</option>
                      <option value="mandla">Mandla</option>
                      <option value="mandsaur">Mandsaur</option>
                      <option value="morena">Morena</option>
                      <option value="narsinghpur">Narsinghpur</option>
                      <option value="neemuch">Neemuch</option>
                      <option value="panna">Panna</option>
                      <option value="raisen">Raisen</option>
                      <option value="rajgarh">Rajgarh</option>
                      <option value="ratlam">Ratlam</option>
                      <option value="rewa">Rewa</option>
                      <option value="sagar">Sagar</option>
                      <option value="satna">Satna</option>
                      <option value="sehore">Sehore</option>
                      <option value="seoni">Seoni</option>
                      <option value="shahdol">Shahdol</option>
                      <option value="shajapur">Shajapur</option>
                      <option value="sheopur">Sheopur</option>
                      <option value="shivpuri">Shivpuri</option>
                      <option value="sidhi">Sidhi</option>
                      <option value="singrauli">Singrauli</option>
                      <option value="tikamgarh">Tikamgarh</option>
                      <option value="ujjain">Ujjain</option>
                      <option value="umaria">Umaria</option>
                      <option value="vidisha">Vidisha</option>

                    </select>

                    <label for="address" style="padding-left: 10px;">Address:</label>
                    <input type="text" id="address" name="address" required />
                </div>

                <!-- Map & GPS -->
                <div class="input">
                    <p><b>Confirm Pickup Location:</b></p>
                    <div id="map" style="height: 300px; margin-bottom: 20px;"></div>
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                </div>

                <div class="btn">
                    <button type="submit" name="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Geolocation & Leaflet Script -->
    <script>
        let map, marker;

        function initMap(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            map = L.map('map').setView([lat, lng], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
            }).addTo(map);

            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function (e) {
                const { lat, lng } = marker.getLatLng();
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            });
        }

        function errorHandler() {
            alert("Unable to get your location. Please allow location access.");
        }

        window.onload = function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(initMap, errorHandler);
            } else {
                alert("Geolocation is not supported by your browser.");
            }
        }
    </script>
</body>
</html>
