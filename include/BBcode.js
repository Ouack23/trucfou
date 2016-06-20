function insertBalise(balise) {
	var textArea = document.getElementsByName("comment")[0];
	var text = textArea.value;
	var selected_txt = text.substring(textArea.selectionStart, textArea.selectionEnd);
   	var before_txt = text.substring(0, textArea.selectionStart);
   	var after_txt = text.substring(textArea.selectionEnd, text.length);
	var direct = ["b", "code", "i", "img", "noparse", "quote", "s", "table", "td", "tr", "u", "url"];
	var isDirect = false;
	
	for (i=0; i<direct.length; i++) {
		if(balise === direct[i]) {
			textArea.value = before_txt + "[" + balise + "]" + selected_txt + "[/" + balise + "]" + after_txt;
			isDirect = true;
		}
	}
	
	if (!(isDirect)) {
		switch(balise) {
			case "size":
				textArea.value = before_txt + "[size=30]" + selected_txt + "[/size]" + after_txt;
			break;
			
			case "color":
				textArea.value = before_txt + "[color=red]" + selected_txt + "[/color]" + after_txt;
			break;

			default:
				textArea.value += ERREEEEUR;
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
}