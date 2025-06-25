$(function() {

    // Handle DV dropdown
    $(document).on('click', '.DVdrop', function(e) {
        e.preventDefault(); // Prevent the default action of the link
        
        // Select the DV dropdown and arrow icon
        var $dvDropdown = $(this).siblings('#DV');
        var $dvArrow = $(this).find('.arrowdown');
        
        // Close DR dropdown if it's open
        var $drDropdown = $('#DR');
        var $drArrow = $('.DRdrop').find('.arrowdown');
        
        if ($drDropdown.is(':visible')) {
            $drDropdown.slideUp();
            $drArrow.removeClass('rotate');
        }
        
        // Toggle DV dropdown and rotate arrow
        $dvDropdown.slideToggle();
        $dvArrow.toggleClass('rotate');
    });

    // Handle DR dropdown
    $(document).on('click', '.DRdrop', function(e) {
        e.preventDefault(); // Prevent the default action of the link
        
        // Select the DR dropdown and arrow icon
        var $drDropdown = $(this).siblings('#DR');
        var $drArrow = $(this).find('.arrowdown');
        
        // Close DV dropdown if it's open
        var $dvDropdown = $('#DV');
        var $dvArrow = $('.DVdrop').find('.arrowdown');
        
        if ($dvDropdown.is(':visible')) {
            $dvDropdown.slideUp();
            $dvArrow.removeClass('rotate');
        }
        
        // Toggle DR dropdown and rotate arrow
        $drDropdown.slideToggle();
        $drArrow.toggleClass('rotate');
    });

    // FOR CONTENT VIEW
    // Load content from last visited URL or default page
    var lastVisitedUrl = localStorage.getItem('lastVisitedUrl'); 
    if (lastVisitedUrl) {
        loadContent(lastVisitedUrl); // Load content from the last visited URL
    } else {
        var defaultUrl = 'MAmyaccount.php?validation=true'; // Default page URL
        loadContent(defaultUrl); // Load content from the default URL
    }
    
    // Click Links Show
    $(document).on('click', '.navlink', function(e) {
        e.preventDefault(); // Prevent default link behavior
        var pageUrl = $(this).attr('data-url'); // Get the data-url attribute of the clicked link
        loadContent(pageUrl); // Load content from the clicked link
    });

    // Load the data-url link to Main Content and put storage of the data-url visited
    function loadContent(url) {

         // Clear the console log
         console.clear(); // Clear console log
         
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                $('#main-content').html(data); // Replace content of main-content with loaded data
                localStorage.setItem('lastVisitedUrl', url); // Store the URL of the last visited page in local storage
            },
            error: function() {
                $('#main-content').html('<p>Error loading content.</p>'); // Display error message if content cannot be loaded
            }
        });
    }

    // Logout and reset storage
    function logout(url) {
        window.location = url;
        localStorage.removeItem('lastVisitedUrl');
    }

    $(document).on('click', '#logout', function(e) {
        e.preventDefault();
        var logoutUrl = $(this).attr('data-url');
        logout(logoutUrl);
    });

})