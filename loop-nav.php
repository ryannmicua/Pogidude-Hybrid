<?php
/**
 * Loop Nav Template
 *
 * This template is used to show your your next/previous post links on singular pages and
 * the next/previous posts links on the home/posts page and archive pages.
 *
 * @package Pogidude
 * @subpackage Template
 */
?>

	<?php if ( is_attachment() ) : ?>

		<div class="loop-nav">
			<?php previous_post_link( '%link', '<span class="previous">' . __( '&larr; Return to entry', hybrid_get_parent_textdomain() ) . '</span>' ); ?>
		</div><!-- .loop-nav -->

	<?php elseif ( is_singular( 'post' ) ) : ?>
	
		<div class="loop-nav">
			
			<?php previous_post_link( '<div class="previous"><span>' . __( 'Previous Post ', hybrid_get_parent_textdomain() ) . '</span>' . __('%link', hybrid_get_parent_textdomain()) . '</div>', '%title' ); ?>
			
			<?php /*
			<div class="previous">
				<?php previous_post_link( '<span>' . __('%link', hybrid_get_parent_textdomain() ) . '</span>','Previous Post'); ?>
				<?php previous_post_link( '%link','%title'); ?>
			</div>
			*/ ?>
			
			<?php next_post_link( '<div class="next"><span>' . __( 'Next Post ', hybrid_get_parent_textdomain() ) . '</span>' . __('%link', hybrid_get_parent_textdomain()) . '</div>', '%title' ); ?>
			
			<?php /*
			<div class="next">
				<?php next_post_link( '<span>' . __('%link', hybrid_get_parent_textdomain() ) . '</span>','Next Post'); ?>
				<?php next_post_link( '%link','%title'); ?>
			</div>
			*/ ?>
			
			<div class="clear"></div>
		</div><!-- .loop-nav -->

	<?php elseif ( !is_singular() && current_theme_supports( 'loop-pagination' ) ) : loop_pagination(); ?>

	<?php elseif ( !is_singular() && $nav = get_posts_nav_link( array( 'sep' => '', 'prelabel' => '<span class="previous">' . __( '&larr; Previous', hybrid_get_parent_textdomain() ) . '</span>', 'nxtlabel' => '<span class="next">' . __( 'Next &rarr;', hybrid_get_parent_textdomain() ) . '</span>' ) ) ) : ?>

		<div class="loop-nav">
			<?php echo $nav; ?>
		</div><!-- .loop-nav -->

	<?php endif; ?>
