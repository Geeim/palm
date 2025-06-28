if (!window.month) {

    const month = {
        '1': 'January',
        '2': 'February',
        '3': 'March',
        '4': 'April',
        '5': 'May',
        '6': 'June',
        '7': 'July',
        '8': 'August',
        '9': 'September',
        '10': 'October',
        '11': 'November',
        '12': 'December'
    };

    const season = {
        '1': 'Wet Season',
        '2': 'Dry Season',
    };


//SEARCH DATA LIST RECORDS FOR PLANTING & HARVESTING - OK
      
    const searchInput = document.querySelector('#SearchInput');
    const table1Rows = document.querySelectorAll('#table1 tbody tr');
    const table2Rows = document.querySelectorAll('#table2 tbody tr');

    searchInput.addEventListener('input', searchTable);

    function searchTable() {
        const searchData = searchInput.value.toLowerCase();
        updateRowVisibility(table1Rows, searchData);
        updateRowVisibility(table2Rows, searchData);
    }

    function updateRowVisibility(rows, searchData) {
        rows.forEach((row, index) => {
            const rowData = row.textContent.toLowerCase();
            const buttonExists = row.querySelector('button'); // Check for button presence
            const isHidden = rowData.indexOf(searchData) < 0 || !buttonExists;

            row.classList.toggle('hide', isHidden);
            row.style.setProperty('--delay', index / 25 + 's');

            // Update row background color if visible
            if (!isHidden) {
                row.style.backgroundColor = (index % 2 === 0) ? 'transparent' : '#0000000b';
            }
        });
    }




// NEXT AND BACK - Transition function

    function transitionTables(outTable, inTable, direction) {
        // Determine the transition classes based on direction
        if (direction === "next") {
            outTable.classList.add("table-leave"); // Add class to leave to the right
            inTable.classList.remove("table-leave-left"); // Ensure the left leave class is not applied
        } else {
            outTable.classList.add("table-leave-left"); // Add class to leave to the left
            inTable.classList.remove("table-leave"); // Ensure the right leave class is not applied
        }

        // Listen for the end of the outTable transition
        outTable.addEventListener("transitionend", function() {
            outTable.classList.add("d-none"); // Hide the outgoing table
            inTable.classList.remove("d-none"); // Show the incoming table
            inTable.classList.add("table-enter"); // Add enter class for incoming table

            // Trigger reflow to enable animation
            inTable.offsetHeight; // Forces reflow
            inTable.classList.add("table-enter-active"); // Start the enter transition

            // Clean up classes after the transition
            inTable.addEventListener("transitionend", function() {
                inTable.classList.remove("table-enter");
                inTable.classList.remove("table-enter-active");
            }, { once: true });
        }, { once: true });
    }


     
    
    
    
// VIEW DATA RAINFED 

    function getMonthAndSeason(monthNum, seasonNum) {
        const monthName = month[monthNum];  
        const seasonType = season[seasonNum];  

        return { monthName, seasonType };
    }
    

//PLANTING PART RAINFED
   

    function ViewPlanting(year, month, range_date) {
        $.ajax({
            url: 'Function/DATARainfedViewPlanting.php',  // PHP script to fetch data
            type: 'POST',
            data: {
                year: year,
                month: month,
                range_date: range_date
            },
            success: function(response) {
                var data = JSON.parse(response);

                const { monthName, seasonType } = getMonthAndSeason(data.INFO.month, data.INFO.season_type);

                $('#season_type').html(seasonType);  // Assuming 'seed_system_type' exists in the response
                $('#yearAndRangeDate').html(monthName + ' ' + data.INFO.range_date + ' ' + data.INFO.year);

                $('#VIEW_PLANTING_FORMAL_NPR_TABLE tbody').empty();
                $('#VIEW_PLANTING_FORMAL_RCEF_TABLE tbody').empty();
                $('#VIEW_PLANTING_FORMAL_OWNOTHERS_TABLE tbody').empty();
                $('#VIEW_PLANTING_INFORMAL_TABLE tbody').empty();
                $('#VIEW_PLANTING_FSS_TABLE tbody').empty();

                if (data.NPR && data.NPR.length > 0){
                    data.NPR.forEach(function(row, index) {
                        var rowHtml = "<tr>";
                            rowHtml += "<td class='first_td'><input type='text' readonly data-field='FROMAL_NPR_Barangay' data-index='" + index + "' value='" + row.FROMAL_NPR_Barangay + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Hybrid_Area_Planted' data-index='" + index + "' value='" + row.Hybrid_Area_Planted + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Hybrid_No_Farmers' data-index='" + index + "' value='" + row.Hybrid_No_Farmers + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Registered_Area_Planted' data-index='" + index + "' value='" + row.Registered_Area_Planted + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Registered_No_Farmers' data-index='" + index + "' value='" + row.Registered_No_Farmers + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Certified_Area_Planted' data-index='" + index + "' value='" + row.Certified_Area_Planted + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Certified_No_Farmers' data-index='" + index + "' value='" + row.Certified_No_Farmers + "'step='any'></td>";
                            rowHtml += "</tr>";
    
                        // Append the new row to table1
                        $('#VIEW_PLANTING_FORMAL_NPR_TABLE').append(rowHtml);
                    });
                }else{
                    $('#VIEW_PLANTING_FORMAL_NPR_TABLE').append('<tr><td colspan="7" class="text-center">No Planted</td></tr>');
                }


                if (data.RCEF && data.RCEF.length > 0){
                    data.RCEF.forEach(function(row, index) {
                        var rowHtml = "<tr>";
                            rowHtml += "<td><input type='text' readonly data-field='FROMAL_RCEF_Barangay' data-index='" + index + "' value='" + row.FROMAL_RCEF_Barangay + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Hybrid_Area_Planted' data-index='" + index + "' value='" + row.Hybrid_Area_Planted + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Hybrid_No_Farmers' data-index='" + index + "' value='" + row.Hybrid_No_Farmers + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Registered_Area_Planted' data-index='" + index + "' value='" + row.Registered_Area_Planted + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Registered_No_Farmers' data-index='" + index + "' value='" + row.Registered_No_Farmers + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Certified_Area_Planted' data-index='" + index + "' value='" + row.Certified_Area_Planted + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Certified_No_Farmers' data-index='" + index + "' value='" + row.Certified_No_Farmers + "'step='any'></td>";
                            rowHtml += "</tr>";
            
                        // Append the new row to table1
                        $('#VIEW_PLANTING_FORMAL_RCEF_TABLE').append(rowHtml);
                    });
                }else{
                    $('#VIEW_PLANTING_FORMAL_RCEF_TABLE').append('<tr><td colspan="7" class="text-center">No Planted</td></tr>');
                }

                
                if (data.OWNOTHERS && data.OWNOTHERS.length > 0){
                    data.OWNOTHERS.forEach(function(row, index) {  
                        var rowHtml = "<tr>";
                            rowHtml += "<td><input type='text' readonly data-field='FROMAL_OWNOTHERS_Barangay' data-index='" + index + "' value='" + row.FROMAL_OWNOTHERS_Barangay + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Hybrid_Area_Planted' data-index='" + index + "' value='" + row.Hybrid_Area_Planted + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Hybrid_No_Farmers' data-index='" + index + "' value='" + row.Hybrid_No_Farmers + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Registered_Area_Planted' data-index='" + index + "' value='" + row.Registered_Area_Planted + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Registered_No_Farmers' data-index='" + index + "' value='" + row.Registered_No_Farmers + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Certified_Area_Planted' data-index='" + index + "' value='" + row.Certified_Area_Planted + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Certified_No_Farmers' data-index='" + index + "' value='" + row.Certified_No_Farmers + "' step='any'></td>";
                            rowHtml += "</tr>";
                
                            // Append the new row to table1
                            $('#VIEW_PLANTING_FORMAL_OWNOTHERS_TABLE').append(rowHtml);
                        });
                }else{
                    $('#VIEW_PLANTING_FORMAL_OWNOTHERS_TABLE').append('<tr><td colspan="7" class="text-center">No Planted</td></tr>');
                }


                if (data.INFORMAL && data.INFORMAL.length > 0){
                    data.INFORMAL.forEach(function(row, index) {
                        var rowHtml = "<tr>";
                            rowHtml += "<td><input type='text' readonly data-field='INFROMAL_Barangay' data-index='" + index + "' value='" + row.INFROMAL_Barangay + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Starter_Area_Planted' data-index='" + index + "' value='" + row.Starter_Area_Planted + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Starter_No_Farmers' data-index='" + index + "' value='" + row.Starter_No_Farmers + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Tagged_Area_Planted' data-index='" + index + "' value='" + row.Tagged_Area_Planted + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Tagged_No_Farmers' data-index='" + index + "' value='" + row.Tagged_No_Farmers + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Traditional_Area_Planted' data-index='" + index + "' value='" + row.Traditional_Area_Planted + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='Traditional_No_Farmers' data-index='" + index + "' value='" + row.Traditional_No_Farmers + "' step='any'></td>";
                            rowHtml += "</tr>";
            
                        // Append the new row to table1
                        $('#VIEW_PLANTING_INFORMAL_TABLE').append(rowHtml);
                    });
                }else{
                    $('#VIEW_PLANTING_INFORMAL_TABLE').append('<tr><td colspan="7" class="text-center">No Planted</td></tr>');
                }


                if (data.FSS && data.FSS.length > 0){
                    data.FSS.forEach(function(row, index) {
                        var rowHtml = "<tr>";
                            rowHtml += "<td><input type='text' readonly data-field='FSS_Barangay' data-index='" + index + "' value='" + row.FSS_Barangay + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='FSS_Area_Planted' data-index='" + index + "' value='" + row.FSS_Area_Planted + "' step='any'></td>";
                            rowHtml += "<td><input type='number' readonly data-field='FSS_No_Farmers' data-index='" + index + "' value='" + row.FSS_No_Farmers + "' step='any'></td>";
                            rowHtml += "</tr>";
                
                        // Append the new row to table1
                        $('#VIEW_PLANTING_FSS_TABLE').append(rowHtml);
                    });
                }else{
                    $('#VIEW_PLANTING_FSS_TABLE').append('<tr><td colspan="3" class="text-center">No Planted</td></tr>');
                }


                $('#viewModalPlanting').modal('show');
            },
            error: function() {
                alert('Failed to load data for viewing');
            }     
        });
   }


    // GET ALL TABLE BUTTON ID - VIEW
     const VIEW_PLANTING_FORMAL_NPR_TABLE_ID = document.getElementById("VIEW_PLANTING_FORMAL_NPR_TABLE");
     const VIEW_PLANTING_FORMAL_RCEF_TABLE_ID = document.getElementById("VIEW_PLANTING_FORMAL_RCEF_TABLE");
     const VIEW_PLANTING_FORMAL_OWNOTHERS_TABLE_ID = document.getElementById("VIEW_PLANTING_FORMAL_OWNOTHERS_TABLE");
     const VIEW_PLANTING_INFORMAL_TABLE_ID = document.getElementById("VIEW_PLANTING_INFORMAL_TABLE");
     const VIEW_PLANTING_FSS_TABLE_ID = document.getElementById("VIEW_PLANTING_FSS_TABLE");

     // GET ALL TABLE NEXT BUTTON ID - VIEW
     const View_PlantingNextTableRCEF = document.getElementById("View_PlantingNextTableRCEF");
     const View_PlantingNextTableOWNOTHRES = document.getElementById("View_PlantingNextTableOWNOTHERS");
     const View_PlantingNextTableINFORMAL = document.getElementById("View_PlantingNextTableINFORMAL");
     const View_PlantingNextTableFSS = document.getElementById("View_PlantingNextTableFSS");

     // GET ALL TABLE BACK BUTTON ID - VIEW
     const View_PlantingBackToNPR = document.getElementById("View_PlantingBackToNPR");
     const View_PlantingBackToRCEF = document.getElementById("View_PlantingBackToRCEF");
     const View_PlantingBackToOWNOTHERS = document.getElementById("View_PlantingBackToOWNOTHERS");
     const View_PlantingBackToINFORMAL = document.getElementById("View_PlantingBackToINFORMAL");


         //NEXT CLICK TRANSITION - OK
         View_PlantingNextTableRCEF.addEventListener("click", function() {
             transitionTables(VIEW_PLANTING_FORMAL_NPR_TABLE_ID, VIEW_PLANTING_FORMAL_RCEF_TABLE_ID, "next");
         });
         View_PlantingNextTableOWNOTHRES.addEventListener("click", function() {
             transitionTables(VIEW_PLANTING_FORMAL_RCEF_TABLE_ID, VIEW_PLANTING_FORMAL_OWNOTHERS_TABLE_ID, "next");
         });
         View_PlantingNextTableINFORMAL.addEventListener("click", function() {
             transitionTables(VIEW_PLANTING_FORMAL_OWNOTHERS_TABLE_ID, VIEW_PLANTING_INFORMAL_TABLE_ID, "next");
         });
         View_PlantingNextTableFSS.addEventListener("click", function() {
             transitionTables(VIEW_PLANTING_INFORMAL_TABLE_ID, VIEW_PLANTING_FSS_TABLE_ID, "next");
         })

         //NEXT CLICK TRANSITION
         View_PlantingBackToNPR.addEventListener("click", function() {
             transitionTables(VIEW_PLANTING_FORMAL_RCEF_TABLE_ID, VIEW_PLANTING_FORMAL_NPR_TABLE_ID, "back");
         });
     
         View_PlantingBackToRCEF.addEventListener("click", function() {
             transitionTables(VIEW_PLANTING_FORMAL_OWNOTHERS_TABLE_ID, VIEW_PLANTING_FORMAL_RCEF_TABLE_ID, "back");
         });
     
         View_PlantingBackToOWNOTHERS.addEventListener("click", function() {
             transitionTables(VIEW_PLANTING_INFORMAL_TABLE_ID, VIEW_PLANTING_FORMAL_OWNOTHERS_TABLE_ID, "back");
         });
     
         View_PlantingBackToINFORMAL.addEventListener("click", function() {
             transitionTables(VIEW_PLANTING_FSS_TABLE_ID, VIEW_PLANTING_INFORMAL_TABLE_ID, "back");
         });






//HARVESTING PART RAINFED

    function ViewHarvesting(year, month, range_date) {
        $.ajax({
            url: 'Function/DATARainfedViewHarvesting.php',  // PHP script to fetch data
            type: 'POST',
            data: {
                year: year,
                month: month,
                range_date: range_date
            },
            success: function(response) {
                var data = JSON.parse(response);

                const { monthName, seasonType } = getMonthAndSeason(data.INFO.month, data.INFO.season_type);
                
                $('#season_type_harvesting').html(seasonType);
                $('#yearAndRangeDate_harvesting').html(monthName + ' ' + data.INFO.range_date + ' ' + data.INFO.year);

                // Clear existing rows
                $('#VIEW_HARVESTING_FORMAL_NPR_TABLE tbody').empty();
                $('#VIEW_HARVESTING_FORMAL_RCEF_TABLE tbody').empty();
                $('#VIEW_HARVESTING_FORMAL_OWNOTHERS_TABLE tbody').empty();
                $('#VIEW_HARVESTING_INFORMAL_TABLE tbody').empty();
                $('#VIEW_HARVESTING_FSS_TABLE tbody').empty();



                if (data.NPR && data.NPR.length > 0){
                    data.NPR.forEach(function(row, index) {
                        var rowHtml = "<tr>";
                            rowHtml += "<td class='first_td'><input type='text' data-field='FROMAL_NPR_Barangay' data-index='" + index + "' value='" + row.FROMAL_NPR_Barangay + "' step='any' readonly></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Hybrid_Area_Harvested' data-index='" + index + "' value='" + row.Hybrid_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Hybrid_Average_Yield' data-index='" + index + "' value='" + row.Hybrid_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Hybrid_Production' data-index='" + index + "' value='" + row.Hybrid_Production + "' step='any' placeholder='Production'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Registered_Area_Harvested' data-index='" + index + "' value='" + row.Registered_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Registered_Average_Yield' data-index='" + index + "' value='" + row.Registered_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Registered_Production' data-index='" + index + "' value='" + row.Registered_Production + "' step='any' placeholder='Production'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Certified_Area_Harvested' data-index='" + index + "' value='" + row.Certified_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly readonly min='0' data-field='Certified_Average_Yield' data-index='" + index + "' value='" + row.Certified_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Certified_Production' data-index='" + index + "' value='" + row.Certified_Production + "' step='any' placeholder='Production'></td>";
                        rowHtml += "</tr>";
    
                        // Append the new row to the table
                        $('#VIEW_HARVESTING_FORMAL_NPR_TABLE tbody').append(rowHtml);
                    });
                }else{
                    $('#VIEW_HARVESTING_FORMAL_NPR_TABLE tbody').append('<tr><td colspan="10" class="text-center">No Harvested</td></tr>');
                }

                if (data.RCEF && data.RCEF.length > 0){
                    data.RCEF.forEach(function(row, index) {
                        var rowHtml = "<tr>";
                            rowHtml += "<td class='first_td'><input type='text' data-field='FROMAL_RCEF_Barangay' data-index='" + index + "' value='" + row.FROMAL_RCEF_Barangay + "' step='any' readonly></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Hybrid_Area_Harvested' data-index='" + index + "' value='" + row.Hybrid_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Hybrid_Average_Yield' data-index='" + index + "' value='" + row.Hybrid_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Hybrid_Production' data-index='" + index + "' value='" + row.Hybrid_Production + "' step='any' placeholder='Production'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Registered_Area_Harvested' data-index='" + index + "' value='" + row.Registered_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Registered_Average_Yield' data-index='" + index + "' value='" + row.Registered_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Registered_Production' data-index='" + index + "' value='" + row.Registered_Production + "' step='any' placeholder='Production'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Certified_Area_Harvested' data-index='" + index + "' value='" + row.Certified_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Certified_Average_Yield' data-index='" + index + "' value='" + row.Certified_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Certified_Production' data-index='" + index + "' value='" + row.Certified_Production + "' step='any' placeholder='Production'></td>";
                        rowHtml += "</tr>";
    
                        // Append the new row to the table
                        $('#VIEW_HARVESTING_FORMAL_RCEF_TABLE tbody').append(rowHtml);
                    });
                }else{
                    $('#VIEW_HARVESTING_FORMAL_RCEF_TABLE tbody').append('<tr><td colspan="10" class="text-center">No Harvested</td></tr>');
                }

                if (data.OWNOTHERS && data.OWNOTHERS.length > 0){
                    data.OWNOTHERS.forEach(function(row, index) {
                        var rowHtml = "<tr>";
                            rowHtml += "<td class='first_td'><input type='text' data-field='FROMAL_OWNOTHERS_Barangay' data-index='" + index + "' value='" + row.FROMAL_OWNOTHERS_Barangay + "' step='any' readonly></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Hybrid_Area_Harvested' data-index='" + index + "' value='" + row.Hybrid_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Hybrid_Average_Yield' data-index='" + index + "' value='" + row.Hybrid_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Hybrid_Production' data-index='" + index + "' value='" + row.Hybrid_Production + "' step='any' placeholder='Production'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Registered_Area_Harvested' data-index='" + index + "' value='" + row.Registered_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Registered_Average_Yield' data-index='" + index + "' value='" + row.Registered_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Registered_Production' data-index='" + index + "' value='" + row.Registered_Production + "' step='any' placeholder='Production'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Certified_Area_Harvested' data-index='" + index + "' value='" + row.Certified_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Certified_Average_Yield' data-index='" + index + "' value='" + row.Certified_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Certified_Production' data-index='" + index + "' value='" + row.Certified_Production + "' step='any' placeholder='Production'></td>";
                        rowHtml += "</tr>";
    
                        // Append the new row to the table
                        $('#VIEW_HARVESTING_FORMAL_OWNOTHERS_TABLE tbody').append(rowHtml);
                    });
                }else{
                    $('#VIEW_HARVESTING_FORMAL_OWNOTHERS_TABLE tbody').append('<tr><td colspan="10" class="text-center">No Harvested</td></tr>');
                }

                if (data.INFORMAL && data.INFORMAL.length > 0){
                    data.INFORMAL.forEach(function(row, index) {
                        var rowHtml = "<tr>";
                            rowHtml += "<td class='first_td'><input type='text' data-field='INFROMAL_Barangay' data-index='" + index + "' value='" + row.INFROMAL_Barangay + "' step='any' readonly></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Starter_Area_Harvested' data-index='" + index + "' value='" + row.Starter_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Starter_Average_Yield' data-index='" + index + "' value='" + row.Starter_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Starter_Production' data-index='" + index + "' value='" + row.Starter_Production + "' step='any' placeholder='Production'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Tagged_Area_Harvested' data-index='" + index + "' value='" + row.Tagged_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Tagged_Average_Yield' data-index='" + index + "' value='" + row.Tagged_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Tagged_Production' data-index='" + index + "' value='" + row.Tagged_Production + "' step='any' placeholder='Production'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Traditional_Area_Harvested' data-index='" + index + "' value='" + row.Traditional_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Traditional_Average_Yield' data-index='" + index + "' value='" + row.Traditional_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Traditional_Production' data-index='" + index + "' value='" + row.Traditional_Production + "' step='any' placeholder='Production'></td>";
                        rowHtml += "</tr>";
    
                        // Append the new row to the table
                        $('#VIEW_HARVESTING_INFORMAL_TABLE tbody').append(rowHtml);
                    });
    
                }else{
                    $('#VIEW_HARVESTING_INFORMAL_TABLE tbody').append('<tr><td colspan="10" class="text-center">No Harvested</td></tr>');
                }

                if (data.FSS && data.FSS.length > 0){
                    data.FSS.forEach(function(row, index) {
                        var rowHtml = "<tr>";
                            rowHtml += "<td class='first_td'><input type='text' data-field='FSS_Barangay' data-index='" + index + "' value='" + row.FSS_Barangay + "' step='any' readonly></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='FSS_Area_Harvested' data-index='" + index + "' value='" + row.FSS_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='FSS_Average_Yield' data-index='" + index + "' value='" + row.FSS_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='FSS_Production' data-index='" + index + "' value='" + row.FSS_Production + "' step='any' placeholder='Production'></td>";
                        rowHtml += "</tr>";
    
                        // Append the new row to the table
                        $('#VIEW_HARVESTING_FSS_TABLE tbody').append(rowHtml);
                    });
                }else{
                    $('#VIEW_HARVESTING_FSS_TABLE tbody').append('<tr><td colspan="4" class="text-center">No Harvested</td></tr>');
                }




                // Show the modal after data has been loaded and processed
                $('#viewModalHarvesting').modal('show');
            },
            error: function() {
                alert('Failed to load data for viewing');
            }
        });
   }


// GET ALL TABLE BUTTON ID - VIEW
    const VIEW_HARVESTING_FORMAL_NPR_TABLE_ID = document.getElementById("VIEW_HARVESTING_FORMAL_NPR_TABLE");
    const VIEW_HARVESTING_FORMAL_RCEF_TABLE_ID = document.getElementById("VIEW_HARVESTING_FORMAL_RCEF_TABLE");
    const VIEW_HARVESTING_FORMAL_OWNOTHERS_TABLE_ID = document.getElementById("VIEW_HARVESTING_FORMAL_OWNOTHERS_TABLE");
    const VIEW_HARVESTING_INFORMAL_TABLE_ID = document.getElementById("VIEW_HARVESTING_INFORMAL_TABLE");
    const VIEW_HARVESTING_FSS_TABLE_ID = document.getElementById("VIEW_HARVESTING_FSS_TABLE");

    // GET ALL TABLE NEXT BUTTON ID - VIEW
    const View_HarvestingNextTableRCEF = document.getElementById("View_HarvestingNextTableRCEF");
    const View_HarvestingNextTableOWNOTHRES = document.getElementById("View_HarvestingNextTableOWNOTHERS");
    const View_HarvestingNextTableINFORMAL = document.getElementById("View_HarvestingNextTableINFORMAL");
    const View_HarvestingNextTableFSS = document.getElementById("View_HarvestingNextTableFSS");

    // GET ALL TABLE BACK BUTTON ID - VIEW
    const View_HarvestingBackToNPR = document.getElementById("View_HarvestingBackToNPR");
    const View_HarvestingBackToRCEF = document.getElementById("View_HarvestingBackToRCEF");
    const View_HarvestingBackToOWNOTHERS = document.getElementById("View_HarvestingBackToOWNOTHERS");
    const View_HarvestingBackToINFORMAL = document.getElementById("View_HarvestingBackToINFORMAL");


        //NEXT CLICK TRANSITION - OK
        View_HarvestingNextTableRCEF.addEventListener("click", function() {
            transitionTables(VIEW_HARVESTING_FORMAL_NPR_TABLE_ID, VIEW_HARVESTING_FORMAL_RCEF_TABLE_ID, "next");
        });
        View_HarvestingNextTableOWNOTHRES.addEventListener("click", function() {
            transitionTables(VIEW_HARVESTING_FORMAL_RCEF_TABLE_ID, VIEW_HARVESTING_FORMAL_OWNOTHERS_TABLE_ID, "next");
        });
        View_HarvestingNextTableINFORMAL.addEventListener("click", function() {
            transitionTables(VIEW_HARVESTING_FORMAL_OWNOTHERS_TABLE_ID, VIEW_HARVESTING_INFORMAL_TABLE_ID, "next");
        });
        View_HarvestingNextTableFSS.addEventListener("click", function() {
            transitionTables(VIEW_HARVESTING_INFORMAL_TABLE_ID, VIEW_HARVESTING_FSS_TABLE_ID, "next");
        });
    
        //BACK CLICK TRANSITION - OK
        View_HarvestingBackToNPR.addEventListener("click", function() {
            transitionTables(VIEW_HARVESTING_FORMAL_RCEF_TABLE_ID, VIEW_HARVESTING_FORMAL_NPR_TABLE_ID, "back");
        });
        View_HarvestingBackToRCEF.addEventListener("click", function() {
            transitionTables(VIEW_HARVESTING_FORMAL_OWNOTHERS_TABLE_ID, VIEW_HARVESTING_FORMAL_RCEF_TABLE_ID, "back");
        });
        View_HarvestingBackToOWNOTHERS.addEventListener("click", function() {
            transitionTables(VIEW_HARVESTING_INFORMAL_TABLE_ID, VIEW_HARVESTING_FORMAL_OWNOTHERS_TABLE_ID, "back");
        });
        View_HarvestingBackToINFORMAL.addEventListener("click", function() {
            transitionTables(VIEW_HARVESTING_FSS_TABLE_ID, VIEW_HARVESTING_INFORMAL_TABLE_ID, "back");
        });


}


