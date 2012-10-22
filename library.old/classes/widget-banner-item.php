<?php
/* LOAD THICKBOX and MEDIA-UPLOAD SCRIPT */
/**
 * Banner Item Widget
 *
 * @package Squar_Library
 * @subpackage Classes
 * @author Ryann Micua
 * @link http://www.pogidude.com
 */

/**
 * Banner Item Widget Class
 *
 * @since 0.1
 * @depends Tim Thumb Script
 * @link http://code.google.com/p/timthumb/
 * @optional use with Hybrid-core framework to use automatic prefix and textdomain.
 * @link http://themehybrid.com/themes/hybrid/widgets
 */

class Squar_Banner_Item_Widget extends WP_Widget{

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
	var $timthumb;
	
	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.1
	 */
	function Squar_Banner_Item_Widget(){
	
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

		/* Setup URL to Tim Thumb Script*/
		$this->timthumb = SQUAR_SCRIPTS_URI . '/thumb.php';
		
		$widget_options = array(
						'classname' => 'banner-item',
						'description' => esc_html__( 'Adds a banner item to frontpage slider or into the inside page banner area.', $this->textdomain ) 
					);
		
		$control_options = array(
						'height' => '300',
						'width' => '700',
						'id_base' => "{$this->prefix}-banner-item", esc_attr__( 'A Banner Item', $this->textdomain )
					);
		
		$this->WP_Widget( "{$this->prefix}-banner-item", esc_attr__( 'A Banner Item', $this->textdomain ), $widget_options, $control_options );
	}
	
