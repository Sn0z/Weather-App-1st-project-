/*Name: Prarambha Shrestha
    Student ID: 2408419*/
  //Fetch weather data

// DOM elements
const searchBox = document.querySelector(".search_box input");
const searchButton = document.querySelector(".search_box button");
const weather_pic = document.querySelector(".pic");
const forecastCard = document.querySelectorAll('.day');

const weather = (city) => {
  fetch(`http://localhost/prototype-2/sample1.php?q=${city}`)
    .then((res) => res.json())
    .then((data) => {
        // Display weather information
        console.log('res data', data);
        document.querySelector('.city').innerHTML = data[0].city;
        document.querySelector('.temp').innerHTML = data[0].temperature + "&degc";
        document.querySelector('.cloud').innerHTML = data[0].weather_description;
        document.querySelector('.humidity').innerHTML = data[0].humidity + "%";
        document.querySelector(".wind").innerHTML = data[0].wind + " km/hr";
        document.querySelector(".pressure").innerHTML = data[0].pressure + " hPa";
        document.querySelector(".weather_icon").src =`https://openweathermap.org/img/wn/${data[0].cloud}@4x.png`;

        //Updating the date and time
        const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        const time = new Date();
        const month = time.getMonth();
        const date = time.getDate();
        const day = time.getDay();
        const year =time.getFullYear();
        document.querySelector(".date").innerHTML = days[day] + ', ' + months[month] + ' ' + date + ', ' + year  ;
      
        // Update map with new location
        document.getElementById("map").innerHTML = null;
        let iframe = document.createElement("iframe");
        iframe.setAttribute("id", "map1");
        iframe.src = `https://maps.google.com/maps?q=${data[0].city}&t=&z=13&ie=UTF8&iwloc=&output=embed`;
        document.getElementById("map").append(iframe);
        
      for (let i in data){
          forecastCard[i].innerHTML = `
            <img class="pic" src="https://openweathermap.org/img/wn/${data[i].cloud}@4x.png" />
            <p><span>${data[i].weather_description}</span><p>
            <p>${data[i].temperature}&degC</p>
            <p>${data[i].Day_date}</p>`
      }

      forecastCard.forEach((card, index) => {
        card.addEventListener("click", () => {
          document.querySelector(".date").innerHTML = data[index].Day_date;
          document.querySelector('.city').innerHTML = data[index].city;
          document.querySelector('.temp').innerHTML = data[index].temperature + "&degc";
          document.querySelector('.cloud').innerHTML = data[index].weather_description;
          document.querySelector('.humidity').innerHTML = data[index].humidity + "%";
          document.querySelector(".wind").innerHTML = data[index].wind + " km/hr";
          document.querySelector(".pressure").innerHTML = data[index].pressure + " hPa";
          document.querySelector(".weather_icon").src =`https://openweathermap.org/img/wn/${data[index].cloud}@4x.png`;
        });
      });
    })
    
    .catch((error) => {
      console.log(error)
      document.querySelector('.city').innerHTML = "city was not found";
      document.querySelector('.date').innerHTML = "";
      document.querySelector('.temp').innerHTML = "";
      document.querySelector('.cloud').innerHTML = "";
      document.querySelector(".weather_icon").src ="https://cdn0.iconfinder.com/data/icons/shift-free/32/Error-1024.png";
      document.getElementById("map").innerHTML = null;
      let iframe = document.createElement("iframe");
      iframe.setAttribute("id", "map1");
      iframe.src = "";
      document.getElementById("map").append(iframe);
      document.querySelector('.humidity').innerHTML = "";
      document.querySelector(".wind").innerHTML = "";
      document.querySelector(".pressure").innerHTML = "";
    });
};
weather("orai")

searchButton.addEventListener("click", () => {
  weather(searchBox.value);
});
searchBox.addEventListener("keypress", (event) => {
  if (event.key === "Enter") {
    weather(searchBox.value);
  }
});
