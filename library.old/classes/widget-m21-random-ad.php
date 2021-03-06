<?php
/**
 * The M21 Random Ad Widget displays random Ads from a group of ads
 *
 * This widget only works when used in the Mobility21 Wordpress Theme
 * @package Mobility21_Library
 * @subpackage Classes
 * @author Ryann Micua
 * @link http://www.pogidude.com
 */

/**
 * M21 Random Ad Widget Class
 *
 * @since 0.1
 * @depends Tim Thumb Script
 * @link http://code.google.com/p/timthumb/
 * @optional use with Hybrid-core framework to use automatic prefix and textdomain.
 * @link http://themehybrid.com/themes/hybrid/widgets
 */

class M21_Random_Ad_Widget extends WP_Widget{

	/**
	 * Prefix for the widget.
	 * @since 0.1
	 */
	var $prefix;

	/**
	 * Textdomain for the widget.
	 * @since 0.1
	 */
	var $textdomain;
	
	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.1
	 */
	function M21_Random_Ad_Widget(){
	
		/* Check if Hybrid-core framework is active */
		if( class_exists( 'Hybrid' ) ){
			/* Set the widget prefix */
			$this->prefix = hybrid_get_prefix();
			
			/* Set the widget textdomain. */
			$this->textdomain = hybrid_get_parent_textdomain();
		} else {
			$this->prefix = 'mobility21';
			$this->textdomain = 'mobility21';
		}
		
		$widget_options = array(
						'classname' => 'm21-random-ad',
						'description' => esc_html__( 'Display a Mobility21 Partner ad from a group of ads.', $this->textdomain ) 
					);
		
		$control_options = array(
						'height' => '300',
						'width' => '525',
						'id_base' => "{$this->prefix}-m21-random-ad", esc_attr__( 'M21 Random Ad', $this->textdomain )
					);
		
		$this->WP_Widget( "{$this->prefix}-m21-random-ad", esc_attr__( 'Mobility Random Ad Widget', $this->textdomain ), $widget_options, $control_options );
	}
	
