<?php
/**
 * Template Name: Events
 */

?>

<?php get_header(); ?>
<!-- #### END HEADER ### -->
	
<div id="content">
	<?php breadcrumb_trail(array('before'=>'')); ?>
	<div class="hfeed">
	
	<!-- ##START CONTENT -->
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
			<h1 class="entry-title"><?php the_title(); ?></h2>
			
			<div class="entry-content">
				
				<?php the_content(); ?>
					
				<div class="clear"></div>
				

<?php /* START EVENT CONTENT 
============================================*/ ?>
<?php
$meta_query = array(
	array(	'key' => 'tm-event-date',
			'value' => date( 'Y-m-d', time() - 60*60*36 ), //less 1.5 days
			'compare' => ">"
	)
);
$args = array( 'posts_per_page' => -1, 'post_type' => 'event', 'order' => 'ASC', 'meta_key' => 'tm-event-date', 'orderby' => 'meta_value ID', 'meta_query' => $meta_query );

$event_query = new WP_Query( $args );
if( $event_query->have_posts() ) :
?>

<ul id="event-list">
	<?php while( $event_query->have_posts() ) : $event_query->the_post(); ?>
	<?php
		$pre_title = $post_title = $display_date = $location_display = $link1 = $link2 = '';
		
		$fields = get_post_custom( get_the_ID() ); //usage: $fields[META_NAME][0] 
		$unixtime = strtotime( $fields['tm-event-date'][0] );
		$main_url = !empty( $fields['tm-event-url'][0] ) ? $fields['tm-event-url'][0] : '';
		if( !empty( $main_url ) ){
			$pre_title = '<a href="' . $main_url . '">';
			$post_title = '</a>';
		}
		$event_title = $pre_title . get_the_title() . $post_title;
							
		$display_date = !empty( $fields['tm-event-display-date'][0] ) ? $fields['tm-event-display-date'][0] : '';
		if( empty( $display_date ) ){ 
			$display_date = $fields['tm-event-date'][0];
			$display_date = date( 'l, F jS, Y', $unixtime );
		}
		$display_date = '<span class="event-date">' . $display_date . '</span>';
	
		$location = !empty( $fields['tm-event-location'][0] ) ? $fields['tm-event-location'][0] : '';
		if( !empty( $location ) ){
			$location_display = ' - <span class="location">' . $location . '</span>';
		}
		
		$event_meta = $display_date . $location_display;
		
		if( !empty( $fields['tm-event-link-text1'][0] ) && !empty( $fields['tm-event-link-url1'] ) ){
			$link1 = '<a class="button" href="' . $fields['tm-event-link-url1'][0] . '">' . $fields['tm-event-link-text1'][0] . '</a>';
		}
		
		if( !empty( $fields['tm-event-link-text2'][0] ) && !empty( $fields['tm-event-link-url2'] ) ){
			$link2 = '<a class="button" href="' . $fields['tm-event-link-url2'][0] . '">' . $fields['tm-event-link-text2'][0] . '</a>';
		}
		
		$secondary_links = "{$link1} {$link2}"; //either or both links will be blank
		
		$event_content = get_the_content();
		
		$event_image_args = array( 'echo' => false, 'image_scan' => true, 'link_to_post' => false, 'meta_key_save' => true, 'image_class' => 'event-image', 'width' => 120, 'size' => 'event-image' );
		$event_image = get_the_image( $event_image_args );
	?>
		<li>
			<?php if( !empty( $event_image ) ) : ?>
				<?php echo $pre_title . $event_image . $post_title; ?>
			<?php endif; ?>
			<div class="details">
				<h4 class="event-title"><?php echo $event_title; ?></h4>
				<p class="event-meta"><?php echo $event_meta; ?></p>
				<div class="event-entry"><?php echo $event_content; ?></div>
				<div class="event-links"><?php echo $secondary_links; ?></div>
			</div>
		</li>
	<?php endwhile; ?>
</ul>

<?php endif; ?>
<?php //======END EVENT CONTENT ================== ?>

		</div><!-- .entry-content -->
		
	</div><!-- #post-<?php the_ID(); ?> -->
	
<?php endwhile; endif; ?>

<!-- ##END CONTENT -->

		</div><!-- .hfeed -->
	</div><!-- #content -->
	
	<?php get_sidebar(); ?>
	
<!-- ### START FOOTER ### -->

<?php get_footer(); ?>