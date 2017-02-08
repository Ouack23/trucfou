// --------------------------------------------------------
// Callback mfunction to sort a JSON table
// -------------------------------------------------------- 
function sortJSONTable(jsonArray, sortKey, reverse){
	jsonArray.sort(function(a, b) {
		if(sortKey == "auteur" || sortKey == "lieu") {
            if(reverse) {
                return a[sortKey] < b[sortKey];
            }
			return a[sortKey] > b[sortKey];
		}
		else {
            if(reverse) {
                return b[sortKey] - a[sortKey];
            }
			return a[sortKey] - b[sortKey];
		}
	});
}

// --------------------------------------------------------
// Callback method to call createTable on click on column header
// -------------------------------------------------------- 
function onClickOnTableHeader(element, liste_annonces, columns, filters, reverse) {
	// store element id
	var id = element.id;
	reverse = element.getAttribute("type") == "sorted" && !reverse;

	// create new table.
	createTable(id, reverse, liste_annonces, columns, filters);
}

// --------------------------------------------------------
// filter a json array
// -------------------------------------------------------- 
function filterJSON(inputArray, filters, columns) {
	var filteredArray = inputArray;

	// for each row
	for(var annonce in inputArray){
    	var hide = false;
    	var row = inputArray[annonce];

		// check each filtered column
    	for(var filterKey in filters) {
    		var element = document.getElementById(filters[filterKey]);
    		if(element.id.startsWith("value_")) {
    			var sortDirection = "sort_" + filterKey;
    			var sortElement = document.getElementById(sortDirection);

    			if (sortElement.value == "sup") {
    				hide = row[filterKey] < element.value;
    			}
    			else {
    				hide = row[filterKey] > element.value;
    			}
    		}
    		else if (element.id.startsWith("sort_")) {
    			hide = element.value != "all" && element.value != row[filterKey];
    		}
    		if(hide)
    			break;
    	}
    	if(hide)
    	{
    		delete filteredArray[annonce];
    	}
    }

	return filteredArray;
}

// --------------------------------------------------------
// create a sorted and filtered table
// -------------------------------------------------------- 
function createTable(sortColumn, reverse, liste_annonces, columns, filters){

	//remove old table
	var oldTable = document.getElementById("annoncesArray");
	if(oldTable != null) {
		oldTable.parentNode.removeChild(oldTable);
	}

	// create DOM elements and json lists.
    var annonceTitle = document.getElementById("annonceTitle"),
        tbl  = document.createElement('table');

	tbl.id = "annoncesArray";

	// generate table header.
    var tr = tbl.insertRow();
    for(var col in columns) {
    	var th = document.createElement("th");
    	th.appendChild(document.createTextNode(columns[col]));
    	th.id = col;

    	var sortingCriteria = "unsorted";
    	if(col == sortColumn) {
			th.style.textDecoration = "underline";
			sortingCriteria = "sorted";
    	}
    	th.setAttribute("type", sortingCriteria);
    	th.addEventListener("click", function() {onClickOnTableHeader(this, liste_annonces, columns, filters, reverse);} );
    	tr.appendChild(th);
    }

    // filter array
    var filtered_annonces = filterJSON(liste_annonces, filters, columns);

    // sort array
    sortJSONTable(filtered_annonces, sortColumn, reverse);

	// generate resulting dom
    for(var annonce in filtered_annonces){
        tr = tbl.insertRow();
        var row = filtered_annonces[annonce];
        for(var col in columns) {
        	var td = tr.insertCell();
            td.appendChild(document.createTextNode(row[col]));

            // add class for note and habit to color cells. 
            if(col == "habit" || col == "note") {
            	var className = "habit"; // TODO rename this class !
            	className += row[col]; // e.g. "habit2"
            	td.setAttribute("class", className);
            }
        }
    }
    annonceTitle.appendChild(tbl);
}
