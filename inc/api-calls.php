


<?php


/**
* Gravity Forms Get Moon Information
*
* PHP code that gets moon data for the dates entered in the form. The data is stored in a hidden field as JSON data. 
* http://www.aerisweather.com/support/docs/api/reference/endpoints/sunmoon/
*
*/

add_action( 'wpuf_edit_post_after_update', 'save_moon', 1, 1 );
add_action( 'wpuf_add_post_after_insert', 'save_moon', 1, 1 );
function save_moon( $post_id ) {
if ($form_id == 849) {	
	// get location values from post
	$location = get_post_meta( $post_id, 'choose_location', true );
	$start_date = get_post_meta( $post_id, 'start_date', true );
	$end_date = get_post_meta( $post_id, 'end_date', true );
	

	// set HTTP header
	$headers = array(
	    'Content-Type: application/json',
	);
	
	// query string

    $client_id 		= 	'MYmwLFI4gwGLYNFMGX8Ve';
    $client_secret 	=   'RSMDrqOWDCkTTIHiMcgLbtKr9KMtqOcQmmgFXIy2';
    $location 		=   $location;
    $from 	 		=   $start_date;
    $to				=   $end_date;
	       
	$url = 'http://api.aerisapi.com/sunmoon/' . $location . '?' . 'client_id=' . $client_id . '&client_secret=' . $client_secret . '&from=' . $from . '&to=' . $to ;
	
	//Get JSSON
	$result = url_get_contents($url);
		
	//save in custom field called weather
	update_post_meta($post_id, 'moon',  wp_slash($result) );
}	
}




/**
* Gravity Forms Get Weather Information
*
* PHP code that gets weather data for the dates entered in the form. The data is stored in a hidden field as JSON data. 
* http://us.worldweatheronline.com/api/docs/historical-weather-api.aspx#astronomy_element
*
*/

add_action( 'wpuf_edit_post_after_update', 'save_weather', 2, 1 );
add_action( 'wpuf_add_post_after_insert', 'save_weather', 2, 1 );
function save_weather( $post_id ) {
	
	// get location values from post
	$location = get_post_meta( $post_id, 'choose_location', true );
	$start_date = get_post_meta( $post_id, 'start_date', true );
	$end_date = get_post_meta( $post_id, 'end_date', true );
	

	// set HTTP header
	$headers = array(
	    'Content-Type: application/json',
	);
	
	// query string
	$fields = array(
	    'key' 	 =>  '12c36bf711e94f3c6791375cc9f98',
	    'q' 	 =>  $location,
	    'date' 	 =>  $start_date,
	    'enddate'=>  $end_date,
	    'format' =>  'json',
	    'tp' 	 =>  '24',
	    
	);
	$url = 'https://api.worldweatheronline.com/free/v2/past-weather.ashx?' . http_build_query($fields);
	
	//Get JSSON
	$result = url_get_contents($url);
	
	//save in custom field called weather
	update_post_meta($post_id, 'weather', wp_slash($result) );
	
}




/**
* Get city name and state information from coordinates
*
* 
* http://stackoverflow.com/questions/14314183/get-country-name-from-latitude-and-longitude
*
*/
if ($form_id == 849) {
add_action( 'wpuf_edit_post_after_update', 'save_location', 3, 1 );
add_action( 'wpuf_add_post_after_insert', 'save_location', 3, 1 );
function save_location( $post_id ) {
	
	// get location values from post
	$location = get_post_meta( $post_id, 'choose_location', true );

	// set HTTP header
	$headers = array(
	    'Content-Type: application/json',
	);
	       
	$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$location.'&sensor=false';
	
	//Get JSSON
	$result = url_get_contents($url);
		
	//save in custom field called weather
	update_post_meta($post_id, 'city',  wp_slash($result) );
}	
}
