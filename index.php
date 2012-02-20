<?php
/**
 * Index Template
 *
 * @package Pogidude
 * @subpackage Template
 */
?>

			<!-- #### END HEADER ### -->
			
				<div id="content">
					<div class="hfeed">
						<?php get_template_part('loop', get_post_format() ); ?>
					</div><!-- .hfeed -->
				</div><!-- #content -->
				
				<?php get_sidebar(); ?>
				
			<!-- ### START FOOTER ### -->

