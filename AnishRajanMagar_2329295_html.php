<?php
    require_once("AnishRajanMagar_2329295.php");
    $pastweek =("SELECT * FROM weather_data ORDER BY date DESC LIMIT 7");
    $result = mysqli_query($connection, $pastweek);
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeatherWebApp</title>
    <link rel="stylesheet" href="AnishRajanMagar_2329295.css" >
    <script src="https://kit.fontawesome.com/cf8eaf9495.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Itim&family=Roboto:wght@100&display=swap" rel="stylesheet">
    
</head>
<body>
    
<!---------------------Anish Rajan Magar-----------UNI id- 2329295-------->
<!-------------------------------------Main Container of Weather box----------------------------->
    <div class="Main-container">

        <!-------------------------------------Left side of box----------------------------->
        <div class="Left-display">
            <div class="left-details">
            <h1 id="temperature"> .</h1>
            <h2 id="location"></h2> <span id="weather-icon"><img src="" alt="" class="icon" /></span>
            <span id="des"></span>
            <p id="time"></p>
        </div>

         <!----------Table for Weather History data---------------------->
    <div class="table-container">
        <form method="get" action="history.php">
            <label>Enter the city name to see past week report.</label>
            <input type="text" id="pastweek" name="city">
            <button type="submit" id="pastbtn">search</button>
        </form>
        <table id="Data-table">
            <h2> Previous Week Weather data.</h2>
           <tr id = "data-title">
            <th>Date</th>
            <th>Weather Condition</th>
            <th>Temperature</th>
            <th>Humidity</th>
            <th>Precipitation</th>
            <th>Cloudy</th>
            <th>Windspeed</th>
        </tr>
  
        <tr id="row1">
        <?php
      

        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){

                $date = $row['date'];
                $temperature = $row['temperature'];
                $condition = $row['weather_condition'];
                $pressure = $row['precipitation'];
                $windspeed = $row['windspeed'];
                $humidity = $row['humidity'];
                $cloudy = $row['cloudy'];
               
        ?>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo $row['weather_condition']; ?></td>
            <td><?php echo $row['temperature']; ?> °C</td>
            <td><?php echo $row['humidity']; ?> %</td>
            <td><?php echo $row['precipitation']; ?> mm</td>
            <td><?php echo $row['cloudy']; ?> %</td>
            <td><?php echo $row['windspeed']; ?> kmph</td>
          

            </tr>

            <?php

            }
        }
        else{
            echo "No Data Found";
        }
            ?>
        </table>


    </div>

        </div>
        <!-------------------------------------Right side of box----------------------------->
        <div class="Right-display">
          
            <input id="searchbar" type="text" placeholder="Another Locations">
            <button id="searchbtn"><i class="fa-solid fa-magnifying-glass"></i></button>
      
            <div class="search-items">
                <ul>
                    <li id="london">London</li>
                    <li id="tokyo">Tokyo</li>
                    <li id="kathmandu">Kathmandu</li>
                    <li id="dubai">Dubai</li>


                </ul>
                <hr>
            </div>
            
            

            <div class="Weather-details">
                <h2>Weather Details</h2>
                <ul id="details">
                    <li id="cloud">Cloudy  </li>
                    <li id="Humidit">Humidity  </li>
                    <li id="wind">Wind Speed </li>
                    <li id="pressure">Air Pressure </li>


                </ul>
               
                <hr>
            </div>
          
        </div>


    </div>
    <script src="AnishRajanMagar_2329295.js"></script>
</body>
</html>
<?php
// Closing database connection
mysqli_close($connection);
?>