// --------------------------------------------------------
// Callback mfunction to sort a JSON table
// -------------------------------------------------------- 
function sortJSONTable(jsonArray, sortKey, reverse){
	jsonArray.sort(function(a, b) {
        // real strings comparison
		if(sortKey == "auteur" || sortKey == "lieu") {
            if(reverse) {
                return a[sortKey] < b[sortKey];
            }
			return a[sortKey] > b[sortKey];
		}
        // numbers (saved as strings) comparison
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

	reverse = element.getAttribute("type") == "sorted" && !reverse;

	// create new table.
	createTable(element.id, reverse, liste_annonces, columns, filters);
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

            // get filter widget
    		var filterWidget = document.getElementById(filters[filterKey]);

            // if filter is a slider (value) + combobox (inf or sup)
    		if(filterWidget.id.startsWith("value_")) {
    			var comparatorName = "sort_" + filterKey; // combobox name
    			var comparatorWidget = document.getElementById(comparatorName);

    			if (comparatorWidget.value == "sup") {
    				hide = row[filterKey] < filterWidget.value;
    			}
    			else {
    				hide = row[filterKey] > filterWidget.value;
    			}
    		}
            // else element is only a comobobox, pick current value
    		else if (filterWidget.id.startsWith("sort_")) {
    			hide = filterWidget.value != "all" && filterWidget.value != row[filterKey];
    		}

            // if column ask for hhide, no need to go further
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

            // links are specifics
            if(col == "link") {
                var link = document.createElement('a');
                link.appendChild(document.createTextNode("Annonce"));
                link.setAttribute("href", row[col]);
                td.appendChild(link);
            }
            else {
                td.appendChild(document.createTextNode(row[col]));
            }

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
