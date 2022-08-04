function initMap() {
  var location = new google.maps.LatLng(52.414169, -4.068680);

  var styledMapType = new google.maps.StyledMapType(
      [{
              "elementType": "geometry",
              "stylers": [{
                  "color": "#f5f5f5"
              }]
          },
          {
              "elementType": "labels",
              "stylers": [{
                  "visibility": "off"
              }]
          },
          {
              "elementType": "labels.icon",
              "stylers": [{
                  "visibility": "off"
              }]
          },
          {
              "elementType": "labels.text.fill",
              "stylers": [{
                  "color": "#616161"
              }]
          },
          {
              "elementType": "labels.text.stroke",
              "stylers": [{
                  "color": "#f5f5f5"
              }]
          },
          {
              "featureType": "administrative.land_parcel",
              "elementType": "labels.text.fill",
              "stylers": [{
                  "color": "#bdbdbd"
              }]
          },
          {
              "featureType": "administrative.neighborhood",
              "stylers": [{
                  "visibility": "off"
              }]
          },
          {
              "featureType": "landscape.natural.terrain",
              "elementType": "geometry.fill",
              "stylers": [{
                  "visibility": "simplified"
              }]
          },
          {
              "featureType": "poi",
              "elementType": "geometry",
              "stylers": [{
                  "color": "#eeeeee"
              }]
          },
          {
              "featureType": "poi",
              "elementType": "labels.text.fill",
              "stylers": [{
                  "color": "#757575"
              }]
          },
          {
              "featureType": "poi.park",
              "elementType": "geometry",
              "stylers": [{
                  "color": "#e5e5e5"
              }]
          },
          {
              "featureType": "poi.park",
              "elementType": "labels.text.fill",
              "stylers": [{
                  "color": "#9e9e9e"
              }]
          },
          {
              "featureType": "road",
              "stylers": [{
                  "visibility": "off"
              }]
          },
          {
              "featureType": "road",
              "elementType": "geometry",
              "stylers": [{
                  "color": "#ffffff"
              }]
          },
          {
              "featureType": "road.arterial",
              "elementType": "labels.text.fill",
              "stylers": [{
                  "color": "#757575"
              }]
          },
          {
              "featureType": "road.highway",
              "elementType": "geometry",
              "stylers": [{
                  "color": "#dadada"
              }]
          },
          {
              "featureType": "road.highway",
              "elementType": "labels.text.fill",
              "stylers": [{
                  "color": "#616161"
              }]
          },
          {
              "featureType": "road.local",
              "elementType": "labels.text.fill",
              "stylers": [{
                  "color": "#9e9e9e"
              }]
          },
          {
              "featureType": "transit",
              "elementType": "geometry.fill",
              "stylers": [{
                  "visibility": "off"
              }]
          },
          {
              "featureType": "transit.line",
              "elementType": "geometry",
              "stylers": [{
                  "color": "#e5e5e5"
              }]
          },
          {
              "featureType": "transit.station",
              "elementType": "geometry",
              "stylers": [{
                  "color": "#eeeeee"
              }]
          },
          {
              "featureType": "water",
              "elementType": "geometry",
              "stylers": [{
                  "color": "#c9c9c9"
              }]
          },
          {
              "featureType": "water",
              "elementType": "geometry.stroke",
              "stylers": [{
                  "visibility": "off"
              }]
          },
          {
              "featureType": "water",
              "elementType": "labels.icon",
              "stylers": [{
                  "visibility": "off"
              }]
          },
          {
              "featureType": "water",
              "elementType": "labels.text",
              "stylers": [{
                  "visibility": "off"
              }]
          },
          {
              "featureType": "water",
              "elementType": "labels.text.fill",
              "stylers": [{
                  "color": "#9e9e9e"
              }]
          }
      ], {
          name: 'Styled Map'
      });


  map = new google.maps.Map(document.getElementById('map'), {
      zoom: 2,

      mapTypeControlOptions: {
          mapTypeIds: ['roadmap', 'satellite', 'hybrid', 'terrain', 'styled_map']
      },

      center: {
          lat: 52.41428963134439, 
          lng: -4.086294988264206
      },
  });
  // directionsDisplay.setMap(map);


  //Associate the styled map with the MapTypeId and set it to display.
  map.mapTypes.set('styled_map', styledMapType);
  map.setMapTypeId('styled_map');


  /* Data points defined as an array of LatLng objects */
  // var heatmapData = [
  //   new google.maps.LatLng(37.782, -122.447),
  //   new google.maps.LatLng(37.782, -122.445),
  //   new google.maps.LatLng(37.782, -122.443),
  //   new google.maps.LatLng(37.782, -122.441),
  //   new google.maps.LatLng(37.782, -122.439),
  //   new google.maps.LatLng(37.782, -122.437),
  //   new google.maps.LatLng(37.782, -122.435),
  //   new google.maps.LatLng(37.785, -122.447),
  //   new google.maps.LatLng(37.785, -122.445),
  //   new google.maps.LatLng(37.785, -122.443),
  //   new google.maps.LatLng(37.785, -122.441),
  //   new google.maps.LatLng(37.785, -122.439),
  //   new google.maps.LatLng(37.785, -122.437),
  //   new google.maps.LatLng(37.785, -122.435)
  // ];

  // var sanFrancisco = new google.maps.LatLng(37.774546, -122.433523);

  // map = new google.maps.Map(document.getElementById('map'), {
  //   center: sanFrancisco,
  //   zoom: 13,
  //   mapTypeId: 'satellite'
  // });

  // var heatmap = new google.maps.visualization.HeatmapLayer({
  //   data: heatmapData
  // });
  // heatmap.setMap(map);
  planet.init();

}