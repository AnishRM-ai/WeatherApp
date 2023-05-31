<?php
// Storing credential in the variables to create connection with the database
    $username = "root";
    $password = "Gothicachyls3!";
    $host = "localhost";
    $database = "id20782522_weather";
    $connection = new mysqli($host, $username, $password, $database); // Connecting database
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }
    
    // Function to fetch weather data from the API and store it in the database and local storage
function fetchWeatherData($city, $fetchFromAPI) {
    $ch = curl_init();
    $url = "https://api.weatherapi.com/v1/history.json?key=98b38670508f4f069a433105232005&q=$city&dt=";

    // Looping through each day of past week to fetch weather data and insert it into the database
    for ($i = 0; $i < 7; $i++) {
        $date = date("Y-m-d", strtotime("-$i day")); // Calculating the date for past 7 days
        // Fetching weather data for each day
        $api_url = $url . $date;
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        $decoded_data = json_decode($resp, true);

        // Inserting weather data for each day into the database
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

            mysqli_query($GLOBALS['connection'], $query);
        }
    }

    // Retrieve the weather data from the database
    $query = "SELECT * FROM weather_data";
    $result = mysqli_query($GLOBALS['connection'], $query);
    $weatherData = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $weatherData[] = array(
            'date' => $row['date'],
            'temperature' => $row['temperature'],
            'condition' => $row['weather_condition'],
            'precipitation' => $row['precipitation'],
            'windspeed' => $row['windspeed'],
            'humidity' => $row['humidity'],
            'cloudy' => $row['cloudy']
        );
    }

    // Convert the weather data array to JSON
    $weatherDataJson = json_encode($weatherData);

    if ($fetchFromAPI) {
        // Store the weather data JSON in local storage
        echo "<script>console.log('Data Accessed from internet')</script>";
        echo "<script>localStorage.setItem('weatherData', JSON.stringify($weatherDataJson))</script>";
    } else {
        // Retrieve the weather data from local storage
        echo "<script>console.log('Data Accessed from Local Storage')</script>";
        echo "<script>var weatherDataJson = localStorage.getItem('weatherData');</script>";
        echo "<script>var weatherData = JSON.parse(weatherDataJson);</script>";
    }

    // Display the weather data on the page using JavaScript
    echo "<script>";
    echo "var weatherElement = document.getElementById('weather');";
    echo "for (var i = 0; i < weatherData.length; i++) {";
    echo "  var data = weatherData[i];";
    echo "  var date = data.date;";
    echo "  var temperature = data.temperature;";
    echo "  var condition = data.condition;";
    echo "  var precipitation = data.precipitation;";
    echo "  var windspeed = data.windspeed;";
    echo "  var humidity = data.humidity;";
    echo "  var cloudy = data.cloudy;";
    echo "  weatherElement.innerHTML += 'Date: ' + date + '<br>';";
    echo "  weatherElement.innerHTML += 'Temperature: ' + temperature + '<br>';";
    echo "  weatherElement.innerHTML += 'Condition: ' + condition + '<br>';";
    echo "  weatherElement.innerHTML += 'Precipitation: ' + precipitation + '<br>';";
    echo "  weatherElement.innerHTML += 'Windspeed: ' + windspeed + '<br>';";
    echo "  weatherElement.innerHTML += 'Humidity: ' + humidity + '<br>';";
    echo "  weatherElement.innerHTML += 'Cloudy: ' + cloudy + '<br>';";
    echo "  weatherElement.innerHTML += '<br>';";
    echo "}";
    echo "</script>";
}

// Check if the city is already searched
if (isset($GET['city'])) {
    $city = $GET['city'];

    // Check if the city exists in the database
    $query = "SELECT * FROM weather_data WHERE city='$city'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        // Case - II: City exists in the database, fetch data from local storage
        fetchWeatherData($city, false);
    } else {
        // Case - I: City is new, fetch data from the API and store in the database and local storage
        fetchWeatherData($city, true);
    }
} else {
    echo "<script>console.log('Failed to fetch data. No city specified.')</script>";
}
?>
