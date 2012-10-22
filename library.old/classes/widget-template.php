<?php
/**
 * Widget Name
 */

class Widget_Class_Name_Widget extends WP_Widget{

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
	 
	function  Widget_Class_Name_Widget(){

		/* Check if Hybrid-core framework is active */
		if( class_exists( 'Hybrid' ) ){
			/* Set the widget prefix */
			$this->prefix = hybrid_get_prefix();
			
			/* Set the widget textdomain. */
			$this->textdomain = hybrid_get_parent_textdomain();
		} else {
			$this->prefix = 'prefix';
			$this->textdomain = 'prefix';
		}
		
		$this->widget_id = 'widget-id';
		
		$widget_options = array(
						'classname' => "{$this->prefix}-{$this->widget_id}",
						'description' => esc_html__( 'Description of the widget', $this->textdomain )
					);
		
		$control_options = array(
						'height' => '300',
						'width' => '240',
						'id_base' => "{$this->prefix}-{$this->widget_id}"
					);
		
		$this->WP_Widget( "{$this->prefix}-{$this->widget_id}", esc_attr__( 'Widget Name', $this->textdomain ), $widget_options, $control_options );
		
	}
	
	function form( $instance ){
		//setup default values
		$defaults = array();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = esc_attr($instance['title']);
		$textdomain = $this->textdomain;
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', $textdomain); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
			<em class="description"><small>Example: Title of the Widget</small></em>
		</p>

		<?php
	}
	
	function update( $new_instance, $old_instance ){
		$instance['title'] = esc_attr( $new_instance['title'] );
		
		return $instance;
	}
	
	function widget( $args, $instance){
		extract( $args );
		$unique_id = $args['widget_id'];
		$title = $instance['title'];

		$textdomain = $this->textdomain;
		?>
		<?php echo $before_widget; ?>
		
		<?php
		/* If a title was input by the user, display it. */
		if ( !empty( $title ) ){ 
			echo $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title;
		} ?>
		
		<div class="custom-class">
			<?php //DO YOUR STUFF HERE ?>
		</div>
		
		<?php echo $after_widget; ?>
		
	<?php
	}

}

