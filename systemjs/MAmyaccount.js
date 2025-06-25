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


    
// Table search and sort setup specific to the table with id "customers_table"
const customersTable = document.querySelector('#customers_table'); // Select the table by ID
const search = customersTable.querySelector('.employeelist input'); // Search input specific to this table
const table_rows = customersTable.querySelectorAll('#TBODYEMPLOYEE tr'); // Table rows specific to this table
const table_headings = customersTable.querySelectorAll('#TBODYEMPLOYEE thead th'); // Table headings specific to this table

// 1. Searching for specific data in HTML table
search.addEventListener('input', searchTable);

function searchTable() {
    const search_data = search.value.toLowerCase(); // Get the search term (lowercased)
    
    table_rows.forEach((row, i) => {
        let table_data = row.textContent.toLowerCase();
        
        // Check if search term exists in the row data
        row.classList.toggle('hide', table_data.indexOf(search_data) < 0);
        row.style.setProperty('--delay', i / 25 + 's');
    });

    // Highlight visible rows with alternating background colors
    document.querySelectorAll('#customers_table tbody tr:not(.hide)').forEach((visible_row, i) => {
        visible_row.style.backgroundColor = (i % 2 === 0) ? 'transparent' : '#0000000b';
    });
}

// 2. Sorting | Ordering data of HTML table
table_headings.forEach((head, i) => {
    let sort_asc = true;
    head.onclick = () => {
        // Remove active class from all headings
        table_headings.forEach(head => head.classList.remove('active'));
        head.classList.add('active'); // Add active class to clicked heading

        // Remove active class from all td elements
        document.querySelectorAll('#customers_table td').forEach(td => td.classList.remove('active'));
        
        // Add active class to td elements in the clicked column
        table_rows.forEach(row => {
            row.querySelectorAll('td')[i].classList.add('active');
        });

        // Toggle the sort direction
        head.classList.toggle('asc', sort_asc);
        sort_asc = head.classList.contains('asc') ? false : true;

        // Call sortTable function to sort the rows
        sortTable(i, sort_asc);
    };
});

function sortTable(column, sort_asc) {
    [...table_rows].sort((a, b) => {
        let first_row = a.querySelectorAll('td')[column].textContent.toLowerCase();
        let second_row = b.querySelectorAll('td')[column].textContent.toLowerCase();

        // Return sorted order based on asc/desc
        return sort_asc ? (first_row < second_row ? -1 : 1) : (first_row < second_row ? 1 : -1);
    })
    .forEach(sorted_row => customersTable.querySelector('tbody').appendChild(sorted_row)); // Append sorted rows
}








    //CREATE ACCOUNT AT ACCOUNT
    $(document).on('submit', '#addATform', function (e) {
        e.preventDefault();

        var ATformData = new FormData(this);
        ATformData.append("addAT", true);


        var submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true).text('Processing...'); 


        $.ajax({
            type: "POST",
            url: "Function/ATFunctionsignup.php",
            data: ATformData,
            processData: false,
            contentType: false,
            success: function (response) {
                var res = jQuery.parseJSON(response);

                if (res.status == 'EMPTY'){
                    alert('All Field Required!');
                }else if(res.status == 'SAMENAME'){
                    alert('User have Already Account!');
                }else if (res.status == 'SAMEEMAIL'){
                    alert('The Email Exist use another one!');
                }else if(res.status == 'SAMEUSERNAME'){
                    alert('The Username Exist use another one!');
                }else if(res.status == 'SUCCESS'){
                    submitButton.prop('disabled', false).text('Save Employee'); 
                    location.reload();
                }else if (res.status == 'ERROR'){
                    $('.Text-Error').removeClass("d-none");
                    $('.Text-Error span').text("Error Inserting Data to Database!");
                }else{
                    $('.Text-Error').removeClass("d-none");
                    $('.Text-Error span').text("JS Code Error!");
                }

            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                $('.Text-Error').removeClass('d-none');
                $('.Text-Error span').text("Error Ajax!");
            }
        });

    });


    //CHANGE PASS MA ACCOUNT
    $(document).on('submit', '#changepassMAForm', function (e) {
        e.preventDefault();

        var ChangePassformData = new FormData(this);
        ChangePassformData.append("chnagepassmyaccount", true);
        
        $.ajax({
            type: "POST",
            url: "Function/MAchangepass.php",
            data: ChangePassformData,
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
                alert(error);
            }
        });

    });


    //EDITMA ACCOUNT
    $(document).on('submit', '#EditAccountMAform', function (e) {
        e.preventDefault();

        var EditAccountMAform = new FormData(this);
        EditAccountMAform.append("editmyaccount", true);

        $.ajax({
            type: "POST",
            url: "Function/MAEditAccountFunction.php",
            data: EditAccountMAform,
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
                alert(error);
            }
        });

    });


})();



