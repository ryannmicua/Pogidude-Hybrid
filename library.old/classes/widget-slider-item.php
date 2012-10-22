<?php
/* LOAD THICKBOX and MEDIA-UPLOAD SCRIPT */

/**
 * Slider Item Widget Class
 * Slider image with text inside.
 *
 * @since 0.1
 * @depends Tim Thumb Script
 * @link http://code.google.com/p/timthumb/
 * @optional use with Hybrid-core framework to use automatic prefix and textdomain.
 * @link http://themehybrid.com/themes/hybrid/widgets
 */

class Pogidude_Slider_Widget extends WP_Widget{

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
	 * ID for the widget
	 */
	var $widget_id;
	
	/**
	 * Path URL to the TimThumb script
	 */
	var $timthumbUrl;
	
	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.1
	 */
	function Pogidude_Slider_Widget(){
	
		/* Check if Hybrid-core framework is active */
		if( class_exists( 'Hybrid' ) ){
			/* Set the widget prefix */
			$this->prefix = hybrid_get_prefix();
			
			/* Set the widget textdomain. */
			$this->textdomain = hybrid_get_parent_textdomain();
		} else {
			$this->prefix = 'pogidude';
			$this->textdomain = 'pogidude';
		}
		
		//setup widget it
		$this->widget_id = 'slider-item';
		
		//setup path to Tim Thumb script
		$this->timthumbUrl = trailingslashit( THEMELIB_SCRIPTS_URI ) . 'thumb.php';
		
		$widget_options = array(
						'classname' => "{$this->prefix}-{$this->widget_id}",
						'description' => esc_html__( 'Adds a slider item. Use on slider widget area.', $this->textdomain )
					);
		
		$control_options = array(
						'height' => '300',
						'width' => '240',
						'id_base' => "{$this->prefix}-{$this->widget_id}"
					);
		
		$this->WP_Widget( "{$this->prefix}-{$this->widget_id}", esc_attr__( 'TM: Slider Item', $this->textdomain ), $widget_options, $control_options );
	}
	
