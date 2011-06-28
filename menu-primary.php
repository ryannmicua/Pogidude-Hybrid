<?php
/**
 * Primary Menu Template
 *
 * Displays the Primary Menu if it has active menu items.
 *
 * @package Pogidude
 * @subpackage Template
 */

if ( has_nav_menu( 'primary' ) ) : ?>

	<div id="menu-primary" class="menu-container">

		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'menu', 'menu_class' => '', 'menu_id' => 'menu-primary-items', 'fallback_cb' => '' ) ); ?>

	</div><!-- #menu-primary .menu-container -->

<?php endif; ?>
