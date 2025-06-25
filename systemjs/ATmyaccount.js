// Combined Clock, Weather, and Table Functionality
(function() {
    // Clock setup
    const hourElement = document.getElementById('clock-hour');
    const minutesElement = document.getElementById('clock-minutes');
    const dateMonthElement = document.getElementById('date-month');
    const dateDayElement = document.getElementById('date-day');
    const dateYearElement = document.getElementById('date-year');
    const timeDisplayElement = document.getElementById('time-ampm-week');

    const updateClockAndDate = () => {
        const date = new Date();
        const hours = date.getHours();
        const minutes = date.getMinutes();

        // Clock hand rotation
        const hoursDegrees = (hours % 12) / 12 * 360 + (minutes / 60) * 30;
        const minutesDegrees = minutes / 60 * 360;
        hourElement.style.transform = `rotateZ(${hoursDegrees}deg)`;
        minutesElement.style.transform = `rotateZ(${minutesDegrees}deg)`;

        // Update date and time display
        const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        dateMonthElement.textContent = months[date.getMonth()];
        dateDayElement.textContent = `${date.getDate()}, `;
        dateYearElement.textContent = date.getFullYear();

        const ampm = hours >= 12 ? 'PM' : 'AM';
        const formattedHours = hours % 12 || 12;
        const formattedMinutes = minutes.toString().padStart(2, '0');

        timeDisplayElement.textContent = `${formattedHours}:${formattedMinutes} ${ampm} - ${daysOfWeek[date.getDay()]}`;
    };

    // Weather setup
    const apiUrl = 'https://api.openweathermap.org/data/2.5/weather';
    const apiKey = 'b1bacc7d0b114c0faca76cb50c43d690';
    const latitude = 14.233;
    const longitude = 120.717;
    const requestUrl = `${apiUrl}?lat=${latitude}&lon=${longitude}&appid=${apiKey}&units=metric`;
    const weatherDiv = document.getElementById('Weather');

    // Fetch weather data
    fetch(requestUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const temperature = data.main.temp;
            const weatherDescription = data.weather[0].description.toLowerCase();
            const cityName = `${data.name}, ${data.sys.country}`;

            // Update weather display
            document.getElementById('temperature').textContent = `${temperature.toFixed(1)}°C`;
            document.getElementById('description').textContent = weatherDescription.toUpperCase();
            document.getElementById('cityName').textContent = cityName;

            // Set weather icon
            const weatherIcon = document.getElementById('weather-icon');
            if (weatherDescription.includes('rain')) {
                weatherIcon.className = 'bi bi-cloud-drizzle-fill rainy';
                weatherIcon.alt = 'Rainy';
            } else if (weatherDescription.includes('cloud')) {
                weatherIcon.className = 'bi bi-clouds-fill cloudy';
                weatherIcon.alt = 'Cloudy';
            } else if (weatherDescription.includes('clear')) {
                const currentHour = new Date().getHours();
                weatherIcon.className = currentHour >= 6 && currentHour < 18 ? 'bi bi-sun-fill sunny' : 'bi bi-moon-stars-fill clear-night';
                weatherIcon.alt = currentHour >= 6 && currentHour < 18 ? 'Sunny' : 'Clear (Night)';
            } else {
                weatherIcon.className = 'bi bi-question unknown';
                weatherIcon.alt = 'Unknown';
            }
        })
        .catch(error => {
            console.error('Error fetching weather data:', error);
            weatherDiv.textContent = 'Error fetching weather data. Please try again later.';
        });

    // Initialize clock and set interval
    updateClockAndDate();
    setInterval(updateClockAndDate, 1000);


    
    // Table search and sort setup
    const search = document.querySelector('.input-group input'),
    table_rows = document.querySelectorAll('tbody tr'),
    table_headings = document.querySelectorAll('thead th');

    // 1. Searching for specific data in HTML table
    search.addEventListener('input', searchTable);

    function searchTable() {
        table_rows.forEach((row, i) => {
            let table_data = row.textContent.toLowerCase(),
                search_data = search.value.toLowerCase();

            row.classList.toggle('hide', table_data.indexOf(search_data) < 0);
            row.style.setProperty('--delay', i / 25 + 's');
        });

        document.querySelectorAll('tbody tr:not(.hide)').forEach((visible_row, i) => {
            visible_row.style.backgroundColor = (i % 2 == 0) ? 'transparent' : '#0000000b';
        });
    }

    // 2. Sorting | Ordering data of HTML table
    table_headings.forEach((head, i) => {
        let sort_asc = true;
        head.onclick = () => {
            table_headings.forEach(head => head.classList.remove('active'));
            head.classList.add('active');

            document.querySelectorAll('td').forEach(td => td.classList.remove('active'));
            table_rows.forEach(row => {
                row.querySelectorAll('td')[i].classList.add('active');
            });

            head.classList.toggle('asc', sort_asc);
            sort_asc = head.classList.contains('asc') ? false : true;

            sortTable(i, sort_asc);
        }
    });

    function sortTable(column, sort_asc) {
        [...table_rows].sort((a, b) => {
            let first_row = a.querySelectorAll('td')[column].textContent.toLowerCase(),
                second_row = b.querySelectorAll('td')[column].textContent.toLowerCase();

            return sort_asc ? (first_row < second_row ? -1 : 1) : (first_row < second_row ? 1 : -1);
        })
        .forEach(sorted_row => document.querySelector('tbody').appendChild(sorted_row));
    }


})();



