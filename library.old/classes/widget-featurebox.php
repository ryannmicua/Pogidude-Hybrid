<?php
/**
 * Feature Box Widget
 *
 * @package Squar_Library
 * @subpackage Classes
 * @author Ryann Micua
 * @link http://www.pogidude.com
 */

/**
 * Feature Box Widget Class
 *
 * @since 0.1
 * @depends Tim Thumb Script
 * @link http://code.google.com/p/timthumb/
 * @optional use with Hybrid-core framework to use automatic prefix and textdomain.
 * @link http://themehybrid.com/themes/hybrid/widgets
 */

class Squar_Feature_Box_Widget extends WP_Widget{

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
	 * Location of Tim Thumb Script
	 */
	var $timthumburl;
	
	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.1
	 */
	function Squar_Feature_Box_Widget(){
	
		/* Setup URL to Tim Thumb Script*/
		$this->timthumburl = SQUAR_SCRIPTS_URI . '/thumb.php';
	
		/* Check if Hybrid-core framework is active */
		if( class_exists( 'Hybrid' ) ){
			/* Set the widget prefix */
			$this->prefix = hybrid_get_prefix();
			
			/* Set the widget textdomain. */
			$this->textdomain = hybrid_get_textdomain();
		} else {
			$this->prefix = 'squar';
			$this->textdomain = 'squar';
		}
		
		$widget_options = array(
						'classname' => 'feature-box',
						'description' => esc_html__( 'Displays a feature box with an image which can link to a URL when clicked.', $this->textdomain )
					);
		
		$control_options = array(
						'height' => '300',
						'width' => '525',
						'id_base' => "{$this->prefix}-feature-box", esc_attr__( 'Feature Box', $this->textdomain )
					);
		
		$this->WP_Widget( "{$this->prefix}-feature-box", esc_attr__( 'A Feature Box', $this->textdomain ), $widget_options, $control_options );
	}
	
