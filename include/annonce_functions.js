// --------------------------------------------------------
// Callback mfunction to sort a JSON table
// -------------------------------------------------------- 
function sortJSONTable(jsonArray, sortKey){
	jsonArray.sort(function(a, b) {
		if(sortKey == "auteur" || sortKey == "lieu") {
			return a[sortKey] > b[sortKey];
		}
		else {
			return a[sortKey] - b[sortKey];
		}
	});
}

// --------------------------------------------------------
// Callback method to call createTable on click on column header
// -------------------------------------------------------- 
function onClickOnTableHeader(element, liste_annonces, columns, filters) {
	// store element id
	var id = element.id;

	// create new table.
	createTable(id, liste_annonces, columns, filters);
}

// --------------------------------------------------------
// filter a json array
// -------------------------------------------------------- 
function filterJSON(inputArray, filters, columns) {
	var filteredArray = inputArray;

	for(var annonce in inputArray){
    	var hide = false;
    	var row = inputArray[annonce];

    	for(var filterKey in filters) {
    		var element = document.getElementById(filters[filterKey]);
    		if(element.id.startsWith("value_")) {
    			hide = row[filterKey] < element.value;
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
function createTable(sortColumn, liste_annonces, columns, filters){

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
    	th.addEventListener("click", function() {onClickOnTableHeader(this, liste_annonces, columns, filters);} );
    	tr.appendChild(th);
    }

    // filter array
    var filtered_annonces = filterJSON(liste_annonces, filters, columns);

    // sort array
    sortJSONTable(filtered_annonces, sortColumn);

	// generate resulting dom
    for(var annonce in filtered_annonces){
        tr = tbl.insertRow();
        var row = filtered_annonces[annonce];
        for(var col in columns) {
        	var td = tr.insertCell();
            td.appendChild(document.createTextNode(row[col]));
            if(col == "habit") {
            	var c = "habit";
            	c += row[col];
            	td.setAttribute("class", c);
            }
        }
    }
    annonceTitle.appendChild(tbl);
}