$(document).on('submit', '#exportrainfed', function (e) {
    e.preventDefault();

    var ExportRainfedForm = new FormData(this);

    $.ajax({
        type: "POST",
        url: "Function/checkexist_rainfed.php",  // URL of the PHP script that processes the form data
        data: ExportRainfedForm,
        processData: false,
        contentType: false,
        success: function (response) {
            var res = JSON.parse(response);

            if (res.status == 'SUCCESS') {
               $('#btnexportRainfed').prop('disabled', true).text('Downloading...');
               $('#monthexportRainfed').prop('disabled', true);
               $('#rangeDateExportRainfed').prop('disabled', true);
               $('#yearExportRainfed').prop('disabled', true);
               $('#mayorsnameexportRainfed').prop('disabled', true);
               window.location.href = res.redirect_url;

               setTimeout(function() {
                    location.reload();
                    $('#btnexportRainfed').prop('disabled', false).text('Submit');
                    $('#monthexportRainfed').prop('disabled', false);
                    $('#rangeDateExportRainfed').prop('disabled', false);
                    $('#yearExportRainfed').prop('disabled', false);
                    $('#mayorsnameexportRainfed').prop('disabled', false);

                   $('#exportmodalRainfed').modal('hide');
                   $('#exportrainfed')[0].reset();
               }, 5000);  // 3-second delay (3000 milliseconds)

            
            } else if (res.status == 'ERROR') {
                alert('No Data Found.');
            } else {
                alert('ERROR PHP');
            }
        },
        error: function(xhr, status, error) {
            alert("Error checking existence: " + error);
        }
    });
});

