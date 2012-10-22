<?php

add_action('cisv_init','cisv_init');

function cisv_init(){
	require_once 'definitions.php';
	
	/** Load Functions **/
	
	/** Load structure **/
	
	/** Load Admin **/
	require_once THEME_ADMIN_DIR . '/theme-settings.php';
}

do_action('cisv_init');