function deleteAccountEmployee(IDemployee) {
    const isConfirmed = confirm(`Are you sure you want to delete this employee's account? Click OK to confirm.`);
 
    if (isConfirmed) {
       
        $.ajax({
            type: "POST",
            url: "Function/ATDeleteAccount.php",
            data: {IDemployee: IDemployee},
            success: function (response) {
                var res = jQuery.parseJSON(response);

                if(res.status == 'SUCCESS'){
                    location.reload();
                }else if(res.status == 'ERROR'){
                    alert(res.message);
                }else{
                    alert('ERROR PHP CODE ATDeleteAccount.php');
                }
            
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                $('.Text-Error').removeClass('d-none');
                $('.Text-Error span').text("Error Ajax!");
            }
        });
       
    }
 }



// FINISH

    function recoverAccountEmployee(IDdeleted) {
        const isConfirmed = confirm(`Are you sure you want to delete this employee's account? Click OK to confirm.`);
        
        if (isConfirmed) {
            $.ajax({
                type: "POST",
                url: "Function/ATRecoverAccount.php",
                data: {IDdeleted: IDdeleted},
                success: function (response) {
                    var res = jQuery.parseJSON(response);

                    if(res.status == 'SUCCESS'){
                        alert(res.message);
                        location.reload();
                    }else if(res.status == 'ERROR'){
                        alert(res.message);
                    }else{
                        alert('ERROR PHP CODE ATRecoverAccount.php');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    alert(error);
                }

            });

        }

    }


    //Permanent Delete
    function PermanentDeleteAccountEmployee(IDdeleted){
        const isConfirmed = confirm(`Are you sure you want to permanently delete this employee's account? This action cannot be undone. Click OK to confirm.`);

        if (isConfirmed) {
            $.ajax({
                type: "POST",
                url: "Function/ATPDAccount.php",
                data: {IDdeleted: IDdeleted},
                success: function (response) {
                    var res = jQuery.parseJSON(response);

                    if(res.status == 'SUCCESS'){
                        alert(res.message);
                        location.reload();
                    }else if(res.status == 'ERROR'){
                        alert(res.message);
                    }else{
                        alert('ERROR PHP CODE ATPDAccount.php');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    alert(error);
                }

            });

        }

        
    }
 
 

    

    function EditMAAccount(IDadmin){

        $.ajax({
            type: "POST",
            url: "Function/MAEditAccount.php",
            data: {IDadmin: IDadmin},
            success: function (response) {

                var res = jQuery.parseJSON(response);

                if(res.status == 'SUCCESS'){
          
                    $('#modalFirstName').val(res.Afname);
                    $('#modalLastName').val(res.Alname);
                    $('#modalMiddleName').val(res.Amname);
                    $('#modalEmail').val(res.Aemail);
                    $('#modalUsername').val(res.Ausername);
    
                    // Show the modal
                    $('#editadminaccount').modal('show'); 

                }else if(res.status == 'ERROR'){
                    alert(res.message);
                }else{
                    alert('ERROR PHP CODE ATRecoverAccount.php');
                }

            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert(error);
            }
        });

    }



    