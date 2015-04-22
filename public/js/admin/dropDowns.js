document.getElementById('state').onchange = function(){
	var state_id = document.getElementById('state').value;

	var xmlHttp = new XMLHttpRequest(); 
    xmlHttp.onreadystatechange = function(){

        if (xmlHttp.readyState==4 && xmlHttp.status==200){

            lgaDropDown(xmlHttp.responseText);
        }
    };
    xmlHttp.open( "GET", 'town/dropdowns?state_id=' + state_id, true );
    xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlHttp.send();
}

var lgaDropDown = function(lga) {

	var lgas = JSON.parse(lga);
		
	document.getElementById("lga").options.length=0;
	for(var i=0; lgas.length;++i){
		
		var option = document.createElement("option");
		option.text = lgas[i]['name'];
		option.value = lgas[i]['id'];
		var select = document.getElementById("lga");
		select.appendChild(option);
	}
}
