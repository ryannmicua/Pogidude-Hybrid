<?php
/**
 * Loop Meta Template
 *
 * Displays information at the top of the page about archive and search results when viewing those pages.  
 * This is not shown on the home page and singular views.
 *
 * @package YCWL
 * @subpackage Template
 */
?>

	<?php global $wp_query; ?>
	
	<?php if ( is_home() && !is_front_page() ) : ?>

		<div class="loop-meta">

			<h3 class="loop-title"><?php echo get_post_field( 'post_title', $wp_query->get_queried_object_id() ); ?></h3>
			
			<?php /*
			<div class="loop-description">
				<?php echo apply_filters( 'the_excerpt', get_post_field( 'post_excerpt', $wp_query->get_queried_object_id() ) ); ?>
			</div><!-- .loop-description -->
			*/ ?>

		</div><!-- .loop-meta -->

	<?php elseif ( is_page() ) : ?>

		<div class="loop-meta">

			<h3 class="loop-title"><?php echo get_post_field( 'post_title', $wp_query->get_queried_object_id() ); ?></h3>

		</div><!-- .loop-meta -->

	<?php elseif ( is_singular() && in_category(array('news') ) ) : ?>

		<div class="loop-meta">

			<h3 class="loop-title"><?php _e('News', hybrid_get_parent_textdomain() ); ?></h3>

		</div><!-- .loop-meta -->
		
	<?php elseif ( is_singular() ) : ?>

		<div class="loop-meta">

			<h3 class="loop-title"><?php _e('Blog', hybrid_get_parent_textdomain() ); ?></h3>

		</div><!-- .loop-meta -->
		
	<?php elseif ( is_category() ) : ?>

		<div class="loop-meta">

			<h3 class="loop-title"><?php single_cat_title(); ?></h3>

			<div class="loop-description">
				<?php echo category_description(); ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_tag() ) : ?>

		<div class="loop-meta">

			<h3 class="loop-title"><?php single_tag_title(); ?></h3>

			<div class="loop-description">
				<?php echo tag_description(); ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_tax() ) : ?>

		<div class="loop-meta">

			<h3 class="loop-title"><?php single_term_title(); ?></h3>

			<div class="loop-description">
				<?php echo term_description( '', get_query_var( 'taxonomy' ) ); ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_author() ) : ?>

		<?php $id = get_query_var( 'author' ); ?>

		<div id="hcard-<?php the_author_meta( 'user_nicename', $id ); ?>" class="loop-meta vcard">

			<h3 class="loop-title fn n"><?php the_author_meta( 'display_name', $id ); ?></h3>

			<div class="loop-description">
				<?php echo get_avatar( get_the_author_meta( 'user_email', $id ), '100', '', get_the_author_meta( 'display_name', $id ) ); ?>

				<p class="user-bio">
					<?php the_author_meta( 'description', $id ); ?>
				</p><!-- .user-bio -->
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_search() ) : ?>

		<div class="loop-meta">

			<h3 class="loop-title"><?php echo esc_attr( get_search_query() ); ?></h3>

			<div class="loop-description">
				<p>
				<?php printf( __( 'You are browsing the search results for &quot;%1$s&quot;', hybrid_get_parent_textdomain() ), esc_attr( get_search_query() ) ); ?>
				</p>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_date() ) : ?>

		<div class="loop-meta">
			<h3 class="loop-title"><?php _e( 'Archives by date', hybrid_get_parent_textdomain() ); ?></h3>

			<div class="loop-description">
				<p>
				<?php _e( 'You are browsing the site archives by date.', hybrid_get_parent_textdomain() ); ?>
				</p>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_post_type_archive() ) : ?>

		<?php $post_type = get_post_type_object( get_query_var( 'post_type' ) ); ?>

		<div class="loop-meta">

			<h3 class="loop-title"><?php post_type_archive_title(); ?></h3>

			<div class="loop-description">
				<?php if ( !empty( $post_type->description ) ) echo "<p>{$post_type->description}</p>"; ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_archive() ) : ?>

		<div class="loop-meta">

			<h3 class="loop-title"><?php _e( 'Archives', hybrid_get_parent_textdomain() ); ?></h3>

			<div class="loop-description">
				<p>
				<?php _e( 'You are browsing the site archives.', hybrid_get_parent_textdomain() ); ?>
				</p>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php endif; ?>