	function form( $instance ){ ?>
	
		<?php
		$defaults = array( 'number_ads' => 1, 'resize_mode' => '2' );
		$instance = wp_parse_args( (array) $instance, $defaults );

		// get list of Ad Groups and store in an array (get list of all non-empty terms in Taxonomy 'Ad Group'
		$tax_args = array('orderby' => 'name');
		$ad_groups = get_terms( 'ad_group', $tax_args );
		
		// get list of Ad Formats and store in an array
		$ad_options = get_option('ad-settings-options');
		$ad_formats = $ad_options['ad-format'];
		
		?>
		
		<div class="hybrid-widget-controls columns-2">
			<h4 style="margin: 0 0 5px 0;">The following selected ads will be shown at random <?php echo (empty($instance['number_ads']) ) ? '<em>(all at once)</em>' : '<em>(' . $instance['number_ads'] . ' at a time)</em>'; ?></h4>
			
			<div style="margin: 0; padding: 5px; background: #f8f8f8; border: 1px solid #eee; list-style-position: inside; font-size: 90%; overflow: auto; max-height: 330px; height: expression(this.scrollHeight > 330 ? '330px' : 'auto')">
							
				<?php
					//get selected ads
					$args_ads = array( 	'post_type' => 'advertisement',
										 'posts_per_page' => -1,
										 'meta_query' => array(
										 	array(
												'key' => 'adAccountStatus',
												'value' => 'activated',
												'compare' => '='
											),
											array(
												'key' => 'adFormat',
												'value' => '',
												'compare' => 'NOT LIKE'
											),
											array(
												'key' => 'adImageUrl',
												'value' => '',
												'compare' => 'NOT LIKE'
											)
										 )
									);
					
					//add taxonomy parameter
					if( !empty( $instance['ad_group']) ){
						$args_ads['tax_query'][] = array( 'taxonomy' => 'ad_group',
															'field'=> 'id',
															'terms' => $instance['ad_group']
														);
					}
					//add a custom field parameter
					if( !empty( $instance['ad_format']) ){
						$args_ads['meta_query'][] = array( 'key' => 'adFormat',
															'value' => $instance['ad_format'],
															'compare' => '='
														);
					}
									
					$selected_ads = get_posts( $args_ads );
					
					//save to $instance for use later
					$instance['selected_ads'] = $selected_ads;
					
					if( !empty( $selected_ads ) ) : ?>
					
						<ol>
						
						<?php //show titles of the selected ads
						foreach( $selected_ads as $ad ): ?>
							
							<li style="margin: 0 0 4px 0;"><?php echo $ad->post_title; ?> <em><small>(<a href="<?php echo admin_url("/post.php?post={$ad->ID}&amp;action=edit"); ?>" >edit</a>)</small></em></li>
		
						<?php endforeach; ?>
						
						</ol>
						
					<?php else: ?>
						
						<p style="text-align: center;">No ads found</p>
						
					<?php endif; ?>
			</div>
			<span class="description"><small>Missing an ad? For an advertisement to be selected, the ad must have the following:</small></span>
			<ol>
				<LI><strong class="description"><small>Status is Activated</small></strong></LI>
				<LI><strong class="description"><small>Ad Format is specified</small></strong></LI>
				<LI><strong class="description"><small>Image source url is specifed</small></strong></LI>
			</ol>
		</div>
		
		<div class="hybrid-widget-controls columns-2 column-last" >
			
			<!-- Display Widget Title -->
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>" ><?php _e( 'Title : ', $this->textdomain ); ?></label><br />
				<input type="text" class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
	
			<!-- // display Ad Group dropdown -->
			<p>
				<label for="<?php echo $this->get_field_id('ad_group'); ?>" ><?php _e('Ad Group: ', $this->textdomain ); ?></label>
				
				<br />
				
				<select id="<?php echo $this->get_field_id('ad_group'); ?>" name="<?php echo $this->get_field_name('ad_group'); ?>" class="widefat" style="min-width: 80%;">
					
					<option value="">Any Group</option>
					
					<?php foreach( $ad_groups as $term ) : ?>
						<option value="<?php echo $term->term_id; ?>" <?php selected( $term->term_id, esc_attr( $instance['ad_group'] ) ); ?> ><?php echo $term->name; ?></option>
					<?php endforeach; ?>
				
				</select>
				<em class="description"><small>Only Ads under the Ad Group you specify above will be selected.</small></em>
			</p>
			
			<!-- // display Ad Formats dropdown -->
			<p>
				<label for="<?php echo $this->get_field_id('ad_format'); ?>" ><?php _e('Ad Format: ', $this->textdomain ); ?></label>
				
				<br />
				
				<select id="<?php echo $this->get_field_id('ad_format'); ?>" name="<?php echo $this->get_field_name('ad_format'); ?>" class="widefat" style="min-width: 80%;">
					
					<option value="">Any Format</option>
					
					<?php foreach( $ad_formats as $ad_format ) : ?>
						<option value="<?php echo $ad_format['id']; ?>" <?php selected( $ad_format['id'], esc_attr( $instance['ad_format'] ) ); ?> ><?php echo $ad_format['name']; ?></option>
					<?php endforeach; ?>
				
				</select>
				<em class="description"><small>Only Ads with the Format you specify above will be selected.</small></em>
			</p>
			
			<!-- // text field - get how many ads to display -->
			<p>
				<label for="<?php echo $this->get_field_id('number_ads'); ?>" ><?php _e( 'Number of Ads to show: ', $this->textdomain ); ?></label>
				<input type="text"  size="4" class="code" name="<?php echo $this->get_field_name('number_ads'); ?>" id="<?php echo $this->get_field_id('number_ads'); ?>" value="<?php echo esc_attr( $instance['number_ads'] ); ?>" /><br />
				<em class="description"><small>Leave blank or put in 0 to show all ads every time.</small></em>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('resize'); ?>" name="<?php echo $this->get_field_name('resize'); ?>" <?php checked( 'checked', $instance['resize'] ); ?> value="checked" /><label>Resize image to fit the Ad Format</label><br /><span class="description"><small>Applicable only if the specified Ad Format above is not set to "Any Format"</small></span>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('resize_mode'); ?>" ><?php _e('Resize Mode: ', $this->textdomain ); ?></label>
				
				<br />
				
				<select id="<?php echo $this->get_field_id('resize_mode'); ?>" name="<?php echo $this->get_field_name('resize_mode'); ?>" class="widefat" style="min-width: 80%;">

					<option value="0" <?php selected( '0', esc_attr( $instance['resize_mode'] ) ); ?> >Mode 1 Resize to fit dimensions</option>
					<option value="1" <?php selected( '1', esc_attr( $instance['resize_mode'] ) ); ?> >Mode 2 Crop and resize to best fit</option>
					<option value="2" <?php selected( '2', esc_attr( $instance['resize_mode'] ) ); ?> >Mode 3 Resize proportionally (Default)</option>
					<option value="3" <?php selected( '3', esc_attr( $instance['resize_mode'] ) ); ?> >Mode 4 Resize proportionally</option>
				
				</select>
				<em class="description"><small><strong>Mode 1</strong> - Resize to Fit specified dimensions (no cropping)<br /><strong>Mode 2</strong> - Crop and resize to best fit the dimensions<br /><strong>Mode 3</strong> - Resize proportionally to fit entire image into specified dimensions, and add borders if required<br /><strong>Mode 4</strong> - Resize proportionally adjusting size of scaled image so there are no borders gaps</small></em>
			</p>

		</div>
		
		<div class="clear"></div>
		
	<?php
	}
	
