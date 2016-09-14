function insertBalise(balise) {
	var textArea = document.getElementsByName("comment")[0];
	var text = textArea.value;
	var selected_txt = text.substring(textArea.selectionStart, textArea.selectionEnd);
   	var before_txt = text.substring(0, textArea.selectionStart);
   	var after_txt = text.substring(textArea.selectionEnd, text.length);
	var direct = ["b", "code", "i", "img", "noparse", "quote", "s", "table", "td", "tr", "u", "url"];
	var isDirect = false;
	
	for(i=0; i<direct.length; i++) {
		if(balise === direct[i]) {
			textArea.value = before_txt + "[" + balise + "]" + selected_txt + "[/" + balise + "]" + after_txt;
			isDirect = true;
		}
	}
	
	if(!(isDirect)) {
		switch(balise) {
			case "size":
				textArea.value = before_txt + "[size=30]" + selected_txt + "[/size]" + after_txt;
			break;
			
			case "color":
				textArea.value = before_txt + "[color=red]" + selected_txt + "[/color]" + after_txt;
			break;

			default:
				textArea.value += 'ERREEEEUR';
			break;
		}
	}
}

function doPreview() {
	var div = document.getElementById("invisiblePreview");
	var myData = div.textContent;
	var textArea = document.getElementsByName("comment")[0];
	console.log(myData);
	var result = XBBCODE.process({text: myData, removeMisalignedTags: true, addInLineBreaks: true});
	var output = document.getElementById("visualPreview");
	output.innerHTML = result.html;
	textArea.value = myData;
	console.error("Errors", result.error);
	console.dir(result.errorQueue);
	console.log(result.html);
}

/* French initialisation for the jQuery UI date picker plugin. */
/* Written by Keith Wood (kbwood{at}iinet.com.au),
			  Stéphane Nahmani (sholby@sholby.net),
			  Stéphane Raimbault <stephane.raimbault@gmail.com> */
( function( factory ) {
	if ( typeof define === "function" && define.amd ) {

		// AMD. Register as an anonymous module.
		define( [ "../widgets/datepicker" ], factory );
	} else {

		// Browser globals
		factory( jQuery.datepicker );
	}
}( function( datepicker ) {

datepicker.regional.fr = {
	closeText: "Fermer",
	prevText: "Précédent",
	nextText: "Suivant",
	currentText: "Aujourd'hui",
	monthNames: [ "janvier", "février", "mars", "avril", "mai", "juin",
		"juillet", "août", "septembre", "octobre", "novembre", "décembre" ],
	monthNamesShort: [ "janv.", "févr.", "mars", "avr.", "mai", "juin",
		"juil.", "août", "sept.", "oct.", "nov.", "déc." ],
	dayNames: [ "dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi" ],
	dayNamesShort: [ "dim.", "lun.", "mar.", "mer.", "jeu.", "ven.", "sam." ],
	dayNamesMin: [ "D","L","M","M","J","V","S" ],
	weekHeader: "Sem.",
	dateFormat: "dd/mm/yy",
	firstDay: 1,
	isRTL: false,
	showMonthAfterYear: false,
	yearSuffix: "" };
datepicker.setDefaults( datepicker.regional.fr );

return datepicker.regional.fr;

} ) );

$(function() {
      $( "#datepicker" ).datepicker();
  });