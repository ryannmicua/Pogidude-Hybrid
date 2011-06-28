<?php
/**
 * Comment Template
 *
 * The comment template displays an individual comment. This can be overwritten by templates specific
 * to the comment type (comment.php, comment-{$comment_type}.php, comment-pingback.php, 
 * comment-trackback.php) in a child theme.
 *
 * @package Pogidude
 * @subpackage Template
 */

	global $post, $comment;
?>

	<li id="comment-<?php comment_ID(); ?>" class="<?php hybrid_comment_class(); ?>">

		<?php do_atomic( 'before_comment' ); // {theme-name}_before_comment ?>
		
		<div class="author-avatar vcard">
			<?php echo get_avatar($comment,$size='48' ); ?>
		</div>
		
		<div class="comment-author">
			<?php printf( __('<h4 class="fn">%s</h4>'), get_comment_author_link() ); ?>
		</div>
		
		<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a> <a class="comment-link" href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('Link to this comment')) ?></a><?php edit_comment_link(__('(Edit)'),'  ','') ?></div>

		
		<div class="comment-text">
			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="alert moderation"><?php _e( 'Your comment is awaiting moderation.', hybrid_get_textdomain() ); ?></p>
			<?php endif; ?>

			<?php comment_text( $comment->comment_ID ); ?>
			
			<div class="reply">
				<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</div>
		</div><!-- .comment-text -->


		<?php do_atomic( 'after_comment' ); // {theme-name}_after_comment ?>

	<?php /* No closing </li> is needed.  WordPress will know where to add it. */ ?>