	function form( $instance ){
	
		$defaults = array(
						'resize_mode' => '2'
					);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
	
		//following variables needed for Tim Thumb script
		$thumb_width=225; //width
		$thumb_height=120; //height without
		$zoom_crop=2; //0, 1, 2 or 3
		$quality = 75; //default 75 max 100
		$thumb_url = $this->timthumburl;
	?>
		<div class="hybrid-widget-controls columns-2 column-last">
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>" ><?php _e( 'Title : ', $this->textdomain ); ?></label><br />
				<input class="widefat" type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('subtitle'); ?>" ><?php _e( 'Subtitle Entry : ', $this->textdomain ); ?></label>
				<textarea class="widefat" name="<?php echo $this->get_field_name('subtitle'); ?>" id="<?php echo $this->get_field_id('subtitle'); ?>" cols="23" rows="8" ><?php echo $instance['subtitle']; ?></textarea><br />
				<span class="description"><small>You may use html code here.</small></span>
			</p>
			
			<?php if( !empty( $instance['image_url'] ) ) : ?>
				
				<p>
					<label><?php _e('Image Preview Thumbnail', $this->textdomain ); ?></label>
					<img style="background: #f4f4f4; border: 1px solid #cfcfcf; padding: 8px;" src="<?php echo $thumb_url . '?src=' . $instance['image_url'] . '&w=' . $thumb_width . '&h=' . $thumb_height . '&zc=' . $zoom_crop; ?>" width="<?php echo $thumb_width; ?>" height="<?php echo $thumb_height; ?>" /><br />
					<em><small><strong>Warning: </strong>Preview image may not reflect how the image will actually look.</small></em>
				</p>
				
			<?php endif; ?>
			
			<p>
				<label for="<?php echo $this->get_field_id('image_url'); ?>" ><?php _e('Image URL: ', $this->textdomain ); ?></label>
				<textarea class="widefat" name="<?php echo $this->get_field_name('image_url'); ?>" id="<?php echo $this->get_field_id('image_url'); ?>" cols="23" rows="2" ><?php echo $instance['image_url']; ?></textarea>
				<em><small>Example: <code>http://squarmilner.com/image.jpg</code> <a href="<?php echo admin_url('/media-upload.php?type=image&amp;TB_iframe=1' ); ?>" class="thickbox">Image Browser</a></small></em>
			</p>
	
			<p>
				<span>Image Dimensions <em>(in px)</em></span><br />
				<label for="<?php echo $this->get_field_id('image_width'); ?>" ><?php _e('Width: ', $this->textdomain ); ?></label>
				<input type="text" name="<?php echo $this->get_field_name('image_width'); ?>" id="<?php echo $this->get_field_id('image_width'); ?>" value="<?php echo $instance['image_width']; ?>" size="2" />
				<label for="<?php echo $this->get_field_id('image_height'); ?>" ><?php _e('Height: ', $this->textdomain ); ?></label>
				<input type="text" name="<?php echo $this->get_field_name('image_height'); ?>" id="<?php echo $this->get_field_id('image_height'); ?>" value="<?php echo $instance['image_height']; ?>" size="2" />
				<br />
				<span class="description"><small>Values here are applied only when they are provided. If not provided, then the actual dimensions of the image will be used. <strong>Tip:</strong> Specify the width only to set height automatically. Example: <code>300</code></small></span>
			</p>

			<?php if( $instance['errors']['resize'] ) : ?>
				<p style="background: #FFEBE8; border: 1px solid #CC0000; padding: 3px; font-size: 90%;"><strong>Advanced Resize</strong> requires the Image Dimensions option <strong>Width</strong> and <strong>Height</strong> set to a valid value. And value must also be greater than 0.</p>
			<?php endif; ?>
			
		</div>
		
		<div class="hybrid-widget-controls columns-2">
			
			<h3>Advanced Options</h3>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('resize'); ?>" name="<?php echo $this->get_field_name('resize'); ?>" <?php checked( 'checked', $instance['resize'] ); ?> value="checked" /><label for="<?php echo $this->get_field_id('resize'); ?>">Use Advanced Resize</label><br /><span class="description"><small>Resize image to fit the specified image dimensions. <strong>Note:</strong> This option only works if the Width and Height fields have been specified.</small></span>
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
				<br /><br />
				<em class="description"><small><strong><code>Mode 1</code></strong> - Resize to Fit specified dimensions (no cropping)<br /><strong><code>Mode 2</code></strong> - Crop and resize to best fit the dimensions<br /><code><strong>Mode 3</strong></code> - Resize proportionally to fit entire image into specified dimensions, and add borders if required<br /><code><strong>Mode 4</strong></code> - Resize proportionally adjusting size of scaled image so there are no borders gaps</small></em>
			</p>
	
			<p>
				<label for="<?php echo $this->get_field_id('link_url'); ?>" ><?php _e('Link URL: ', $this->textdomain ); ?></label>
				<textarea class="widefat" name="<?php echo $this->get_field_name('link_url'); ?>" id="<?php echo $this->get_field_id('link_url'); ?>" cols="23" rows="2" ><?php echo $instance['link_url']; ?></textarea>
				<em><small>URL the image will link to when clicked. Example: <code>http://example.com</code> <strong>Leave blank</strong> to make the image not link to anything.</small></em>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('newwindow'); ?>" name="<?php echo $this->get_field_name('newwindow'); ?>" <?php checked( 'checked', $instance['newwindow'] ); ?> value="checked" /><label for="<?php echo $this->get_field_id('newwindow'); ?>">Open link in new window or tab</label><br /><span class="description"><small>Adds the <code>target="_blank"</code> attribute to the link.</small></span>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('reorder'); ?>" name="<?php echo $this->get_field_name('reorder'); ?>" <?php checked( 'checked', $instance['reorder'] ); ?> value="checked" /><label for="<?php echo $this->get_field_id('reorder'); ?>" >Show Image before Subtitle Entry</label><br /><span class="description"><small>By default, subtitles are shown before images. Check this box to show the image before the subtitle entry.</small></span>
			</p>
			
		</div>
		
		<div class="clear"></div>
	<?php
	}
	