	function form( $instance ){
	
		$defaults = array( 'resize_mode' => 3 );
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		//following variables needed for Tim Thumb script
		$thumb_width=378; //width
		$thumb_height=140; //height without
		$zoom_crop=2; //0 | 1 | 2 | 3
		$quality = 75; //default 75 max 100
		$timthumb = $this->timthumb;
	?>

		<div class="hybrid-widget-controls columns-2" style="width:400px;" >
		
			<?php if( !empty( $instance['image_url'] ) ) : ?>
			<h3 style="margin: 0 0 3px 0;"><?php _e('Image Preview Thumbnail', $this->textdomain ); ?></h3>
			<div style="background: #f4f4f4; border: 1px solid #ccc; padding: 10px; min-height: 120px; margin-bottom: 3px; text-align: center;">
				<img src="<?php echo $timthumb . '?src=' . $instance['image_url'] . '&w=' . $thumb_width . '&h=' . $thumb_height . '&zc=' . $zoom_crop; ?>" />
			</div>
			<span class="description"><small><strong>Warning: </strong>Preview image may not reflect how the image will actually look.</small></span>
			<?php endif; ?>
			
			<h3 style="margin-bottom: 0;">Advanced Settings</h3>
			<p><em><small>Use the settings here to create additional html content.</small></em></p>
			
			<p>
				<label for="<?php echo $this->get_field_id('enable_html_code'); ?>" ><?php _e('Enable Custom HTML Code: ', $this->textdomain ); ?></label>
				<input type="checkbox" id="<?php echo $this->get_field_id('enable_html_code'); ?>" name="<?php echo $this->get_field_name('enable_html_code'); ?>" value="checked" <?php checked('checked', $instance['enable_html_code'] ); ?> />
				<em><small></small></em>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('html_code'); ?>" ><?php _e('Custom HTML Content: ', $this->textdomain ); ?></label>
				
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
			
			<p>
				<h3 style="margin: 0 0 3px 0;"><?php _e('Link URL: ', $this->textdomain ); ?></h3>
				<textarea class="widefat code" name="<?php echo $this->get_field_name('link_url'); ?>" id="<?php echo $this->get_field_id('link_url'); ?>" cols="32" rows="3" ><?php echo $instance['link_url']; ?></textarea>
				<br /><em><small>Setting this option will make the whole banner area clickable. Provide the URL here. <strong>Example: http://example-site.com</strong>
				<br />
				<br />This setting is ignored when <strong>HTML Content</strong> is added in the <strong>Advanced Settings</strong>.</small></em>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('newwindow'); ?>" name="<?php echo $this->get_field_name('newwindow'); ?>" <?php checked( 'checked', $instance['newwindow'] ); ?> value="checked" /><label for="<?php echo $this->get_field_id('newwindow'); ?>">Open link in new window or tab</label><br /><span class="description"><small>Adds the <code>target="_blank"</code> attribute to the link.</small></span>
			</p>
			
		</div>
		
		<div class="hybrid-widget-controls columns-2 column-last" style="width: 280px;">

			<h3 style="margin: 0 0 3px 0;"><?php _e( 'Title', $this->textdomain ); ?></h3>
			<input type="text" class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			<br /><em><small>This won't be displayed. Used only for identifying this widget in the widget area.</small></em>
			
			<h3 style="margin-bottom: 0;">Basic Settings</h3>
			<p><em><small>Add a banner image and optionally make it clickable by providing a link url.</small></em></p>
			
			<p>
				<label for="<?php echo $this->get_field_id('heading'); ?>" ><?php _e( 'Main Heading <small>(optional)</small>: ', $this->textdomain ); ?></label><br />
				<input class="widefat" type="text" name="<?php echo $this->get_field_name('heading'); ?>" id="<?php echo $this->get_field_id('heading'); ?>" value="<?php echo esc_attr( $instance['heading'] ); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('subheading'); ?>" ><?php _e('Subheading <small>(optional)</small>: ', $this->textdomain ); ?></label>
				<textarea class="widefat code" name="<?php echo $this->get_field_name('subheading'); ?>" id="<?php echo $this->get_field_id('subheading'); ?>" cols="32" rows="3" ><?php echo $instance['subheading']; ?></textarea>
				<br />
				<em><small>Text that will appear below the Main Heading. HTML Code accepted. Use <code>&lt;br /&gt;</code> to create a new line.</small></em>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('image_url'); ?>" ><?php _e('Background Image URL <small>(required)</small>: ', $this->textdomain ); ?></label>
				<textarea class="widefat code" name="<?php echo $this->get_field_name('image_url'); ?>" id="<?php echo $this->get_field_id('image_url'); ?>" cols="32" rows="3" ><?php echo $instance['image_url']; ?></textarea>
				<br />
				<em><small>Use the <strong>width</strong> and <strong>height</strong> settings below to resize the image. Example: <code>http://example.com/image.jpg</code> <a href="<?php echo admin_url('/media-upload.php?type=image&amp;TB_iframe=1' ); ?>" class="thickbox">Image Browser</a></small></em>
			</p>
	
			<p>
				<strong>Image Dimension Options</strong><br />
				<label for="<?php echo $this->get_field_id('image_width'); ?>" ><?php _e('Width: ', $this->textdomain ); ?></label>
				<input type="text" name="<?php echo $this->get_field_name('image_width'); ?>" id="<?php echo $this->get_field_id('image_width'); ?>" value="<?php echo $instance['image_width']; ?>" size="4" />
				<label for="<?php echo $this->get_field_id('image_height'); ?>" ><?php _e('Height: ', $this->textdomain ); ?></label>
				<input type="text" name="<?php echo $this->get_field_name('image_height'); ?>" id="<?php echo $this->get_field_id('image_height'); ?>" value="<?php echo $instance['image_height']; ?>" size="4" />
				<br />
				<em><small>Values here are applied only when they are provided. If not provided, then the actual dimensions of the background image will be used. <strong>Example: 250.</strong></small></em>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('resize'); ?>" name="<?php echo $this->get_field_name('resize'); ?>" <?php checked( 'checked', $instance['resize'] ); ?> value="checked" /><label for="<?php echo $this->get_field_id('resize'); ?>">Use Advanced Resize</label><br /><span class="description"><small>Resize image to fit the specified image dimensions. <em>Note:</em> This option only works if the Width and Height fields have been specified.</small></span>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('resize_mode'); ?>" ><?php _e('Resize Mode: ', $this->textdomain ); ?></label>
				
				<br />
				
				<select id="<?php echo $this->get_field_id('resize_mode'); ?>" name="<?php echo $this->get_field_name('resize_mode'); ?>" class="widefat" style="min-width: 80%;">
	
					<option value="0" <?php selected( '0', esc_attr( $instance['resize_mode'] ) ); ?> >Mode 1 Resize to fit dimensions</option>
					<option value="1" <?php selected( '1', esc_attr( $instance['resize_mode'] ) ); ?> >Mode 2 Crop and resize to best fit</option>
					<option value="2" <?php selected( '2', esc_attr( $instance['resize_mode'] ) ); ?> >Mode 3 Resize proportionally w/ borders</option>
					<option value="3" <?php selected( '3', esc_attr( $instance['resize_mode'] ) ); ?> >Mode 4 Resize proportionally (Default)</option>
				
				</select>
				<br /><br />
				<em class="description"><small><strong><code>Mode 1</code></strong> - Resize to Fit specified dimensions (no cropping)<br /><strong><code>Mode 2</code></strong> - Crop and resize to best fit the dimensions<br /><code><strong>Mode 3</strong></code> - Resize proportionally to fit entire image into specified dimensions, and add borders if required<br /><code><strong>Mode 4</strong></code> - Resize proportionally adjusting size of scaled image so there are no borders gaps</small></em>
			</p>
			
			<!--p>
				<input type="checkbox" id="<?php echo $this->get_field_id('center'); ?>" name="<?php echo $this->get_field_name('center'); ?>" <?php checked( 'checked', $instance['center'] ); ?> value="checked" /><label for="<?php echo $this->get_field_id('center'); ?>">Center the banner</label><br /><span class="description"><small>Resize image to fit the specified image dimensions. <em>Note:</em> This option only works if the Width and Height fields have been specified.</small></span>
			</p-->

			<?php if( $instance['errors']['resize'] ) : ?>
				<p style="background: #FFEBE8; border: 1px solid #CC0000; padding: 3px; font-size: 90%;">Resize option requires the Image Dimensions option <strong>Width</strong> and <strong>Height</strong> set to a valid value. And value must also be greater than 0.</p>
			<?php endif; ?>
			
		</div>

		
		<div style="clear:both;">&nbsp;</div>

	<?php
	}
	
