<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Outdoor_Journal
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) : the_post(); 
		
		$bare_url = '/edit/?pid='. $post->ID;
		$complete_url = wp_nonce_url( $bare_url, '_wpnonce', 'my_nonce' );
		?>
		
			<a href="<?php echo $complete_url; ?>">Edit Entry</a>	
				
				<?php $location = get_post_meta( $post->ID, 'full_address', true );
				
				do_action('gform_update_post/edit_link', array(
    'post_id' => $post->ID,
    'url'     => home_url('/record/?location=' . $location )
) );
				
		
				$json_string = get_post_meta( $post->ID, 'weather', true );	
				
				$decoded = json_decode($json_string);
				$weather = $decoded->data->weather;
				
				
				$json_moon_string = get_post_meta( $post->ID, 'moon', true );
				
				$moon_decoded = json_decode($json_moon_string);
				$moon = $moon_decoded;
				
				$i = -1;
			
				foreach($weather as $weather_item){
				
					$i++;
				
					$mm_percip = $weather_item->hourly[0]->precipMM;
					$in_percip = $mm_percip * 0.0393701;
					
					$km_vis = $weather_item->hourly[0]->visibility;
					$ml_vis = $km_vis * 0.621371;
					
					$short_date = $weather_item->date;
					$full_date = date("l, F j, Y", strtotime($short_date));
			  		
			  		echo '<h2>Date: ' . $full_date . '</h2>';
			  		echo '<p>Max Temp: ' . $weather_item->maxtempF . '&deg; F</p>';
			  		echo '<p>Min Temp: ' . $weather_item->mintempF . '&deg; F</p>';
			  		echo '<p>Description: ' . $weather_item->hourly[0]->weatherDesc[0]->value . '</p>';
			  		echo '<p>Code: ' . $weather_item->hourly[0]->weatherCode . '</p>';
			  		echo '<p>Wind Direction: ' . $weather_item->hourly[0]->winddir16Point . '</p>';
			  		echo '<p>Wind Speed: ' . $weather_item->hourly[0]->windspeedMiles . ' mph</p>';
			  		echo '<p>Wind Gusts: ' . $weather_item->hourly[0]->WindGustMiles . ' mph</p>';
			  		echo '<p>Cloud Cover: ' . $weather_item->hourly[0]->cloudcover . '%</p>';
			  		echo '<p>Precipitation: ' . round($in_percip, 2) . ' in</p>';
			  		echo '<p>Visibility: ' . round($ml_vis, 1) . ' miles</p>';
			  		echo '<p>Sunrise: ' . $weather_item->astronomy[0]->sunrise . '</p>';
			   		echo '<p>Sunset: ' . $weather_item->astronomy[0]->sunset . '</p>';
			   		
			   		echo '<p>Moon Phase: ' . $moon->response[$i]->moon->phase->name . '</p>';
			   		echo '<p>Illuminated: ' . $moon->response[$i]->moon->phase->illum . '%</p>';
			   		
			   		echo '<br /><hr><br />';
			 	}

		

	$new_items = get_post_meta( $post->ID, 'item', true );
        $new_items = explode(', ', $new_items);
        
        foreach($new_items as $item) {
			echo $item;
		}
        			

			get_template_part( 'template-parts/content', get_post_format() );
			
			
			$post_id = $post->ID;
			$post = get_post($post_id);

	// Get Location and User Name
	$city = get_post_meta( $post_id, 'city', true );
	$city_info = json_decode($city);
	$i = -1;
			
	foreach($city_info as $city_item){
		$i++;
		$city = $city_item[0]->address_components[2]->long_name;
		$state = $city_item[0]->address_components[5]->short_name;
		
		if( $i == 0 ){ echo 'Location: ' . $city . ', ' . $state; }
	}
	
  		
  		
  		
  	// get location values from post
	$start_date = get_post_meta( $post->ID, 'start_date', true );
	$end_date = get_post_meta( $post->ID, 'end_date', true );
	
	
		$date1=date_create($start_date);
		$date2=date_create($end_date);
		$diff=date_diff($date1,$date2);
		$day_count = $diff->d+1;
	    echo 'Days: ' . $day_count;
	



			endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->
	
	

<?php
get_footer();
