
<?php
//Anish Rajan Magar
//2329295
// Storing credential in the variables to create connection with the database
$username = "root";
$password = "Gothicachyls3!";
$host = "localhost";
$database = "id20782522_weather";
$connection = new mysqli($host, $username, $password, $database); // Connecting to the database

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['city']) && !empty($_GET['city'])) {
        $city = $_GET['city'];

        // Fetch weather forecast data from API
        $ch = curl_init();
        $url = "https://api.weatherapi.com/v1/history.json?key=98b38670508f4f069a433105232005&q=$city&dt="; 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        $decoded_data = json_decode($resp, true);


        // Inserting weather data for each day into the database
        for ($i = 0; $i < 7; $i++) {
            $date = date("Y-m-d", strtotime("-$i day")); // Calculating the date for past 7 days
            // Fetching weather data for each day
            $api_url = $url . $date;
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $resp = curl_exec($ch);
            $decoded_data = json_decode($resp, true);
    
            foreach ($decoded_data['forecast']['forecastday'] as $forecast) {
                $date = $forecast['date'];
                $temperature = $forecast['day']['avgtemp_c'];
                $condition = $forecast['day']['condition']['text'];
                $humidity = $forecast['day']['avghumidity'];
                $precipitation = $forecast['day']['totalprecip_in'];
                $cloudy = $forecast['day']['avgvis_km'];
                $windspeed = $forecast['day']['maxwind_kph'];
    
                // Prepare the SQL statement
                $query = "INSERT INTO weather_data (date, temperature, weather_condition, precipitation, windspeed, humidity, cloudy) 
                          VALUES ('$date', '$temperature', '$condition', '$precipitation', '$windspeed', '$humidity', '$cloudy')
                          ON DUPLICATE KEY UPDATE 
                          temperature = VALUES(temperature),
                          weather_condition = VALUES(weather_condition),
                          precipitation = VALUES(precipitation),
                          windspeed = VALUES(windspeed),
                          humidity = VALUES(humidity),
                          cloudy = VALUES(cloudy)";
    
                mysqli_query($connection, $query);
            }
        }
    }
}

?>
<?php
$get_data_query = "SELECT * FROM weather_data ORDER BY date DESC LIMIT 7";
$result = mysqli_query($connection, $get_data_query);

$weather_data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $weather_data[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Weather App</title>
</head>
<body>
    <h1>Weather App</h1>
    <a href="AnishRajanMagar_2329295_html.php"> <button> Home </button></a>
    <div>
        <form method="get" action="history.php">
            <label>Enter the city name to fetch 7-day weather forecast:</label>
            <input type="text" name="city" required>
            <button type="submit">Fetch Data</button>
        </form>
    </div>

    <?php if (!empty($weather_data)) { ?>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Weather Condition</th>
                <th>Temperature</th>
                <th>Precipitation</th>
                <th>Wind Speed</th>
                <th>Humidity</th>
                <th>Cloudy</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($weather_data as $day) { ?>
                <tr>
                    <td><?php echo $day['date']; ?></td>
                    <td><?php echo $day['weather_condition']; ?></td>
                    <td><?php echo $day['temperature']; ?></td>
                    <td><?php echo $day['precipitation']; ?></td>
                    <td><?php echo $day['windspeed']; ?></td>
                    <td><?php echo $day['humidity']; ?></td>
                    <td><?php echo $day['cloudy']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>
            </body>