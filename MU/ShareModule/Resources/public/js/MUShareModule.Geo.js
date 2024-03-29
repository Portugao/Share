'use strict';

var map;
var marker;
var circle;
var popup;
var offerdescription;
var latitude = '';
var longitude = '';
var counter;
var markertext;

/**
 * Initialises geographical display features.
 */
function mUShareInitGeographicalDisplay(parameters, isEditMode)
{
    var centerLocation;

    centerLocation = new L.LatLng(parameters.latitude, parameters.longitude);

    // create map and center to given coordinates
    map = L.map('mapContainer').setView(centerLocation, parameters.zoomLevel);

    // add tile layer
    L.tileLayer(parameters.tileLayerUrl, {
        attribution: parameters.tileLayerAttribution
    }).addTo(map);
    
    // create custom icon
    var paperIcon = L.icon({
        iconUrl: '../../modules/MU/ShareModule/Resources/public/images/icon1.png',
        shadowUrl: '../../modules/MU/ShareModule/Resources/public/images/icon1-shadow.png',
        iconSize: [50, 50], // size of the icon
        shadowSize:   [50, 40], // size of the shadow
        iconAnchor:   [25, 50], // point of the icon which will correspond to marker's location
        shadowAnchor: [25, 40],  // the same for the shadow
        popupAnchor:  [0, -45] // point from which the popup should open relative to the iconAnchor
        });


    // add a marker
    marker = new L.marker(centerLocation, {
    	icon: paperIcon,
        draggable: isEditMode
    });
    marker.addTo(map);

    jQuery('#tabMap').on('shown.bs.tab', function () {
        // redraw the map after it's tab has been opened
        map.invalidateSize();
    });
}

/**
 * Initialises geographical view features.
 */
function mUShareInitGeographicalView(parameters, isEditMode)
{
    var centerLocation;

    centerLocation = new L.LatLng(parameters.latitude, parameters.longitude);

    // create map and center to given coordinates
    map = L.map('mapContainer').setView(centerLocation, parameters.zoomLevel);

    // add tile layer
    L.tileLayer(parameters.tileLayerUrl, {
        attribution: parameters.tileLayerAttribution
    }).addTo(map);
    
    if (parameters.logged == 1) {
    circle = L.circle([parameters.latitude , parameters.longitude] , parameters.radius , {
    	color : 'green',
    	fillColor : 'grey',
    	fillOpacity : 0.2
    	}).addTo(map); 
    
    // create custom icon
    var paperIcon = L.icon({
        iconUrl: '../../modules/MU/ShareModule/Resources/public/images/icon1.png',
        shadowUrl: '../../modules/MU/ShareModule/Resources/public/images/icon1-shadow.png',
        iconSize: [50, 50], // size of the icon
        shadowSize:   [50, 40], // size of the shadow
        iconAnchor:   [25, 50], // point of the icon which will correspond to marker's location
        shadowAnchor: [25, 40],  // the same for the shadow
        popupAnchor:  [0, -45] // point from which the popup should open relative to the iconAnchor
        });
    }
    
    jQuery('.marker-data').each(function (index) {
        
    // add a marker
    marker = new L.marker([jQuery(this).attr('data-latitude'), jQuery(this).attr('data-longitude')], {
    	icon: paperIcon,
        draggable: isEditMode
    });
    marker.addTo(map);

    marker.bindPopup('<h4><a title="' + Translator.__('See more about this offer!') + '" href="">' + jQuery(this).attr('data-product') + '</a></h4>' + jQuery(this).attr('data-description'));
    });   
  
    counter = 0;
    markertext = '';
    jQuery('.pooloffer-data').each(function (index) {
        
    if(counter == 0) {
    // add a marker
    marker = new L.marker([jQuery(this).attr('data-latitude'), jQuery(this).attr('data-longitude')], {
        draggable: isEditMode
    });
    marker.addTo(map);
    counter = counter + 1;
    }
    
    if(jQuery(this).attr('data-description') != '') {
    	offerdescription = jQuery(this).attr('data-description');
    } else {
    	offerdescription = 'No description';
    }
    markertext = markertext + '<h4>' + jQuery(this).attr('data-product') + '</h4>' + offerdescription;
    marker.bindPopup(markertext);
    });

    jQuery('#tabMap').on('shown.bs.tab', function() {
        // redraw the map after it's tab has been opened
        map.invalidateSize();
    });

}

/**
 * Callback method for geolocation functionality.
 */
function mUShareNewCoordinatesEventHandler() {
    var position;

    position = new L.LatLng(jQuery("[id$='latitude']").val(), jQuery("[id$='longitude']").val());
    marker.setLatLng(position);
    map.setView(position, map.getZoom());
}

/**
 * Callback method for geolocation functionality.
 */
function mUShareSetDefaultCoordinates(position) {
    jQuery("[id$='latitude']").val(position.coords.latitude.toFixed(7));
    jQuery("[id$='longitude']").val(position.coords.longitude.toFixed(7));
    mUShareNewCoordinatesEventHandler();
}

function mUShareHandlePositionError(event) {
    mUShareSimpleAlert(jQuery('#mapContainer'), Translator.__('Error during geolocation'), event.message, 'geoLocationAlert', 'danger');
}

/**
 * Initialises geographical editing features.
 */
function mUShareInitGeographicalEditing(parameters)
{
    mUShareInitGeographicalDisplay(parameters, true);

    // init event handler
    jQuery("[id$='latitude']").change(mUShareNewCoordinatesEventHandler);
    jQuery("[id$='longitude']").change(mUShareNewCoordinatesEventHandler);

    map.on('click', function(event) {
        var coords = event.latlng;
        jQuery("[id$='latitude']").val(coords.lat.toFixed(7));
        jQuery("[id$='longitude']").val(coords.lng.toFixed(7));
        mUShareNewCoordinatesEventHandler();
    });
    marker.on('dragend', function (event) {
        var coords = event.target.getLatLng();
        jQuery("[id$='latitude']").val(coords.lat.toFixed(7));
        jQuery("[id$='longitude']").val(coords.lng.toFixed(7));
        mUShareNewCoordinatesEventHandler();
    });

    if (true === parameters.useGeoLocation) {
        // derive default coordinates from users position with html5 geolocation feature
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(mUShareSetDefaultCoordinates, mUShareHandlePositionError, {
                enableHighAccuracy: true,
                maximumAge: 10000,
                timeout: 20000
            });
        }
    }
}

jQuery(document).ready(function() {
    var infoElem, parameters;

    infoElem = jQuery('#geographicalInfo');
    if (infoElem.length == 0) {
        return;
    }

    //if (infoElem.data('context') == 'display' || infoElem.data('context') == 'edit') {
    parameters = {
        latitude: infoElem.data('latitude'),
        longitude: infoElem.data('longitude'),
        zoomLevel: infoElem.data('zoom-level'),
        radius: infoElem.data('radius'),
        logged: infoElem.data('logged'),
        tileLayerUrl: infoElem.data('tile-layer-url'),
        tileLayerAttribution: infoElem.data('tile-layer-attribution'),
        useGeoLocation: false
    };
    //}

    if (infoElem.data('context') == 'display') {
        mUShareInitGeographicalDisplay(parameters, false);
    } else if (infoElem.data('context') == 'edit') {
        parameters.useGeoLocation = infoElem.data('use-geolocation');
        mUShareInitGeographicalEditing(parameters);
    } else if (infoElem.data('context') == 'view') {
    	mUShareInitGeographicalView(parameters, false);
    }
});
