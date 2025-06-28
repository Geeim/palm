
if (!window.barangays) {

    // Function to reset a form
    function resetform(formid) {
        const form = document.getElementById(formid);
        form.reset();
    }

    const barangays = {
        '1': 'Bucal I',
        '2': 'Bucal II',
        '3': 'Bucal III A & B',
        '4': 'Bucal IV A & B',
        '5': 'Caingin Poblacion',
        '6': 'Garita A & B',
        '7': 'Layong Mabilog',
        '8': 'Mabato',
        '9': 'Pantihan I',
        '10': 'Pantihan II',
        '11': 'Pantihan III',
        '12': 'Pantihan IV',
        '13': 'Patungan',
        '14': 'Pinagsanhan I A',
        '15': 'Pinagsanhan I B',
        '16': 'Poblacion I A',
        '17': 'Poblacion I B',
        '18': 'Poblacion II A',
        '19': 'Poblacion II B',
        '20': 'San Miguel I A & B',
        '21': 'Talipusngo',
        '22': 'Tulay Kanluran',
        '23': 'Tulay Silangan'
    };

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


    // GET AVERAGE YIELD - FORMAL NPR
    function calculateYield(row) {
        const areaHarvestedInputs = row.querySelectorAll('input[placeholder="Area Harvested"]');
        const productionInputs = row.querySelectorAll('input[placeholder="Production"]');
        const averageYieldInputs = row.querySelectorAll('input[placeholder="Average Yield"]');
    
        areaHarvestedInputs.forEach((areaHarvestedInput, index) => {
            const areaHarvested = parseFloat(areaHarvestedInput.value) || 0;
            const production = parseFloat(productionInputs[index].value) || 0;
    
            // Calculation
            const averageYield = areaHarvested > 0 ? (production / areaHarvested).toFixed(2) : 0;
    
            // Update the corresponding average yield input
            averageYieldInputs[index].value = averageYield;
        });
    }



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





// MODAL SET UP

    const SetUpForm = document.getElementById('SetUpForm');
    const cancelInserting = document.getElementById('cancelInserting');

    let selectedseason;
    let rangeDate;
    let yearInsert;
    let selectedType;
    let monthpick;

    SetUpForm.addEventListener('submit', function(event) {
        event.preventDefault();

        selectedseason = document.getElementById('SeasonType').value;
        rangeDate = document.getElementById('rangeDate').value;
        yearInsert = document.getElementById('yearInsert').value;
        selectedType = document.getElementById('InsertType').value;
        monthpick = document.getElementById('monthpick').value;

        const Pseason = document.getElementById('P-season');
        const Pperiod = document.getElementById('P-period');
        const Hseason = document.getElementById('H-season');
        const Hperiod = document.getElementById('H-period');


        if (this.checkValidity()) {

            $.ajax({
                type: 'POST',
                url: 'Function/DRCheckSetUpModalRainfed.php',
                data: { 
                    yearInsert: yearInsert, 
                    monthpick: monthpick,
                    rangeDate: rangeDate,
                    selectedType: selectedType},
                success: function(response) {
                    var res = jQuery.parseJSON(response);

                    if(res.status == 'SUCCESS'){
                        $('#SetUpModalHP').modal('hide'); // Hide the setup modal

                        const seasonName = season[selectedseason] || 'Unknown Season'; //Convert Season to String
                        const monthName = month[monthpick] || 'Unknown Month'; //Convert Month to String
        
                        if (selectedType === "Planting") {
                            $('#InsertModalPlanting').modal('show'); // Show planting modal
                            Pseason.innerText = `${seasonName.toUpperCase()}`;
                            Pperiod.innerText = `${monthName.toUpperCase()} ${rangeDate} ${yearInsert}`;
                        } else if (selectedType === "Harvesting") {
                            $('#InsertModalHarvesting').modal('show'); // Show harvesting modal
                            Hseason.innerText = `${seasonName.toUpperCase()}`;
                            Hperiod.innerText = `${monthName.toUpperCase()} ${rangeDate} ${yearInsert}`;
                        } 
                    }else if(res.status == 'EXISTS'){
                        alert(res.message);
                    }else if(res.status == 'NOPICKED'){
                        alert(res.message);
                    }else{
                        alert("WALANG NAKUHANG DATA SA PHP");
                    }

                }, error: function() {
                    alert("An error occurred while checking the database.");
                }
            });


        } else {
            this.classList.add('was-validated'); 
        }
    });


    cancelInserting.addEventListener('click', function() {
        $('#SetUpModalHP').modal('hide');
        resetform('SetUpForm');
        SetUpForm.classList.remove('was-validated');
    });





// PLANTING & HARVESTING - OK
    const HarvestingBackToSetUp = document.getElementById('HarvestingBackToSetUp');
    const PlantingBackToSetUp = document.getElementById('PlantingBackToSetUp');
    const cancelHarvestingInsert = document.getElementById('cancelHarvestingInsert');
    const cancelPlantingInsert = document.getElementById('cancelPlantingInsert');

    function handleCancel(event) {
        if (confirm("Are you sure you want to cancel? All inputted data will be lost.")) {
            location.reload();
        } else {
            event.preventDefault();
        }
    }

    function setUpBackButton(currentModalId) {
        $(currentModalId).modal('hide');
        $('#SetUpModalHP').modal('show');
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


//HARVESTING MODAL - OK

    //Cancel Inserting Harvesting
    cancelHarvestingInsert.addEventListener('click', handleCancel);
        
    // Back to Set Up
    HarvestingBackToSetUp.addEventListener('click', function() {
        setUpBackButton('#InsertModalHarvesting');
    });


    //NEXT & BACK TABLE FUNCTION

        // GET ALL HARVESTING TABLE ID
        const HARVESTING_FORMAL_NPR_TABLE_ID = document.getElementById("HARVESTING_FORMAL_NPR_TABLE");
        const HARVESTING_FORMAL_RCEF_TABLE_ID = document.getElementById("HARVESTING_FORMAL_RCEF_TABLE");
        const HARVESTING_FORMAL_OWNOTHERS_TABLE_ID = document.getElementById("HARVESTING_FORMAL_OWNOTHERS_TABLE");
        const HARVESTING_INFORMAL_TABLE_ID = document.getElementById("HARVESTING_INFORMAL_TABLE");
        const HARVESTING_FSS_TABLE_ID = document.getElementById("HARVESTING_FSS_TABLE");

        // GET ALL TABLE NEXT BUTTON ID
        const HarvestingNextTableRCEF = document.getElementById("HarvestingNextTableRCEF");
        const HarvestingNextTableOWNOTHRES = document.getElementById("HarvestingNextTableOWNOTHRES");
        const HarvestingNextTableINFORMAL = document.getElementById("HarvestingNextTableINFORMAL");
        const HarvestingNextTableFSS = document.getElementById("HarvestingNextTableFSS");

        // GET ALL TABLE BACK BUTTON ID
        const HarvestingBackToNPR = document.getElementById("HarvestingBackToNPR");
        const HarvestingBackToRCEF = document.getElementById("HarvestingBackToRCEF");
        const HarvestingBackToOWNOTHERS = document.getElementById("HarvestingBackToOWNOTHERS");
        const HarvestingBackToINFORMAL = document.getElementById("HarvestingBackToINFORMAL");

            //NEXT CLICK TRANSITION - OK
            HarvestingNextTableRCEF.addEventListener("click", function() {
                transitionTables(HARVESTING_FORMAL_NPR_TABLE_ID, HARVESTING_FORMAL_RCEF_TABLE_ID, "next");
            });
            HarvestingNextTableOWNOTHRES.addEventListener("click", function() {
                transitionTables(HARVESTING_FORMAL_RCEF_TABLE_ID, HARVESTING_FORMAL_OWNOTHERS_TABLE_ID, "next");
            });
            HarvestingNextTableINFORMAL.addEventListener("click", function() {
                transitionTables(HARVESTING_FORMAL_OWNOTHERS_TABLE_ID, HARVESTING_INFORMAL_TABLE_ID, "next");
            });
            HarvestingNextTableFSS.addEventListener("click", function() {
                transitionTables(HARVESTING_INFORMAL_TABLE_ID, HARVESTING_FSS_TABLE_ID, "next");
            })

            //NEXT CLICK TRANSITION
            HarvestingBackToNPR.addEventListener("click", function() {
                transitionTables(HARVESTING_FORMAL_RCEF_TABLE_ID, HARVESTING_FORMAL_NPR_TABLE_ID, "back");
            });
        
            HarvestingBackToRCEF.addEventListener("click", function() {
                transitionTables(HARVESTING_FORMAL_OWNOTHERS_TABLE_ID, HARVESTING_FORMAL_RCEF_TABLE_ID, "back");
            });
        
            HarvestingBackToOWNOTHERS.addEventListener("click", function() {
                transitionTables(HARVESTING_INFORMAL_TABLE_ID, HARVESTING_FORMAL_OWNOTHERS_TABLE_ID, "back");
            });
        
            HarvestingBackToINFORMAL.addEventListener("click", function() {
                transitionTables(HARVESTING_FSS_TABLE_ID, HARVESTING_INFORMAL_TABLE_ID, "back");
            });


         //HARVESTING FORMAL NPR - ADD & DELETE BARANGAY SELECTION
         const Harvesting_FORMAL_NPR_selection = document.getElementById('Harvesting_FORMAL_NPR_selection');
         const HARVESTING_FORMAL_NPR_TABLE_TBODY = HARVESTING_FORMAL_NPR_TABLE_ID.getElementsByTagName('tbody')[0];
         const Harvesting_FORMAL_NPR_addedBarangaysContainer = new Set();

         function Harvesting_AddBarangayFORMAL_NPR() {
            const HARVESTING_FORMAL_NPR_selectedvalue = Harvesting_FORMAL_NPR_selection.value; // Get Value of Barangay
            const barangayName = barangays[HARVESTING_FORMAL_NPR_selectedvalue] || 'Select Barangay';

            if (HARVESTING_FORMAL_NPR_selectedvalue && !Harvesting_FORMAL_NPR_addedBarangaysContainer.has(HARVESTING_FORMAL_NPR_selectedvalue)) {
                const row = HARVESTING_FORMAL_NPR_TABLE_TBODY.insertRow(0); // Insert at the top

                row.innerHTML = `
                    <td> 
                        <i class="bi bi-x-circle-fill text-danger me-2" onclick="Harvesting_DeleteBarangayNPR(this)"></i>
                        <span>${barangayName}</span>
                    </td>
    
                    <td><input type="number" name="${HARVESTING_FORMAL_NPR_selectedvalue}_AH_NPR_H" min='0' value="0" placeholder="Area Harvested"></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_NPR_selectedvalue}_AY_NPR_H" min='0' value="0" placeholder="Average Yield" readonly></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_NPR_selectedvalue}_P_NPR_H" min='0' value="0" placeholder="Production"></td>
    
                    <td><input type="number" name="${HARVESTING_FORMAL_NPR_selectedvalue}_AH_NPR_R" min='0' value="0" placeholder="Area Harvested"></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_NPR_selectedvalue}_AY_NPR_R" min='0' value="0" placeholder="Average Yield" readonly></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_NPR_selectedvalue}_P_NPR_R" min='0' value="0" placeholder="Production"></td>
                                
                    <td><input type="number" name="${HARVESTING_FORMAL_NPR_selectedvalue}_AH_NPR_C" min='0' value="0" placeholder="Area Harvested"></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_NPR_selectedvalue}_AY_NPR_C" min='0' value="0" placeholder="Average Yield" readonly></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_NPR_selectedvalue}_P_NPR_C" min='0' value="0" placeholder="Production"></td>
                `;

                    // Attach event listeners to each relevant input
                    const areaHarvestedInputs = row.querySelectorAll('input[placeholder="Area Harvested"]');
                    const productionInputs = row.querySelectorAll('input[placeholder="Production"]');
                    
                    [...areaHarvestedInputs, ...productionInputs].forEach(input => {
                        input.addEventListener('input', () => {
                            calculateYield(row);
                        });
                    });

                Harvesting_FORMAL_NPR_addedBarangaysContainer.add(HARVESTING_FORMAL_NPR_selectedvalue); // Add to Container
                Harvesting_FORMAL_NPR_selection.options[Harvesting_FORMAL_NPR_selection.selectedIndex].disabled = true; // Disable selected barangay
                Harvesting_FORMAL_NPR_selection.selectedIndex = 0; // Reset selection     

            }
        }


        function Harvesting_DeleteBarangayNPR(button) {
            const row = button.closest('tr');
            const barangayName = row.cells[0].querySelector('span').innerText; // Kuhain ang Span sa 1st TD
            const Harvesting_FORMAL_NPR_barangayoptions = Harvesting_FORMAL_NPR_selection.options;

            row.parentNode.removeChild(row); // Delete Row

            // Get the barangay ID based on the name
            let barangayId;
            for (let [id, name] of Object.entries(barangays)) { // Get Laman ng Barangays - Array of Barangay
                if (name === barangayName) {
                    barangayId = id; // Get the Matches ID
                    break;
                }
            }

            Harvesting_FORMAL_NPR_barangayoptions[barangayId].disabled = false; // Enable Ulit ang Barangay
            Harvesting_FORMAL_NPR_addedBarangaysContainer.delete(barangayId); // Delete to be Available ulit
        }




    //HARVTESING FORMAL RCEF - ADD & DELETE BARANGAY SELECTION
        const Harvesting_FORMAL_RCEF_selection = document.getElementById('HARVESTING_FORMAL_RCEF_selection');
        const HARVESTING_FORMAL_RCEF_TABLE_TBODY = HARVESTING_FORMAL_RCEF_TABLE_ID.getElementsByTagName('tbody')[0];
        const Harvesting_INFORMAL_RCEF_addedBarangaysContainer = new Set();
        
        function Harvesting_AddBarangayFORMAL_RCEF() {
            const HARVESTING_FORMAL_RCEF_selectedvalue = Harvesting_FORMAL_RCEF_selection.value; // Get Value of Barangay
            const barangayName = barangays[HARVESTING_FORMAL_RCEF_selectedvalue] || 'Select Barangay';
        
            if (HARVESTING_FORMAL_RCEF_selectedvalue && !Harvesting_INFORMAL_RCEF_addedBarangaysContainer.has(HARVESTING_FORMAL_RCEF_selectedvalue)) {
                const row = HARVESTING_FORMAL_RCEF_TABLE_TBODY.insertRow(0); // Insert at the top
                
                row.innerHTML = `
                    <td> 
                        <i class="bi bi-x-circle-fill text-danger me-2" onclick="Harvesting_DeleteBarangayRCEF(this)"></i>
                        <span>${barangayName}</span>
                    </td>
    
                    <td><input type="number" name="${HARVESTING_FORMAL_RCEF_selectedvalue}_AH_RCEF_H" min='0' value="0" placeholder="Area Harvested"></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_RCEF_selectedvalue}_AY_RCEF_H" min='0' value="0" placeholder="Average Yield" readonly></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_RCEF_selectedvalue}_P_RCEF_H" min='0' value="0" placeholder="Production"></td>
    
                    <td><input type="number" name="${HARVESTING_FORMAL_RCEF_selectedvalue}_AH_RCEF_R" min='0' value="0" placeholder="Area Harvested"></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_RCEF_selectedvalue}_AY_RCEF_R" min='0' value="0" placeholder="Average Yield" readonly></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_RCEF_selectedvalue}_P_RCEF_R" min='0' value="0" placeholder="Production"></td>
                                
                    <td><input type="number" name="${HARVESTING_FORMAL_RCEF_selectedvalue}_AH_RCEF_C" min='0' value="0" placeholder="Area Harvested"></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_RCEF_selectedvalue}_AY_RCEF_C" min='0' value="0" placeholder="Average Yield" readonly></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_RCEF_selectedvalue}_P_RCEF_C" min='0' value="0" placeholder="Production"></td>
                `;

                    // Attach event listeners to each relevant input
                    const areaHarvestedInputs = row.querySelectorAll('input[placeholder="Area Harvested"]');
                    const productionInputs = row.querySelectorAll('input[placeholder="Production"]');
                    
                    [...areaHarvestedInputs, ...productionInputs].forEach(input => {
                        input.addEventListener('input', () => {
                            calculateYield(row);
                        });
                    });

                Harvesting_INFORMAL_RCEF_addedBarangaysContainer.add(HARVESTING_FORMAL_RCEF_selectedvalue); // Add to Container
                Harvesting_FORMAL_RCEF_selection.options[Harvesting_FORMAL_RCEF_selection.selectedIndex].disabled = true; // Disable selected barangay
                Harvesting_FORMAL_RCEF_selection.selectedIndex = 0; // Reset selection     

            }
        }


        function Harvesting_DeleteBarangayRCEF(button) {
            const row = button.closest('tr');
            const barangayName = row.cells[0].querySelector('span').innerText; // Kuhain ang Span sa 1st TD
            const Harvesting_FORMAL_RCEF_barangayoptions = Harvesting_FORMAL_RCEF_selection.options;

            row.parentNode.removeChild(row); // Delete Row

            // Get the barangay ID based on the name
            let barangayId;
            for (let [id, name] of Object.entries(barangays)) { // Get Laman ng Barangays - Array of Barangay
                if (name === barangayName) {
                    barangayId = id; // Get the Matches ID
                    break;
                }
            }

            Harvesting_FORMAL_RCEF_barangayoptions[barangayId].disabled = false; // Enable Ulit ang Barangay
            Harvesting_INFORMAL_RCEF_addedBarangaysContainer.delete(barangayId); // Delete to be Available ulit
        }




    //HARVTESING FORMAL OWNOTHERS - ADD & DELETE BARANGAY SELECTION
        const Harvesting_FORMAL_OWNOTHERS_selection = document.getElementById('HARVESTING_FORMAL_OWNOTHERS_selection');
        const HARVESTING_FORMAL_OWNOTHERS_TABLE_TBODY = HARVESTING_FORMAL_OWNOTHERS_TABLE_ID.getElementsByTagName('tbody')[0];
        const Harvesting_FORMAL_OWNOTHERS_addedBarangaysContainer = new Set();

        function Harvesting_AddBarangayFORMAL_OWNOTHERS(){
            const HARVESTING_FORMAL_OWNOTHERS_selectedvalue = Harvesting_FORMAL_OWNOTHERS_selection.value; // Get Value of Barangay
            const barangayName = barangays[HARVESTING_FORMAL_OWNOTHERS_selectedvalue] || 'Select Barangay';
            
            if (HARVESTING_FORMAL_OWNOTHERS_selectedvalue && !Harvesting_FORMAL_OWNOTHERS_addedBarangaysContainer.has(HARVESTING_FORMAL_OWNOTHERS_selectedvalue)) {
                const row = HARVESTING_FORMAL_OWNOTHERS_TABLE_TBODY.insertRow(0); // Insert at the top
            
                row.innerHTML = `
                    <td> 
                        <i class="bi bi-x-circle-fill text-danger me-2" onclick="Harvesting_DeleteBarangayOWNOTHERS(this)"></i>
                        <span>${barangayName}</span>
                    </td>
    
                    <td><input type="number" name="${HARVESTING_FORMAL_OWNOTHERS_selectedvalue}_AH_OWNOTHERS_H" min='0' value="0" placeholder="Area Harvested"></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_OWNOTHERS_selectedvalue}_AY_OWNOTHERS_H" min='0' value="0" placeholder="Average Yield" readonly></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_OWNOTHERS_selectedvalue}_P_OWNOTHERS_H" min='0' value="0" placeholder="Production"></td>
    
                    <td><input type="number" name="${HARVESTING_FORMAL_OWNOTHERS_selectedvalue}_AH_OWNOTHERS_R" min='0' value="0" placeholder="Area Harvested"></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_OWNOTHERS_selectedvalue}_AY_OWNOTHERS_R" min='0' value="0" placeholder="Average Yield" readonly></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_OWNOTHERS_selectedvalue}_P_OWNOTHERS_R" min='0' value="0" placeholder="Production"></td>
                                
                    <td><input type="number" name="${HARVESTING_FORMAL_OWNOTHERS_selectedvalue}_AH_OWNOTHERS_C" min='0' value="0" placeholder="Area Harvested"></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_OWNOTHERS_selectedvalue}_AY_OWNOTHERS_C" min='0' value="0" placeholder="Average Yield" readonly></td>
                    <td><input type="number" name="${HARVESTING_FORMAL_OWNOTHERS_selectedvalue}_P_OWNOTHERS_C" min='0' value="0" placeholder="Production"></td>
                `;

                    // Attach event listeners to each relevant input
                    const areaHarvestedInputs = row.querySelectorAll('input[placeholder="Area Harvested"]');
                    const productionInputs = row.querySelectorAll('input[placeholder="Production"]');
                    
                    [...areaHarvestedInputs, ...productionInputs].forEach(input => {
                        input.addEventListener('input', () => {
                            calculateYield(row);
                        });
                    });

                Harvesting_FORMAL_OWNOTHERS_addedBarangaysContainer.add(HARVESTING_FORMAL_OWNOTHERS_selectedvalue); // Add to Container
                Harvesting_FORMAL_OWNOTHERS_selection.options[Harvesting_FORMAL_OWNOTHERS_selection.selectedIndex].disabled = true; // Disable selected barangay
                Harvesting_FORMAL_OWNOTHERS_selection.selectedIndex = 0; // Reset selection  
            }
        }


        function Harvesting_DeleteBarangayOWNOTHERS(button) {
            const row = button.closest('tr');
            const barangayName = row.cells[0].querySelector('span').innerText; // Kuhain ang Span sa 1st TD
            const Harvesting_FORMAL_OWNOTHERS_barangayoptions = Harvesting_FORMAL_OWNOTHERS_selection.options;

            row.parentNode.removeChild(row); // Delete Row

            // Get the barangay ID based on the name
            let barangayId;
            for (let [id, name] of Object.entries(barangays)) { // Get Laman ng Barangays - Array of Barangay
                if (name === barangayName) {
                    barangayId = id; // Get the Matches ID
                    break;
                }
            }

            Harvesting_FORMAL_OWNOTHERS_barangayoptions[barangayId].disabled = false; // Enable Ulit ang Barangay
            Harvesting_FORMAL_OWNOTHERS_addedBarangaysContainer.delete(barangayId); // Delete to be Available ulit
        }



        
    //HARVTESING INFORMAL - ADD & DELETE BARANGAY SELECTION
        const Harvesting_INFORMAL_selection = document.getElementById('HARVESTING_INFORMAL_selection');
        const HARVESTING_INFORMAL_TABLE_TBODY = HARVESTING_INFORMAL_TABLE_ID.getElementsByTagName('tbody')[0];
        const Harvesting_INFORMAL_addedBarangaysContainer = new Set();

        function Harvesting_AddBarangayINFORMAL(){
            const HARVESTING_INFORMAL_selectedvalue = Harvesting_INFORMAL_selection.value; // Get Value of Barangay
            const barangayName = barangays[HARVESTING_INFORMAL_selectedvalue] || 'Select Barangay';
        
            if (HARVESTING_INFORMAL_selectedvalue && !Harvesting_INFORMAL_addedBarangaysContainer.has(HARVESTING_INFORMAL_selectedvalue)) {
                const row = HARVESTING_INFORMAL_TABLE_TBODY.insertRow(0); // Insert at the top

                row.innerHTML = `
                    <td> 
                        <i class="bi bi-x-circle-fill text-danger me-2" onclick="Harvesting_DeleteBarangayINFORMAL(this)"></i>
                        <span>${barangayName}</span>
                    </td>
    
                    <td><input type="number" name="${HARVESTING_INFORMAL_selectedvalue}_AH_INFORMAL_Starter" min='0' value="0" placeholder="Area Harvested"></td>
                    <td><input type="number" name="${HARVESTING_INFORMAL_selectedvalue}_AY_INFORMAL_Starter" min='0' value="0" placeholder="Average Yield" readonly></td>
                    <td><input type="number" name="${HARVESTING_INFORMAL_selectedvalue}_P_INFORMAL_Starter" min='0' value="0" placeholder="Production"></td>
    
                    <td><input type="number" name="${HARVESTING_INFORMAL_selectedvalue}_AH_INFORMAL_Tagged " min='0' value="0" placeholder="Area Harvested"></td>
                    <td><input type="number" name="${HARVESTING_INFORMAL_selectedvalue}_AY_INFORMAL_Tagged " min='0' value="0" placeholder="Average Yield" readonly></td>
                    <td><input type="number" name="${HARVESTING_INFORMAL_selectedvalue}_P_INFORMAL_Tagged " min='0' value="0" placeholder="Production"></td>
                                
                    <td><input type="number" name="${HARVESTING_INFORMAL_selectedvalue}_AH_INFORMAL_Traditional" min='0' value="0" placeholder="Area Harvested"></td>
                    <td><input type="number" name="${HARVESTING_INFORMAL_selectedvalue}_AY_INFORMAL_Traditional" min='0' value="0" placeholder="Average Yield" readonly></td>
                    <td><input type="number" name="${HARVESTING_INFORMAL_selectedvalue}_P_INFORMAL_Traditional" min='0' value="0" placeholder="Production"></td>
                `;

                    // Attach event listeners to each relevant input
                    const areaHarvestedInputs = row.querySelectorAll('input[placeholder="Area Harvested"]');
                    const productionInputs = row.querySelectorAll('input[placeholder="Production"]');
                    
                    [...areaHarvestedInputs, ...productionInputs].forEach(input => {
                        input.addEventListener('input', () => {
                            calculateYield(row);
                        });
                    });

                Harvesting_INFORMAL_addedBarangaysContainer.add(HARVESTING_INFORMAL_selectedvalue); // Add to Container
                Harvesting_INFORMAL_selection.options[Harvesting_INFORMAL_selection.selectedIndex].disabled = true; // Disable selected barangay
                Harvesting_INFORMAL_selection.selectedIndex = 0; // Reset selection
            }
        }


        function Harvesting_DeleteBarangayINFORMAL(button) {
            const row = button.closest('tr');
            const barangayName = row.cells[0].querySelector('span').innerText; // Kuhain ang Span sa 1st TD
            const Harvesting_INFORMAL_barangayoptions = Harvesting_INFORMAL_selection.options;

            row.parentNode.removeChild(row); // Delete Row

            // Get the barangay ID based on the name
            let barangayId;
            for (let [id, name] of Object.entries(barangays)) { // Get Laman ng Barangays - Array of Barangay
                if (name === barangayName) {
                    barangayId = id; // Get the Matches ID
                    break;
                }
            }

            Harvesting_INFORMAL_barangayoptions[barangayId].disabled = false; // Enable Ulit ang Barangay
            Harvesting_INFORMAL_addedBarangaysContainer.delete(barangayId); // Delete to be Available ulit
        }


        

    //HARVTESING FSS - ADD & DELETE BARANGAY SELECTION
        const Harvesting_FSS_selection = document.getElementById('HARVESTING_FSS_selection');
        const HARVESTING_FSS_TABLE_TBODY = HARVESTING_FSS_TABLE_ID.getElementsByTagName('tbody')[0];
        const Harvesting_FSS_addedBarangaysContainer = new Set();

        function Harvesting_AddBarangayFSS(){
            const HARVESTING_FSS_selectedvalue = Harvesting_FSS_selection.value; // Get Value of Barangay
            const barangayName = barangays[HARVESTING_FSS_selectedvalue] || 'Select Barangay';
        
            if (HARVESTING_FSS_selectedvalue && !Harvesting_FSS_addedBarangaysContainer.has(HARVESTING_FSS_selectedvalue)) {
                const row = HARVESTING_FSS_TABLE_TBODY.insertRow(0); // Insert at the top

                row.innerHTML = `
                    <td> 
                        <i class="bi bi-x-circle-fill text-danger me-2" onclick="Harvesting_DeleteBarangayFSS(this)"></i>
                        <span>${barangayName}</span>
                    </td>
    
                    <td><input type="number" name="${HARVESTING_FSS_selectedvalue}_AH_FSS" min='0' value="0" placeholder="Area Harvested"></td>
                    <td><input type="number" name="${HARVESTING_FSS_selectedvalue}_AY_FSS" min='0' value="0" placeholder="Average Yield" readonly></td>
                    <td><input type="number" name="${HARVESTING_FSS_selectedvalue}_P_FSS" min='0' value="0" placeholder="Production"></td>
                `;

                    // Attach event listeners to each relevant input
                    const areaHarvestedInputs = row.querySelectorAll('input[placeholder="Area Harvested"]');
                    const productionInputs = row.querySelectorAll('input[placeholder="Production"]');
                    
                    [...areaHarvestedInputs, ...productionInputs].forEach(input => {
                        input.addEventListener('input', () => {
                            calculateYield(row);
                        });
                    });

                Harvesting_FSS_addedBarangaysContainer.add(HARVESTING_FSS_selectedvalue); // Add to Container
                Harvesting_FSS_selection.options[Harvesting_FSS_selection.selectedIndex].disabled = true; // Disable selected barangay
                Harvesting_FSS_selection.selectedIndex = 0; // Reset selection

            }
        }

        
        function Harvesting_DeleteBarangayFSS(button) {
            const row = button.closest('tr');
            const barangayName = row.cells[0].querySelector('span').innerText; // Kuhain ang Span sa 1st TD
            const Harvesting_FSS_barangayoptions = Harvesting_FSS_selection.options;

            row.parentNode.removeChild(row); // Delete Row

            // Get the barangay ID based on the name
            let barangayId;
            for (let [id, name] of Object.entries(barangays)) { // Get Laman ng Barangays - Array of Barangay
                if (name === barangayName) {
                    barangayId = id; // Get the Matches ID
                    break;
                }
            }

            Harvesting_FSS_barangayoptions[barangayId].disabled = false; // Enable Ulit ang Barangay
            Harvesting_FSS_addedBarangaysContainer.delete(barangayId); // Delete to be Available ulit
        }




//PLANTING MODAL - OK

    //Cancel Inserting Harvesting
    cancelPlantingInsert.addEventListener('click', handleCancel);
        
    // Back to Set Up
    PlantingBackToSetUp.addEventListener('click', function() {
    setUpBackButton('#InsertModalPlanting');
    });


    //NEXT & BACK TABLE FUNCTION

    // GET ALL HARVESTING TABLE ID
    const PLANTING_FORMAL_NPR_TABLE_ID = document.getElementById("PLANTING_FORMAL_NPR_TABLE");
    const PLANTING_FORMAL_RCEF_TABLE_ID = document.getElementById("PLANTING_FORMAL_RCEF_TABLE");
    const PLANTING_FORMAL_OWNOTHERS_TABLE_ID = document.getElementById("PLANTING_FORMAL_OWNOTHERS_TABLE");
    const PLANTING_INFORMAL_TABLE_ID = document.getElementById("PLANTING_INFORMAL_TABLE");
    const PLANTING_FSS_TABLE_ID = document.getElementById("PLANTING_FSS_TABLE");

    // GET ALL TABLE NEXT BUTTON ID
    const PlantingNextTableRCEF = document.getElementById("PlantingNextTableRCEF");
    const PlantingNextTableOWNOTHRES = document.getElementById("PlantingNextTableOWNOTHRES");
    const PlantingNextTableINFORMAL = document.getElementById("PlantingNextTableINFORMAL");
    const PlantingNextTableFSS = document.getElementById("PlantingNextTableFSS");

    // GET ALL TABLE BACK BUTTON ID
    const PlantingBackToNPR = document.getElementById("PlantingBackToNPR");
    const PlantingBackToRCEF = document.getElementById("PlantingBackToRCEF");
    const PlantingBackToOWNOTHERS = document.getElementById("PlantingBackToOWNOTHERS");
    const PlantingBackToINFORMAL = document.getElementById("PlantingBackToINFORMAL");


        //NEXT CLICK TRANSITION - OK
        PlantingNextTableRCEF.addEventListener("click", function() {
            transitionTables(PLANTING_FORMAL_NPR_TABLE_ID, PLANTING_FORMAL_RCEF_TABLE_ID, "next");
        });
        PlantingNextTableOWNOTHRES.addEventListener("click", function() {
            transitionTables(PLANTING_FORMAL_RCEF_TABLE_ID, PLANTING_FORMAL_OWNOTHERS_TABLE_ID, "next");
        });
        PlantingNextTableINFORMAL.addEventListener("click", function() {
            transitionTables(PLANTING_FORMAL_OWNOTHERS_TABLE_ID, PLANTING_INFORMAL_TABLE_ID, "next");
        });
        PlantingNextTableFSS.addEventListener("click", function() {
            transitionTables(PLANTING_INFORMAL_TABLE_ID, PLANTING_FSS_TABLE_ID, "next");
        })

        //NEXT CLICK TRANSITION
        PlantingBackToNPR.addEventListener("click", function() {
            transitionTables(PLANTING_FORMAL_RCEF_TABLE_ID, PLANTING_FORMAL_NPR_TABLE_ID, "back");
        });
    
        PlantingBackToRCEF.addEventListener("click", function() {
            transitionTables(PLANTING_FORMAL_OWNOTHERS_TABLE_ID, PLANTING_FORMAL_RCEF_TABLE_ID, "back");
        });
    
        PlantingBackToOWNOTHERS.addEventListener("click", function() {
            transitionTables(PLANTING_INFORMAL_TABLE_ID, PLANTING_FORMAL_OWNOTHERS_TABLE_ID, "back");
        });
    
        PlantingBackToINFORMAL.addEventListener("click", function() {
            transitionTables(PLANTING_FSS_TABLE_ID, PLANTING_INFORMAL_TABLE_ID, "back");
        });




    //PLANTING FORMAL NPR - ADD & DELETE BARANGAY SELECTION
    const Planting_FORMAL_NPR_selection = document.getElementById('Planting_FORMAL_NPR_selection');
    const PLANTING_FORMAL_NPR_TABLE_TBODY = PLANTING_FORMAL_NPR_TABLE_ID.getElementsByTagName('tbody')[0];
    const Planting_FORMAL_NPR_addedBarangaysContainer = new Set();

    function Planting_AddBarangayFORMAL_NPR() {
        const PLANTING_FORMAL_NPR_selectedvalue = Planting_FORMAL_NPR_selection.value; // Get Value of Barangay
        const barangayName = barangays[PLANTING_FORMAL_NPR_selectedvalue] || 'Select Barangay';

        if (PLANTING_FORMAL_NPR_selectedvalue && !Planting_FORMAL_NPR_addedBarangaysContainer.has(PLANTING_FORMAL_NPR_selectedvalue)) {
            const row = PLANTING_FORMAL_NPR_TABLE_TBODY.insertRow(0); // Insert at the top

            row.innerHTML = `
                <td> 
                    <i class="bi bi-x-circle-fill text-danger me-2" onclick="Planting_DeleteBarangayNPR(this)"></i>
                    <span>${barangayName}</span>
                </td>

                <td><input type="number" name="${PLANTING_FORMAL_NPR_selectedvalue}_AP_NPR_H" min='0' value="0" placeholder="Area Planted"></td>
                <td><input type="number" name="${PLANTING_FORMAL_NPR_selectedvalue}_NF_NPR_H" min='0' value="0" placeholder="No. of Farmers"></td>

                <td><input type="number" name="${PLANTING_FORMAL_NPR_selectedvalue}_AP_NPR_R" min='0' value="0" placeholder="Area Planted"></td>
                <td><input type="number" name="${PLANTING_FORMAL_NPR_selectedvalue}_NF_NPR_R" min='0' value="0" placeholder="No. of Farmers"></td>
                            
                <td><input type="number" name="${PLANTING_FORMAL_NPR_selectedvalue}_AP_NPR_C" min='0' value="0" placeholder="Area Planted"></td>
                <td><input type="number" name="${PLANTING_FORMAL_NPR_selectedvalue}_NF_NPR_C" min='0' value="0" placeholder="No. of Farmers"></td>
            `;

            Planting_FORMAL_NPR_addedBarangaysContainer.add(PLANTING_FORMAL_NPR_selectedvalue); // Add to Container
            Planting_FORMAL_NPR_selection.options[Planting_FORMAL_NPR_selection.selectedIndex].disabled = true; // Disable selected barangay
            Planting_FORMAL_NPR_selection.selectedIndex = 0; // Reset selection 
            
        }
    }


    function Planting_DeleteBarangayNPR(button) {
        const row = button.closest('tr');
        const barangayName = row.cells[0].querySelector('span').innerText; // Kuhain ang Span sa 1st TD
        const Planting_FORMAL_NPR_barangayoptions = Planting_FORMAL_NPR_selection.options;

        row.parentNode.removeChild(row); // Delete Row

        // Get the barangay ID based on the name
        let barangayId;
        for (let [id, name] of Object.entries(barangays)) { // Get Laman ng Barangays - Array of Barangay
            if (name === barangayName) {
                barangayId = id; // Get the Matches ID
                break;
            }
        }

        Planting_FORMAL_NPR_barangayoptions[barangayId].disabled = false; // Enable Ulit ang Barangay
        Planting_FORMAL_NPR_addedBarangaysContainer.delete(barangayId); // Delete to be Available ulit
    }




    //PLANTING FORMAL RCEF - ADD & DELETE BARANGAY SELECTION
    const Planting_FORMAL_RCEF_selection = document.getElementById('PLANTING_FORMAL_RCEF_selection');
    const PLANTING_FORMAL_RCEF_TABLE_TBODY = PLANTING_FORMAL_RCEF_TABLE_ID.getElementsByTagName('tbody')[0];
    const Planting_INFORMAL_RCEF_addedBarangaysContainer = new Set();

    function Planting_AddBarangayFORMAL_RCEF() {
        const PLANTING_FORMAL_RCEF_selectedvalue = Planting_FORMAL_RCEF_selection.value; // Get Value of Barangay
        const barangayName = barangays[PLANTING_FORMAL_RCEF_selectedvalue] || 'Select Barangay';

        if (PLANTING_FORMAL_RCEF_selectedvalue && !Planting_INFORMAL_RCEF_addedBarangaysContainer.has(PLANTING_FORMAL_RCEF_selectedvalue)) {
            const row = PLANTING_FORMAL_RCEF_TABLE_TBODY.insertRow(0); // Insert at the top
            
            row.innerHTML = `
                <td> 
                    <i class="bi bi-x-circle-fill text-danger me-2" onclick="Planting_DeleteBarangayRCEF(this)"></i>
                    <span>${barangayName}</span>
                </td>

                <td><input type="number" name="${PLANTING_FORMAL_RCEF_selectedvalue}_AP_RCEF_H" min='0' value="0" placeholder="Area Planted"></td>
                <td><input type="number" name="${PLANTING_FORMAL_RCEF_selectedvalue}_NF_RCEF_H" min='0' value="0" placeholder="No. of Farmers"></td>

                <td><input type="number" name="${PLANTING_FORMAL_RCEF_selectedvalue}_AP_RCEF_R" min='0' value="0" placeholder="Area Planted"></td>
                <td><input type="number" name="${PLANTING_FORMAL_RCEF_selectedvalue}_NF_RCEF_R" min='0' value="0" placeholder="No. of Farmers"></td>
                            
                <td><input type="number" name="${PLANTING_FORMAL_RCEF_selectedvalue}_AP_RCEF_C" min='0' value="0" placeholder="Area Planted"></td>
                <td><input type="number" name="${PLANTING_FORMAL_RCEF_selectedvalue}_NF_RCEF_C" min='0' value="0" placeholder="No. of Farmers"></td>
            `;

            Planting_INFORMAL_RCEF_addedBarangaysContainer.add(PLANTING_FORMAL_RCEF_selectedvalue); // Add to Container
            Planting_FORMAL_RCEF_selection.options[Planting_FORMAL_RCEF_selection.selectedIndex].disabled = true; // Disable selected barangay
            Planting_FORMAL_RCEF_selection.selectedIndex = 0; // Reset selection     

        }
    }


    function Planting_DeleteBarangayRCEF(button) {
        const row = button.closest('tr');
        const barangayName = row.cells[0].querySelector('span').innerText; // Kuhain ang Span sa 1st TD
        const Planting_FORMAL_RCEF_barangayoptions = Planting_FORMAL_RCEF_selection.options;

        row.parentNode.removeChild(row); // Delete Row

        // Get the barangay ID based on the name
        let barangayId;
        for (let [id, name] of Object.entries(barangays)) { // Get Laman ng Barangays - Array of Barangay
            if (name === barangayName) {
                barangayId = id; // Get the Matches ID
                break;
            }
        }

        Planting_FORMAL_RCEF_barangayoptions[barangayId].disabled = false; // Enable Ulit ang Barangay
        Planting_INFORMAL_RCEF_addedBarangaysContainer.delete(barangayId); // Delete to be Available ulit
    }




    //PLANTING FORMAL OWNOTHERS - ADD & DELETE BARANGAY SELECTION
    const Planting_FORMAL_OWNOTHERS_selection = document.getElementById('PLANTING_FORMAL_OWNOTHERS_selection');
    const PLANTING_FORMAL_OWNOTHERS_TABLE_TBODY = PLANTING_FORMAL_OWNOTHERS_TABLE_ID.getElementsByTagName('tbody')[0];
    const Planting_FORMAL_OWNOTHERS_addedBarangaysContainer = new Set();

    function Planting_AddBarangayFORMAL_OWNOTHERS(){
        const PLANTING_FORMAL_OWNOTHERS_selectedvalue = Planting_FORMAL_OWNOTHERS_selection.value; // Get Value of Barangay
        const barangayName = barangays[PLANTING_FORMAL_OWNOTHERS_selectedvalue] || 'Select Barangay';
        
        if (PLANTING_FORMAL_OWNOTHERS_selectedvalue && !Planting_FORMAL_OWNOTHERS_addedBarangaysContainer.has(PLANTING_FORMAL_OWNOTHERS_selectedvalue)) {
            const row = PLANTING_FORMAL_OWNOTHERS_TABLE_TBODY.insertRow(0); // Insert at the top
        
            row.innerHTML = `
                <td> 
                    <i class="bi bi-x-circle-fill text-danger me-2" onclick="Planting_DeleteBarangayOWNOTHERS(this)"></i>
                    <span>${barangayName}</span>
                </td>

                <td><input type="number" name="${PLANTING_FORMAL_OWNOTHERS_selectedvalue}_AP_OWNOTHERS_H" min='0' value="0" placeholder="Area Planted"></td>
                <td><input type="number" name="${PLANTING_FORMAL_OWNOTHERS_selectedvalue}_NF_OWNOTHERS_H" min='0' value="0" placeholder="No. of Farmers" ></td>
                
                <td><input type="number" name="${PLANTING_FORMAL_OWNOTHERS_selectedvalue}_AP_OWNOTHERS_R" min='0' value="0" placeholder="Area Planted"></td>
                <td><input type="number" name="${PLANTING_FORMAL_OWNOTHERS_selectedvalue}_NF_OWNOTHERS_R" min='0' value="0" placeholder="No. of Farmers" ></td>
                
                <td><input type="number" name="${PLANTING_FORMAL_OWNOTHERS_selectedvalue}_AP_OWNOTHERS_C" min='0' value="0" placeholder="Area Planted"></td>
                <td><input type="number" name="${PLANTING_FORMAL_OWNOTHERS_selectedvalue}_NF_OWNOTHERS_C" min='0' value="0" placeholder="No. of Farmers" ></td>
            `;

            Planting_FORMAL_OWNOTHERS_addedBarangaysContainer.add(PLANTING_FORMAL_OWNOTHERS_selectedvalue); // Add to Container
            Planting_FORMAL_OWNOTHERS_selection.options[Planting_FORMAL_OWNOTHERS_selection.selectedIndex].disabled = true; // Disable selected barangay
            Planting_FORMAL_OWNOTHERS_selection.selectedIndex = 0; // Reset selection  
        }
    }


    function Planting_DeleteBarangayOWNOTHERS(button) {
        const row = button.closest('tr');
        const barangayName = row.cells[0].querySelector('span').innerText; // Kuhain ang Span sa 1st TD
        const Planting_FORMAL_OWNOTHERS_barangayoptions = Planting_FORMAL_OWNOTHERS_selection.options;

        row.parentNode.removeChild(row); // Delete Row

        // Get the barangay ID based on the name
        let barangayId;
        for (let [id, name] of Object.entries(barangays)) { // Get Laman ng Barangays - Array of Barangay
            if (name === barangayName) {
                barangayId = id; // Get the Matches ID
                break;
            }
        }

        Planting_FORMAL_OWNOTHERS_barangayoptions[barangayId].disabled = false; // Enable Ulit ang Barangay
        Planting_FORMAL_OWNOTHERS_addedBarangaysContainer.delete(barangayId); // Delete to be Available ulit
    }




    //PLANTING INFORMAL - ADD & DELETE BARANGAY SELECTION
    const Planting_INFORMAL_selection = document.getElementById('PLANTING_INFORMAL_selection');
    const PLANTING_INFORMAL_TABLE_TBODY = PLANTING_INFORMAL_TABLE_ID.getElementsByTagName('tbody')[0];
    const Planting_INFORMAL_addedBarangaysContainer = new Set();

    function Planting_AddBarangayINFORMAL(){
        const PLANTING_INFORMAL_selectedvalue = Planting_INFORMAL_selection.value; // Get Value of Barangay
        const barangayName = barangays[PLANTING_INFORMAL_selectedvalue] || 'Select Barangay';
    
        if (PLANTING_INFORMAL_selectedvalue && !Planting_INFORMAL_addedBarangaysContainer.has(PLANTING_INFORMAL_selectedvalue)) {
            const row = PLANTING_INFORMAL_TABLE_TBODY.insertRow(0); // Insert at the top

            row.innerHTML = `
                <td> 
                    <i class="bi bi-x-circle-fill text-danger me-2" onclick="Planting_DeleteBarangayINFORMAL(this)"></i>
                    <span>${barangayName}</span>
                </td>

                <td><input type="number" name="${PLANTING_INFORMAL_selectedvalue}_AP_INFORMAL_Starter" min='0' value="0" placeholder="Area Planted"></td>
                <td><input type="number" name="${PLANTING_INFORMAL_selectedvalue}_NF_INFORMAL_Starter" min='0' value="0" placeholder="No. of Farmers"></td>

                <td><input type="number" name="${PLANTING_INFORMAL_selectedvalue}_AP_INFORMAL_Tagged " min='0' value="0" placeholder="Area Planted"></td>
                <td><input type="number" name="${PLANTING_INFORMAL_selectedvalue}_NF_INFORMAL_Tagged " min='0' value="0" placeholder="No. of Farmers"></td>
                            
                <td><input type="number" name="${PLANTING_INFORMAL_selectedvalue}_AP_INFORMAL_Traditional" min='0' value="0" placeholder="Area Planted"></td>
                <td><input type="number" name="${PLANTING_INFORMAL_selectedvalue}_NF_INFORMAL_Traditional" min='0' value="0" placeholder="No. of Farmers"></td>
            `;

            Planting_INFORMAL_addedBarangaysContainer.add(PLANTING_INFORMAL_selectedvalue); // Add to Container
            Planting_INFORMAL_selection.options[Planting_INFORMAL_selection.selectedIndex].disabled = true; // Disable selected barangay
            Planting_INFORMAL_selection.selectedIndex = 0; // Reset selection
        }
    }


    function Planting_DeleteBarangayINFORMAL(button) {
        const row = button.closest('tr');
        const barangayName = row.cells[0].querySelector('span').innerText; // Kuhain ang Span sa 1st TD
        const Planting_INFORMAL_barangayoptions = Planting_INFORMAL_selection.options;

        row.parentNode.removeChild(row); // Delete Row

        // Get the barangay ID based on the name
        let barangayId;
        for (let [id, name] of Object.entries(barangays)) { // Get Laman ng Barangays - Array of Barangay
            if (name === barangayName) {
                barangayId = id; // Get the Matches ID
                break;
            }
        }

        Planting_INFORMAL_barangayoptions[barangayId].disabled = false; // Enable Ulit ang Barangay
        Planting_INFORMAL_addedBarangaysContainer.delete(barangayId); // Delete to be Available ulit
    }




    //PLANTING FSS - ADD & DELETE BARANGAY SELECTION
        const Planting_FSS_selection = document.getElementById('PLANTING_FSS_selection');
        const PLANTING_FSS_TABLE_TBODY = PLANTING_FSS_TABLE_ID.getElementsByTagName('tbody')[0];
        const Planting_FSS_addedBarangaysContainer = new Set();

        function Planting_AddBarangayFSS(){
            const PLANTING_FSS_selectedvalue = Planting_FSS_selection.value; // Get Value of Barangay
            const barangayName = barangays[PLANTING_FSS_selectedvalue] || 'Select Barangay';
        
            if (PLANTING_FSS_selectedvalue && !Planting_FSS_addedBarangaysContainer.has(PLANTING_FSS_selectedvalue)) {
                const row = PLANTING_FSS_TABLE_TBODY.insertRow(0); // Insert at the top

                row.innerHTML = `
                    <td> 
                        <i class="bi bi-x-circle-fill text-danger me-2" onclick="Planting_DeleteBarangayFSS(this)"></i>
                        <span>${barangayName}</span>
                    </td>

                    <td><input type="number" name="${PLANTING_FSS_selectedvalue}_AP_FSS" min='0' value="0" placeholder="Area Planted"></td>
                    <td><input type="number" name="${PLANTING_FSS_selectedvalue}_NF_FSS" min='0' value="0" placeholder="No. of Farmers"></td>
                `;

                Planting_FSS_addedBarangaysContainer.add(PLANTING_FSS_selectedvalue); // Add to Container
                Planting_FSS_selection.options[Planting_FSS_selection.selectedIndex].disabled = true; // Disable selected barangay
                Planting_FSS_selection.selectedIndex = 0; // Reset selection

            }
        }

        
        function Planting_DeleteBarangayFSS(button) {
            const row = button.closest('tr');
            const barangayName = row.cells[0].querySelector('span').innerText; // Kuhain ang Span sa 1st TD
            const Planting_FSS_barangayoptions = Planting_FSS_selection.options;

            row.parentNode.removeChild(row); // Delete Row

            // Get the barangay ID based on the name
            let barangayId;
            for (let [id, name] of Object.entries(barangays)) { // Get Laman ng Barangays - Array of Barangay
                if (name === barangayName) {
                    barangayId = id; // Get the Matches ID
                    break;
                }
            }

            Planting_FSS_barangayoptions[barangayId].disabled = false; // Enable Ulit ang Barangay
            Planting_FSS_addedBarangaysContainer.delete(barangayId); // Delete to be Available ulit
        }





//SUBMIT THE INPUT HARVESTING
    const SubmitDataHarvestedID = document.getElementById('SubmitDataHarvested');
    const HarvestedData_FORM = new FormData();

    SubmitDataHarvestedID.addEventListener('click', function() {
        
        HarvestedData_FORM.append('selectedseason', selectedseason);
        HarvestedData_FORM.append('rangeDate', rangeDate);
        HarvestedData_FORM.append('yearInsert', yearInsert);
        HarvestedData_FORM.append('monthpick', monthpick);

        const FORMAL_NPR_TABLE_Rows = HARVESTING_FORMAL_NPR_TABLE_TBODY.rows;
        const FORMAL_RCEF_TABLE_Rows = HARVESTING_FORMAL_RCEF_TABLE_TBODY.rows;
        const FORMAL_OWNOTHERS_TABLE_Rows = HARVESTING_FORMAL_OWNOTHERS_TABLE_TBODY.rows;
        const INFORMAL_TABLE_Rows = HARVESTING_INFORMAL_TABLE_TBODY.rows;
        const FSS_TABLE_Rows = HARVESTING_FSS_TABLE_TBODY.rows;

        let CheckRows = false; // FLAG

        //HARVESTING NPR PART
        for (let i = 0; i < FORMAL_NPR_TABLE_Rows.length - 1; i++) {
            CheckRows = true; 
            const row = FORMAL_NPR_TABLE_Rows[i]; 
            const barangayName = row.cells[0].querySelector('span').innerText;

            let FORMAL_NPR_barangayName;
            for (let [id, name] of Object.entries(barangays)) {
                if (name === barangayName) {
                    FORMAL_NPR_barangayName = id;
                    break;
                }
            }

            const FORMAL_AH_NPR_H = row.querySelector(`input[name*="_AH_NPR_H"]`);
            const FORMAL_AY_NPR_H = row.querySelector(`input[name*="_AY_NPR_H"]`);
            const FORMAL_P_NPR_H = row.querySelector(`input[name*="_P_NPR_H"]`);

            const FORMAL_AH_NPR_R = row.querySelector(`input[name*="_AH_NPR_R"]`);
            const FORMAL_AY_NPR_R = row.querySelector(`input[name*="_AY_NPR_R"]`);
            const FORMAL_P_NPR_R = row.querySelector(`input[name*="_P_NPR_R"]`);

            const FORMAL_AH_NPR_C = row.querySelector(`input[name*="_AH_NPR_C"]`);
            const FORMAL_AY_NPR_C = row.querySelector(`input[name*="_AY_NPR_C"]`);
            const FORMAL_P_NPR_C= row.querySelector(`input[name*="_P_NPR_C"]`);  

            HarvestedData_FORM.append('FORMAL_NPR_barangayName[]', FORMAL_NPR_barangayName || '0');


            HarvestedData_FORM.append('FORMAL_AH_NPR_H[]', FORMAL_AH_NPR_H ? FORMAL_AH_NPR_H.value : '0');
            HarvestedData_FORM.append('FORMAL_AY_NPR_H[]', FORMAL_AY_NPR_H ? FORMAL_AY_NPR_H.value : '0');
            HarvestedData_FORM.append('FORMAL_P_NPR_H[]', FORMAL_P_NPR_H ? FORMAL_P_NPR_H.value : '0');
        
            HarvestedData_FORM.append('FORMAL_AH_NPR_R[]', FORMAL_AH_NPR_R ? FORMAL_AH_NPR_R.value : '0');
            HarvestedData_FORM.append('FORMAL_AY_NPR_R[]', FORMAL_AY_NPR_R ? FORMAL_AY_NPR_R.value : '0');
            HarvestedData_FORM.append('FORMAL_P_NPR_R[]', FORMAL_P_NPR_R ? FORMAL_P_NPR_R.value : '0');
        
            HarvestedData_FORM.append('FORMAL_AH_NPR_C[]', FORMAL_AH_NPR_C ? FORMAL_AH_NPR_C.value : '0');
            HarvestedData_FORM.append('FORMAL_AY_NPR_C[]', FORMAL_AY_NPR_C ? FORMAL_AY_NPR_C.value : '0');
            HarvestedData_FORM.append('FORMAL_P_NPR_C[]', FORMAL_P_NPR_C ? FORMAL_P_NPR_C.value : '0');

        }

        //HARVESTING RCEF PART
        for (let i = 0; i < FORMAL_RCEF_TABLE_Rows.length - 1; i++) {
            CheckRows = true; 
            const row = FORMAL_RCEF_TABLE_Rows[i]; 
            const barangayName = row.cells[0].querySelector('span').innerText;

            let FORMAL_RCEF_barangayName;
            for (let [id, name] of Object.entries(barangays)) {
                if (name === barangayName) {
                    FORMAL_RCEF_barangayName = id;
                    break;
                }
            }

            const FORMAL_AH_RCEF_H = row.querySelector(`input[name*="_AH_RCEF_H"]`);
            const FORMAL_AY_RCEF_H = row.querySelector(`input[name*="_AY_RCEF_H"]`);
            const FORMAL_P_RCEF_H = row.querySelector(`input[name*="_P_RCEF_H"]`);

            const FORMAL_AH_RCEF_R = row.querySelector(`input[name*="_AH_RCEF_R"]`);
            const FORMAL_AY_RCEF_R = row.querySelector(`input[name*="_AY_RCEF_R"]`);
            const FORMAL_P_RCEF_R = row.querySelector(`input[name*="_P_RCEF_R"]`);

            const FORMAL_AH_RCEF_C = row.querySelector(`input[name*="_AH_RCEF_C"]`);
            const FORMAL_AY_RCEF_C = row.querySelector(`input[name*="_AY_RCEF_C"]`);
            const FORMAL_P_RCEF_C= row.querySelector(`input[name*="_P_RCEF_C"]`); 
            
            HarvestedData_FORM.append('FORMAL_RCEF_barangayName[]', FORMAL_RCEF_barangayName || '0');

            HarvestedData_FORM.append('FORMAL_AH_RCEF_H[]', FORMAL_AH_RCEF_H ? FORMAL_AH_RCEF_H.value : '0');
            HarvestedData_FORM.append('FORMAL_AY_RCEF_H[]', FORMAL_AY_RCEF_H ? FORMAL_AY_RCEF_H.value : '0');
            HarvestedData_FORM.append('FORMAL_P_RCEF_H[]', FORMAL_P_RCEF_H ? FORMAL_P_RCEF_H.value : '0');    

            HarvestedData_FORM.append('FORMAL_AH_RCEF_R[]', FORMAL_AH_RCEF_R ? FORMAL_AH_RCEF_R.value : '0');
            HarvestedData_FORM.append('FORMAL_AY_RCEF_R[]', FORMAL_AY_RCEF_R ? FORMAL_AY_RCEF_R.value : '0');
            HarvestedData_FORM.append('FORMAL_P_RCEF_R[]', FORMAL_P_RCEF_R ? FORMAL_P_RCEF_R.value : '0');

            HarvestedData_FORM.append('FORMAL_AH_RCEF_C[]', FORMAL_AH_RCEF_C ? FORMAL_AH_RCEF_C.value : '0');
            HarvestedData_FORM.append('FORMAL_AY_RCEF_C[]', FORMAL_AY_RCEF_C ? FORMAL_AY_RCEF_C.value : '0');
            HarvestedData_FORM.append('FORMAL_P_RCEF_C[]', FORMAL_P_RCEF_C ? FORMAL_P_RCEF_C.value : '0');
            

        }

        //HARVESTING OWNOTHERS PART
        for (let i = 0; i < FORMAL_OWNOTHERS_TABLE_Rows.length - 1; i++) {
            CheckRows = true; 
            const row = FORMAL_OWNOTHERS_TABLE_Rows[i]; 
            const barangayName = row.cells[0].querySelector('span').innerText;

            let FORMAL_OWNOTHERS_barangayName;
            for (let [id, name] of Object.entries(barangays)) {
                if (name === barangayName) {
                    FORMAL_OWNOTHERS_barangayName = id;
                    break;
                }
            }

            const FORMAL_AH_OWNOTHERS_H = row.querySelector(`input[name*="_AH_OWNOTHERS_H"]`);
            const FORMAL_AY_OWNOTHERS_H = row.querySelector(`input[name*="_AY_OWNOTHERS_H"]`);
            const FORMAL_P_OWNOTHERS_H = row.querySelector(`input[name*="_P_OWNOTHERS_H"]`);

            const FORMAL_AH_OWNOTHERS_R = row.querySelector(`input[name*="_AH_OWNOTHERS_R"]`);
            const FORMAL_AY_OWNOTHERS_R = row.querySelector(`input[name*="_AY_OWNOTHERS_R"]`);
            const FORMAL_P_OWNOTHERS_R = row.querySelector(`input[name*="_P_OWNOTHERS_R"]`);

            const FORMAL_AH_OWNOTHERS_C = row.querySelector(`input[name*="_AH_OWNOTHERS_C"]`);
            const FORMAL_AY_OWNOTHERS_C = row.querySelector(`input[name*="_AY_OWNOTHERS_C"]`);
            const FORMAL_P_OWNOTHERS_C= row.querySelector(`input[name*="_P_OWNOTHERS_C"]`); 
            
            
            HarvestedData_FORM.append('FORMAL_OWNOTHERS_barangayName[]', FORMAL_OWNOTHERS_barangayName || '0');
    
            HarvestedData_FORM.append('FORMAL_AH_OWNOTHERS_H[]', FORMAL_AH_OWNOTHERS_H ? FORMAL_AH_OWNOTHERS_H.value : '0');
            HarvestedData_FORM.append('FORMAL_AY_OWNOTHERS_H[]', FORMAL_AY_OWNOTHERS_H ? FORMAL_AY_OWNOTHERS_H.value : '0');
            HarvestedData_FORM.append('FORMAL_P_OWNOTHERS_H[]', FORMAL_P_OWNOTHERS_H ? FORMAL_P_OWNOTHERS_H.value : '0');    
    
            HarvestedData_FORM.append('FORMAL_AH_OWNOTHERS_R[]', FORMAL_AH_OWNOTHERS_R ? FORMAL_AH_OWNOTHERS_R.value : '0');
            HarvestedData_FORM.append('FORMAL_AY_OWNOTHERS_R[]', FORMAL_AY_OWNOTHERS_R ? FORMAL_AY_OWNOTHERS_R.value : '0');
            HarvestedData_FORM.append('FORMAL_P_OWNOTHERS_R[]', FORMAL_P_OWNOTHERS_R ? FORMAL_P_OWNOTHERS_R.value : '0');
    
            HarvestedData_FORM.append('FORMAL_AH_OWNOTHERS_C[]', FORMAL_AH_OWNOTHERS_C ? FORMAL_AH_OWNOTHERS_C.value : '0');
            HarvestedData_FORM.append('FORMAL_AY_OWNOTHERS_C[]', FORMAL_AY_OWNOTHERS_C ? FORMAL_AY_OWNOTHERS_C.value : '0');
            HarvestedData_FORM.append('FORMAL_P_OWNOTHERS_C[]', FORMAL_P_OWNOTHERS_C ? FORMAL_P_OWNOTHERS_C.value : '0');
            
        }

        //HARVESTING INFORMAL PART
        for (let i = 0; i < INFORMAL_TABLE_Rows.length - 1; i++) {
            CheckRows = true; 
            const row = INFORMAL_TABLE_Rows[i]; 
            const barangayName = row.cells[0].querySelector('span').innerText;

            let INFORMAL_barangayName;
            for (let [id, name] of Object.entries(barangays)) {
                if (name === barangayName) {
                    INFORMAL_barangayName = id;
                    break;
                }
            }

            const INFORMAL_AH_Starter = row.querySelector(`input[name*="_AH_INFORMAL_Starter"]`);
            const INFORMAL_AY_Starter = row.querySelector(`input[name*="_AY_INFORMAL_Starter"]`);
            const INFORMAL_P_Starter = row.querySelector(`input[name*="_P_INFORMAL_Starter"]`);

            const INFORMAL_AH_Tagged = row.querySelector(`input[name*="_AH_INFORMAL_Tagged"]`);
            const INFORMAL_AY_Tagged = row.querySelector(`input[name*="_AY_INFORMAL_Tagged"]`);
            const INFORMAL_P_Tagged = row.querySelector(`input[name*="_P_INFORMAL_Tagged"]`);

            const INFORMAL_AH_Traditional = row.querySelector(`input[name*="_AH_INFORMAL_Traditional"]`);
            const INFORMAL_AY_Traditional = row.querySelector(`input[name*="_AY_INFORMAL_Traditional"]`);
            const INFORMAL_P_Traditional = row.querySelector(`input[name*="_P_INFORMAL_Traditional"]`); 

            HarvestedData_FORM.append('INFORMAL_barangayName[]', INFORMAL_barangayName || '0');

            HarvestedData_FORM.append('INFORMAL_AH_Starter[]', INFORMAL_AH_Starter ? INFORMAL_AH_Starter.value : '0');
            HarvestedData_FORM.append('INFORMAL_AY_Starter[]', INFORMAL_AY_Starter ? INFORMAL_AY_Starter.value : '0');
            HarvestedData_FORM.append('INFORMAL_P_Starter[]', INFORMAL_P_Starter ? INFORMAL_P_Starter.value : '0');    
    
            HarvestedData_FORM.append('INFORMAL_AH_Tagged[]', INFORMAL_AH_Tagged ? INFORMAL_AH_Tagged.value : '0');
            HarvestedData_FORM.append('INFORMAL_AY_Tagged[]', INFORMAL_AY_Tagged ? INFORMAL_AY_Tagged.value : '0');
            HarvestedData_FORM.append('INFORMAL_P_Tagged[]', INFORMAL_P_Tagged ? INFORMAL_P_Tagged.value : '0');    

            HarvestedData_FORM.append('INFORMAL_AH_Traditional[]', INFORMAL_AH_Traditional ? INFORMAL_AH_Traditional.value : '0');
            HarvestedData_FORM.append('INFORMAL_AY_Traditional[]', INFORMAL_AY_Traditional ? INFORMAL_AY_Traditional.value : '0');
            HarvestedData_FORM.append('INFORMAL_P_Traditional[]', INFORMAL_P_Traditional ? INFORMAL_P_Traditional.value : '0');
            
        }

        //HARVESTING FSS PART
        for (let i = 0; i < FSS_TABLE_Rows.length - 1; i++) {
            CheckRows = true; 
            const row = FSS_TABLE_Rows[i]; 
            const barangayName = row.cells[0].querySelector('span').innerText;

            let FSS_barangayName;
            for (let [id, name] of Object.entries(barangays)) {
                if (name === barangayName) {
                    FSS_barangayName = id;
                    break;
                }
            }

            const FSS_AH = row.querySelector(`input[name*="_AH_FSS"]`);
            const FSS_AY = row.querySelector(`input[name*="_AY_FSS"]`);
            const FSS_P = row.querySelector(`input[name*="_P_FSS"]`);


            HarvestedData_FORM.append('FSS_barangayName[]', FSS_barangayName || '0');

            HarvestedData_FORM.append('FSS_AH[]', FSS_AH ? FSS_AH.value : '0');
            HarvestedData_FORM.append('FSS_AY[]', FSS_AY ? FSS_AY.value : '0');
            HarvestedData_FORM.append('FSS_P[]', FSS_P ? FSS_P.value : '0');
            
        }



        //AJAX PASSING DATA
        if (CheckRows) {
            $.ajax({
                url: 'Function/DRHarvestingFunctionRainfed.php',
                type: 'POST',
                data: HarvestedData_FORM,
                contentType: false,
                processData: false,
                success: function(response) {
                    var res = jQuery.parseJSON(response);

                    if(res.status == 'ERROR') {
                        alert(res.message);
                    }else if (res.status == 'SUCCESS'){
                        location.reload();
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('ERROR PHP OR AJAX.');
                }
            });
        } else {
            alert('Please enter at least one set of valid data before submitting.'); // Warning message
        }

    });



//SUBMIT THE INPUT PLANTING
   const SubmitDataPlantedID = document.getElementById('SubmitDataPlanted');
   const PlantedData_FORM = new FormData();

   SubmitDataPlantedID.addEventListener('click', function() {
       
       PlantedData_FORM.append('selectedseason', selectedseason);
       PlantedData_FORM.append('rangeDate', rangeDate);
       PlantedData_FORM.append('yearInsert', yearInsert);
       PlantedData_FORM.append('monthpick', monthpick);

       const FORMAL_NPR_TABLE_Rows = PLANTING_FORMAL_NPR_TABLE_TBODY.rows;
       const FORMAL_RCEF_TABLE_Rows = PLANTING_FORMAL_RCEF_TABLE_TBODY.rows;
       const FORMAL_OWNOTHERS_TABLE_Rows = PLANTING_FORMAL_OWNOTHERS_TABLE_TBODY.rows;
       const INFORMAL_TABLE_Rows = PLANTING_INFORMAL_TABLE_TBODY.rows;
       const FSS_TABLE_Rows = PLANTING_FSS_TABLE_TBODY.rows;

       let CheckRows = false; // FLAG
       
       for (let i = 0; i < FORMAL_NPR_TABLE_Rows.length - 1; i++) {
           CheckRows = true; 
           const row = FORMAL_NPR_TABLE_Rows[i]; 
           const barangayName = row.cells[0].querySelector('span').innerText;

           let FORMAL_NPR_barangayName;
           for (let [id, name] of Object.entries(barangays)) {
               if (name === barangayName) {
                   FORMAL_NPR_barangayName = id;
                   break;
               }
           }

           const FORMAL_AP_NPR_H = row.querySelector(`input[name*="_AP_NPR_H"]`);
           const FORMAL_NF_NPR_H = row.querySelector(`input[name*="_NF_NPR_H"]`);

           const FORMAL_AP_NPR_R = row.querySelector(`input[name*="_AP_NPR_R"]`);
           const FORMAL_NF_NPR_R = row.querySelector(`input[name*="_NF_NPR_R"]`);

           const FORMAL_AP_NPR_C = row.querySelector(`input[name*="_AP_NPR_C"]`);
           const FORMAL_NF_NPR_C = row.querySelector(`input[name*="_NF_NPR_C"]`);  

           PlantedData_FORM.append('FORMAL_NPR_barangayName[]', FORMAL_NPR_barangayName || '0');

           PlantedData_FORM.append('FORMAL_AP_NPR_H[]', FORMAL_AP_NPR_H ? FORMAL_AP_NPR_H.value : '0');
           PlantedData_FORM.append('FORMAL_NF_NPR_H[]', FORMAL_NF_NPR_H ? FORMAL_NF_NPR_H.value : '0');
           
           PlantedData_FORM.append('FORMAL_AP_NPR_R[]', FORMAL_AP_NPR_R ? FORMAL_AP_NPR_R.value : '0');
           PlantedData_FORM.append('FORMAL_NF_NPR_R[]', FORMAL_NF_NPR_R ? FORMAL_NF_NPR_R.value : '0');
           
           PlantedData_FORM.append('FORMAL_AP_NPR_C[]', FORMAL_AP_NPR_C ? FORMAL_AP_NPR_C.value : '0');
           PlantedData_FORM.append('FORMAL_NF_NPR_C[]', FORMAL_NF_NPR_C ? FORMAL_NF_NPR_C.value : '0');
           
       }


       for (let i = 0; i < FORMAL_RCEF_TABLE_Rows.length - 1; i++) {
           CheckRows = true; 
           const row = FORMAL_RCEF_TABLE_Rows[i]; 
           const barangayName = row.cells[0].querySelector('span').innerText;

           let FORMAL_RCEF_barangayName;
           for (let [id, name] of Object.entries(barangays)) {
               if (name === barangayName) {
                   FORMAL_RCEF_barangayName = id;
                   break;
               }
           }

           const FORMAL_AP_RCEF_H = row.querySelector(`input[name*="_AP_RCEF_H"]`);
           const FORMAL_NF_RCEF_H = row.querySelector(`input[name*="_NF_RCEF_H"]`);

           const FORMAL_AP_RCEF_R = row.querySelector(`input[name*="_AP_RCEF_R"]`);
           const FORMAL_NF_RCEF_R = row.querySelector(`input[name*="_NF_RCEF_R"]`);

           const FORMAL_AP_RCEF_C = row.querySelector(`input[name*="_AP_RCEF_C"]`);
           const FORMAL_NF_RCEF_C = row.querySelector(`input[name*="_NF_RCEF_C"]`);  

           PlantedData_FORM.append('FORMAL_RCEF_barangayName[]', FORMAL_RCEF_barangayName || '0');

           PlantedData_FORM.append('FORMAL_AP_RCEF_H[]', FORMAL_AP_RCEF_H ? FORMAL_AP_RCEF_H.value : '0');
           PlantedData_FORM.append('FORMAL_NF_RCEF_H[]', FORMAL_NF_RCEF_H ? FORMAL_NF_RCEF_H.value : '0');

           PlantedData_FORM.append('FORMAL_AP_RCEF_R[]', FORMAL_AP_RCEF_R ? FORMAL_AP_RCEF_R.value : '0');
           PlantedData_FORM.append('FORMAL_NF_RCEF_R[]', FORMAL_NF_RCEF_R ? FORMAL_NF_RCEF_R.value : '0');

           PlantedData_FORM.append('FORMAL_AP_RCEF_C[]', FORMAL_AP_RCEF_C ? FORMAL_AP_RCEF_C.value : '0');
           PlantedData_FORM.append('FORMAL_NF_RCEF_C[]', FORMAL_NF_RCEF_C ? FORMAL_NF_RCEF_C.value : '0');
               

       }


       for (let i = 0; i < FORMAL_OWNOTHERS_TABLE_Rows.length - 1; i++) {
           CheckRows = true; 
           const row = FORMAL_OWNOTHERS_TABLE_Rows[i]; 
           const barangayName = row.cells[0].querySelector('span').innerText;

           let FORMAL_OWNOTHERS_barangayName;
           for (let [id, name] of Object.entries(barangays)) {
               if (name === barangayName) {
                   FORMAL_OWNOTHERS_barangayName = id;
                   break;
               }
           }

           const FORMAL_AP_OWNOTHERS_H = row.querySelector(`input[name*="_AP_OWNOTHERS_H"]`);
           const FORMAL_NF_OWNOTHERS_H = row.querySelector(`input[name*="_NF_OWNOTHERS_H"]`);

           const FORMAL_AP_OWNOTHERS_R = row.querySelector(`input[name*="_AP_OWNOTHERS_R"]`);
           const FORMAL_NF_OWNOTHERS_R = row.querySelector(`input[name*="_NF_OWNOTHERS_R"]`);

           const FORMAL_AP_OWNOTHERS_C = row.querySelector(`input[name*="_AP_OWNOTHERS_C"]`);
           const FORMAL_NF_OWNOTHERS_C = row.querySelector(`input[name*="_NF_OWNOTHERS_C"]`);  

           PlantedData_FORM.append('FORMAL_OWNOTHERS_barangayName[]', FORMAL_OWNOTHERS_barangayName || '0');

           PlantedData_FORM.append('FORMAL_AP_OWNOTHERS_H[]', FORMAL_AP_OWNOTHERS_H ? FORMAL_AP_OWNOTHERS_H.value : '0');
           PlantedData_FORM.append('FORMAL_NF_OWNOTHERS_H[]', FORMAL_NF_OWNOTHERS_H ? FORMAL_NF_OWNOTHERS_H.value : '0');
           
           PlantedData_FORM.append('FORMAL_AP_OWNOTHERS_R[]', FORMAL_AP_OWNOTHERS_R ? FORMAL_AP_OWNOTHERS_R.value : '0');
           PlantedData_FORM.append('FORMAL_NF_OWNOTHERS_R[]', FORMAL_NF_OWNOTHERS_R ? FORMAL_NF_OWNOTHERS_R.value : '0');
           
           PlantedData_FORM.append('FORMAL_AP_OWNOTHERS_C[]', FORMAL_AP_OWNOTHERS_C ? FORMAL_AP_OWNOTHERS_C.value : '0');
           PlantedData_FORM.append('FORMAL_NF_OWNOTHERS_C[]', FORMAL_NF_OWNOTHERS_C ? FORMAL_NF_OWNOTHERS_C.value : '0');
           

       }


       for (let i = 0; i < INFORMAL_TABLE_Rows.length - 1; i++) {
           CheckRows = true; 
           const row = INFORMAL_TABLE_Rows[i]; 
           const barangayName = row.cells[0].querySelector('span').innerText;

           let INFORMAL_barangayName;
           for (let [id, name] of Object.entries(barangays)) {
               if (name === barangayName) {
                   INFORMAL_barangayName = id;
                   break;
               }
           }

           const INFORMAL_AP_Starter = row.querySelector(`input[name*="_AP_INFORMAL_Starter"]`);
           const INFORMAL_NF_Starter = row.querySelector(`input[name*="_NF_INFORMAL_Starter"]`);

           const INFORMAL_AP_Tagged = row.querySelector(`input[name*="_AP_INFORMAL_Tagged"]`);
           const INFORMAL_NF_Tagged = row.querySelector(`input[name*="_NF_INFORMAL_Tagged"]`);

           const INFORMAL_AP_Traditional = row.querySelector(`input[name*="_AP_INFORMAL_Traditional"]`);
           const INFORMAL_NF_Traditional = row.querySelector(`input[name*="_NF_INFORMAL_Traditional"]`);  

           PlantedData_FORM.append('INFORMAL_barangayName[]', INFORMAL_barangayName || '0');

           PlantedData_FORM.append('INFORMAL_AP_Starter[]', INFORMAL_AP_Starter ? INFORMAL_AP_Starter.value : '0');
           PlantedData_FORM.append('INFORMAL_NF_Starter[]', INFORMAL_NF_Starter ? INFORMAL_NF_Starter.value : '0');

           PlantedData_FORM.append('INFORMAL_AP_Tagged[]', INFORMAL_AP_Tagged ? INFORMAL_AP_Tagged.value : '0');
           PlantedData_FORM.append('INFORMAL_NF_Tagged[]', INFORMAL_NF_Tagged ? INFORMAL_NF_Tagged.value : '0');

           PlantedData_FORM.append('INFORMAL_AP_Traditional[]', INFORMAL_AP_Traditional ? INFORMAL_AP_Traditional.value : '0');
           PlantedData_FORM.append('INFORMAL_NF_Traditional[]', INFORMAL_NF_Traditional ? INFORMAL_NF_Traditional.value : '0');
   
       }


       for (let i = 0; i < FSS_TABLE_Rows.length - 1; i++) {
           CheckRows = true; 
           const row = FSS_TABLE_Rows[i]; 
           const barangayName = row.cells[0].querySelector('span').innerText;

           let FSS_barangayName;
           for (let [id, name] of Object.entries(barangays)) {
               if (name === barangayName) {
                   FSS_barangayName = id;
                   break;
               }
           }

           const FSS_AP = row.querySelector(`input[name*="_AP_FSS"]`);
           const FSS_NF = row.querySelector(`input[name*="_NF_FSS"]`);

           PlantedData_FORM.append('FSS_barangayName[]', FSS_barangayName || '0');
           PlantedData_FORM.append('FSS_AP[]', FSS_AP ? FSS_AP.value : '0');
           PlantedData_FORM.append('FSS_NF[]', FSS_NF ? FSS_NF.value : '0');
           

       }



       //AJAX PASSING DATA
       if (CheckRows) {
           $.ajax({
               url: 'Function/DRPlantingFunctionRainfed.php',
               type: 'POST',
               data: PlantedData_FORM,
               contentType: false,
               processData: false,
               success: function(response) {
                   var res = jQuery.parseJSON(response);

                   if(res.status == 'ERROR') {
                       alert(res.message);
                   }else if (res.status == 'SUCCESS'){
                       location.reload();
                   }

               },
               error: function(jqXHR, textStatus, errorThrown) {
                   alert('ERROR PHP OR AJAX.');
               }
           });
       } else {
           alert('Please enter at least one set of valid data before submitting.'); // Warning message
       }



   });



//DELETE LINE TABLE
    function DeleteHarvesting(year, month, range_date) {
        if (confirm('Are you sure you want to delete this harvesting record?')) {
            $.ajax({
                url: 'Function/DATARainfedDeleteHarvesting.php',
                type: 'POST',
                data: {
                    year: year,
                    month: month,
                    range_date: range_date
                },
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.status === 'SUCCESS') {
                        alert(res.message);
                        location.reload();
                    } else if(res.status === 'NOTSUCCESS'){
                        alert(res.message);
                    }else if(res.status === 'ERROR'){
                        alert(res.message);
                    }else{
                        alert(res.message);
                    }
                },
                error: function() {
                    alert('An error occurred while deleting the record');
                }
            });
        }
    }

    function DeletePlanting(year, month, range_date) {
        if (confirm('Are you sure you want to delete this planting record?')) {
            $.ajax({
                url: 'Function/DATARainfedDeletePlanting.php',
                type: 'POST',
                data: {
                    year: year,
                    month: month,
                    range_date: range_date
                },
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.status === 'SUCCESS') {
                        alert(res.message);
                        location.reload();
                    } else if(res.status === 'NOTSUCCESS'){
                        alert(res.message);
                    }else if(res.status === 'ERROR'){
                        alert(res.message);
                    }else{
                        alert(res.message);
                    }
                },
                error: function() {
                    alert('An error occurred while deleting the record');
                }
            });
        }
    }



    // Helper function to get the month and season names
    function getMonthAndSeason(monthNum, seasonNum) {
        const monthName = month[monthNum];  // Get month name using monthNum
        const seasonType = season[seasonNum];  // Get season type using seasonNum

        return { monthName, seasonType };
    }

