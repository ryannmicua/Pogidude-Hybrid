<?php
/**
 * The Image Button Widget allows a user to add image buttons that links to a URL
 *
 * @package Mobility21_Library
 * @subpackage Classes
 * @author Ryann Micua
 * @link http://www.pogidude.com
 */

/**
 * Image Button Widget Class
 *
 * @since 0.1
 * @depends Tim Thumb Script
 * @link http://code.google.com/p/timthumb/
 * @optional use with Hybrid-core framework to use automatic prefix and textdomain.
 * @link http://themehybrid.com/themes/hybrid/widgets
 */

class Image_Button_Widget extends WP_Widget{

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
	function Image_Button_Widget(){
	
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
						'classname' => 'image-button',
						'description' => esc_html__( 'Displays an image button and can link to a URL when clicked.', $this->textdomain ) 
					);
		
		$control_options = array(
						'height' => '300',
						'width' => '250',
						'id_base' => "{$this->prefix}-image-button", esc_attr__( 'Image Button', $this->textdomain )
					);
		
		$this->WP_Widget( "{$this->prefix}-image-button", esc_attr__( 'Mobility Image Button', $this->textdomain ), $widget_options, $control_options );
	}
	
	function form( $instance ){
	
		$defaults = array(
						'div_class' => 'image-button-wrap'
					);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
	
		//following variables needed for Tim Thumb script
		$thumb_width=225; //width
		$thumb_height=120; //height without
		$zoom_crop=0; //0 or 1
		$quality = 75; //default 75 max 100
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>" ><?php _e( 'Title : ', $this->textdomain ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('image_url'); ?>" ><?php _e('Image URL: ', $this->textdomain ); ?></label>
			<textarea name="<?php echo $this->get_field_name('image_url'); ?>" id="<?php echo $this->get_field_id('image_url'); ?>" cols="23" rows="4" ><?php echo $instance['image_url']; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('image_alt_text'); ?>" ><?php _e('Alt Text ', $this->textdomain ); ?><em><small>(optional - for seo purposes)</small></em> :</label>
			<input type="text" name="<?php echo $this->get_field_name('image_alt_text'); ?>" id="<?php echo $this->get_field_id('image_alt_text'); ?>" value="<?php echo $instance['image_alt_text']; ?>" size="25" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('image_width'); ?>" ><?php _e('Width: ', $this->textdomain ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('image_width'); ?>" id="<?php echo $this->get_field_id('image_width'); ?>" value="<?php echo $instance['image_width']; ?>" size="2" />
			<label for="<?php echo $this->get_field_id('image_height'); ?>" ><?php _e('Height: ', $this->textdomain ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('image_height'); ?>" id="<?php echo $this->get_field_id('image_height'); ?>" value="<?php echo $instance['image_height']; ?>" size="2" />
			<br />
			<em><small>Tip: Set width only to set height automatically.</small></em>
		</p>

		<?php if( !empty( $instance['image_url'] ) ) : ?>
		<p>
			<label><?php _e('Image Preview Thumbnail', $this->textdomain ); ?></label>
			<img src="<?php echo MOBILITY_SCRIPTS_URI . '/thumb.php?src=' . $instance['image_url'] . '&w=' . $thumb_width . '&h=' . $thumb_height . '&zc=' . $zoom_crop; ?>" width="<?php echo $thumb_width; ?>" height="<?php echo $thumb_height; ?>" />
		</p>
		<?php endif; ?>

		<p>
			<label for="<?php echo $this->get_field_id('link_url'); ?>" ><?php _e('Link URL: ', $this->textdomain ); ?></label>
			<textarea name="<?php echo $this->get_field_name('link_url'); ?>" id="<?php echo $this->get_field_id('link_url'); ?>" cols="23" rows="4" ><?php echo $instance['link_url']; ?></textarea>
		</p>

		<?php /*
		<p>
			<label for="<?php echo $this->get_field_id('link_text'); ?>" ><?php _e('Link Text ', $this->textdomain ); ?><em><small>(optional - for seo purposes)</small></em> :</label>
			<input type="text" name="<?php echo $this->get_field_name('link_text'); ?>" id="<?php echo $this->get_field_id('link_text'); ?>" value="<?php echo $instance['link_text']; ?>" size="25" />
		</p>
		*/ ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('link_title'); ?>" ><?php _e('Link Title: ', $this->textdomain ); ?><em><small>(optional - for seo purposes)</small></em> :</label>
			<input type="text" name="<?php echo $this->get_field_name('link_title'); ?>" id="<?php echo $this->get_field_id('link_title'); ?>" value="<?php echo $instance['link_title']; ?>" size="25" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('div_class'); ?>" ><?php _e('Div Class ', $this->textdomain ); ?><em><small>(default: image-button-wrap)</small></em> :</label>
			<input type="text" name="<?php echo $this->get_field_name('div_class'); ?>" id="<?php echo $this->get_field_id('div_class'); ?>" value="<?php echo $instance['div_class']; ?>" size="25" />
		</p>
	<?php
	}
	
	function update( $new_instance, $old_instance ){
		$instance = $old_instance;
		
		$instance = $new_instance;
		
		$instance['title'] = sanitize_title( $new_instance['title'] );
		$instance['body_text'] = $new_instance['body_text'];
		$instance['image_url'] = $new_instance['image_url'];
		$instance['image_height'] = strip_tags( $new_instance['image_height'] );
		$instance['image_width'] = strip_tags( $new_instance['image_width'] );
		$instance['image_alt_text'] = strip_tags( $new_instance['image_alt_text'] );
		$instance['link_url'] = $new_instance['link_url'];
		$instance['link_text'] = strip_tags( $new_instance['link_text'] );
		$instance['link_title'] = strip_tags( $new_instance['link_title'] );
		$instance['div_class'] = strip_tags( $new_instance['div_class'] );
		
		return $instance;
	}
	
	function widget( $args, $instance ){
		extract( $args );
		
		/* if the title was input by the user, get it */
		$title =  !empty( $instance['title'] ) ? $instance['title'] : '';
		
		/* additional text usually a description */
		$body_text = $instance['body_text'];
		
		/* URL to the image used */
		$image_url = $instance['image_url'];
		
		/* Height of the image */
		$image_height = ( !empty( $instance['image_height'] ) ) ? 'height="' . $instance['image_height'] . '"' : '';
		
		/* Width of the image */
		$image_width = (  $instance['image_width'] ) ? 'width="' . $instance['image_width'] . '"' : '';
		
		/* Alt text for the image */
		$image_alt_text = ( !empty( $instance['image_alt_text'] ) ) ? 'alt="' . $instance['image_alt_text'] . '"' : '';
		
		/* href URL */
		$link_url = ( !empty( $instance['link_url'] ) ) ? $instance['link_url'] : '';
		
		/* Link text */
		$link_text = ( !empty( $instance['link_text'] ) ) ? $instance['link_text'] : '';
		
		/* Link title */
		$link_title = ( !empty( $instance['link_title'] ) ) ? 'title="' . $instance['link_title'] . '"' : '';
		
		/* div class wrapping the image button */
		$div_class = ( empty( $instance['div_class'] ) ) ? 'image-button-wrap' : $instance['div_class'] ;
		
		$out = '<div class="' . $div_class . '">';
		
		if( !empty( $link_url ) ){
			$out .= '<a href="' . $link_url . '" ' . $link_title . ' >' . $link_text;
		}
		
		$out .= '<img src="' . $image_url . '" ' . $image_height . '" ' . $image_width . '" ' . $image_alt_text . ' />';
		
		if( !empty( $link_url ) ){
			$out .= '</a>';
		}
		
		/* close the div class */
		$out .= '</div>';
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
		echo $out;
		echo $after_widget;
		
	}
}

?>
