<?php
/**
 * Sidebar Primary Template
 *
 * Displays any widgets for the Primary Sidebar dynamic sidebar if they are available.
 *
 * @package Pogidude
 * @subpackage Template
 */

?>

	<div id="sidebar-primary" class="sidebar">
 		<div class="padder">
 		
			<?php if ( is_active_sidebar( 'primary' ) ) : ?>
			
				<?php dynamic_sidebar( 'primary' ); ?>
				
			<?php else: ?>
				
				<div class="widget">
					<div class="widget-wrap">
						<h3 class="widget-title">Primary Sidebar</h3>
						<p style="padding: 10px; background: #fff;">This is the <em>Primary Sidebar Widget Area</em>. You can modify this widget area by going to the <a href="<?php echo admin_url('widgets.php'); ?>" >widget settings</a> page.</p>
					</div>
				</div>
				
			<?php endif; ?>
		
		</div>
	</div><!-- #sidebar-primary -->