	function update( $new_instance, $old_instance ){
	
		$instance = $old_instance;
		
		unset( $instance['errors'] );
		$instance['errors'] = array();
		
		$instance['title'] = esc_html( $new_instance['title'] );
		$instance['heading'] = esc_html( $new_instance['heading'] );
		$instance['subheading'] = $new_instance['subheading'];
		$instance['image_url'] = esc_url( $new_instance['image_url'] );
		$instance['image_height'] = ( $new_instance['image_height'] == '' ) ? '' : intval( $new_instance['image_height'] );
		$instance['image_width'] = ( $new_instance['image_width'] == '' ) ? '' : intval( $new_instance['image_width'] );
		$instance['link_url'] = esc_url( $new_instance['link_url'] );
		$instance['html_code'] = $new_instance['html_code'];
		$instance['enable_html_code'] = $new_instance['enable_html_code'] == 'checked' ? $new_instance['enable_html_code'] : '' ;
		$instance['resize'] = $new_instance['resize'] == 'checked' ? $new_instance['resize'] : '' ;
		$instance['resize_mode'] = intval( $new_instance['resize_mode'] );
		$instance['newwindow'] = $new_instance['newwindow'] == 'checked' ? $new_instance['newwindow'] : '' ;
		$instance['center'] = $new_instance['center'] == 'checked' ? $new_instance['center'] : '';

		//check if requirements for Resize Option are met
		if( !empty( $instance['resize'] ) ){
			if( empty( $instance['image_height'] ) || empty( $instance['image_width'] ) ){
				$instance['errors'] = array( 'resize' => true );
				//$instance['resize'] = '';
			}
		}
		
		return $instance;
	}
	
	function widget( $args, $instance ){
		extract( $args );
		
		$out = '';
		
		if( $instance['errors'] ) return;
		
		/* Check if the Image File is specified. */
		if( empty( $instance['image_url'] ) ){

			//Image URL is not set. Do nothing.
			$noimage = true;
			
		} else {

			//Create the html output for the image
			
			/* Check if resize option is set. */
			if( $instance['resize'] === 'checked' ){
				//setup to use Tim Thumb Script
				$use_timthumb = true;
				$tim_image_width = $instance['image_width'];
				$tim_image_height = $instance['image_height'];
				$zc = $instance['resize_mode'];
				$thumbscript_url = $this->timthumb;
			} else {
				$use_timthumb = false;
				$image_width = ( empty($instance['image_width']) ) ? ' ' : ' width="' . $instance['image_width'] . '" ';
				$image_height = ( empty($instance['image_height']) ) ? ' ' : ' height="' . $instance['image_height'] . '" ';
			}
			
			//create image html output
			$out_image = '';
			if( $use_timthumb ){
				$out_image .= '<img class="banner-img" src="' . $thumbscript_url . '?src=' . esc_url( $instance['image_url'] ) . '&w=' . $tim_image_width . '&h=' . $tim_image_height . '&zc=' . $zc . '" />';
			} else {
				$out_image .= '<img src="' . esc_url( $instance['image_url'] ) . '" ' . $image_width . ' ' . $image_height . ' />';
			}
			
		}
		
		//get Main Heading
		$main_heading = empty( $instance['heading'] ) ? '' : '<h3 class="banner-heading">' . esc_html( $instance['heading'] ) . '</h3>';
		
		//get Sub-Heading
		$sub_heading = empty( $instance['subheading'] ) ? '' : '<p class="banner-subheading">' . do_shortcode( $instance['subheading'] ) . '</p>';
		
		//get custom HTML content
		if( $instance['enable_html_code'] === 'checked' ){
			$html_content = do_shortcode( $instance['html_code'] );
		} else {
			$html_content = '';
		}
		
		//wrap all other content
		$out = '<div class="banner-html"><div class="banner-html-padding">' . $main_heading . $sub_heading . $html_content . '</div></div>';
		
		//prepend image
		$out = $out_image . $out;
		
		//check if everything should be wrapped in a link <a> tag
		if( !empty( $instance['link_url'] ) ) {
			$targetblank = ( $instance['newwindow'] === 'checked' ) ? 'target="_blank"' : '';
			$out = '<a class="banner-img-link" href="' . esc_url( $instance['link_url'] ) . '" ' . $targetblank . ' />' . $out . '</a>';
			// $out holds '<a href=...><img src=... /></a>'
		}
		
		echo $before_widget;
		//echo $before_title . $title . $after_title;
		echo $out;
		echo $after_widget;
	}
}

?>
