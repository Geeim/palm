$(document).ready(function() {
    // STORAGE NG LAST VIEW
    var lastViewedForm = localStorage.getItem('lastViewedForm');

    // ASKING KUNG ANO YUNG LAST VIEW
    if (lastViewedForm === 'MA') {
        showMAForm();
    } else {
        showATForm();
    }

    // SWITCHING AT MA if click- SHOW MA
    $('#btnAT').click(function() {
        showMAForm();
    });

    // SWITCHING AT MA if click - SHOW AT
    $('#btnMA').click(function() {
        showATForm();
    });

    // DITO TINITIGNAN NAG BABASE YUNG ASKING
    function showMAForm() {
        $('#MA').removeClass('d-none').css('opacity', '0'); // Make MA visible
        $('#btnsignup').removeClass('d-none').css('opacity', '0');
        $('#errorMessage').addClass('d-none');
        document.getElementById('Eusername').value = '';
        document.getElementById('Epassword').value = '';

        setTimeout(function() {
            $('#MA').css('opacity', '1'); // Set opacity to 1 to trigger the transition
            $('#btnsignup').css('opacity', '1'); // Set opacity to 1 to trigger the transition
        }, 10); // Delay to ensure the transition starts after the class is removed

        $('#AT').addClass('d-none'); // Hide AT login form

        // Store the last viewed form in local storage
        localStorage.setItem('lastViewedForm', 'MA');
    }

    // Function to show AT login form
    function showATForm() {
        $('#AT').removeClass('d-none').css('opacity', '0'); // Make AT visible
        $('#AerrorMessage').addClass('d-none');
        document.getElementById('Ausername').value = '';
        document.getElementById('Apassword').value = '';

        setTimeout(function() {
            $('#AT').css('opacity', '1'); // Set opacity to 1 to trigger the transition
        }, 10); // Delay to ensure the transition starts after the class is removed

        $('#btnsignup').addClass('d-none'); // Hide sign up button
        $('#MA').addClass('d-none'); // Hide MA login form

        // Store the last viewed form in local storage
        localStorage.setItem('lastViewedForm', 'AT');
    }
});

    
 //For Login AT
$(document).on('submit', '#FormAT', function (e) {
    e.preventDefault();

    var ATformData = new FormData(this);
    ATformData.append("loginbtn", true);

    $.ajax({
        type: "POST",
        url: "Function/ATFunctionLog.php",
        data: ATformData,
        processData: false,
        contentType: false,
        success: function (response) {
            
            var res = jQuery.parseJSON(response);
            if(res.status == 'NULL') {
                $('#errorMessage').removeClass('d-none');
                $('#errorMessage').text(res.message);
            }else if(res.status == 'NODATA'){
                $('#errorMessage').removeClass('d-none');
                $('#errorMessage').text(res.message);
                document.getElementById('Eusername').value = '';
                document.getElementById('Epassword').value = '';
            }else if (res.status == 'success'){
                $('#errorMessage').addClass('d-none');
                document.getElementById('Eusername').value = '';
                document.getElementById('Epassword').value = '';

                window.location.href = 'ATDashboard.php';

            }else{
                $('#errorMessage').removeClass('d-none');
                $('#errorMessage').text('Code ERROR');
            }


        },error: function() {
            $('#errorMessage').text("Error Login.");
        }
    });

});



//For Login MA
$(document).on('submit', '#FormMA', function (e) {
    e.preventDefault();

    var MAformData = new FormData(this);
    MAformData.append("loginbtn", true);

    $.ajax({
        type: "POST",
        url: "Function/MAFunctionLog.php",
        data: MAformData,
        processData: false,
        contentType: false,
        success: function (response) {
            
            var res = jQuery.parseJSON(response);
            if(res.status == 'NULL') {
                $('#AerrorMessage').removeClass('d-none');
                $('#AerrorMessage').text(res.message);
            }else if(res.status == 'NODATA'){
                $('#AerrorMessage').removeClass('d-none');
                $('#AerrorMessage').text(res.message);
                document.getElementById('Ausername').value = '';
                document.getElementById('Apassword').value = '';
            }else if (res.status == 'success'){
                $('#AerrorMessage').addClass('d-none');
                document.getElementById('Ausername').value = '';
                document.getElementById('Apassword').value = '';

                window.location.href = 'MADashboard.php';

               
            }else{
                $('#AerrorMessage').removeClass('d-none');
                $('#AerrorMessage').text('Code ERROR');
            }


        },error: function() {
            $('#errorMessage').text("Error Login.");
        }
    });

});


