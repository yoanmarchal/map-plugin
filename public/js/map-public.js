function initialize() {
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
	var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
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

		google.maps.event.addListener(data.marker, 'click', function() {
			stores.filter(function(store){
				return store !== data.marker;
			}).forEach(function(store){
				store.infoWindow.close(map, data.marker);
			});

			data.infoWindow.open(map, data.marker);
		});
		return data.marker;
	})
	console.log(markers);
	markers.forEach(function(marker){
		bounds.extend(marker.getPosition());
	});

	map.fitBounds(bounds);
	/*
	*
	* Load
	*
	*/
	google.maps.event.addDomListener(window, 'load', initialize);

	/*
	*
	* rezize
	*
	*/
	google.maps.event.addDomListener(window, "resize", function() {
		var center = map.getCenter();
		/* google map v3  */
		google.maps.event.trigger(map, "resize");
		map.setCenter(center);
	});

	//Associate the styled map with the MapTypeId and set it to display.
	map.mapTypes.set('map_style', styledMap);
	map.setMapTypeId('map_style');

}


function loadScript() {

	'use strict';

	var script = document.createElement('script');
	script.type = 'text/javascript';
	script.src = 'https://maps.googleapis.com/maps/api/js?' +
    'callback=initialize';
	document.body.appendChild(script);
}

window.onload = loadScript;
