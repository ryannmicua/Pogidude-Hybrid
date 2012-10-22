<?php
/**
 * The M21 Single Ad Widget displays a single Ad by selecting from a list of ads.
 *
 * This widget only works when used in the Mobility21 Wordpress Theme
 * @package Mobility21_Library
 * @subpackage Classes
 * @author Ryann Micua
 * @link http://www.pogidude.com
 */

/**
 * M21 Single Ad Widget Class
 *
 * @since 0.1
 * @depends Tim Thumb Script
 * @link http://code.google.com/p/timthumb/
 * @optional use with Hybrid-core framework to use automatic prefix and textdomain.
 * @link http://themehybrid.com/themes/hybrid/widgets
 */

class M21_Single_Ad_Widget extends WP_Widget{

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
	function M21_Single_Ad_Widget(){
	
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
						'classname' => 'm21-single-ad',
						'description' => esc_html__( 'Place a Mobility21 Partner ad.', $this->textdomain ) 
					);
		
		$control_options = array(
						'height' => '300',
						'width' => '525',
						'id_base' => "{$this->prefix}-m21-single-ad", esc_attr__( 'M21 Single Ad', $this->textdomain )
					);
		
		$this->WP_Widget( "{$this->prefix}-m21-single-ad", esc_attr__( 'Mobility Single Ad Widget', $this->textdomain ), $widget_options, $control_options );
	}
	
	function form( $instance ){
	
		$defaults = array(
						'div_class' => 'm21-single-ad-wrap'
					);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		//get list of all Ad content type posts and store in $ad_posts
		//to be used for select box
		$args = array( 	'post_type' => 'advertisement', 
						'posts_per_page' => -1, 
						'orderby' => 'title', 
						'order' => 'DESC',
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
		$ad_posts_array = get_posts( $args );
		$ad_posts = array();
		
		//check the post's Ad Format setting against currently available formats
		$ad_formats = mobility21_get_ad_formats();
		
		foreach( $ad_posts_array as $ad_post ){
			//check if the post's ad format is in the array of available formats
			if(isset( $ad_formats[ strip_tags( get_post_meta( $ad_post->ID, 'adFormat', true) ) ] ) )
				$ad_posts[ $ad_post->ID ] = $ad_post->post_title;
		}
		
	?>
	
	<div class="hybrid-widget-controls columns-2 column-last" >
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>" ><?php _e( 'Title : ', $this->textdomain ); ?></label><br />
			<input type="text" class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('m21_ad'); ?>" ><?php _e('Mobility21 Partner Ad: ', $this->textdomain ); ?></label>
			
			<br />
			
			<select id="<?php echo $this->get_field_id('m21_ad_id'); ?>" name="<?php echo $this->get_field_name('m21_ad_id'); ?>" class="widefat" style="min-width: 80%;">
				
				<option value="">--</option>
				
				<?php foreach( $ad_posts as $ad_id => $ad_title ) : ?>
					<option value="<?php echo $ad_id; ?>" <?php selected( esc_attr( $ad_id ), esc_attr( $instance['m21_ad_id'] ) ); ?> ><?php echo $ad_title; ?></option>
				<?php endforeach; ?>
			
			</select>
			<br />
			
			<?php if( !empty( $instance['m21_ad_error'] ) ) : ?>
				<span class="error" style="display: block; background: #FFEBE8; border: 1px solid #CC0000; padding: 2px 5px; margin: 5px 0;"><?php echo $instance['m21_ad_error']; ?></span>
			<?php endif; ?>

			<span class="description">
				<small>Ads must have the following options set in order to show up here:
				<br />1. Status must be activated.
				<br />2. Ad Format must be specified.
				<br />All ads are listed in the <a href="<?php echo admin_url('edit.php?post_type=advertisement'); ?>" >Ads page</a>.
				<br />This is the link to the <a href="<?php echo admin_url('edit.php?post_type=advertisement&page=mobility21-ad-settings'); ?>">Ad Settings</a> page.</small>
			</span>
		</p>
		<p>
			<h3>Styling</h3>
			<input type="checkbox" id="<?php echo $this->get_field_id('m21_ad_resize'); ?>" name="<?php echo $this->get_field_name('m21_ad_resize'); ?>" <?php checked( 'checked', $instance['m21_ad_resize'] ); ?> value="checked" /><strong>Fit image to Ad Format</strong><br /><span class="description"><small>Check to resize the image to the dimensions of the selected Ad Format.</small></span>
			<br /><br />
			<input type="checkbox" id="<?php echo $this->get_field_id('m21_ad_zoomcrop'); ?>" name="<?php echo $this->get_field_name('m21_ad_zoomcrop'); ?>" class="checkbox" <?php checked( 'checked', $instance['m21_ad_zoomcrop'] ); ?> value="checked" /><strong>Resize Proportionally</strong><br /><span class="description"><small>Does nothing if the setting above is not checked. This setting will try to maintain the image's aspect ratio while resizing.</small></span>
			<br /><br />
			<input type="checkbox" id="<?php echo $this->get_field_id('m21_ad_custom_style'); ?>" name="<?php echo $this->get_field_name('m21_ad_custom_style'); ?>" class="checkbox" <?php checked( 'checked', $instance['m21_ad_custom_style'] ); ?> value="checked" /><strong>Use custom style</strong><br /><span class="description"><small>Check to have additional control over your ad design. By default, Ads are centered within the dimensions of the selected ad format.</small></span>
		</p>
		
	</div>
	
	<div class="hybrid-widget-controls columns-2 " >
	
		<?php /* Show an image of the Ad if available */ ?>
		<?php 
			//get image src url
			$m21_ad_imageurl = esc_url( get_post_meta( $instance['m21_ad_id'], 'adImageUrl', true ) ); 
			
			//following variables needed for Tim Thumb script
			$thumb_width=225; //width
			$thumb_height=120; //height without
			$zoom_crop=3; //0 | 1| 2 | 3
			$quality = 75; //default 75 max 100
		?>
		
		<?php if( !empty( $m21_ad_imageurl ) ) : ?>
		<div>
			<h3><?php _e('Preview', $this->textdomain ); ?></h3>
			<img src="<?php echo MOBILITY_SCRIPTS_URI . '/thumb.php?src=' . $m21_ad_imageurl . '&w=' . $thumb_width . '&h=' . $thumb_height . '&zc=' . $zoom_crop; ?>" />
			<span class="description"><small><strong>Warning: </strong>Preview image may not reflect how the image will actually look.</small></span>
		</div>
		<?php endif; ?>
		
		<?php /* Show Ad Details */ ?>
		<?php if( !empty( $instance['m21_ad_show_details'] ) ) : ?>
		<?php
		//get Ad Post ID
		$ad_id= $instance['m21_ad_id'];

		//get ad post meta		
		$m21_ad_account_name = strip_tags( get_post_meta( $ad_id, 'adAccountName', true ) );
		$m21_ad_format_id = strip_tags( get_post_meta( $ad_id, 'adFormat', true ) );
		$m21_ad_linkurl = esc_url( get_post_meta( $ad_id, 'adLinkUrl', true ) );
		
		//get ad format details
		$ad_format = mobility21_get_ad_format( $m21_ad_format_id );
		$m21_ad_width = intval( $ad_format['width'] );
		$m21_ad_height = intval( $ad_format['height'] );
		$m21_ad_format_name = strip_tags( $ad_format['name'] );
		?>
		
		<div>
			<h3>Ad Details (<small><a href="<?php echo admin_url('post.php?post=' . $ad_id . '&action=edit'); ?>">Edit this ad</a></small>)</h3>
			
			<p style="background: #f3f3f3; padding: 2px 5px;"><small>Company: <strong><?php echo empty($m21_ad_account_name) ? '<em>Not set</em>' : $m21_ad_account_name; ?></strong></small></p>
			
			<p style="background: #f3f3f3; padding: 2px 5px;"><small>Ad Format: <strong><?php echo empty( $m21_ad_format_name ) ? '<em>Not set</em>' : $m21_ad_format_name; ?></strong></small></p>
			
			<p style="background: #f3f3f3; padding: 2px 5px;"><small>Width: <strong><?php echo empty( $m21_ad_width ) ? '<em>Not set</em>' : $m21_ad_width . 'px'; ?></strong> Height: <strong><?php echo empty( $m21_ad_height ) ? '<em>Not set</em>' : $m21_ad_height . 'px'; ?></strong></small></p>
			
			<p style="background: #f3f3f3; padding: 2px 5px;"><small>Link URL: <strong><?php echo empty( $m21_ad_linkurl ) ? '<em>Not set</em>' : $m21_ad_linkurl; ?></strong></small></p>
			
		</div>
		<?php endif; ?>
	
	</div>
	
	<div class="clear"></div>
	<?php
	}
	
	function update( $new_instance, $old_instance ){
	
		//$instance = $old_instance;
		
		//$instance = $new_instance;
		
		$instance['title'] = sanitize_title( $new_instance['title'] );
		
		$instance['m21_ad_id'] = intval( $new_instance['m21_ad_id'] );
		
		$instance['m21_ad_error'] = '';
		
		if( empty( $instance['m21_ad_id'] ) ){
			$instance['m21_ad_error'] = 'Please select an ad from the dropdown menu.';
			$instance['m21_ad_show_details'] = false;
			return $instance;
		}
		
		$instance['m21_ad_show_details'] = true;

		//save other stuff
		$instance['m21_ad_resize'] = $new_instance['m21_ad_resize'];
		$instance['m21_ad_zoomcrop'] = $new_instance['m21_ad_zoomcrop'];
		$instance['m21_ad_custom_style'] = $new_instance['m21_ad_custom_style'];
		
		return $instance;

	}
	
	function widget( $args, $instance ){
	
		extract( $args );
		
		$ad_id = $instance['m21_ad_id'];
		
		/* if the title was entered by the user, get it */
		$title =  !empty( $instance['title'] ) ? $instance['title'] : '';
		
		//Get company name
		$m21_ad_account_name = strip_tags( get_post_meta( $ad_id, 'adAccountName', true ) );
		
		//get link url
		$m21_ad_linkurl = esc_url( get_post_meta( $ad_id, 'adLinkUrl', true ) );

		//get ad format
		$m21_ad_format_id = strip_tags( get_post_meta( $ad_id, 'adFormat', true ) );
		
		//get ad format dimensions
		$ad_format = mobility21_get_ad_format( $m21_ad_format_id );
		$m21_ad_width = intval( $ad_format['width'] );
		$m21_ad_height = intval( $ad_format['height'] );
		
		/* URL to the image used */
		$m21_ad_imageurl = esc_url( get_post_meta( $ad_id, 'adImageUrl', true ) );
		
		/* Check if image should be resized to the ad format dimensions */
		if( $instance['m21_ad_resize'] == 'checked'){
		
			/* Height of the image */
			$image_height = ( !empty( $m21_ad_width ) ) ? 'height="' . $m21_ad_height . '"' : '';
			
			/* Width of the image */
			$image_width = (  !empty($m21_ad_width) ) ? 'width="' . $m21_ad_width . '"' : '';
			
		} else {
			
			$image_height = "";
			$image_width = "";
			
		}
		
		/* href URL */
		$link_url = ( !empty( $m21_ad_linkurl ) ) ? $m21_ad_linkurl : '';
		
		/* Link text */
		$link_text = ( !empty( $m21_ad_account_name ) ) ? $$m21_ad_account_name : '';
		
		/* Link title */
		$link_title = ( !empty( $m21_ad_account_name ) ) ? 'title="' . $m21_ad_account_name . '"' : '';
		
		/* div class wrapping the image button */
		$div_class = ( empty( $instance['div_class'] ) ) ? 'image-button-wrap' : $instance['div_class'] ;
		
		
		/* Check if image should be resized to the ad format dimensions */
		if( $instance['m21_ad_resize'] == 'checked'){
			
			//Use Tim Thumb script to resize the image
			$thumb_width= $m21_ad_width; //width
			$thumb_height= $m21_ad_height; //height without
			$zoom_crop=0; //resize to fit specified dimensions
			$quality = 75; //default 75 max 100
			$script_path = MOBILITY_SCRIPTS_URI . '/thumb.php';
			
			/* Check if image should be resized proportionally */
			if( $instance['m21_ad_zoomcrop'] == 'checked' ){
				
				$zoom_crop = 3; //Resize proportionally while adjusting size of scaled image
				
			}
			
			$out = '<img src="' . $script_path . '?src=' . $m21_ad_imageurl . '&w=' . $thumb_width . '&h=' . $thumb_height . '&zc=' . $zoom_crop . '" />';
		
		} else {
			
			//display image as is
			$out = '<img src="' . $m21_ad_imageurl . '" ' . $image_alt_text . ' />';
		
		}
		
		
		//wrap with link tags if link url is not empty
		if( !empty( $m21_ad_linkurl ) ){
			$out = '<a href="' . $m21_ad_linkurl . '" ' . $link_title . ' >' . $out . '</a>';
		}
		
		//check whether to use default styles
		if( $instance['m21_ad_custom_style'] != 'checked' ){
			$out = '<div class="m21-ad-default-style" style="background: #fff; display: table-cell; text-align: center; vertical-align: middle; width: ' . $m21_ad_width . 'px; height:' . $m21_ad_height . 'px;" ><div style="overflow: hidden; width: ' . $m21_ad_width . 'px;">' . $out . '</div></div>';
		}
		
		//wrap final output inside div tags
		$out = '<div class="m21-ad-wrap m21-single-ad">' . $out . '</div>';
		
		echo $before_widget;
		
		if( !empty( $title ) ){
			echo $before_title . $title . $after_title;
		}
		
		echo $out;
		echo $after_widget;
		
	}
}

?>