//For Create Account
$(document).on('submit', '#MACA', function (e) {
    e.preventDefault();

    var MAformDataCA = new FormData(this);
    MAformDataCA.append("btnCA", true);

    $.ajax({
        type: "POST",
        url: "Function/MAFunctionCA.php",
        data: MAformDataCA,
        processData: false,
        contentType: false,
  
        success: function(response) {
            var res = jQuery.parseJSON(response);
            if(res.status == 'EMPTY') {
                $('.Text-Error-CA').removeClass('d-none');
                $('.Text-Error-CA p').text('Please Enter Your Email.');

            }else if(res.status == 'ERROR'){
                $('.Text-Error-CA').removeClass('d-none');
                $('.Text-Error-CA p').text('ERROR SENDING!');
                
            }else if (res.status == 'DOMAIN'){
                $('.Text-Error-CA').removeClass('d-none');
                $('.Text-Error-CA p').text("Please provide valid email address.");

            }else if(res.status == 'SUCCESS'){
                $('.Text-Esent-CA').removeClass('d-none');
                $('.Text-Error-CA').addClass('d-none');
                $('.Text-Esent-CA p').text('Request Sent: Check System Email.');

                setTimeout(function() {
                    location.reload();
                }, 3000);

            }else{
                $('.Text-Error-CA').removeClass('d-none');
                $('.Text-Error-CA p').text('ERROR CA CODE FUNCTION.');
            }

            },
        error: function() {
            $('.Text-Error-CA').removeClass('d-none');
            $('.Text-Error-CA p').text('AJAX ERROR!');
        }
    });
});



//For MA Forgot Password
$(document).on('submit', '#MAFP', function (e) {
    e.preventDefault();

    var MAformDataFP = new FormData(this);
    MAformDataFP.append("FPbutton", true);


    $.ajax({
        type: "POST",
        url: "Function/MAFunctionFP.php",
        data: MAformDataFP,
        processData: false,
        contentType: false,
  
        success: function(response) {
            var res = jQuery.parseJSON(response);
            if(res.status == 'EMPTY') {
                $('.Text-Error-FP').removeClass('d-none');
                $('.Text-Error-FP p').text('Please Enter Your Email.');

            }else if(res.status == 'ERROR'){
                $('.Text-Error-FP').removeClass('d-none');
                $('.Text-Error-FP p').text('ERROR SENDING!');
                
            }else if (res.status == 'DOMAIN'){
                $('.Text-Error-FP').removeClass('d-none');
                $('.Text-Error-FP p').text("Please provide valid email address.");

            }else if(res.status == 'NODATA'){
                $('.Text-Error-FP').removeClass('d-none');
                $('.Text-Error-FP p').text("NO ACCOUNT FOUND.");

            }else if(res.status == 'SUCCESS'){
                $('.Text-Esent-FP').removeClass('d-none');
                $('.Text-Error-FP').addClass('d-none');
                $('.Text-Esent-FP p').text('Reset link send please check your Inbox.');

                setTimeout(function() {
                    location.reload();
                }, 3000);

            }else{
                $('.Text-Error-FP').removeClass('d-none');
                $('.Text-Error-FP p').text('ERROR FP CODE FUNCTION.');
            }

            },
        error: function() {
            $('.Text-Error-FP').removeClass('d-none');
            $('.Text-Error-FP p').text('AJAX ERROR!');
        }
    });
});


//For AT Forgot Password
$(document).on('submit', '#ATFP', function (e) {
    e.preventDefault();

    var ATformDataFP = new FormData(this);
    ATformDataFP.append("FPbuttonAT", true);


    $.ajax({
        type: "POST",
        url: "Function/ATFunctionFP.php",
        data: ATformDataFP,
        processData: false,
        contentType: false,
  
        success: function(response) {
            var res = jQuery.parseJSON(response);
            if(res.status == 'EMPTY') {
                $('.ATText-Error-FP').removeClass('d-none');
                $('.ATText-Error-FP p').text('Please Enter Your Email.');

            }else if(res.status == 'ERROR'){
                $('.ATText-Error-FP').removeClass('d-none');
                $('.ATText-Error-FP p').text('ERROR SENDING!');
                
            }else if (res.status == 'DOMAIN'){
                $('.ATText-Error-FP').removeClass('d-none');
                $('.ATText-Error-FP p').text("Please provide valid email address.");

            }else if(res.status == 'NODATA'){
                $('.ATText-Error-FP').removeClass('d-none');
                $('.ATText-Error-FP p').text("NO ACCOUNT FOUND.");

            }else if(res.status == 'SUCCESS'){
                $('.ATText-Esent-FP').removeClass('d-none');
                $('.ATText-Error-FP').addClass('d-none');
                $('.ATText-Esent-FP p').text('Reset link send please check your Inbox.');

                setTimeout(function() {
                    location.reload();
                }, 3000);

            }else{
                $('.ATText-Error-FP').removeClass('d-none');
                $('.ATText-Error-FP p').text('ERROR FP CODE FUNCTION.');
            }

            },
        error: function() {
            $('.ATText-Error-FP').removeClass('d-none');
            $('.ATText-Error-FP p').text('AJAX ERROR!');
        }
    });
});