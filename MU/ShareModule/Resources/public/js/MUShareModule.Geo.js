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

    // add a marker
    marker = new L.marker(centerLocation, {
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
    
    circle = L.circle([parameters.latitude , parameters.longitude] , {radius:parameters.radius} , {
    	color : '#81F79F',
    	fillColor : '#9FF781',
    	fillOpacity : 0.5
    	}).addTo(map);    
    
    jQuery('.marker-data').each(function (index) {
        
    // add a marker
    marker = new L.marker([jQuery(this).attr('data-latitude'), jQuery(this).attr('data-longitude')], {
        draggable: isEditMode
    });
    marker.addTo(map);

    marker.bindPopup('<h4>' + jQuery(this).attr('data-product') + '</h4>' + jQuery(this).attr('data-description'));
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
