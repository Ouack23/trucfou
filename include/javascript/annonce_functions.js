// --------------------------------------------------------
// Callback mfunction to sort a JSON table
// -------------------------------------------------------- 
function sortJSONTable(jsonArray, sortKey){
	jsonArray.sort(function(a, b) {
        // real strings comparison
		if(sortKey == "auteur" || sortKey == "lieu") {
			return a[sortKey] > b[sortKey];
		}
        // numbers (saved as strings) comparison
		else {
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
    	var row = inputArray[annonce];
        var hide = false;

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
            else if (filterWidget.id == "hide_disabled" && filterWidget.checked) { 
                hide = row["available"] == 0;
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
// unavailable
// -------------------------------------------------------- 
function unavailable() {
    var id = document.getElementsByClassName("detailsNumber")[0].innerText;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var availability = document.getElementsByClassName("availability")[0];
            availability.getElementsByTagName("h3")[0].style.display = "inline-block";
            availability.getElementsByTagName("input")[0].style.display = "none";   
        }
    }

    xmlhttp.open("GET", "include/Ajax/unavailable_annonce.php?id=" + id, true);
    xmlhttp.send();
}

// --------------------------------------------------------
// AJAX method to show details
// -------------------------------------------------------- 
function showDetails(elementSource) {

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var stringData = this.response;
            var data = JSON.parse(stringData);

            var box = document.getElementById("annonce_details");
            box.setAttribute("style", 'display="inline-block"');

            // set title
            var title = document.getElementsByClassName("details_number")[0];
            title.innerText = "Détails de l'annonce " + data["id"];

            // reminder
            var table_reminder = document.getElementsByClassName("table-reminder")[0];
            while(table_reminder.firstChild) {
                table_reminder.removeChild(table_reminder.firstChild);
            }
            var table_header = document.getElementsByClassName("table-header")[0].cloneNode(true);
            table_reminder.appendChild(table_header);
            var table_row = elementSource.parentNode.cloneNode(true);
            table_reminder.appendChild(table_row);

            // Set note comboBox
            var sel = document.getElementsByClassName("userNote")[0];
            var options = sel.getElementsByTagName("option");
            var i = options.length;

            while (i--) {
                option = options[i];
                if (option.getAttribute("value") == data["note"]) {
                    sel.selectedIndex = i;
                    break;
                }
            }

            // available
            var availability = document.getElementsByClassName("availability")[0];

            if(data["available"] == 1) {
                availability.getElementsByTagName("h3")[0].style.display = "none";
                availability.getElementsByTagName("input")[0].style.display = "inline-block";   
            }
            else {
                availability.getElementsByTagName("h3")[0].style.display = "inline-block";
                availability.getElementsByTagName("input")[0].style.display = "none";   
            }

            // author line
            var authorActions = document.getElementsByClassName("author-element")[0];
            authorActions.style.display = "none";

            // set comment button
            var add_comment_btn = document.getElementsByClassName("new_comment_btn")[0];
            add_comment_btn.setAttribute("action", "new_comment.php?annonce="+data["id"]);

            // list all comments
            var comment_title = document.getElementsByClassName("comment_title")[0];
            comment_title.innerText = data["comments"].length > 0 ? "Commentaires" : "Pas de commentaires";

            var box_content = document.getElementsByClassName("table")[0];
            var old_comments = box_content.getElementsByClassName("block");
            while(old_comments[0]) {
                old_comments[0].parentNode.removeChild(old_comments[0]);
            }

            for (var comment_id = 0; comment_id < data["comments"].length ; comment_id++) {
                var com_date = data["comments"][comment_id]["date"];
                var com_author = data["comments"][comment_id]["auteur"];
                var com_text = data["comments"][comment_id]["comment"];

                var com_block = document.createElement("div");
                com_block.setAttribute("class", "block");
                com_block.innerHTML = `
                            <ul class="block-titre">
                                <li class="block-quand"><i class="fa fa-clock-o fa-fw"></i>` + com_date + `</li>
                                <li class="block-quoi"><i class="fa fa-commenting-o fa-fw"></i> Par <span class="comment-author">` + com_author + `</span></li>
                            </ul>
                            <p>`+com_text+`</p>
                `
                
                box_content.insertBefore(com_block, add_comment_btn);
            }


        }
    };

    xmlhttp.open("GET", "include/Ajax/annonce_details.php?id=" + elementSource.id, true);
    xmlhttp.send();

}

// --------------------------------------------------------
// create a sorted and filtered table
// -------------------------------------------------------- 
function createTable(sortColumn, reverse, liste_annonces, columns, filters){

	//remove old table
	var oldTable = document.getElementById("annonces-table");
    if(oldTable != null) {
        while(oldTable.firstChild != null) {
            oldTable.removeChild(oldTable.firstChild);
        }
    }

	// create DOM elements and json lists.
    var annonces_box = document.getElementById("annonces-table"),
        tbl  = document.createElement('table');

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
    tr.setAttribute("class", "table-header");

    // filter array
    var filtered_annonces = filterJSON(liste_annonces, filters, columns);

    // sort array
    sortJSONTable(filtered_annonces, sortColumn);
    if(reverse) {
        filtered_annonces.reverse();
    }


	// generate resulting dom
    for (var annonce in filtered_annonces){
        tr = tbl.insertRow();
        var row = filtered_annonces[annonce];

        for (var col in columns) {
        	var td = tr.insertCell();

            // links are specifics
            if (col == "link") {
                var link = document.createElement('a');
                link.appendChild(document.createTextNode("Annonce"));
                link.setAttribute("href", row[col]);
                td.appendChild(link);
            }
            // details are specifics too
            else if (col == "details") {
                var detailsSpan = document.createElement("span");
                detailsSpan.appendChild(document.createTextNode("Details"));
                detailsSpan.setAttribute("class", "simili-link");
                td.appendChild(detailsSpan);
                td.addEventListener("click", function() {showDetails(this); });
                td.id = row["id"];
            }
            else if (col.startsWith("superf") && row[col] == 1) {
                td.setAttribute("class", "unknown");
                td.appendChild(document.createTextNode("Inconnue"));
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

        if (row["available"] == 0) {
            tr.setAttribute("class", "unavailable");
        }

    }
    annonces_box.appendChild(tbl);
}
