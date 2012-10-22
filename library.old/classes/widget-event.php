<?php
/**
 * WMN About Widget
 */

class Pogidude_Event_Widget extends WP_Widget{

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
	 
	function Pogidude_Event_Widget(){

		/* Check if Hybrid-core framework is active */
		if( class_exists( 'Hybrid' ) ){
			/* Set the widget prefix */
			$this->prefix = hybrid_get_prefix();
			
			/* Set the widget textdomain. */
			$this->textdomain = hybrid_get_textdomain();
		} else {
			$this->prefix = 'pogidude';
			$this->textdomain = 'pogidude';
		}
		
		$this->widget_id = 'event-widget';
		
		$widget_options = array(
						'classname' => "{$this->prefix}-{$this->widget_id}",
						'description' => esc_html__( 'Displays your Events in the sidebar.', $this->textdomain )
					);
		
		$control_options = array(
						'height' => '300',
						'width' => '240',
						'id_base' => "{$this->prefix}-{$this->widget_id}"
					);
		
		$this->WP_Widget( "{$this->prefix}-{$this->widget_id}", esc_attr__( 'TM: Event Widget', $this->textdomain ), $widget_options, $control_options );
		
	}
	
	function form( $instance ){
		//setup default values
		$defaults = array( 'display-count' => 4 );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = esc_attr($instance['title']);
		$display_count = intval( $instance['display-count'] );
		$events_page = esc_url( $instance['main-event-page'] );
		$textdomain = $this->textdomain;
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', $textdomain); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('display-count'); ?>"><?php _e('Events to show:', $textdomain); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('display-count'); ?>" name="<?php echo $this->get_field_name('display-count'); ?>">
				<option value="1" <?php selected( $display_count, 1 ); ?>>1</option>
				<option value="2" <?php selected( $display_count, 2 ); ?>>2</option>
				<option value="3" <?php selected( $display_count, 3 ); ?>>3</option>
				<option value="4" <?php selected( $display_count, 4 ); ?>>4</option>
				<option value="5" <?php selected( $display_count, 5 ); ?>>5</option>
				<option value="6" <?php selected( $display_count, 6 ); ?>>6</option>
				<option value="7" <?php selected( $display_count, 7 ); ?>>7</option>
			</select>
			
			<?php /*<input type="text" name="<?php echo $this->get_field_name('display-count'); ?>"  value="<?php echo $instance['display-count']; ?>" class="widefat" id="<?php echo $this->get_field_id('display-count'); ?>" /> */ ?>
			<em class="description"><small><strong>Default: 4.</strong> Nearest upcoming event will be listed first.</small></em>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('main-event-page'); ?>"><?php _e('Main Event Page URL:', $textdomain); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('main-event-page'); ?>"  value="<?php echo $events_page; ?>" class="widefat" id="<?php echo $this->get_field_id('main-event-page'); ?>" />
			<em class="description"><small>URL to the page where all upcoming events are listed.</small></em>
		</p>
		
		<?php
	}
	
	function update( $new_instance, $old_instance ){
		$instance['title'] = esc_attr( $new_instance['title'] );
		$instance['display-count'] = intval( $new_instance['display-count'] );
		$instance['main-event-page'] = esc_url( $new_instance['main-event-page'] );
		return $instance;
	}
	
	function widget( $args, $instance){
		extract( $args );
		$unique_id = $args['widget_id'];
		$title = $instance['title'];
		$display_count = isset( $instance['display-count'] ) ? $instance['display-count'] : 4;
		$textdomain = $this->textdomain;
		?>
		
		<?php echo $before_widget; ?>
		
		<?php
		/* If a title was input by the user, display it. */
		if ( !empty( $title ) ){ 
			echo $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title;
		} 

		$meta_query = array(
			array(	'key' => 'tm-event-date',
					'value' => date( 'Y-m-d', time() - 60*60*36 ), //less 1.5 days
					'compare' => ">"
			)
		);

		$args = array( 'posts_per_page' => $display_count, 'post_type' => 'event', 'order'=> 'ASC', 'meta_key' => 'tm-event-date', 'orderby' => 'meta_value ID', 'meta_query' => $meta_query );
		$event_query = new WP_Query( $args );
		?>
		
		<div class="wmn-event-widget widget-content">
			<?php if( $event_query->have_posts() ) : ?>
				<ul>
					<?php while( $event_query->have_posts() ) : $event_query->the_post(); ?>
						<?php 	
								$fields = get_post_custom( get_the_ID );
								$link = $fields['tm-event-url'][0];
								$pre_title = '';
								$post_title = '';
								$location_display = '';
								$location = $fields['tm-event-location'];
								$display_date = $fields['tm-event-display-date'];
								
								if( empty( $display_date ) ){ 
									$display_date = $fields['tm-event-date'];
									$display_date = date( 'F j, Y', strtotime( $display_date ) );
								}
								
								if( !empty($link ) ){
									$pre_title = '<a href="' . $link . '">';
									$post_title = '</a>';
								}
								
								if( !empty( $location ) ){
									$location_display = ' - <span class="location">' . $location . '</span>';
								}
								
						?>
						<li>
							<h5 class="event-title"><?php echo $pre_title; ?><?php the_title(); ?><?php echo $post_title; ?></h5>
							<span class="date"><?php echo $display_date; ?> <?php echo $location_display; ?></span>
						</li>
	
					<?php endwhile; ?>
				</ul>
				<?php if( !empty( $instance['main-event-page'] ) ) : ?>
				<a class="event-page" href="<?php echo $instance['main-event-page']; ?>">Complete List</a>
				<?php endif; ?>
			
			<?php else: ?>
				No upcoming events.
			<?php endif; ?>
		</div>
		
		<?php echo $after_widget; ?>
		
	<?php
	}

}
