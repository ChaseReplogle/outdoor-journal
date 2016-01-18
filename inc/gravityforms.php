<?php

/**
* 
* Gear List
*
* Uses a custom hook field to pull a list of users custom post type: gear items into a list of checkboxes.
*/

add_action('gear_list', 'add_gear', 1, 3 );
function add_gear( $form_id, $post_id, $form_settings ) { 
        

    $user_ID = get_current_user_id(); 
    $posts = get_posts( 'post_type=gear&posts_per_page=-1&author=' . $user_ID );
    
    echo 	"<div class='wpuf-label'>
        	<label>Gear</label>
    		</div>
    		
    		<div class='wpuf-fields'>
        	<ul class='wpuf-category-checklist'>";
    
    foreach ( $posts as $post ) {
        $item = $post->post_title; 
        $gear_name = get_post_meta( $post_id, 'gear', false);  ?>
        		<li id="<?php echo $item->id; ?>">
        			<label class="selectit"></label>
        			<input type="checkbox" name="gear[]" value="<?php echo $item; ?>" <?php if (in_array($item, $gear_name)){ echo "checked"; } ?> >
        			 <?php echo $item; ?> 
        			</label>
        		</li>
    	<?php }

    echo "</li>
    	 </div>";

}




/****** NOTE: Priority steps 2, 3, 4 are saved in the api-calls.php files ******/



/**
* 
* Save Gear Choices
*
* Deletes the previously set gear choices and saves the new gear choices as a custom field with a key of 'gear.'
*/
  
add_action( 'wpuf_add_post_after_insert', 'update_gear_list', 4, 1  );
add_action( 'wpuf_edit_post_after_update', 'update_gear_list', 4, 1  );     
function update_gear_list( $post_id ) {

    if ( isset( $_POST['gear'] ) ) {
    	delete_post_meta($post_id, 'gear', '');
    	
    	foreach($_POST['gear'] as $checkbox) {
           add_post_meta( $post_id, 'gear', $checkbox, false);
    	}
	}

}

     
     


/**
* Create Custom post for each new gear item
*
* Splits apart the item entry by commas, and then creates a custom post in the gear type for each item entered.
*/

add_action( 'wpuf_edit_post_after_update', 'add_gear_item', 6, 1 );
add_action( 'wpuf_add_post_after_insert', 'add_gear_item', 6, 1 );
function add_gear_item( $post_id ) {


    $new_items = get_post_meta( $post_id, 'item', true );
    
	if ($new_items != '') {
	    $new_items = explode(', ', $new_items);
	    $user = get_current_user_id();
	
		foreach($new_items as $item) {
		
	        $item_post = array(
			  'post_title'    => $item,
			  'post_status'   => 'publish',
			  'post_author'   => $user,
			  'post_type'	  => 'gear',
			);
			
			wp_insert_post( $item_post );
		}
	}  	
}




     


/**
* 
* Add new gear items to entry
*
* This function gets then values saved in the item field and breaks them apart by commas. It then saves those values as new gear items and deletes the item field when done.
*/

add_action( 'wpuf_edit_post_after_update', 'post_gear_item', 7, 1 );
add_action( 'wpuf_add_post_after_insert', 'post_gear_item', 7, 1 );
function post_gear_item( $post_id ) {


		$new_items = get_post_meta( $post_id, 'item', true );
        $new_items = explode(', ', $new_items);
        
        foreach($new_items as $item) {
			add_post_meta( $post_id, 'gear', $item, false );
		}
		
		delete_post_meta($post_id, 'item');
}









/**
* Save Number of Days Between Dates
*
* Calculates the number of days between start and end. Saves in a custom field named 'day_count.' If no end date the custom field is set to 1.
*/

add_action( 'wpuf_edit_post_after_update', 'count_days', 8, 1 );
add_action( 'wpuf_add_post_after_insert', 'count_days', 8, 1 );
function count_days( $post_id ) {

	$start_date = get_post_meta( $post_id, 'start_date', true );
	$end_date = get_post_meta( $post_id, 'end_date', true );
		
	if ( $end_date != '') {
		$date1=date_create($start_date);
		$date2=date_create($end_date);
		$diff=date_diff($date1,$date2);
		$count = $diff->d+1;
			
		update_post_meta($post_id, 'day_count',  $count );
	} else {
		update_post_meta($post_id, 'day_count',  '1' );
	}
}






/**
* Save Map to Work with WP GEO
*
* This is a special function sued by the WP GEO plugin. It saves the latitude and longitude to the plugins special custom fields.
*/

function wpufe_update_wp_geo( $post_id ) {
    if ( isset( $_POST['choose_location'] ) ) {
        list( $lat, $long ) = explode(',', $_POST['choose_location']);

        update_post_meta( $post_id, WPGEO_LATITUDE_META, $lat );
        update_post_meta( $post_id, WPGEO_LONGITUDE_META, $long );
    }

    	$title = get_the_title($post_id);
        update_post_meta( $post_id, WPGEO_TITLE_META, $title );
   
}

add_action( 'wpuf_add_post_after_insert', 'wpufe_update_wp_geo' );
add_action( 'wpuf_edit_post_after_update', 'wpufe_update_wp_geo' );






