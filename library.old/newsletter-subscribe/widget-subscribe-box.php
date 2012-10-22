<?php
/**
 * Newsletter Subscribe Widget
 */

class Widget_Subscribe_Box extends WP_Widget{

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
	 
	function  Widget_Subscribe_Box(){

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
		
		$this->widget_id = 'newsletter-subscribe-box';
		
		$widget_options = array(
						'classname' => "{$this->prefix}-{$this->widget_id}",
						'description' => esc_html__( 'Add a newsletter subscribe box to your sidebar. Do not use this widget more than once in the same page.', $this->textdomain )
					);
		
		$control_options = array(
						'height' => '300',
						'width' => '240',
						'id_base' => "{$this->prefix}-{$this->widget_id}"
					);
		
		$this->WP_Widget( "{$this->prefix}-{$this->widget_id}", esc_attr__( 'Newsletter Subscribe', $this->textdomain ), $widget_options, $control_options );
		
	}
	
	function form( $instance ){
		//setup default values
		$defaults = array(
			'placeholder' => 'Email Updates and Special Offers'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = $instance['title'];
		$placeholder = $instance['placeholder'];
		$textdomain = $this->textdomain;
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', $textdomain); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('placeholder'); ?>"><?php _e('Placeholder Text:', $textdomain); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('placeholder'); ?>"  value="<?php echo $placeholder; ?>" class="widefat" id="<?php echo $this->get_field_id('placeholder'); ?>" />
			<em class="description"><small>Text to display when user has not entered anything into the email input box.</small></em>
		</p>

		<?php
	}
	
	function update( $new_instance, $old_instance ){
		$instance['title'] = esc_attr( $new_instance['title'] );
		$instance['placeholder'] = esc_attr( $new_instance['placeholder'] );
		return $instance;
	}
	
	function widget( $args, $instance){
		extract( $args );
		$unique_id = $args['widget_id'];
		$title = $instance['title'];
		$placeholder = $instance['placeholder'];
		$textdomain = $this->textdomain;
		?>
		<?php echo $before_widget; ?>
		
		<?php
		/* If a title was input by the user, display it. */
		if ( !empty( $title ) ){ 
			echo $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title;
		} ?>
		
		<form class="newsletter-subscribe-wrap" method="POST">
			<input class="input" type="email" placeholder="<?php echo $placeholder; ?>" name="email" />
			<input type="submit" class="submit" value="Sign Up" />
			<input type="hidden" name="action" value="newsletter-ajax-subscribe" />
			<?php wp_nonce_field( 'newsletter-nonce', 'nonce' ); //generate nonce ?>
		</form>
		
		<div class="newsletter-subscribe-box-response" style="display: none;">
			<div class="response">
			</div>
		</div>
		
		<?php echo $after_widget; ?>
		
	<?php
	}

}

