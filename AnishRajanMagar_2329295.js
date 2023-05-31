//Anish Rajan Magar
//2329295

var today = new Date();
const day = today.toLocaleString('en-us', { weekday: 'long' });
const date = today.toLocaleString('en-us', { day: 'numeric' });
const month = today.toLocaleString('en-us', { month: 'long'});
const result = day + ', ' + month + ' ' + date;
let weatherData = [];

let weather = {
  fetchWeather: function(city) {
    fetch(
      "https://api.weatherapi.com/v1/current.json?key=98b38670508f4f069a433105232005&q=" + city + "&aqi=no"
    )
      .then(response => response.json())
      .then(data => {
        this.displayWeather(data, city);
        this.saveToLocalStorage();
      })
      .catch(error => {
        console.log(alert("Error: " + error));
        this.loadDataFromLocalStorage();
      });
  },

  displayWeather: function(data, city) {
    const { name } = data.location;
    const { icon, text } = data.current.condition;
    const { temp_c, humidity } = data.current;
    const { wind_kph } = data.current;
    const { cloud } = data.current;
    const { pressure_mb } = data.current;

    const weatherElement = document.createElement("div");
    weatherElement.classList.add("weather-data");
    weatherElement.innerHTML = 
    document.querySelector("#location").innerText = name;
    document.querySelector("#temperature").innerHTML = temp_c + "°" + 'C';
    document.querySelector("#weather-icon").src =        document.querySelector(".icon").src = icon;
    document.querySelector("#des").innerText = text;
    document.querySelector("#time").innerText = result ;
    document.querySelector("#cloud").innerText = "Cloudy " + cloud + "%";
    document.querySelector("#Humidit").innerText = "Humidity " + humidity + "%";
    document.querySelector("#wind").innerText = "Wind Speed " + wind_kph + "km/h";
    document.querySelector("#pressure").innerText = "Pressure " + pressure_mb + " hPa";
    
    ;

    document.querySelector(".Main-container").appendChild(weatherElement);

    let cityData = {
     name,
      icon,
      text,
      temp_c,
      humidity,
      wind_kph,
      cloud,
      pressure_mb,
      city
    };

    weatherData.push(cityData);
  },

  saveToLocalStorage: function() {
    localStorage.setItem("weatherData", JSON.stringify(weatherData));
  },

  loadDataFromLocalStorage: function() {
    let storedWeatherData = localStorage.getItem("weatherData");
    if (storedWeatherData) {
      weatherData = JSON.parse(storedWeatherData);

      // Display weather data for each city
      weatherData.forEach(cityData => {
        if (cityData.city !== "Birmingham") {
          this.displayWeather(cityData, cityData.city);
        }
      });
    }
  },

  search: function() {
    let city = document.querySelector("#searchbar").value;
    if (city) {
      this.fetchWeather(city);
    }
  }
};

document.querySelector("#searchbtn").addEventListener("click", function() {
  weather.search();
});

document.querySelector("#searchbar").addEventListener("keydown", function(event) {
  if (event.key === "Enter") {
    weather.search();
  }
});

window.addEventListener("load", function() {
  weather.loadDataFromLocalStorage();
});

document.querySelector("#london").addEventListener("click", function() {
  weather.fetchWeather("London");
});

document.querySelector("#tokyo").addEventListener("click", function() {
  weather.fetchWeather("Tokyo");
});

document.querySelector("#kathmandu").addEventListener("click", function() {
  weather.fetchWeather("Kathmandu");
});

document.querySelector("#dubai").addEventListener("click", function() {
  weather.fetchWeather("Dubai");
});

weather.fetchWeather("Birmingham");






