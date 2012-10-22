<?php
/**
 * Social Connect Widget
 */

class Widget_Social_Connect extends WP_Widget{

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
	
	function Widget_Social_Connect(){

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
		
		$this->widget_id = 'social-connect';
		
		$widget_options = array(
						'classname' => "{$this->prefix}-{$this->widget_id}",
						'description' => esc_html__( 'Add social icons to your sidebar', $this->textdomain )
					);
		
		$control_options = array(
						'height' => '300',
						'width' => '240',
						'id_base' => "{$this->prefix}-{$this->widget_id}"
					);
		
		$this->WP_Widget( "{$this->prefix}-{$this->widget_id}", esc_attr__( 'Social Connect', $this->textdomain ), $widget_options, $control_options );
		
	}
	
	function form( $instance ){
		//setup default values
		$defaults = array();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = esc_attr($instance['title']);
		$contacturl = esc_url( $instance['contact-url'] );
		$linkedin = esc_url( $instance['linkedin'] );
		$facebook = esc_url( $instance['facebook'] );
		$phone = esc_attr( $instance['phone'] );
		$email = $instance['email'];
		$textdomain = $this->textdomain;
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', $textdomain); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('contact-url'); ?>"><?php _e('Contact Form URL:', $textdomain); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('contact-url'); ?>"  value="<?php echo $contacturl; ?>" class="widefat" id="<?php echo $this->get_field_id('contact-url'); ?>" />
			<em class="description"><small>Ex: http://www.ampersandmiami.com/contact/</small></em>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('linkedin'); ?>"><?php _e('LinkedIn Profile URL:', $textdomain); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('linkedin'); ?>"  value="<?php echo $linkedin; ?>" class="widefat" id="<?php echo $this->get_field_id('linkedin'); ?>" />
			<em class="description"><small>Ex: http://www.linkedin/in/ampersandmiami/</small></em>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('FaceBook Page URL:', $textdomain); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('facebook'); ?>"  value="<?php echo $facebook; ?>" class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" />
			<em class="description"><small>Ex: http://www.facebook.com/pages/ampersandmiami/</small></em>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('phone'); ?>"><?php _e('Contact Number:', $textdomain); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('phone'); ?>"  value="<?php echo $phone; ?>" class="widefat" id="<?php echo $this->get_field_id('phone'); ?>" />
			<em class="description"><small>Ex: 305.206.1047</small></em>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('E-mail Address:', $textdomain); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('email'); ?>"  value="<?php echo $email; ?>" class="widefat" id="<?php echo $this->get_field_id('email'); ?>" />
			<em class="description"><small>Ex: info@ampersandmiami.com</small></em>
		</p>
		
		<?php
	}
	
	function update( $new_instance, $old_instance ){
		$instance['title'] = esc_attr( $new_instance['title'] );
		$instance['contact-url'] = esc_url( $new_instance['contact-url'] );
		$instance['facebook'] = esc_url( $new_instance['facebook'] );
		$instance['linkedin'] = esc_url( $new_instance['linkedin'] );
		$instance['email'] = is_email( $new_instance['email'] ) ? $new_instance['email'] : '';
		$instance['phone'] = esc_attr( $new_instance['phone'] );
		
		return $instance;
	}
	
	function widget( $args, $instance){
		extract( $args );
		$title = $instance['title'];
		$contacturl = $instance['contact-url'];
		$facebook = $instance['facebook'];
		$linkedin = $instance['linkedin'];
		$email = $instance['email'];
		$phone = $instance['phone'];
		$unique_id = $args['widget_id'];

		$textdomain = $this->textdomain;
		?>
		<?php echo $before_widget; ?>
		
		<div id="<?php echo $unique_id; ?>" class="social-connect-wrap">
			<?php if ($title){ echo $before_title . $title . $after_title;} ?>
			<div class="contents">
				<ul class="icons">
					<li><a href="<?php echo $contacturl; ?>" class="contacturl">Contact</a></li>
					<li><a href="<?php echo $linkedin; ?>" class="linkedin">LinkedIn</a></li>
					<li><a href="<?php echo $facebook; ?>" class="facebook">FaceBook</a></li>
				</ul>
				<p class="phone"><?php echo $phone; ?></p>
				<a class="email" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
			</div>
		</div>
		
		<?php echo $after_widget; ?>
		
	<?php
	}
}