//PLANTING PART RAINFED

    //FETCH RAINFED PLANTING VIEW AND UPDATE
    let updatedDataNPR = [];
    let updatedDataRCEF = [];
    let updatedDataOWNOTHERS = [];
    let updatedDataINFORMAL = [];
    let updatedDataFSS = [];

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
                         //Insert rows for the first table (table1)
                    data.NPR.forEach(function(row, index) {
                        if (!updatedDataNPR[index]) {
                            updatedDataNPR[index] = {
                                FROMAL_NPR_Barangay: row.FROMAL_NPR_Barangay,
                                Hybrid_Area_Planted: row.Hybrid_Area_Planted,
                                Hybrid_No_Farmers: row.Hybrid_No_Farmers,
                                Registered_Area_Planted: row.Registered_Area_Planted,
                                Registered_No_Farmers: row.Registered_No_Farmers,
                                Certified_Area_Planted: row.Certified_Area_Planted,
                                Certified_No_Farmers: row.Certified_No_Farmers,
        
                                year: data.INFO.year,      
                                month: data.INFO.month,    // Add month from table2
                                range_date: data.INFO.range_date // Add range_date from table2
                            };
                        }
                            var rowHtml = "<tr>";
                                rowHtml += "<td class='first_td'><input type='text' data-field='FROMAL_NPR_Barangay' data-index='" + index + "' value='" + row.FROMAL_NPR_Barangay + "' step='any' readonly></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Hybrid_Area_Planted' data-index='" + index + "' value='" + row.Hybrid_Area_Planted + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Hybrid_No_Farmers' data-index='" + index + "' value='" + row.Hybrid_No_Farmers + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Registered_Area_Planted' data-index='" + index + "' value='" + row.Registered_Area_Planted + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Registered_No_Farmers' data-index='" + index + "' value='" + row.Registered_No_Farmers + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Certified_Area_Planted' data-index='" + index + "' value='" + row.Certified_Area_Planted + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Certified_No_Farmers' data-index='" + index + "' value='" + row.Certified_No_Farmers + "'step='any'></td>";
                                rowHtml += "</tr>";
            
                            // Append the new row to table1
                            $('#VIEW_PLANTING_FORMAL_NPR_TABLE tbody').append(rowHtml);
                        });
                }else{
                    $('#VIEW_PLANTING_FORMAL_NPR_TABLE tbody').append('<tr><td colspan="7" class="text-center">No Planted</td></tr>');
                }




                if (data.RCEF && data.RCEF.length > 0){
                    data.RCEF.forEach(function(row, index) {
                        if (!updatedDataRCEF[index]) {
                            updatedDataRCEF[index] = {
                                FROMAL_RCEF_Barangay: row.FROMAL_RCEF_Barangay,
                                Hybrid_Area_Planted: row.Hybrid_Area_Planted,
                                Hybrid_No_Farmers: row.Hybrid_No_Farmers,
                                Registered_Area_Planted: row.Registered_Area_Planted,
                                Registered_No_Farmers: row.Registered_No_Farmers,
                                Certified_Area_Planted: row.Certified_Area_Planted,
                                Certified_No_Farmers: row.Certified_No_Farmers,
    
                                year: data.INFO.year,      
                                month: data.INFO.month,    
                                range_date: data.INFO.range_date
                            };
                        }
                            var rowHtml = "<tr>";
                                rowHtml += "<td><input type='text' data-field='FROMAL_RCEF_Barangay' data-index='" + index + "' value='" + row.FROMAL_RCEF_Barangay + "' step='any' readonly></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Hybrid_Area_Planted' data-index='" + index + "' value='" + row.Hybrid_Area_Planted + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Hybrid_No_Farmers' data-index='" + index + "' value='" + row.Hybrid_No_Farmers + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Registered_Area_Planted' data-index='" + index + "' value='" + row.Registered_Area_Planted + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Registered_No_Farmers' data-index='" + index + "' value='" + row.Registered_No_Farmers + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Certified_Area_Planted' data-index='" + index + "' value='" + row.Certified_Area_Planted + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Certified_No_Farmers' data-index='" + index + "' value='" + row.Certified_No_Farmers + "'step='any'></td>";
                                rowHtml += "</tr>";
            
                            // Append the new row to table1
                            $('#VIEW_PLANTING_FORMAL_RCEF_TABLE tbody').append(rowHtml);
                        });    
                }else{
                    $('#VIEW_PLANTING_FORMAL_RCEF_TABLE tbody').append('<tr><td colspan="7" class="text-center">No Planted</td></tr>');
                }

                if (data.OWNOTHERS && data.OWNOTHERS.length > 0){
                    data.OWNOTHERS.forEach(function(row, index) {
                        if (!updatedDataOWNOTHERS[index]) {
                            updatedDataOWNOTHERS[index] = {
                                FROMAL_OWNOTHERS_Barangay: row.FROMAL_OWNOTHERS_Barangay,
                                Hybrid_Area_Planted: row.Hybrid_Area_Planted,
                                Hybrid_No_Farmers: row.Hybrid_No_Farmers,
                                Registered_Area_Planted: row.Registered_Area_Planted,
                                Registered_No_Farmers: row.Registered_No_Farmers,
                                Certified_Area_Planted: row.Certified_Area_Planted,
                                Certified_No_Farmers: row.Certified_No_Farmers,
    
                                year: data.INFO.year,      
                                month: data.INFO.month,    
                                range_date: data.INFO.range_date
                            };
                        }
                            var rowHtml = "<tr>";
                                rowHtml += "<td><input type='text' data-field='FROMAL_OWNOTHERS_Barangay' data-index='" + index + "' value='" + row.FROMAL_OWNOTHERS_Barangay + "' step='any' readonly></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Hybrid_Area_Planted' data-index='" + index + "' value='" + row.Hybrid_Area_Planted + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Hybrid_No_Farmers' data-index='" + index + "' value='" + row.Hybrid_No_Farmers + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Registered_Area_Planted' data-index='" + index + "' value='" + row.Registered_Area_Planted + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Registered_No_Farmers' data-index='" + index + "' value='" + row.Registered_No_Farmers + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Certified_Area_Planted' data-index='" + index + "' value='" + row.Certified_Area_Planted + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Certified_No_Farmers' data-index='" + index + "' value='" + row.Certified_No_Farmers + "' step='any'></td>";
                                rowHtml += "</tr>";
            
                            // Append the new row to table1
                            $('#VIEW_PLANTING_FORMAL_OWNOTHERS_TABLE tbody').append(rowHtml);
                        });
                }else{
                    $('#VIEW_PLANTING_FORMAL_OWNOTHERS_TABLE tbody').append('<tr><td colspan="7" class="text-center">No Planted</td></tr>');
                }

                if (data.INFORMAL && data.INFORMAL.length > 0){
                    data.INFORMAL.forEach(function(row, index) {
                        if (!updatedDataINFORMAL[index]) {
                            updatedDataINFORMAL[index] = {
                                INFROMAL_Barangay: row.INFROMAL_Barangay,
                                Starter_Area_Planted: row.Starter_Area_Planted,
                                Starter_No_Farmers: row.Starter_No_Farmers,
                                Tagged_Area_Planted: row.Tagged_Area_Planted,
                                Tagged_No_Farmers: row.Tagged_No_Farmers,
                                Traditional_Area_Planted: row.Traditional_Area_Planted,
                                Traditional_No_Farmers: row.Traditional_No_Farmers,
        
                                year: data.INFO.year,      
                                month: data.INFO.month,    
                                range_date: data.INFO.range_date
                            };
                        }
                            var rowHtml = "<tr>";
                                rowHtml += "<td><input type='text' data-field='INFROMAL_Barangay' data-index='" + index + "' value='" + row.INFROMAL_Barangay + "' step='any' readonly></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Starter_Area_Planted' data-index='" + index + "' value='" + row.Starter_Area_Planted + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Starter_No_Farmers' data-index='" + index + "' value='" + row.Starter_No_Farmers + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Tagged_Area_Planted' data-index='" + index + "' value='" + row.Tagged_Area_Planted + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Tagged_No_Farmers' data-index='" + index + "' value='" + row.Tagged_No_Farmers + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Traditional_Area_Planted' data-index='" + index + "' value='" + row.Traditional_Area_Planted + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='Traditional_No_Farmers' data-index='" + index + "' value='" + row.Traditional_No_Farmers + "' step='any'></td>";
                                rowHtml += "</tr>";
            
                            // Append the new row to table1
                            $('#VIEW_PLANTING_INFORMAL_TABLE tbody').append(rowHtml);
                        });
                }else{
                    $('#VIEW_PLANTING_INFORMAL_TABLE tbody').append('<tr><td colspan="7" class="text-center">No Planted</td></tr>');
                }

                if (data.FSS && data.FSS.length > 0){
                    data.FSS.forEach(function(row, index) {
                        if (!updatedDataFSS[index]) {
                            updatedDataFSS[index] = {
                                FSS_Barangay: row.FSS_Barangay,
                                FSS_Area_Planted: row.FSS_Area_Planted,
                                FSS_No_Farmers: row.FSS_No_Farmers,
                        
                                year: data.INFO.year,      
                                month: data.INFO.month,    
                                range_date: data.INFO.range_date
                            };
                        }
                            var rowHtml = "<tr>";
                                rowHtml += "<td><input type='text' data-field='FSS_Barangay' data-index='" + index + "' value='" + row.FSS_Barangay + "' step='any' readonly></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='FSS_Area_Planted' data-index='" + index + "' value='" + row.FSS_Area_Planted + "' step='any'></td>";
                                rowHtml += "<td><input type='number' min='0' data-field='FSS_No_Farmers' data-index='" + index + "' value='" + row.FSS_No_Farmers + "' step='any'></td>";
                                rowHtml += "</tr>";
            
                            // Append the new row to table1
                            $('#VIEW_PLANTING_FSS_TABLE tbody').append(rowHtml);
                        });
                }else{
                    $('#VIEW_PLANTING_FSS_TABLE tbody').append('<tr><td colspan="3" class="text-center">No Planted</td></tr>');
                }



                    // For VIEW_PLANTING_FORMAL_NPR_TABLE
                    $('#VIEW_PLANTING_FORMAL_NPR_TABLE').on('input', 'input[type="number"]', function() {
                        var updatedValue = $(this).val();
                        var field = $(this).data('field');
                        var index = $(this).data('index');
                        updatedDataNPR[index] = updatedDataNPR[index] || {};  // Create the object if it doesn't exist
                        updatedDataNPR[index][field] = updatedValue;  // Update the field with the new value
                    });

                    // For VIEW_PLANTING_FORMAL_RCEF_TABLE
                    $('#VIEW_PLANTING_FORMAL_RCEF_TABLE').on('input', 'input[type="number"]', function() {
                        var updatedValue = $(this).val();
                        var field = $(this).data('field');
                        var index = $(this).data('index');
                        updatedDataRCEF[index] = updatedDataRCEF[index] || {};  // Create the object if it doesn't exist
                        updatedDataRCEF[index][field] = updatedValue;  // Update the field with the new value
                    });

                    // For VIEW_PLANTING_FORMAL_OWNOTHERS_TABLE
                    $('#VIEW_PLANTING_FORMAL_OWNOTHERS_TABLE').on('input', 'input[type="number"]', function() {
                        var updatedValue = $(this).val();
                        var field = $(this).data('field');
                        var index = $(this).data('index');
                        updatedDataOWNOTHERS[index] = updatedDataOWNOTHERS[index] || {};  // Create the object if it doesn't exist
                        updatedDataOWNOTHERS[index][field] = updatedValue;  // Update the field with the new value
                    });

                    // For VIEW_PLANTING_INFORMAL_TABLE
                    $('#VIEW_PLANTING_INFORMAL_TABLE').on('input', 'input[type="number"]', function() {
                        var updatedValue = $(this).val();
                        var field = $(this).data('field');
                        var index = $(this).data('index');
                        updatedDataINFORMAL[index] = updatedDataINFORMAL[index] || {};  // Create the object if it doesn't exist
                        updatedDataINFORMAL[index][field] = updatedValue;  // Update the field with the new value
                    });

                    // For VIEW_PLANTING_FSS_TABLE
                    $('#VIEW_PLANTING_FSS_TABLE').on('input', 'input[type="number"]', function() {
                        var updatedValue = $(this).val();
                        var field = $(this).data('field');
                        var index = $(this).data('index');
                        updatedDataFSS[index] = updatedDataFSS[index] || {};  // Create the object if it doesn't exist
                        updatedDataFSS[index][field] = updatedValue;  // Update the field with the new value
                    });

                    
                $('#saveChanges').on('click', function() {
                    const dataToSend = {
                        updatedDataNPR: JSON.stringify(updatedDataNPR),
                        updatedDataRCEF: JSON.stringify(updatedDataRCEF),
                        updatedDataOWNOTHERS: JSON.stringify(updatedDataOWNOTHERS),
                        updatedDataINFORMAL: JSON.stringify(updatedDataINFORMAL),
                        updatedDataFSS: JSON.stringify(updatedDataFSS)
                    };


                    $.ajax({
                        url: 'Function/UpdateDATARainfedPlanting.php',  // PHP script to save updated data
                        type: 'POST',
                        data: dataToSend,  // Stringify the updatedData before sending
                        dataType: 'json',  // Expecting a JSON response
                        success: function(response) {
                          
                            if (response.status === 'success') {
                                location.reload(); 
                            } else {
                                alert('Failed to update data!');
                                console.log('Error details: ', response.message);
                            }
            
                        },
                        error: function(xhr, status, error) {
                            // Enhanced debugging
                            console.error('AJAX Error Details:');
                            console.error('Status:', status);
                            console.error('Error:', error);
                            console.error('XHR Response Text:', xhr.responseText);  // This will log the response from the PHP backend
                
                            // If the server sent a response, log it
                            if (xhr.responseText) {
                                console.log('Server Response:', xhr.responseText);
                            }
                
                            alert('Failed to save changes');
                        }
                    });


                });



                $('#viewModalPlanting').modal('show');
            },
            error: function() {
                alert('Failed to load data for viewing');
            }     
        });
    }

 
