<?php
// Name: Prarambha Shrestha
// Student ID: 2408419
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Cache-Control: max-age=604800");

function fetch_weather_data($city, $servername, $username, $password, $database)
{
    $url = "http://api.openweathermap.org/data/2.5/weather?q={$city}&APPID=66b9ecac02127c11e361e3f4169d4522&units=metric";
    $res = file_get_contents($url);
    $data = json_decode($res, true);

    $conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $cityName = $data['name'];
    if ($cityName=== ""){
        echo "Fill the Form";
    }
    $day = date('Y-M-D');
    $temperature = round($data['main']['temp']);
    $pressure = $data['main']['pressure'];
    $wind = $data['wind']['speed'];
    $cloud = $data['weather'][0]['icon'];
    $humidity = $data['main']['humidity'];
    $description = $data['weather'][0]['description'];

    return [$cityName, $day, $temperature, $description, $cloud, $pressure, $wind, $humidity];
}

function create_DB($servername, $username, $password, $database)
{
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        die("Data Connection Failed: " . $conn->connect_error);
    }

    $createDatabase = "CREATE DATABASE IF NOT EXISTS $database";
    $conn->query($createDatabase);
    $conn->close();
}

function create_table($servername, $username, $password, $database)
{
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Data Connection Failed: " . $conn->connect_error);
    }

    $createTable = "CREATE TABLE IF NOT EXISTS weather_data(Id int AUTO_INCREMENT PRIMARY KEY, city varchar(225), weather_description varchar(50), cloud varchar(50), temperature varchar(50), pressure varchar(255), wind float(4,2), humidity varchar(50), Day_date varchar(50))";
    $conn->query($createTable);
    $conn->close();
}

function updateData($cityName, $day, $temperature, $description, $cloud, $pressure, $wind, $humidity, $servername, $username, $password, $database){
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Data Connection Failed: " . $conn->connect_error);
    }

    // Check if the row exists for the specified city and day
    $checkSql = "SELECT * FROM weather_data WHERE city = '$cityName' AND Day_date = '$day'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows === 0) {
        // If the row doesn't exist, insert a new one
        $insert = "INSERT INTO weather_data(city, weather_description, cloud, temperature, pressure, wind, humidity, Day_date) VALUES('$cityName', '$description','$cloud','$temperature','$pressure','$wind','$humidity','$day')";
        if (!$conn->query($insert)) {
            echo "ERROR: " . $conn->error;
        }
    } else {
        // If the row exists, update the existing data
        $updateSql = "UPDATE weather_data SET weather_description = '$description', cloud = '$cloud', temperature = '$temperature', pressure = '$pressure', wind = '$wind', humidity = '$humidity' WHERE city = '$cityName' AND Day_date = '$day'";
        if (!$conn->query($updateSql)) {
            echo "ERROR: " . $conn->error;
        }
    }

    $conn->close();
}
function showData($cityName, $servername, $username, $password, $database) {
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Data Connection Failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM weather_data where city = '$cityName' ORDER BY id DESC ";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
        }
        return json_encode($data);
    } else {
        echo "No data found.";
    }

    $conn->close();
}

function main(){
    $servername = "localhost";
    $username = "id21871463_sn0z";
    $password = "Her@ld123";
    $database = "id21871463_root";
    if (isset($_GET['q'])){
        $city = $_GET['q'];
    }else{
        $city = "Orai";
    }

    create_DB($servername, $username, $password, $database);

    create_table($servername, $username, $password, $database);

    list($cityName, $day, $temperature, $description, $cloud, $pressure, $wind, $humidity) = fetch_weather_data($city, $servername, $username, $password, $database);

    updateData($cityName, $day, $temperature, $description, $cloud, $pressure, $wind, $humidity, $servername, $username, $password, $database);

    $data = showData($cityName, $servername, $username, $password, $database);
        
    echo $data;
}
main();
?>