//EDIT DELETE CHANGEPASS

function editAccount(IDemployee){
    $.ajax({
        type: "POST",
        url: "Function/ATEditAccount.php",
        data: {IDemployee: IDemployee},
        success: function (response) {
            var res = jQuery.parseJSON(response);

            if (res.status == 'SUCCESS') {
                // Set modal content with data from response
                $('#modalFirstName').val(res.Efname);
                $('#modalLastName').val(res.Elname);
                $('#modalMiddleName').val(res.Emname);
                $('#modalEmail').val(res.Eemail);
                $('#modalUsername').val(res.Eusername);

                // Show the modal
                $('#accountModalEdit').modal('show');  // Bootstrap's method to show the modal

            } else if (res.status == 'ERROR') {
                alert(res.message);
            } else {
                alert('ERROR PHP CODE ATEditAccount.php');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
            $('.Text-Error').removeClass('d-none');
            $('.Text-Error span').text("Error Ajax!");
        }
    });
}




    //CHANGE PASSWORD ACCOUNT AT ACCOUNT
    $(document).on('submit', '#changepassATForm', function (e) {
        e.preventDefault();
 
        var ChangePassFormAT = new FormData(this);

        $.ajax({
            type: "POST",
            url: "Function/ATChangePassword.php",
            data: ChangePassFormAT,
            processData: false,
            contentType: false,
            success: function (response) {
                var res = jQuery.parseJSON(response);

                if(res.status == 'SUCCESS'){
                    alert(res.message);
                    location.reload();
                }else if(res.status == 'ERROR'){
                    alert(res.message);
                }else{
                    alert(res.message);
                }

            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }

        });
    });





    //EDIT ACCOUNT AT ACCOUNT
    $(document).on('submit', '#EditFormAT', function (e) {
        e.preventDefault();

        var EditFormAT = new FormData(this);
        EditFormAT.append("saveChangeEdit", true);

        $.ajax({
            type: "POST",
            url: "Function/ATEditAccountFunction.php",
            data: EditFormAT,
            processData: false,
            contentType: false,
            success: function (response) {
                var res = jQuery.parseJSON(response);

                if(res.status == 'SUCCESS'){
                    alert(res.message);
                    location.reload();    
                }else if(res.status == 'ERROR'){
                    alert(res.message);
                }else{
                    alert(res.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });

    });