	function update( $new_instance, $old_instance ){
	
		$instance = $old_instance;
		
		$instance['title'] = sanitize_title( $new_instance['title'] );
		$instance['number_ads'] = intval( $new_instance['number_ads'] );
		$instance['ad_format'] = $new_instance['ad_format'];
		$instance['ad_group'] = $new_instance['ad_group'];
		$instance['resize'] = $new_instance['resize'];
		$instance['resize_mode'] = $new_instance['resize_mode'];
		$instance['selected_ads'] = $new_instance['selected_ads'];
		
		return $instance;

	}
	
	function widget( $args, $instance ){
	
		extract( $args );
		
		$out = '';
		
		$zoom_crop= $instance['resize_mode']; //0 | 1| 2 | 3
		$quality = 75; //default 75 max 100
		$script_path = MOBILITY_SCRIPTS_URI . '/thumb.php';
		
		//get selected ads
		$posts_to_get = empty( $instance['number_ads'] ) ? -1 : $instance['number_ads'];
		
		//get selected ads
		$args_ads = array( 	'post_type' => 'advertisement',
								'posts_per_page' => $posts_to_get,
								'orderby' => 'rand',
								'meta_query' => array(
									array(
										'key' => 'adAccountStatus',
										'value' => 'activated',
										'compare' => '='
									),
									array(
										'key' => 'adFormat',
										'value' => '',
										'compare' => 'NOT LIKE'
									)
								)
						);
		
		//add taxonomy parameter
		if( !empty( $instance['ad_group']) ){
			$args_ads['tax_query'][] = array( 'taxonomy' => 'ad_group',
												'field'=> 'id',
												'terms' => $instance['ad_group']
											);
		}
		//add a custom field parameter
		if( !empty( $instance['ad_format']) ){
			$args_ads['meta_query'][] = array( 'key' => 'adFormat',
												'value' => $instance['ad_format'],
												'compare' => '='
											);
		}
						
		$selected_ads = get_posts( $args_ads );	
		
		//loop and show the ads
		foreach( $selected_ads as $ad ){
			
			//get url to the ad image
			$ad_imageurl = esc_url( get_post_meta( $ad->ID, 'adImageUrl', true ) );
			
			//get link url to the image if any
			$ad_linkurl = esc_url( get_post_meta( $ad->ID, 'adLinkUrl', true ) );
			
			//get adformat id
			$ad_format_id = get_post_meta( $ad->ID, 'adFormat', true );
			
			//get adformat details
			$ad_format = mobility21_get_ad_format( $ad_format_id );
			
			//setup Tim Thumb
			$thumb_width= intval( $ad_format['width'] ); //width
			$thumb_height= intval( $ad_format['height'] ); //height without
			
			$ad_out = '<img src="' . $script_path . '?src=' . $ad_imageurl . '&w=' . $thumb_width . '&h=' . $thumb_height . '&zc=' . $zoom_crop . '&q=' . $quality . '" />';
			
			if( !empty($ad_linkurl) ){
				//wrap in anchor <a> tags
				$ad_out = '<a href="' . $ad_linkurl . '" >' . $ad_out . '</a>';
			}
			
			//wrap in <li> tags
			$ad_out = '<li class="ad-wrap">' . $ad_out . '</li>';
			
			//finally add to $out
			$out .= $ad_out;
			
		}//endforeach

		//wrap in <ul> tags
		$out = '<ul class="m21-random-ad-wrap">' . $out . '</ul>';
		
		echo $before_widget;
		
		if( !empty( $title ) ){
			echo $before_title . $title . $after_title;
		}
		
		echo $out;
		echo $after_widget;
		
	}
}

?>
