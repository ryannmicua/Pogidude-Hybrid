<?php
/* This file contains bootstrap for Options Framework Theme Settings */

//get the theme prefix
$prefix = hybrid_get_prefix();

define('OPTIONS_FRAMEWORK_URL', trailingslashit( THEME_ADMIN_DIR ) );
define('OPTIONS_FRAMEWORK_DIRECTORY', trailingslashit( THEME_ADMIN_URI ) );

require_once (OPTIONS_FRAMEWORK_URL . 'options-framework.php');