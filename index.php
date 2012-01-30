<?php
/**
 * Index Template
 *
 * @package Pogidude
 * @subpackage Template
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="utf-8" />
<title><?php hybrid_document_title(); ?></title>

<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" media="screen" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!-- enables older IE browsers to accept HTML5 elements
take out the script below if using modernizr or other similar solution 
=========================================================================-->
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>

</head>

<body class="<?php hybrid_body_class(); ?>">

	<div id="main-wrap">
		<div id="header">
			<div id="branding">
				<?php hybrid_site_title(); ?>
				<?php hybrid_site_description(); ?>
			</div><!-- #branding -->
			
			<?php get_template_part( 'menu', 'primary' ); // Loads the menu-primary.php template. ?>
			
		</div><!-- #header -->
		
		<div id="main">
			<div class="padder">
			<!-- #### END HEADER ### -->
			
				<div id="content">
					<div class="hfeed">
						<?php get_template_part('loop', get_post_format() ); ?>
					</div><!-- .hfeed -->
				</div><!-- #content -->
				
				<?php get_sidebar(); ?>
				
			<!-- ### START FOOTER ### -->
			</div><!-- .padder -->
		</div><!-- #main -->
		
		<div id="footer">
		</div><!-- #footer -->
		
	</div><!-- #main-wrap-->

<?php wp_footer(); ?>
</body>
</html>
