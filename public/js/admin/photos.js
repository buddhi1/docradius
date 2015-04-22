
// photo class
function Photo(arguments) {
	this.file;
	//this.url = "http://www.barkteryneexistuje.cz/wp-content/uploads/Cute-Panda-Bears-animals-34915025-2560-1600.jpg";
	this.imageData;
	this.imgWidth;
	this.imgHeight;
	this.fixedHeight = 300;
	this.fixedWidth = 300;

	this.status = false;


	if(!(arguments.file === undefined)){
		this.file = arguments.file;
	}
	if(!(arguments.url === undefined)) {
		this.url = arguments.url;
	}
	if(!(arguments.imageData === undefined)) {
		this.imageData = arguments.imageData;
	}
}

// load an image
Photo.prototype.loadFromFile = function(callback) {
	var photos = this;
	var reader = new FileReader();

	reader.onload = function(e){

		var canvas = document.createElement('canvas');
		var context = canvas.getContext("2d");

		var image = new Image;
	
		image.onload = function(){
			canvas.width = image.width;
			canvas.height = image.height;
			context.drawImage(image, 0, 0);
			photos.imageData = canvas.toDataURL('Image/jpeg',.7);
			photos.imgWidth = image.width;
			photos.imgHeight = image.height;
			photos.status = true;
			callback();
		}

		image.src = e.target.result;
	}

	reader.readAsDataURL(this.file);

}

// resizing the images according to the given X and Y values
Photo.prototype.resize = function(x, y) {

	var canvas = document.createElement('canvas');

	canvas.width  = x;
	canvas.height = y;

	var context = canvas.getContext("2d");
	
	var img = new Image;

	img.src = this.imageData;
	context.drawImage(img, 0, 0, canvas.width, canvas.height);
	var imgCanvas = canvas.toDataURL('Image/jpeg',.7);
	return new Photo({imageData: imgCanvas});
}

// Resize the width of a photo according to a give X value and corrects the Y value according to the X value
Photo.prototype.resizeX = function(x) {

	var bestHeight, bestWidth;
	var ratio = x/this.imgWidth;

	if(this.imgWidth>x) {
		bestWidth = x;
		bestHeight = this.imgHeight*ratio;
	} else {
		bestHeight = this.imgHeight*ratio;
		bestWidth = x;
	}

	return new Photo(this.resize(bestWidth, bestHeight));
}

// Resize the height of a photo according the given Y value and corrects the X value according to the Y value
Photo.prototype.resizeY = function(y) {

	var bestHeight, bestWidth;
	var ratio = y/this.imgHeight;

	if(this.imgHeight>y) {
		bestHeight = y;
		bestWidth = this.imgWidth*ratio;
	} else {
		bestHeight = y;
		bestWidth = this.imgWidth*ratio;
	}

	return new Photo(this.resize(bestWidth, bestHeight));
}

// Resize the Long Edge according to the given parameter
Photo.prototype.resizeLongEdge = function(edge) {

	if(this.imgHeight>this.imgWidth) {
		if(edge<=this.imgHeight) {
			return new Photo(this.resizeY(edge));
		} else {
			return new Photo(this.resizeY(this.imgHeight));
		}
	} else {
		if(edge<=this.imgWidth) {
			return new Photo(this.resizeX(edge));
		} else {
			return new Photo(this.resizeX(this.imgWidth));
		}
	}
}

//upload an image
//Send a post request to the server with the imageData of the image
Photo.prototype.upload = function(url,callback, id, abc) {
	var image = encodeURIComponent(this.imageData);
    var headers = 'image=' + image;

    if(id!==undefined) {
    	headers += '&id=' + id;
    }

    var xmlHttp = new XMLHttpRequest(); 
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState==4 && xmlHttp.status==200){
            callback(id,abc+1);
        }
    };
    xmlHttp.open( "POST", url, true );
    xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlHttp.send(headers);
}


//-----------
var photo = []; // save the resized original images in this array.
var thumb =[]; // save the resized thumbnails in this array.

// First loads the images to the "photos" array. After all the files have been successfully loaded, images will be resized according to the appropriate thumbnail size
// and displayed in a <img>, inside a <div> called displayArea1. This function is mainly used by category.js
function handleFileSelect(e) {

	document.getElementById("displayArea1").innerHTML = "";
	var file = e.target.files;

	for(var i=0; i < file.length; i++) {
		var f = file[i];
		photo.push(new Photo({file: f}));
	}

	for(p in photo){
		photo[p].loadFromFile(function(){});
	}

	var interval;

	interval = setInterval(function(){
		var loadingStatus = true;
		for (var i = 0; i < photo.length; i++) {
			if(!photo[i].status){
				loadingStatus = false;
			}
		};

		if(loadingStatus){
			for (var i = 0; i < photo.length; i++) {
				clearInterval(interval);
				var resizedImg = photo[i].resizeLongEdge(400);
				var img_thumb = document.createElement("IMG");
				img_thumb.src = resizedImg.imageData;
				document.getElementById("displayArea1").appendChild(img_thumb);
			}
		}
	},1);
}

function uploadOneImage() {
	var interval;

	interval = setInterval(function(){
		var loadingStatus = true;
		for (var i = 0; i < thumb.length; i++) {
			if(!thumb[i].status){
				loadingStatus = false;
			}
		};

		if(loadingStatus){
			for (var i = 0; i < thumb.length; i++) {
				clearInterval(interval);
				var resizedImg = thumb[i].resizeLongEdge(1400);
			}
		}
	},1);
}

