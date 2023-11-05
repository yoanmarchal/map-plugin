function initializeMap() {
	'use strict';

	// Create an array of styles.
	var styles = [
		{
			stylers: [
				{ hue: '00ffe6' },
				{ saturation: -20 }
			]
		}, {
			featureType: 'road',
			elementType: 'geometry',
			stylers: [
				{ lightness: 100 },
				{ visibility: 'simplified' }
			]
		}, {
			featureType: 'road',
			elementType: 'labels',
			stylers: [
				{ visibility: 'on' }
			]
		}
	];

	// Create a new StyledMapType object, passing it the array of styles,
	// as well as the name to be displayed on the map type control.
	var styledMap = new google.maps.StyledMapType(styles, { name: 'Styled Map' });

	// Enable the visual refresh
	google.maps.visualRefresh = true;

	var domNode = document.getElementById('map-canvas');
	let optZoom = domNode.dataset.zoom ?? 14;
	let optPanControl = domNode.dataset.pancontrol ?? false;
	let optZoomControl = domNode.dataset.zoomcontrol ?? true;
	let optDraggable = domNode.dataset.draggable ?? true;
	let optTilt = domNode.dataset.tilt ?? 45;
	let optScrollwheel = domNode.dataset.scrollwheel ?? true;
	let optScaleControl = domNode.dataset.scalecontrol ?? false;
	let optDisableDoubleClickZoom = domNode.dataset.disabledblclickzoom ?? true;

	// Option de la map
	var mapOptions = {
		center: new google.maps.LatLng(45.8438184, 1.2637197),
		zoom: optZoom,
		panControl: optPanControl,
		draggable: optDraggable,
		tilt: optTilt,
		zoomControl: optZoomControl,
		scaleControl: optScaleControl,
		scrollwheel: optScrollwheel,
		disableDoubleClickZoom: optDisableDoubleClickZoom,
		mapTypeControlOptions: {
			mapTypeIds: [google.maps.MapTypeId.HYBRID, 'map_style']
		}
	};

	// creation de la map

	var map = new google.maps.Map(domNode, mapOptions);
	var stores = [].slice.call(document.querySelectorAll('.store-item'));
	var bounds = new google.maps.LatLngBounds();

	var markers = stores.map(function (item) {
		return {
			title: item.getAttribute('data-title'),
			coords: item.getAttribute('data-coords').split(',').map(function (coord) {
				return coord.trim();
			}),
			adress: item.getAttribute('data-adress'),
			phone: item.getAttribute('data-phone')
		}
	}).map(function (item) {
		var contentString = '<div id="info-map" >' + item.title + '</p></div>';
		var contentStringSecond = '<p>' + item.adress + '</p>';
		// creation de la info box
		var infoWindow = new google.maps.InfoWindow({
			content: '<div class="marker-info">' + contentString + contentStringSecond + '</div>',
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

	}).map(function (data) {

		google.maps.event.addListener(data.marker, 'click', () => {

			if (data.marker.wasClicked) {
				data.infoWindow.close(map, data.marker);
				data.marker.wasClicked = false;
			} else {
				data.infoWindow.open(map, data.marker);
				data.marker.wasClicked = true;
			}

		}, {passive: true});

		google.maps.event.addListener(data.marker, 'mouseover', () => {
			data.infoWindow.open(map, data.marker);
		}, {passive: true});

		google.maps.event.addListener(data.marker, 'mouseout', () => {
			if (data.infoWindow.getMap() && data.marker.wasClicked === false) {
				data.infoWindow.close();
			}
		}, {passive: true});

		google.maps.event.addListener(data.infoWindow, 'closeclick', () => {
			data.marker.wasClicked = false;
		}, {passive: true});

		return data.marker;
	});

	markers.forEach((marker) => {
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
	google.maps.event.addListener(window, "resize", () => {
		var center = map.getCenter();
		/* google map v3  */
		google.maps.event.trigger(map, "resize");
		map.setCenter(center);
	}, {passive: true});

	//Associate the styled map with the MapTypeId and set it to display.
	map.mapTypes.set('map_style', styledMap);
	map.setMapTypeId('map_style');

}