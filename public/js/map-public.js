function initializeMap() {
	'use strict';

	// Create an array of styles.
	var styles = [
		{
			stylers: [
				{ hue: '00ffe6' },
				{ saturation: -20 }
			]
		},{
			featureType: 'road',
			elementType: 'geometry',
			stylers: [
				{ lightness: 100 },
				{ visibility: 'simplified' }
			]
		},{
			featureType: 'road',
			elementType: 'labels',
			stylers: [
				{ visibility: 'on' }
			]
		}
	];

	// Create a new StyledMapType object, passing it the array of styles,
	// as well as the name to be displayed on the map type control.
	var styledMap = new google.maps.StyledMapType(styles,{name: 'Styled Map'});

	// Enable the visual refresh
	google.maps.visualRefresh = true;

	// Option de la map
	var mapOptions = {
		center: new google.maps.LatLng(45.8438184,1.2637197),
		zoom: 14,
		panControl: false,
		draggable: true,
		tilt: 45,
		zoomControl: true,
		scaleControl: false,
		scrollwheel: true,
		disableDoubleClickZoom: true,
		mapTypeControlOptions: {
			mapTypeIds: [google.maps.MapTypeId.HYBRID, 'map_style']
		}
	};

	// creation de la map
	var domNode = document.getElementById('map-canvas');
	var map = new google.maps.Map(domNode, mapOptions);
	var stores = [].slice.call(document.querySelectorAll('.store-item'));
	var bounds = new google.maps.LatLngBounds();

	var markers = stores.map(function(item){
		return {
			title: item.getAttribute('data-title'),
			coords: item.getAttribute('data-coords').split(',').map(function(coord){
				return coord.trim();
			}),
			adress: item.getAttribute('data-adress'),
			phone: item.getAttribute('data-phone')
		}
	}).map(function(item){
		var contentString = '<div id="info-map" >'+	item.title +	'</p></div>';
		var contentStringSecond = '<p>'	+	item.adress 	+	'</p>';
		// creation de la info box
		var infoWindow = new google.maps.InfoWindow({
			content: contentString + contentStringSecond,
			position: new google.maps.LatLng(item.coords[0], item.coords[1]),
			disableAutoPan: 1
		});

		// marker
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(item.coords[0], item.coords[1]),
			map: map,
			animation: google.maps.Animation.DROP,
			title: item.title
		});

		return {
			marker: marker,
			infoWindow: infoWindow
		};

	}).map(function(data){

		console.log(data);

		google.maps.event.addListener(data.marker, 'click', function() {

				if (data.marker.wasClicked) {
            data.infoWindow.close(map, data.marker);
            data.marker.wasClicked = false;
        } else {
						data.infoWindow.open(map, data.marker);
            data.marker.wasClicked = true;
        }

		});

		google.maps.event.addListener(data.marker, 'mouseover', function () {
			 data.infoWindow.open(map, data.marker);
	 	});

		 google.maps.event.addListener(data.marker, 'mouseout', function () {
				 if (data.infoWindow.getMap() && data.marker.wasClicked === false) {
						 data.infoWindow.close();
				 }
		 });

		 google.maps.event.addListener(data.infoWindow, 'closeclick', function () {
				 data.marker.wasClicked = false;
		 });

		return data.marker;
	});

	console.log(markers);
	markers.forEach(function(marker){
		bounds.extend(marker.getPosition());
	});


	// Add a marker clusterer to manage the markers.
	// When adding via unpkg, the MarkerClusterer can be accessed at markerClusterer.MarkerClusterer.
  new markerClusterer.MarkerClusterer({ markers, map });

	map.fitBounds(bounds);


	/*
	*
	* rezize
	*
	*/
	google.maps.event.addListener(window, "resize", function() {
		var center = map.getCenter();
		/* google map v3  */
		google.maps.event.trigger(map, "resize");
		map.setCenter(center);
	});

	//Associate the styled map with the MapTypeId and set it to display.
	map.mapTypes.set('map_style', styledMap);
	map.setMapTypeId('map_style');

}

	/*
	*
	* Load
	*
	*/
	document.addEventListener('DOMContentLoaded', initializeMap)