function uploadImages() {
	var interval;

	interval = setInterval(function(){
		var loadingStatus = true;
		for (var i = 0; i < photo.length; i++) {
			if(!photo[i].status){
				loadingStatus = false;
			}
		};

		if(loadingStatus){
			for (var i = 0; i < photo.length; i++) {
				clearInterval(interval);
				var resizedImg = photo[i].resizeLongEdge(1400);
				resizedImg.upload('create', resizedImg.responseCheck);
			}
		}
	},1);
}

Photo.prototype.responseCheck = function (res) {
	var img = this;
	if(res == 'Failed') {
		console.log('hello');
	}
}

// First loads the images to the "thumb" array. After all the files have been successfully loaded, images will be resized according to the appropriate thumbnail size
// and displayed in a <img>, inside a <div> called displayArea2.
// Then add the "1400" resized image dataurl to the hidden field
function handleOneFile(e) {

	document.getElementById("displayArea2").innerHTML = "";
	document.getElementById('image_data').value = "";
	thumb = [];
	var hiddenValues;
	var file = e.target.files;

	for(var i=0; i < file.length; i++) {
		var f = file[i];
		thumb.push(new Photo({file: f}));
	}

	for(p in thumb){
		thumb[p].loadFromFile(function(){});
	}

	var interval;

	interval = setInterval(function(){
		var loadingStatus = true;
		for (var i = 0; i < thumb.length; i++) {
			if(!thumb[i].status){
				loadingStatus = false;
			}
		};

		if(loadingStatus){
			for (var i = 0; i < thumb.length; i++) {
				clearInterval(interval);
				var resizedImg = thumb[i].resizeLongEdge(400);
				var maxSize = thumb[i].resizeLongEdge(1400)
				var img_thumb = document.createElement("IMG");
				img_thumb.src = resizedImg.imageData;
				document.getElementById("displayArea2").appendChild(img_thumb);
				document.getElementById('image_data').value = maxSize.imageData;
			}
		}
	},1);
}


// Save all the data in the project form with the project image in the projects table using an ajax post request
function saveProject(callback) {

	var title = document.getElementById('title').value;
	var content = tinyMCE.activeEditor.getContent({format : 'raw'});
	var date = document.getElementById('date').value;
	var summary = document.getElementById('summary').value;	
	var active = 0;
	var member = 0;
	if(document.getElementById('active').checked){
		active = 1;
	}
	if(document.getElementById('member').checked){
		member = 1;
	}
	
	var e = document.getElementById('category_id');
	var category_id = e.value;	

	if(title == "" || summary == ""){		
		alert("Title and summary are required fields. Please check inputs");
	}else{
		if(summary.length > 420){
			alert("Summary length has exceeded the limit. Please restrict summary up to 420 characters");
		}else{
			var headers ='title=' + title + '&content=' + content + '&date=' + date + '&active=' + active + '&member=' + member + '&category_id=' + category_id + '&summary=' + summary;

		    var xmlHttp = new XMLHttpRequest(); 
		    xmlHttp.onreadystatechange = function(){
		        if (xmlHttp.readyState==4 && xmlHttp.status==200){
		            var news_details = xmlHttp.responseText;            
		            uploadProjectImages(news_details, 0);
		        }
		    };
		    xmlHttp.open( "POST", 'create', true );
		    xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		    xmlHttp.send(headers);
		}		
	}
   
}




// Display all the thumbnalis in a <div> called "displayArea1"
function handleProjectFileSelect(e) {
	document.getElementById("displayArea1").innerHTML = "";
	var file = e.target.files;

	for(var i=0; i < file.length; i++) {
		var f = file[i];
		photo.push(new Photo({file: f}));
	}

	for(p in photo){
		photo[p].loadFromFile(function(){});
	}

	var interval;

	interval = setInterval(function(){
		var loadingStatus = true;
		for (var i = 0; i < photo.length; i++) {
			if(!photo[i].status){
				loadingStatus = false;
			}
		};

		if(loadingStatus){
			for (var i = 0; i < photo.length; i++) {
				clearInterval(interval);
				var resizedThumb = photo[i].resizeLongEdge(400);
				var resizedImg = photo[i].resizeLongEdge(600);
				photo[i] = resizedImg;
				var img_thumb = document.createElement("IMG");
				img_thumb.src = resizedThumb.imageData;
				document.getElementById("displayArea1").appendChild(img_thumb);
			}
		}
	},1);
}

Photo.prototype.albumUpload = function(url,callback) {

	var image = encodeURIComponent(this.imageData);
	var pro_name = document.getElementById("name").value;
    var headers = 'image=' + image + '&pro_name=' + pro_name;

    var xmlHttp = new XMLHttpRequest(); 
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState==4 && xmlHttp.status==200){
            callback(xmlHttp.responseText);
        }
    };
    xmlHttp.open( "POST", url, true );
    xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlHttp.send(headers);
}

var uploadStatus = true;
function uploadProjectImages(id, index) {

	if(photo.length-1 < index) {
		Redirect();
	}

	photo[index].upload('album', uploadProjectImages, id, index);
	
}

function saveProjectDetails() {
	saveProject();
}

function Redirect()
{
    window.location="../../admin/news/index";
}

var img_id = document.getElementById('album_data').value;
function uploadAlbum() {

	uploadProjectImages(img_id, 0);
}