<?php
/**
 * Loop Nav Template
 *
 * Modified for non-hybrid themes. This template is used to show your your next/previous post links on singular pages and
 * the next/previous posts links on the home/posts page and archive pages.
 *
 * @package Pogidude
 * @subpackage Template
 */
?>
	<?php $loop_args = array(	'end_size' => 2,
								'mid_size' => 2
							);
	?>
							
	<?php if ( is_attachment() ) : ?>

		<div class="loop-nav">
			<?php previous_post_link( '%link', '<span class="previous">' . __( '&larr; Return to entry', 'cleanaire' ) . '</span>' ); ?>
		</div><!-- .loop-nav -->

	<?php elseif ( is_singular( 'post' ) ) : ?>

		<div class="loop-nav">
			<?php previous_post_link( '<div class="previous">' . __( 'Previous Entry: %link', 'cleanaire' ) . '</div>', '%title' ); ?>
			<?php next_post_link( '<div class="next">' . __( 'Next Entry: %link', 'cleanaire' ) . '</div>', '%title' ); ?>
		</div><!-- .loop-nav -->

	<?php elseif ( !is_singular() ) : loop_pagination( $loop_args ); ?>

	<?php elseif ( !is_singular() && $nav = get_posts_nav_link( array( 'sep' => '', 'prelabel' => '<span class="previous">' . __( '&larr; Previous', 'cleanaire' ) . '</span>', 'nxtlabel' => '<span class="next">' . __( 'Next &rarr;', 'cleanaire' ) . '</span>' ) ) ) : ?>

		<div class="loop-nav">
			<?php echo $nav; ?>
		</div><!-- .loop-nav -->

	<?php endif; ?>