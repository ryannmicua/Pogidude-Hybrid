<?php
/**
 * Index Template
 *
 * @package Pogidude
 * @subpackage Template
 */
?>
<?php get_header(); ?>
<!-- #### END HEADER ### -->

	<div id="content">
		<div class="hfeed">
			<?php get_template_part('loop', get_post_format() ); ?>
		</div><!-- .hfeed -->
	</div><!-- #content -->
	
	<?php get_sidebar(); ?>
	
<!-- ### START FOOTER ### -->
<?php get_footer(); ?>