<?php
/**
 * Loop that displays posts. Fallback for all loop templates
 *
 * @package Pogidude
 * @subpackage Template
 */

$textdomain = hybrid_get_parent_textdomain();
?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
		<?php if( is_archive() || is_search() || is_home() )  : ?>
			<h2 class="entry-title"><a rel="bookmark" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
		<?php else: ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php endif; ?>
		
		<?php echo apply_atomic_shortcode( 'byline', '<div class="byline">' . get_the_time( esc_attr__( 'F jS, Y, g:i A', $textdomain ) ) . __( ' By [entry-author] [entry-edit-link before=" | "]', $textdomain ) . '</div>' ); ?>
		
		<div class="entry-content">
			
			<?php if( is_archive() || is_search() || is_home() )  : ?>
				<?php the_excerpt(); ?>
				<a class="readmore" href="<?php the_permalink(); ?>">read more</a>
			<?php else: ?>
				<?php the_content(); ?>
				
				<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', hybrid_get_parent_textdomain() ), 'after' => '</div>' ) ); ?>
			<?php endif; ?>
				
			<div class="clear"></div>
		</div><!-- .entry-content -->
		
	</div><!-- #post-<?php the_ID(); ?> -->
	
	<?php comments_template( '/comments.php', true ); // Loads the comments.php template ?>
	
<?php endwhile; endif; ?>

<?php get_template_part('loop','nav'); ?>