	function update( $new_instance, $old_instance ){
		$instance = $old_instance;
		
		unset( $instance['errors'] );
		$instance['errors'] = false;
		
		$instance['title'] = esc_html( $new_instance['title'] );
		$instance['subtitle'] = $new_instance['subtitle'];
		$instance['image_url'] = esc_url( $new_instance['image_url'] );
		$instance['image_height'] = ( $new_instance['image_height'] == '' ) ? '' : intval( $new_instance['image_height'] );
		$instance['image_width'] = ( $new_instance['image_width'] == '' ) ? '' : intval( $new_instance['image_width'] );
		$instance['resize'] = ( $new_instance['resize'] == 'checked' ) ? $new_instance['resize'] : '';
		$instance['resize_mode'] = intval( $new_instance['resize_mode'] );
		$instance['link_url'] = esc_url( $new_instance['link_url'] );
		$instance['newwindow'] = ( $new_instance['newwindow'] == 'checked' ) ? $new_instance['newwindow'] : '';
		$instance['reorder'] = ( $new_instance['reorder'] == 'checked' ) ? $new_instance['reorder'] : '';
		
		//check if requirements for Resize Option are met
		if( !empty( $instance['resize'] ) ){
			if( empty( $instance['image_height'] ) || empty( $instance['image_width'] ) ){
				$instance['errors'] = array( 'resize' => true );
			//	$instance['resize'] = '';
			}
		}
		
		return $instance;
	}
	
	function widget( $args, $instance ){
		extract( $args );
		
	//	var_dump( $instance );
		
		//stop on error
		if( $instance['errors'] ) return;
		
		$out = ''; //Will hold the final html output
		
		/* If the title was input by the user, get it */
		$title = !empty( $instance['title'] ) ? $instance['title'] : '';
		
		/* Get the subtitle */
		$subtitle = !empty( $instance['subtitle'] ) ? '<div class="subtitle" >' . $instance['subtitle'] . '</div>' : '' ;
		
		/* Check if the Image File is specified. */
		if( empty( $instance['image_url'] ) ){

			//Image URL is not set. Do nothing.
			$out_image = '';

		} else {
			//Create the html output for the image
			
			/* Check if resize option is set. */
			if( $instance['resize'] === 'checked' ){
				//setup to use Tim Thumb Script
				$use_timthumb = true;
				$tim_image_width = $instance['image_width'];
				$tim_image_height = $instance['image_height'];
				$zc = $instance['resize_mode'];
				$thumbscript_url = $this->timthumburl;
			} else {
				$use_timthumb = false;
				$image_width = ( empty($instance['image_width']) ) ? ' ' : ' width="' . $instance['image_width'] . '" ';
				$image_height = ( empty($instance['image_height']) ) ? ' ' : ' height="' . $instance['image_height'] . '" ';
			}
			
			//create image html output
			$out_image = '';
			if( $use_timthumb ){
				$out_image .= '<img src="' . $thumbscript_url . '?src=' . esc_url( $instance['image_url'] ) . '&w=' . $tim_image_width . '&h=' . $tim_image_height . '&zc=' . $zc . '" />';
			} else {
				$out_image .= '<img src="' . esc_url( $instance['image_url'] ) . '" ' . $image_width . ' ' . $image_height . ' />';
			}
			
			//check if the image should be wrapped in a link <a> tag
			if( !empty( $instance['link_url'] ) ) {
				$targetblank = ( $instance['newwindow'] === 'checked' ) ? 'target="_blank"' : '';
				$out_image = '<a class="subfeature-box-img-link" href="' . esc_url( $instance['link_url'] ) . '" ' . $targetblank . ' />' . $out_image . '</a>';
				// $out holds '<a href=...><img src=... /></a>'
			} else {
				$out_image = $out_image; //$out holds '<img src=... />'
			}
			
			//add wrapping div to image
			$out_image = '<div class="subfeature-box-img-wrap" >' . $out_image . '</div>';
		
		}
		
		/* DISPLAY EVERYTHING */
		
		echo $before_widget;
		
		if( !empty( $title ) ){
			echo $before_title . $title . $after_title;
		}
		
		//check if image should be shown before subtitles
		if( $instance['reorder'] === 'checked' ){
		
			echo $out_image;
			echo $subtitle;
			
		} else {
		
			echo $subtitle;
			echo $out_image;
			
		}
		
		echo $after_widget;
		
	}
}

?>
