<?php
/**
 * Social Sharing Buttons
 * Requires WP Socializer Plugin. http://www.aakashweb.com/docs/wp-socializer-docs/manual-placement/
 */
?>
<?php if( function_exists( 'wp_socializer' ) ) : ?>
<div class="social-share">
	<?php echo wp_socializer( 'facebook'  ); ?>
	<?php echo wp_socializer( 'retweet', array('type'=>'nocount') ); ?>
	<?php echo wp_socializer( 'linkedin'  ); ?>
	<?php echo wp_socializer( 'plusone', array('type' => 'small') ); ?>
</div>
<?php endif; ?>
