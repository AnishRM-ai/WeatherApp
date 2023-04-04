//Anish Rajan Magar
//2329295
var today = new Date();
const day = today.toLocaleString('en-us', { weekday: 'long' });
const date = today.toLocaleString('en-us', { day: 'numeric' });
const month = today.toLocaleString('en-us', { month: 'long'});
const result = day + ', ' + month + ' ' + date;

let weather = {
    apiKey:"b4e9fe6e36d44fbb1e68136d04bab99e",
    fetchWeather:function(city){
        fetch(
            "https://api.openweathermap.org/data/2.5/weather?q="+ city +"&APPID=b4e9fe6e36d44fbb1e68136d04bab99e"
        ).then((response) => response.json())
        .then((data) => this.displayWeather(data))

    },
    displayWeather:function(data){
        const{name} = data;
        const{icon, description } = data.weather[0];
        const{temp, humidity} = data.main;
        const{ speed } = data.wind;
        const{ all } = data.clouds;
        const{pressure} = data.main;
        console.log(name, icon, description, temp, humidity, speed, all, pressure );
        document.querySelector("#location").innerText = name;
        document.querySelector("#temperature").innerHTML = (temp - 273.15).toFixed(1) + "°" + 'C';
        document.querySelector("#weather-icon").src =        document.querySelector(".icon").src = "https://openweathermap.org/img/wn/" + icon + ".png";
        document.querySelector("#des").innerText = description;
        document.querySelector("#time").innerText = result ;
        document.querySelector("#cloud").innerText = "Cloudy " + all + "%";
        document.querySelector("#Humidit").innerText = "Humidity " + humidity + "%";
        document.querySelector("#wind").innerText = "Wind Speed " + speed + "km/h";
        document.querySelector("#pressure").innerText = "Pressure " + pressure + " hPa";
        document.body.style.backgroundImage =  "url('https://source.unsplash.com/1600x900/?" + name + "')";
        
    
    },
    search: function(){
        this.fetchWeather(document.querySelector("#searchbar").value);
    }
};

document.querySelector("#searchbtn").addEventListener("click", function(){
   weather.search();
});

document.querySelector("#searchbar").addEventListener("keydown", function(event){
    if(event.key === "Enter"){
        weather.search();
    }
   
});

document.querySelector("#london").addEventListener("click", function(){
    weather.fetchWeather("London");
});

document.querySelector("#tokyo").addEventListener("click", function(){
    weather.fetchWeather("Tokyo");
});

document.querySelector("#kathmandu").addEventListener("click", function(){
    weather.fetchWeather("Kathmandu");
});

document.querySelector("#dubai").addEventListener("click", function(){
    weather.fetchWeather("Dubai");
});
weather.fetchWeather("Birmingham");