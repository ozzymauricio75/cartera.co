/////http://stackoverflow.com/questions/8082405/parsing-address-components-in-google-maps-upon-autocomplete-select
//https://developers.google.com/maps/documentation/javascript/geocoding#GeocodingRequests
//https://developers.google.com/maps/documentation/javascript/examples/maptype-overlay?hl=es
//https://developers.google.com/maps/documentation/javascript/examples/map-projection-simple?hl=es

//https://developers.google.com/maps/documentation/javascript/tutorial?hl=es-419
//https://developers.google.com/maps/documentation/javascript/examples/map-geolocation?hl=es-419

var geocoder;
var map;
var marker;

function initialize() {
    var latlng = new google.maps.LatLng({lat: 3.4516467, lng: -76.5319854}); //-> cali
    //var latlng = new google.maps.LatLng({lat: 4.710988599999999, lng: -74.072092}); //-> bogota
	var options = {
		zoom: 12,
		center: latlng,
        mapTypeControl: false,
        streetViewControl: false,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	
	map = new google.maps.Map(document.getElementById("mapa"), options);
	
	geocoder = new google.maps.Geocoder();
	
	marker = new google.maps.Marker({
		map: map,
		draggable: true,
	});
	
	marker.setPosition(latlng);
}

$(document).ready(function () {

	initialize();
	
	function carregarNoMapa(endereco) {
		//geocoder.geocode({ 'address': endereco + ', Brasil', 'region': 'BR' }, function (results, status) {
        geocoder.geocode({ 'address': endereco }, function (results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				if (results[0]) {
					var latitude = results[0].geometry.location.lat();
					var longitude = results[0].geometry.location.lng();
		
					$('#txtEndereco').val(results[0].formatted_address);
					$('#txtLatitude').val(latitude);
                   	$('#txtLongitude').val(longitude);                                                                                                                                                        
                    var address_components = results[0].address_components;
                    var components={}; 
                    var componentscort={}; 
                    jQuery.each(address_components, function(k,v1) {jQuery.each(v1.types, function(k2, v2){components[v2]=v1.long_name});});
                    jQuery.each(address_components, function(kcort,v1cort) {
                        jQuery.each(v1cort.types, function(k2cort, v2cort){
                            componentscort[v2cort]=v1cort.short_name;
                        });
                    });
                    
                    ///////////////
                    /*
                        street_number: "1100", 
                        route: "E Hector St", 
                        locality: "Conshohocken", 
                        political: "United States", 
                        administrative_area_level_3: "Whitemarsh"…
                        administrative_area_level_1: "Pennsylvania"
                        administrative_area_level_2: "Montgomery"
                        administrative_area_level_3: "Whitemarsh"
                        country: "United States"
                        locality: "Conshohocken"
                        political: "United States"
                        postal_code: "19428"
                        route: "E Hector St"
                        street_number: "1100"
                    
                    */
                    //////////////
                    
                    //alert(components.country);
                    $('#countrycod').val(componentscort.country);                     
                    $('#country').val(components.country);
                    $('#locality').val(components.locality);
					$('#administrative_area_level_2').val(components.administrative_area_level_2);
					$('#administrative_area_level_3').val(components.administrative_area_level_3); 
                    $('#administrative_area_level_1').val(components.administrative_area_level_1);                         
                    $('#administrative_area_short_level_1').val(componentscort.administrative_area_level_1);                         
                    $('#postal_code').val(components.postal_code);
                    $('#idplacegeomap').val(results[0].place_id);
                                                                                                                                        
					var location = new google.maps.LatLng(latitude, longitude);
					marker.setPosition(location);
					map.setCenter(location);
					map.setZoom(16);
				}
			}
		})
	}
	
	/*$("#btnEndereco").click(function() {
		if($(this).val() != "")
			carregarNoMapa($("#txtEndereco").val());
	})*/
	
	$("#txtEndereco").blur(function() {
		if($(this).val() != "")
			carregarNoMapa($(this).val());
	})
	
	google.maps.event.addListener(marker, 'drag', function () {
		geocoder.geocode({ 'latLng': marker.getPosition() }, function (results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				if (results[0]) {  
					$('#txtEndereco').val(results[0].formatted_address);
					$('#txtLatitude').val(marker.getPosition().lat());
					$('#txtLongitude').val(marker.getPosition().lng()); 
                    
                     var address_components = results[0].address_components;
                    var components={};
                    var componentscort={}; 
                    jQuery.each(address_components, function(k,v1) {
                        jQuery.each(v1.types, function(k2, v2){
                            components[v2]=v1.long_name
                        });
                    });
                    jQuery.each(address_components, function(kcort,v1cort) {
                        jQuery.each(v1cort.types, function(k2cort, v2cort){
                            componentscort[v2cort]=v1cort.short_name;
                        });
                    });
                    
                    $('#countrycod').val(componentscort.country);                     
                    $('#country').val(components.country);                     
                    $('#locality').val(components.locality);
					$('#administrative_area_level_2').val(components.administrative_area_level_2);
					$('#administrative_area_level_3').val(components.administrative_area_level_3); 
                    $('#administrative_area_level_1').val(components.administrative_area_level_1); 
                    $('#administrative_area_short_level_1').val(componentscort.administrative_area_level_1); 
                    $('#postal_code').val(components.postal_code);
                    $('#idplacegeomap').val(results[0].place_id);
                                                                            
				}
                
                //if (results[1]) {
                    /*
                    .address_components[i][componentForm[addressType]]
                    locality: 'long_name',
                    administrative_area_level_3: 'long_name',
                    administrative_area_level_2: 'long_name',
                    administrative_area_level_1: 'short_name',
                    country: 'long_name'
                    */
                    
                   
                
                //  } 
                
			}
		});
	});
	
	$("#txtEndereco").autocomplete({
		source: function (request, response) {
			//geocoder.geocode({ 'address': request.term + ', Brasil', 'region': 'BR' }, function (results, status) {
            geocoder.geocode({ 'address': request.term }, function (results, status) {
				response($.map(results, function (item) {
					return {
						label: item.formatted_address,
						value: item.formatted_address,
						latitude: item.geometry.location.lat(),
          				longitude: item.geometry.location.lng()
					}
				}));
			})
		},
		select: function (event, ui) {
			$("#txtLatitude").val(ui.item.latitude);
    		$("#txtLongitude").val(ui.item.longitude);
			var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
			marker.setPosition(location);
			map.setCenter(location);
			map.setZoom(16);
		}
	});
	
	/*$("form").submit(function(event) {
		event.preventDefault();
		
		var endereco = $("#txtEndereco").val();
		var latitude = $("#txtLatitude").val();
		var longitude = $("#txtLongitude").val();
		
		alert("Endereço: " + endereco + "\nLatitude: " + latitude + "\nLongitude: " + longitude);
	});*/

});