	function form( $instance ){
	
		$defaults = array();
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		//following variables needed for Tim Thumb script
		$thumb_width=378; //width
		$thumb_height=140; //height without
		$zoom_crop=3; //0 || 1 || 2 || 3
		$quality = 75; //default 75 max 100
		
	?>
		
		<div class="hybrid-widget-controls">

			<h4 style="margin: 0 0 3px 0;"><?php _e( 'Title', $this->textdomain ); ?></h4>
			<p>
				<input type="text" class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
				<br /><em><small>This won't be displayed. Used only for identifying this widget in the widget area.</small></em>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('image_url'); ?>" ><strong><?php _e('Background Image URL: ', $this->textdomain ); ?></strong></label>
				<textarea class="widefat code <?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" id="<?php echo $this->get_field_id('image_url'); ?>" cols="32" rows="3" ><?php echo $instance['image_url']; ?></textarea>
				<em style="margin-bottom: 5px;"><small>Enter URL to the image. Example: http://example.com/image.jpg</small></em>
				<a style="display: inline-block;" href="<?php echo admin_url('/media-upload.php?type=image&amp;TB_iframe=1' ); ?>" class="button <?php echo $this->get_field_id('image_url'); ?>-button"><small>Image Browser</small></a>
				<br />
				<small><strong>NOTE:</strong> If the Image Browser popup is not working, try saving the widget first.</small>
			</p>
			
			<script>
				jQuery('.<?php echo $this->get_field_id('image_url'); ?>-button').on( 'click', function() {
			
					window.send_to_editor = function(html){
						var imgurl = jQuery('img',html).attr('src');
						jQuery('.<?php echo $this->get_field_id('image_url'); ?>').val(imgurl);
						tb_remove();
					}

					//Change "insert into post" to "Use this File"
					setInterval(function() {jQuery('#TB_iframeContent').contents().find('.savesend .button').addClass('button-primary').val('Use This Image');}, 2000);

					tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
					return false;
			
				});
			</script>

			<?php if( !empty( $instance['image_url'] ) ) : ?>
			<h4 style="margin: 0 0 3px 0;"><?php _e('Image Preview Thumbnail', $this->textdomain ); ?></h4>
			<p>
				<img src="<?php echo $this->timthumbUrl . '?src=' . $instance['image_url'] . '&w=' . $thumb_width . '&h=' . $thumb_height . '&zc=' . $zoom_crop; ?>" />
				<span class="description"><small><strong>Warning: </strong>Preview image may not reflect how the image will actually look.</small></span>
			</p>
			<?php endif; ?>
			
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
				<label for="<?php echo $this->get_field_id('zoomcrop'); ?>" >
				<input type="checkbox"  class="checkbox" id="<?php echo $this->get_field_id('zoomcrop'); ?>" name="<?php echo $this->get_field_name('zoomcrop'); ?>" <?php checked( 'checked', $instance['zoomcrop'] ); ?> value="checked" /><strong style="margin-left: 2px;">Zoom Crop</strong></label>
				<br />
				<small>Check this option to resize the image to the specified dimensions while maintaining aspect ration. <strong>Important:</strong> Both width and height values above need to be specified.</small>
			</p>
			
			<p>
				<h4 style="margin: 0 0 3px 0;"><?php _e('Slide Text: ', $this->textdomain ); ?></h4>
				<textarea class="widefat" name="<?php echo $this->get_field_name('slider-text'); ?>" id="<?php echo $this->get_field_id('slider-text'); ?>" cols="32" rows="3" ><?php echo $instance['slider-text']; ?></textarea>
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
		$imageheight = intval( $new_instance['image_height'] );
		$instance['image_height'] = empty( $imageheight ) ? '' : $imageheight;
		$imagewidth = intval( $new_instance['image_width'] );
		$instance['image_width'] = empty( $imagewidth ) ? '' : $imagewidth;
		$instance['slider-text'] = $new_instance['slider-text'];
		$instance['zoomcrop'] = $new_instance['zoomcrop'];
		
		return $instance;
	}
	
	function widget( $args, $instance ){
		extract( $args );
		
		/* if the title was input by the user, get it */
		//$title =  !empty( $instance['title'] ) ? $instance['title'] : '';
		
		/* URL to the image used */
		$image_url = !empty( $instance['image_url'] ) ? $instance['image_url'] : '';
		
		/* Height of the image */
		$image_height = ( !empty( $instance['image_height'] ) ) ? 'height="' . $instance['image_height'] . '"' : '';
		
		/* Width of the image */
		$image_width = (  !empty( $instance['image_width'] ) ) ? 'width="' . $instance['image_width'] . '"' : '';
		
		/* div class wrapping the image button */
	//	$div_class = ( empty( $instance['div_class'] ) ) ? 'image-button-wrap' : $instance['div_class'] ;
		
	//	$out = '<div class="' . $div_class . '">';

		//Use Tim Thumb script to resize the image
		$thumb_width= $instance['image_width']; //width
		$thumb_height= $instance['image_height']; //height
		$zoom_crop=1; //resize to fit specified dimensions
		$quality = 75; //default 75 max 100
		$script_path = $this->timthumbUrl;
		
		/* Condition is true only if zoomcrop, width and height have been specified */
		if( $instance['zoomcrop'] == 'checked' && !empty($thumb_width) && !empty($thumb_height) ){
			
			$out = '<img src="' . $script_path . '?src=' . $image_url . '&w=' . $thumb_width . '&h=' . $thumb_height . '&zc=' . $zoom_crop . '" />';
			
		} else {

			$out = '<img src="' . $image_url . '" ' . $image_height . ' ' . $image_width . ' />';		
		
		}
		
		if( !empty( $instance['slider-text'] ) ){
			$out .= "\n" . '<span class="slidetext">' . $instance['slider-text'] . '</span>';
		}
		
		echo $before_widget;
	//	echo $before_title . $title . $after_title;
		echo $out;
		echo $after_widget;
	}
}

?>
