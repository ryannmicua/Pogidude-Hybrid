<?php
/* LOAD THICKBOX and MEDIA-UPLOAD SCRIPT */
/**
 * The M21 Banner Item Widget
 *
 * @package Mobility21_Library
 * @subpackage Classes
 * @author Ryann Micua
 * @link http://www.pogidude.com
 */

/**
 * The M21 Banner Item Widget Class
 *
 * @since 0.1
 * @depends Tim Thumb Script
 * @link http://code.google.com/p/timthumb/
 * @optional use with Hybrid-core framework to use automatic prefix and textdomain.
 * @link http://themehybrid.com/themes/hybrid/widgets
 */

class M21_Banner_Item_Widget extends WP_Widget{

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
	function M21_Banner_Item_Widget(){
	
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
						'classname' => 'm21-banner-item',
						'description' => esc_html__( 'Adds a banner item to Mobility 21 site frontpage slider or into the inside page banner area.', $this->textdomain ) 
					);
		
		$control_options = array(
						'height' => '300',
						'width' => '700',
						'id_base' => "{$this->prefix}-m21-banner-item", esc_attr__( 'M21 Banner Item', $this->textdomain )
					);
		
		$this->WP_Widget( "{$this->prefix}-m21-banner-item", esc_attr__( 'Mobility Banner Item', $this->textdomain ), $widget_options, $control_options );
	}
	
	function form( $instance ){
	
		$defaults = array(
						'div_class' => 'm21-single-ad-wrap'
					);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		//following variables needed for Tim Thumb script
		$thumb_width=378; //width
		$thumb_height=140; //height without
		$zoom_crop=3; //0 or 1
		$quality = 75; //default 75 max 100
		
	?>

		<div class="hybrid-widget-controls columns-2" style="width:400px;" >
		
			<?php if( !empty( $instance['image_url'] ) ) : ?>
			<h3 style="margin: 0 0 3px 0;"><?php _e('Image Preview Thumbnail', $this->textdomain ); ?></h3>
			<div style="background: #f4f4f4; border: 1px solid #ccc; padding: 10px; min-height: 120px; margin-bottom: 3px;">
				<img src="<?php echo MOBILITY_SCRIPTS_URI . '/thumb.php?src=' . $instance['image_url'] . '&w=' . $thumb_width . '&h=' . $thumb_height . '&zc=' . $zoom_crop; ?>" />
			</div>
			<span class="description"><small><strong>Warning: </strong>Preview image may not reflect how the image will actually look.</small></span>
			<?php endif; ?>
			
			<h3 style="margin-bottom: 0;">Advanced Settings</h3>
			<p><em><small>Use the settings here to create additional html content.</small></em></p>
			
			<p>
				<label for="<?php echo $this->get_field_id('enable_html_code'); ?>" ><?php _e('Enable Advanced Settings: ', $this->textdomain ); ?></label>
				<input type="checkbox" id="<?php echo $this->get_field_id('enable_html_code'); ?>" name="<?php echo $this->get_field_name('enable_html_code'); ?>" value="enabled" <?php checked('enabled', $instance['enable_html_code'] ); ?> />
				<em><small></small></em>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('html_code'); ?>" ><?php _e('HTML Content: ', $this->textdomain ); ?></label>
				
				<textarea class="widefat code" name="<?php echo $this->get_field_name('html_code'); ?>" id="<?php echo $this->get_field_id('html_code'); ?>" cols="30" rows="12" ><?php echo $instance['html_code']; ?></textarea>
				<br /><em><small>Some examples of common html elements are shown below. You can copy and paste it into the text input field above.</small></em>
			</p>
			
			<div style="background: #f4f4f4; border: 1px solid #ccc; padding: 10px; ">
				<h3 style="margin: 0 0 3px 0;">Button</h3>
	
				<code style="display: block; padding: 5px; background: #fafafa; border: 1px solid #ccc; ">&lt;a href="http://www.example.com" class="button" style="position: absolute; top: <strong>100px;</strong> left: <strong>400px;</strong>"&gt;Button Title&lt;/a&gt;</code>
	
				<p>
					<em><small>Copy and paste the code above into the text input field above. Adjust the <strong><code>top</code></strong> and <strong><code>left</code></strong> values to position the button within the banner area.</small></em>
				</p>
			</div>
			
		</div>
		
		<div class="hybrid-widget-controls columns-2 column-last" style="width: 280px;">

			<h3 style="margin: 0 0 3px 0;"><?php _e( 'Title', $this->textdomain ); ?></h3>
			<input type="text" class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			<br /><em><small>This won't be displayed. Used only for identifying this widget in the widget area.</small></em>
			
			<h3 style="margin-bottom: 0;">Basic Settings</h3>
			<p><em><small>Add a banner image and optionally make it clickable by providing a link url.</small></em></p>
	
			<p>
				<label for="<?php echo $this->get_field_id('image_url'); ?>" ><?php _e('Background Image URL: ', $this->textdomain ); ?></label>
				<textarea class="widefat code" name="<?php echo $this->get_field_name('image_url'); ?>" id="<?php echo $this->get_field_id('image_url'); ?>" cols="32" rows="3" ><?php echo $instance['image_url']; ?></textarea>
				<br />
				<em><small>Use the <strong>width</strong> and <strong>height</strong> settings below to resize the image. Example: http://example.com/image.jpg <a href="<?php echo admin_url('/media-upload.php?type=image&amp;TB_iframe=1' ); ?>" class="thickbox">Image Browser</a></small></em>
			</p>
	
			<p>
				<strong>Banner Dimensions</strong><br />
				<label for="<?php echo $this->get_field_id('image_width'); ?>" ><?php _e('Width: ', $this->textdomain ); ?></label>
				<input type="text" name="<?php echo $this->get_field_name('image_width'); ?>" id="<?php echo $this->get_field_id('image_width'); ?>" value="<?php echo $instance['image_width']; ?>" size="4" />
				<label for="<?php echo $this->get_field_id('image_height'); ?>" ><?php _e('Height: ', $this->textdomain ); ?></label>
				<input type="text" name="<?php echo $this->get_field_name('image_height'); ?>" id="<?php echo $this->get_field_id('image_height'); ?>" value="<?php echo $instance['image_height']; ?>" size="4" />
				<br />
				<em><small>Values here are applied only when they are provided. If not provided, then the actual dimensions of the background image will be used. <strong>Example: 250.</strong></small></em>
			</p>
			
			<p>
				<input type="checkbox"  class="checkbox" id="<?php echo $this->get_field_id('zoomcrop'); ?>" name="<?php echo $this->get_field_name('zoomcrop'); ?>" <?php checked( 'checked', $instance['zoomcrop'] ); ?> value="checked" /><strong style="margin-left: 2px;">Resize banner image proportionally</strong><br /><span class="description"><small>This setting will try to maintain the image's aspect ratio while resizing the banner image. Both width and height values above need to be specified.</small></span>
			</p>
			
			<p>
				<h3 style="margin: 0 0 3px 0;"><?php _e('Link URL: ', $this->textdomain ); ?></h3>
				<textarea class="widefat code" name="<?php echo $this->get_field_name('link_url'); ?>" id="<?php echo $this->get_field_id('link_url'); ?>" cols="32" rows="3" ><?php echo $instance['link_url']; ?></textarea>
				<br /><em><small>Setting this option will make the whole banner area clickable. Provide the URL here. <strong>Example: http://example-site.com</strong>
				<br />
				<br />This setting is ignored when <strong>HTML Content</strong> is added in the <strong>Advanced Settings</strong>.</small></em>
			</p>
			
		</div>

		
		<div style="clear:both;">&nbsp;</div>

	<?php
	}
	
	function update( $new_instance, $old_instance ){
	
		$instance = $old_instance;
		
	//	$instance = $new_instance;
		
		$instance['title'] = sanitize_title( $new_instance['title'] );
		$instance['image_url'] = $new_instance['image_url'];
		$instance['image_height'] = intval( $new_instance['image_height'] );
		$instance['image_width'] = intval( $new_instance['image_width'] );
		$instance['link_url'] = $new_instance['link_url'];
		$instance['html_code'] = $new_instance['html_code'];
		$instance['enable_html_code'] = $new_instance['enable_html_code'];
		$instance['zoomcrop'] = $new_instance['zoomcrop'];
		$instance['centerimage'] = $new_instance['centerimage'];
		
		return $instance;
	}
	
	function widget( $args, $instance ){
		extract( $args );
		
		/* if the title was input by the user, get it */
		//$title =  !empty( $instance['title'] ) ? $instance['title'] : '';
		
		/* URL to the image used */
		$image_url = $instance['image_url'];
		
		/* Height of the image */
		$image_height = ( !empty( $instance['image_height'] ) ) ? 'height="' . $instance['image_height'] . '"' : '';
		
		/* Width of the image */
		$image_width = (  $instance['image_width'] ) ? 'width="' . $instance['image_width'] . '"' : '';
		
		/* Additional HTML Content */
		$html_content = ( $instance['html_code'] ) ? $instance['html_code'] : '';
		
		/* href URL */
		$link_url = ( !empty( $instance['link_url'] ) ) ? $instance['link_url'] : '';
		
		/* div class wrapping the image button */
	//	$div_class = ( empty( $instance['div_class'] ) ) ? 'image-button-wrap' : $instance['div_class'] ;
		
	//	$out = '<div class="' . $div_class . '">';

		//Use Tim Thumb script to resize the image
		$thumb_width= $instance['image_width']; //width
		$thumb_height= $instance['image_height']; //height
		$zoom_crop=0; //resize to fit specified dimensions
		$quality = 75; //default 75 max 100
		$script_path = MOBILITY_SCRIPTS_URI . '/thumb.php';
		
		/* Condition is true only if zoomcrop, width and height have been specified */
		if( $instance['zoomcrop'] == 'checked' && !empty($thumb_width) && !empty($thumb_height) ){
		
			$zoom_crop = 3;
			
			$out = '<img src="' . $script_path . '?src=' . $image_url . '&w=' . $thumb_width . '&h=' . $thumb_height . '&zc=' . $zoom_crop . '" />';
			
		} else {

			$out = '<img src="' . $image_url . '" ' . $image_height . '" ' . $image_width . '" />';		
		
		}
		
		if( !empty( $link_url ) ){
			$out = '<a href="' . $link_url . '" class="banner-link">' . $out . '</a>';
		}
		
		if( $instance['enable_html_code'] == 'enabled' ){
			if( !empty( $html_content ) ){
				//wrap the html content inside a div
				//add additional html content
				$out .= '<div class="banner-html-inside">' . $html_content . '</div>';
			}
		}
		
		echo $before_widget;
	//	echo $before_title . $title . $after_title;
		echo $out;
		echo $after_widget;
	}
}

?>
