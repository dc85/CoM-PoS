var tags = Array("abc","cbc","cnn");


function tagItem(obj) {
	if(obj.click == "false") {
		obj.style.background = '#000000';
		obj.style.color = '#ffffff';
		obj.click = "true";
		tags[tags.length] = obj.key;
		alert(tags.length);
	} else if(obj.click == "true") {
		obj.style.background = '#ffffe0';
		obj.style.color = '#000000';
		obj.click = "false";
		tags[tags.length] = obj.key;
		alert(tags.length);
	}
}