//HARVESTING PART RAINFED

    //FETCH RAINFED HARVESTING VIEW AND UPDATE
    let updatedDataNPRHarvesting = [];
    let updatedDataRCEFHarvesting = [];
    let updatedDataOWNOTHERSHarvesting = [];
    let updatedDataINFORMALHarvesting = [];
    let updatedDataFSSHarvesting = [];

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

                        if (!updatedDataNPRHarvesting[index]) {
                            updatedDataNPRHarvesting[index] = {
                                FROMAL_NPR_Barangay: row.FROMAL_NPR_Barangay,
                                Hybrid_Area_Harvested: row.Hybrid_Area_Harvested,
                                Hybrid_Average_Yield: row.Hybrid_Average_Yield,
                                Hybrid_Production: row.Hybrid_Production,
                                Registered_Area_Harvested: row.Registered_Area_Harvested,
                                Registered_Average_Yield: row.Registered_Average_Yield,
                                Registered_Production: row.Registered_Production,
                                Certified_Area_Harvested: row.Certified_Area_Harvested,
                                Certified_Average_Yield: row.Certified_Average_Yield,
                                Certified_Production: row.Certified_Production,
                                year: data.INFO.year,      
                                month: data.INFO.month,    // Add month from table2
                                range_date: data.INFO.range_date // Add range_date from table2
                            };
                        }

                        var rowHtml = "<tr>";
                            rowHtml += "<td class='first_td'><input type='text' data-field='FROMAL_NPR_Barangay' data-index='" + index + "' value='" + row.FROMAL_NPR_Barangay + "' step='any' readonly></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Hybrid_Area_Harvested' data-index='" + index + "' value='" + row.Hybrid_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Hybrid_Average_Yield' data-index='" + index + "' value='" + row.Hybrid_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Hybrid_Production' data-index='" + index + "' value='" + row.Hybrid_Production + "' step='any' placeholder='Production'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Registered_Area_Harvested' data-index='" + index + "' value='" + row.Registered_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Registered_Average_Yield' data-index='" + index + "' value='" + row.Registered_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Registered_Production' data-index='" + index + "' value='" + row.Registered_Production + "' step='any' placeholder='Production'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Certified_Area_Harvested' data-index='" + index + "' value='" + row.Certified_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Certified_Average_Yield' data-index='" + index + "' value='" + row.Certified_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Certified_Production' data-index='" + index + "' value='" + row.Certified_Production + "' step='any' placeholder='Production'></td>";
                        rowHtml += "</tr>";
        
                        // Append the new row to the table
                        $('#VIEW_HARVESTING_FORMAL_NPR_TABLE tbody').append(rowHtml);
                    });
                }else{
                    $('#VIEW_HARVESTING_FORMAL_NPR_TABLE tbody').append('<tr><td colspan="10" class="text-center">No Harvested</td></tr>');
                }


                if (data.RCEF && data.RCEF.length > 0){
                    data.RCEF.forEach(function(row, index) {

                        if (!updatedDataRCEFHarvesting[index]) {
                            updatedDataRCEFHarvesting[index] = {
                                FROMAL_RCEF_Barangay: row.FROMAL_RCEF_Barangay,
                                Hybrid_Area_Harvested: row.Hybrid_Area_Harvested,
                                Hybrid_Average_Yield: row.Hybrid_Average_Yield,
                                Hybrid_Production: row.Hybrid_Production,
                                Registered_Area_Harvested: row.Registered_Area_Harvested,
                                Registered_Average_Yield: row.Registered_Average_Yield,
                                Registered_Production: row.Registered_Production,
                                Certified_Area_Harvested: row.Certified_Area_Harvested,
                                Certified_Average_Yield: row.Certified_Average_Yield,
                                Certified_Production: row.Certified_Production,
                                year: data.INFO.year,      
                                month: data.INFO.month,    // Add month from table2
                                range_date: data.INFO.range_date // Add range_date from table2
                            };
                        }
    
                        var rowHtml = "<tr>";
                            rowHtml += "<td class='first_td'><input type='text' data-field='FROMAL_RCEF_Barangay' data-index='" + index + "' value='" + row.FROMAL_RCEF_Barangay + "' step='any' readonly></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Hybrid_Area_Harvested' data-index='" + index + "' value='" + row.Hybrid_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Hybrid_Average_Yield' data-index='" + index + "' value='" + row.Hybrid_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Hybrid_Production' data-index='" + index + "' value='" + row.Hybrid_Production + "' step='any' placeholder='Production'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Registered_Area_Harvested' data-index='" + index + "' value='" + row.Registered_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Registered_Average_Yield' data-index='" + index + "' value='" + row.Registered_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Registered_Production' data-index='" + index + "' value='" + row.Registered_Production + "' step='any' placeholder='Production'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Certified_Area_Harvested' data-index='" + index + "' value='" + row.Certified_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Certified_Average_Yield' data-index='" + index + "' value='" + row.Certified_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Certified_Production' data-index='" + index + "' value='" + row.Certified_Production + "' step='any' placeholder='Production'></td>";
                        rowHtml += "</tr>";
        
                        // Append the new row to the table
                        $('#VIEW_HARVESTING_FORMAL_RCEF_TABLE tbody').append(rowHtml);
                    });
                }else{
                    $('#VIEW_HARVESTING_FORMAL_RCEF_TABLE tbody').append('<tr><td colspan="10" class="text-center">No Harvested</td></tr>');
                }


                if (data.OWNOTHERS && data.OWNOTHERS.length > 0){
                    data.OWNOTHERS.forEach(function(row, index) {

                        if (!updatedDataOWNOTHERSHarvesting[index]) {
                            updatedDataOWNOTHERSHarvesting[index] = {
                                FROMAL_OWNOTHERS_Barangay: row.FROMAL_OWNOTHERS_Barangay,
                                Hybrid_Area_Harvested: row.Hybrid_Area_Harvested,
                                Hybrid_Average_Yield: row.Hybrid_Average_Yield,
                                Hybrid_Production: row.Hybrid_Production,
                                Registered_Area_Harvested: row.Registered_Area_Harvested,
                                Registered_Average_Yield: row.Registered_Average_Yield,
                                Registered_Production: row.Registered_Production,
                                Certified_Area_Harvested: row.Certified_Area_Harvested,
                                Certified_Average_Yield: row.Certified_Average_Yield,
                                Certified_Production: row.Certified_Production,
                                year: data.INFO.year,      
                                month: data.INFO.month,    // Add month from table2
                                range_date: data.INFO.range_date // Add range_date from table2
                            };
                        }
    
                        var rowHtml = "<tr>";
                            rowHtml += "<td class='first_td'><input type='text' data-field='FROMAL_OWNOTHERS_Barangay' data-index='" + index + "' value='" + row.FROMAL_OWNOTHERS_Barangay + "' step='any' readonly></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Hybrid_Area_Harvested' data-index='" + index + "' value='" + row.Hybrid_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Hybrid_Average_Yield' data-index='" + index + "' value='" + row.Hybrid_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Hybrid_Production' data-index='" + index + "' value='" + row.Hybrid_Production + "' step='any' placeholder='Production'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Registered_Area_Harvested' data-index='" + index + "' value='" + row.Registered_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Registered_Average_Yield' data-index='" + index + "' value='" + row.Registered_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Registered_Production' data-index='" + index + "' value='" + row.Registered_Production + "' step='any' placeholder='Production'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Certified_Area_Harvested' data-index='" + index + "' value='" + row.Certified_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Certified_Average_Yield' data-index='" + index + "' value='" + row.Certified_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Certified_Production' data-index='" + index + "' value='" + row.Certified_Production + "' step='any' placeholder='Production'></td>";
                        rowHtml += "</tr>";
        
                        // Append the new row to the table
                        $('#VIEW_HARVESTING_FORMAL_OWNOTHERS_TABLE tbody').append(rowHtml);
                    });
    
                }else{
                    $('#VIEW_HARVESTING_FORMAL_OWNOTHERS_TABLE tbody').append('<tr><td colspan="10" class="text-center">No Harvested</td></tr>');
                }


                if (data.INFORMAL && data.INFORMAL.length > 0){
                    data.INFORMAL.forEach(function(row, index) {

                        if (!updatedDataINFORMALHarvesting[index]) {
                            updatedDataINFORMALHarvesting[index] = {
                                INFROMAL_Barangay: row.INFROMAL_Barangay,
                                Starter_Area_Harvested: row.Starter_Area_Harvested,
                                Starter_Average_Yield: row.Starter_Average_Yield,
                                Starter_Production: row.Starter_Production,
                                Tagged_Area_Harvested: row.Tagged_Area_Harvested,
                                Tagged_Average_Yield: row.Tagged_Average_Yield,
                                Tagged_Production: row.Tagged_Production,
                                Traditional_Area_Harvested: row.Traditional_Area_Harvested,
                                Traditional_Average_Yield: row.Traditional_Average_Yield,
                                Traditional_Production: row.Traditional_Production,
                                year: data.INFO.year,      
                                month: data.INFO.month,    // Add month from table2
                                range_date: data.INFO.range_date // Add range_date from table2
                            };
                        }
    
                        var rowHtml = "<tr>";
                            rowHtml += "<td class='first_td'><input type='text' data-field='INFROMAL_Barangay' data-index='" + index + "' value='" + row.INFROMAL_Barangay + "' step='any' readonly></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Starter_Area_Harvested' data-index='" + index + "' value='" + row.Starter_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Starter_Average_Yield' data-index='" + index + "' value='" + row.Starter_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Starter_Production' data-index='" + index + "' value='" + row.Starter_Production + "' step='any' placeholder='Production'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Tagged_Area_Harvested' data-index='" + index + "' value='" + row.Tagged_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Tagged_Average_Yield' data-index='" + index + "' value='" + row.Tagged_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Tagged_Production' data-index='" + index + "' value='" + row.Tagged_Production + "' step='any' placeholder='Production'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Traditional_Area_Harvested' data-index='" + index + "' value='" + row.Traditional_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='Traditional_Average_Yield' data-index='" + index + "' value='" + row.Traditional_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='Traditional_Production' data-index='" + index + "' value='" + row.Traditional_Production + "' step='any' placeholder='Production'></td>";
                        rowHtml += "</tr>";
        
                        // Append the new row to the table
                        $('#VIEW_HARVESTING_INFORMAL_TABLE tbody').append(rowHtml);
                    });
                }else{
                    $('#VIEW_HARVESTING_INFORMAL_TABLE tbody').append('<tr><td colspan="10" class="text-center">No Harvested</td></tr>');
                }


                if (data.FSS && data.FSS.length > 0){
                    data.FSS.forEach(function(row, index) {

                        if (!updatedDataFSSHarvesting[index]) {
                            updatedDataFSSHarvesting[index] = {
                                FSS_Barangay: row.FSS_Barangay,
                                FSS_Area_Harvested: row.FSS_Area_Harvested,
                                FSS_Average_Yield: row.FSS_Average_Yield,
                                FSS_Production: row.FSS_Production,
                                year: data.INFO.year,      
                                month: data.INFO.month,    // Add month from table2
                                range_date: data.INFO.range_date // Add range_date from table2
                            };
                        }
    
                        var rowHtml = "<tr>";
                            rowHtml += "<td class='first_td'><input type='text' data-field='FSS_Barangay' data-index='" + index + "' value='" + row.FSS_Barangay + "' step='any' readonly></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='FSS_Area_Harvested' data-index='" + index + "' value='" + row.FSS_Area_Harvested + "' step='any' placeholder='Area Harvested'></td>";
                            rowHtml += "<td><input type='number' readonly min='0' data-field='FSS_Average_Yield' data-index='" + index + "' value='" + row.FSS_Average_Yield + "' step='any' placeholder='Average Yield'></td>";
                            rowHtml += "<td><input type='number' min='0' data-field='FSS_Production' data-index='" + index + "' value='" + row.FSS_Production + "' step='any' placeholder='Production'></td>";
                        rowHtml += "</tr>";
        
                        // Append the new row to the table
                        $('#VIEW_HARVESTING_FSS_TABLE tbody').append(rowHtml);
                    });
                }else{
                    $('#VIEW_HARVESTING_FSS_TABLE tbody').append('<tr><td colspan="4" class="text-center">No Harvested</td></tr>');
                }
                

               

            //GET THE AVERAGE YIELD

                // After appending all rows, add event listeners for "Area Harvested" and "Production" inputs
                $('#VIEW_HARVESTING_FORMAL_NPR_TABLE tbody tr').each(function() {
                    const areaHarvestedInputs = $(this).find('input[placeholder="Area Harvested"]');
                    const productionInputs = $(this).find('input[placeholder="Production"]');
    
                    // Add event listeners to inputs in the current row
                    [...areaHarvestedInputs, ...productionInputs].forEach(input => {
                        input.addEventListener('input', () => {
                            calculateYield($(this)[0]);  // Pass the entire row to calculateYield
                        });
                    });
                });

                $('#VIEW_HARVESTING_FORMAL_RCEF_TABLE tbody tr').each(function() {
                    const areaHarvestedInputs = $(this).find('input[placeholder="Area Harvested"]');
                    const productionInputs = $(this).find('input[placeholder="Production"]');
    
                    // Add event listeners to inputs in the current row
                    [...areaHarvestedInputs, ...productionInputs].forEach(input => {
                        input.addEventListener('input', () => {
                            calculateYield($(this)[0]);  // Pass the entire row to calculateYield
                        });
                    });
                });

                $('#VIEW_HARVESTING_FORMAL_OWNOTHERS_TABLE tbody tr').each(function() {
                    const areaHarvestedInputs = $(this).find('input[placeholder="Area Harvested"]');
                    const productionInputs = $(this).find('input[placeholder="Production"]');
    
                    // Add event listeners to inputs in the current row
                    [...areaHarvestedInputs, ...productionInputs].forEach(input => {
                        input.addEventListener('input', () => {
                            calculateYield($(this)[0]);  // Pass the entire row to calculateYield
                        });
                    });
                });

                $('#VIEW_HARVESTING_INFORMAL_TABLE tbody tr').each(function() {
                    const areaHarvestedInputs = $(this).find('input[placeholder="Area Harvested"]');
                    const productionInputs = $(this).find('input[placeholder="Production"]');
    
                    // Add event listeners to inputs in the current row
                    [...areaHarvestedInputs, ...productionInputs].forEach(input => {
                        input.addEventListener('input', () => {
                            calculateYield($(this)[0]);  // Pass the entire row to calculateYield
                        });
                    });
                });

                $('#VIEW_HARVESTING_FSS_TABLE tbody tr').each(function() {
                    const areaHarvestedInputs = $(this).find('input[placeholder="Area Harvested"]');
                    const productionInputs = $(this).find('input[placeholder="Production"]');
    
                    // Add event listeners to inputs in the current row
                    [...areaHarvestedInputs, ...productionInputs].forEach(input => {
                        input.addEventListener('input', () => {
                            calculateYield($(this)[0]);  // Pass the entire row to calculateYield
                        });
                    });
                });

                
                    
                //GET UPDATE

                    // For VIEW_HARVESTING_FORMAL_NPR_TABLE
                    $('#VIEW_HARVESTING_FORMAL_NPR_TABLE').on('input', 'input[type="number"]', function() {
                        var updatedValue = $(this).val();
                        var field = $(this).data('field');
                        var index = $(this).data('index');
                        updatedDataNPRHarvesting[index] = updatedDataNPRHarvesting[index] || {};  // Create the object if it doesn't exist
                        updatedDataNPRHarvesting[index][field] = updatedValue;  // Update the field with the new value
                    });

                    // For VIEW_HARVESTING_FORMAL_RCEF_TABLE
                    $('#VIEW_HARVESTING_FORMAL_RCEF_TABLE').on('input', 'input[type="number"]', function() {
                        var updatedValue = $(this).val();
                        var field = $(this).data('field');
                        var index = $(this).data('index');
                        updatedDataRCEFHarvesting[index] = updatedDataRCEFHarvesting[index] || {};  // Create the object if it doesn't exist
                        updatedDataRCEFHarvesting[index][field] = updatedValue;  // Update the field with the new value
                    });

                    // For VIEW_HARVESTING_FORMAL_OWNOTHERS_TABLE
                    $('#VIEW_HARVESTING_FORMAL_OWNOTHERS_TABLE').on('input', 'input[type="number"]', function() {
                        var updatedValue = $(this).val();
                        var field = $(this).data('field');
                        var index = $(this).data('index');
                        updatedDataOWNOTHERSHarvesting[index] = updatedDataOWNOTHERSHarvesting[index] || {};  // Create the object if it doesn't exist
                        updatedDataOWNOTHERSHarvesting[index][field] = updatedValue;  // Update the field with the new value
                    });

                    // For VIEW_HARVESTING_INFORMAL_TABLE
                    $('#VIEW_HARVESTING_INFORMAL_TABLE').on('input', 'input[type="number"]', function() {
                        var updatedValue = $(this).val();
                        var field = $(this).data('field');
                        var index = $(this).data('index');
                        updatedDataINFORMALHarvesting[index] = updatedDataINFORMALHarvesting[index] || {};  // Create the object if it doesn't exist
                        updatedDataINFORMALHarvesting[index][field] = updatedValue;  // Update the field with the new value
                    });

                    // For VIEW_HARVESTING_FSS_TABLE
                    $('#VIEW_HARVESTING_FSS_TABLE').on('input', 'input[type="number"]', function() {
                        var updatedValue = $(this).val();
                        var field = $(this).data('field');
                        var index = $(this).data('index');
                        updatedDataFSSHarvesting[index] = updatedDataFSSHarvesting[index] || {};  // Create the object if it doesn't exist
                        updatedDataFSSHarvesting[index][field] = updatedValue;  // Update the field with the new value
                    });


                    $('#saveChangesHarvesting').on('click', function() {
                
                        const dataToSend = {
                            updatedDataNPRHarvesting: JSON.stringify(updatedDataNPRHarvesting),
                            updatedDataRCEFHarvesting: JSON.stringify(updatedDataRCEFHarvesting),
                            updatedDataOWNOTHERSHarvesting: JSON.stringify(updatedDataOWNOTHERSHarvesting),
                            updatedDataINFORMALHarvesting: JSON.stringify(updatedDataINFORMALHarvesting),
                            updatedDataFSSHarvesting: JSON.stringify(updatedDataFSSHarvesting)
                        };


                        $.ajax({
                            url: 'Function/UpdateDATARainfedHarvesting.php',  // PHP script to save updated data
                            type: 'POST',
                            data: dataToSend,  // Stringify the updatedData before sending
                            dataType: 'json',  // Expecting a JSON response
                            success: function(response) {
                              
                                if (response.status === 'success') {
                                    location.reload(); 
                                } else {
                                    alert('Failed to update data!');
                                    console.log('Error details: ', response.message);
                                }
                
                            },
                            error: function(xhr, status, error) {
                                // Enhanced debugging
                                console.error('AJAX Error Details:');
                                console.error('Status:', status);
                                console.error('Error:', error);
                                console.error('XHR Response Text:', xhr.responseText);  // This will log the response from the PHP backend
                    
                                // If the server sent a response, log it
                                if (xhr.responseText) {
                                    console.log('Server Response:', xhr.responseText);
                                }
                    
                                alert('Failed to save changes');
                            }
                        });


                    

                    });
    


                // Show the modal after data has been loaded and processed
                $('#viewModalHarvesting').modal('show');
            },
            error: function() {
                alert('Failed to load data for viewing');
            }
        });
    }








//TRANSITION VIEW TABLE - PLANTING 

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



//TRANSITION VIEW TABLE - HARVESTING

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