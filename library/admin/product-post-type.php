<?php
/**
 * Needs Cart66 http://cart66.com
 */

define('DOG_PRODUCT_CSS', trailingslashit( THEME_ADMIN_URI ) . 'css' );
define('DOG_PRODUCT_JS', trailingslashit( THEME_ADMIN_URI ) . 'js' );

$textdomain = hybrid_get_parent_textdomain();

require_once( trailingslashit( THEME_ADMIN_DIR ) . 'admin-print-field.php' );
//path to css for pogidude admin stylesheet
$pogidude_admin_stylesheet = trailingslashit( THEME_ADMIN_URI ) . 'css/pogidude-admin-style.css';
//register pogidude admin stylesheet
wp_register_style( 'pogidude-admin-stylesheet', $pogidude_admin_stylesheet );


//add theme support for post thumbnails just in case it's not activated
add_theme_support( 'post-thumbnails' );
//add image sizes needed for the product thumbnails
add_image_size('product-single', 220, 9999 ); //220 pixels wide and unlimited height
add_image_size('product-thumb', 135, 135, true ); //135x135 image thumbnails cropped

add_action( 'init', 'bulldog_product_posttype' );
add_action( 'save_post', 'bulldog_save_product_metadata', 10, 2 );

//add submenu page to reorder product display of product pages
add_action( 'admin_menu', 'bulldog_product_menus_init' );

//saves the menu order of product pages using AJAX
add_action( 'wp_ajax_save-product-order', 'bulldog_save_product_order' );

//Enqueue product page specific scripts and styles
add_action( 'admin_enqueue_scripts', 'bulldog_product_print_scripts' );
add_action( 'admin_enqueue_scripts', 'bulldog_product_print_styles' );

//Sort display in main product edit page
add_filter('parse_query', 'bulldog_product_edit_column_order' );

//Modify the "Enter Title Here" text
add_filter('enter_title_here', 'bulldog_product_enter_title_here' );

//allows us to use media library with thickbox
//USAGE: <a href="' . admin_url('/media-upload.php?type=image&amp;TB_iframe=1' ) . '" class="thickbox">Image Browser</a>

//  Add Columns to Product Edit Screen
//  http://wptheming.com/2010/07/column-edit-pages/
add_filter("manage_edit-product_columns", "bulldog_product_edit_columns");
add_action("manage_posts_custom_column",  "bulldog_product_columns_display", 10, 2);

//modify the query $request
add_filter( 'pre_get_posts', 'bulldog_product_alter_the_query' );

function bulldog_product_menus_init(){
	 add_submenu_page('edit.php?post_type=product', 'Reorder Product Page', 'Reorder Products', 'edit_posts', 'sort-product-pages', 'bulldog_product_sort_menu_page');
}

/**
 * Create Product Page custom post type
 * http://codex.wordpress.org/Function_Reference/register_post_type
 */
function bulldog_product_posttype() {
	$textdomain = hybrid_get_parent_textdomain();
	
	$labels = array(
		'name' => __( 'Product Pages', $textdomain ),
		'menu_name' => __( 'The Store', $textdomain ),
		'all_items' => __('Product Pages', $textdomain ),
		'singular_name' => __( 'Product Page', $textdomain ),
		'add_new' => __( 'Add New', $textdomain ),
		'add_new_item' => __( 'Add New Product Page', $textdomain ),
		'edit_item' => __( 'Edit Product Page', $textdomain ),
		'new_item' => __( 'Add New Product Page', $textdomain ),
		'view_item' => __( 'View Product Page', $textdomain ),
		'search_items' => __( 'Search Product Pages', $textdomain ),
		'not_found' => __( 'No product pages found', $textdomain ),
		'not_found_in_trash' => __( 'No product pages found in trash', $textdomain )
	);

	$args = array(
    	'labels' => $labels,
    	'public' => true,
		'supports' => array( 'title', 'editor', 'thumbnail' ),
		//'supports' => array(''),
		'capability_type' => 'post',
		'rewrite' => array("slug" => "shop/products", "with_front"=>false), // Permalinks format
		'menu_position' => 80,
		'has_archive' => true,
		'menu_icon' => trailingslashit( get_theme_root_uri() ) . 'thedog/images/cart.png',
		'register_meta_box_cb' => 'bulldog_product_metabox_init',
		'show_in_nav_menus' => true
	); 

	register_post_type( 'product', $args);
	
	$taxonomy_args = array(
		'hierarchical' => true,
		'label' => __( 'Product Categories', $textdomain ), 
		'labels' => array(
			'name' => __( 'Product Categories', $textdomain ),
			'singular_name' => __( 'Product Category', $textdomain )
		),
		'public' => true,
		'rewrite' => array( 'with_front' => true, 'hierarchical' => true, 'slug' => 'shop/category' ),
		'query_var' => 'product-category'
	);
	
	register_taxonomy( 'product-category', 'product', $taxonomy_args);
}

/**
 * Update Messages
 */
function bulldog_product_updated_messages( $msg ){
	global $post, $post_ID;
	
	$msg['product'] = array(
		0 => '', //unused. Messages start at index 1.
		1 => sprintf( __('Product Page updated. <a href="%s">View Product Page</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Product Page updated.'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Product Page restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Product Page created. <a href="%s">View Product Page</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('Book saved.'),
		8 => sprintf( __('Product Page submitted. <a target="_blank" href="%s">Preview Product Page</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Product Page scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Product Page</a>'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Product Page draft updated. <a target="_blank" href="%s">Preview Product Page</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	
	return $msg;
}

/**
 * Product Enter Title Here Filter - modify the text displayed
 */
function bulldog_product_enter_title_here( $content ){
	if( 'product' == get_post_type() ){
		return 'Enter name of product';
	} else {
		return $content;
	}
}

/**
 * Products Metabox Init
 */
function bulldog_product_metabox_init(){
	add_meta_box('product-metabox-details', 'Product Details', 'bulldog_product_metabox_details', 'product', 'normal', 'high' );
}

/**
 * Product Fields
 *
 * Returns an array containing data for displaying input fields.
 * @param string $fieldgroup
 * @return array
 */
function bulldog_product_fields( $field_group = '' ){
	$fields = array();
	$products_list = array();
	
	if( class_exists( 'Cart66Product' ) ){
	
		//get list of products and store in array
		$product = new Cart66Product();
		$products = $product->getNonSubscriptionProducts();
		
		foreach( $products as $prod ){
			$products_list[ $prod->item_number ] = $prod->name;
		}
		
	}
		
	$fields['product-details'] = array(
									array(	'title' => 'Product to Display',
											'type' => 'select',
											'id' => 'bulldog-product-number',
											'description' => 'Select a product from the list.',
											'options' => $products_list
									),
									array(	'title' => 'Upload a PDF Document',
											'type' => 'wpupload',
											'id' => 'bulldog-product-pdf',
											'description' => 'click on the "Upload Button"'
									),
									array( 	'title' => 'Short Product Description',
											'type' => 'textarea',
											'id' => 'bulldog-product-description',
											'description' => 'Enter a short product description to display on Producat Category pages.',
											'options' => array( 'cols' => 60, 'rows'=> 6 )
									)
									
									/*
									array( 	'title' => 'First Product Image',
											'type' => 'textarea',
											'id' => 'product-image',
											'description' => 'Specify the URL to the product image. You can use the <a href="' . admin_url('/media-upload.php?type=image&amp;TB_iframe=1' ) . '" class="thickbox">Image Browser</a> for this.',
											'options' => array( 'cols' => 20, 'rows'=> 2 )
									),
									/* array(	'title' => 'Use Call for Pricing button',
											'type' => 'checkbox',
											'id' => 'use-call-pricing-button',
											'description' => 'Check this box to replace the "Price" button and show a "Call for Pricing" button instead.'
									) */
								);
	
	if( empty( $field_group ) ){
		return $fields;
	} else {
		if( isset( $fields[ $field_group ] ) ){
			return $fields[ $field_group ];
		} else {
			return array();
		}
	}
}

/**
 * Displays fields for getting Product Details
 *
 * @param object $object Post object that holds all the post information
 * @param array $box The particular meta box being shown and its information
 */
function bulldog_product_metabox_details( $object, $box ){
	$fields = bulldog_product_fields('product-details');
	/*
	$product = new Cart66Product();
	$products = $product->getNonSubscriptionProducts();
	//var_dump( $products[0]->getCheckoutPrice() );
	$product->loadByItemNumber('sape');
	$test = bulldog_check_item_number( 0 );
	var_dump( $test );
	*/
	pogi_print_fields( $fields );
	?>

<?php
}


/**
 * Save post metadata
 */
function bulldog_save_product_metadata( $post_id, $post ){

	//ignore autosaves
	if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	
	//verify nonce
	
	//check if current user has permission
	
	//get the fields to be saved
	$fields = bulldog_product_fields();
	
	//save
	pogi_save_meta_array( $fields['product-details'] );
}

/**
 * Displays the Add to Cart link by calling the "add_to_cart_anchor" shortcode
 *
 * @param string $item_number
 * @return none
 */
function bulldog_add_to_cart_link( $item_number, $args = array() ){
	//setup default values
	$defaults = array( 
		'cartUrl' => trailingslashit( site_url('store/cart') ),
		'class' => 'button add-to-cart',
		'linkText' => 'Add to Cart',
		'options' => ''
	);
	//merge $args
	$args = wp_parse_args( $args, $defaults );
	
	$urlOptions = !empty( $args['options'] ) ? '&options=' . urlencode( $args['options'] ) : '';
	
	/*
	if( class_exists( 'Cart66' ) ){
		echo do_shortcode( '[add_to_cart_anchor item="' . $item_number . '" class="button add-to-cart" options="Small~Black"]Add to Cart[/add_to_cart_anchor]' );
	} else {
		echo 'Cart66 Plugin is not installed.';
	}*/

	if( !class_exists( 'Cart66Product' ) ){
		$out = 'Please install Cart66 plugin.';
	} else {
		$product = new Cart66Product();
		$pid = $product->loadByItemNumber( $item_number );

		$classes = 'Cart66AddToCart add-to-cart ' . $args['class'];
		$cart_url = trailingslashit( site_url('store/cart') );
		$join_char = (strpos($cart_url, '?') === FALSE) ? '?' : '&';
		$url = $args['cartUrl'] . $join_char . "task=add-to-cart-anchor&cart66ItemId={$pid}{$urlOptions}";
		
		$out = '<a class="' . $classes . '" href="' . $url . '">' . $args['linkText'] . '</a>';
		
	}
		
	return $out;

}

/**
 * Get Product ID
 *
 * @param string $item_number (sku)
 * @return int|null
 */
function bulldog_get_product_id( $item_number ){
	$out = '';
	$pid = '';
	
	if( class_exists( 'Cart66Product' ) ){
		$product = new Cart66Product();
		$pid = $product->loadByItemNumber( $item_number );
		
		$out = $pid;
		
	} else {
		$out = 'Please install Cart66 plugin.';
	}
		
	return $out;
}

/**
 * Returns the Product Price - wrapper function around Cart66Product->getCheckoutPrice()
 *
 * @param string $item_number
 * @param bool $echo - default true. display the price or return it.
 * @return string - price of the product
 */
function bulldog_get_product_price( $item_number ){
	
	$out = '';
	
	if( class_exists( 'Cart66Product' ) ){
		$product = new Cart66Product();
		$product->loadByItemNumber( $item_number );
		$price = $product->getCheckoutPrice();

		if( defined(CART66_CURRENCY_SYMBOL) ){
			$currencySymbol = CART66_CURRENCY_SYMBOL;
		} else {
			$currencySymbol = '$';
		}
	
		$out = $currencySymbol . $price;
		
	} else {
		$out = 'Please install Cart66 plugin.';
	}
		
	return $out;
}

/**
 * Product Excerpt. Use in the Loop
 */
function bulldog_product_excerpt( $charlength = 100, $ender = null ){
	
	$charlength++;
	$out = '';
	$str = get_the_excerpt();
		
	if( strlen( $str ) > $charlength ){
		$subex = substr($str,0,$charlength-5);
       	$exwords = explode(" ",$subex);
		$excut = -(strlen($exwords[count($exwords)-1]));
		if( $excut < 0 ){
			$out = substr( $subex, 0, $excut );
		} else {
			$out = $subex;
		}
		
		$out .= isset( $ender ) ? $ender : '...' ;
		
	} else {
		$out = $str;
	}
	
	return $out;
}

/**
 * Get the product image
 *
 * @param string $post_id
 * @param array $imagesize = thumb | medium | large
 * @param string $echo default true
 * @return string = return or echo image on success | empty string on failure
 */
function bulldog_get_product_image( $post_id = '' , $imagesize = 'thumb', $echo = true ){
	
	if( empty( $post_id ) ){
		$post_id = get_the_ID();
	} else {
		$post_id = intval( $post_id );
	}
	
	$sizes = array( 'thumb' => 155, 'medium' => 300, 'large' => 600 );
	
	//check if $imagesize is in the list of sizes. Set to default size if not found
	if( !array_key_exists( $imagesize, $sizes ) ){
		$imagesize = 'thumb';
	}
	
	//get product image
	$product_image = get_post_meta( $post_id, 'product-image', true );
	
	if( empty( $product_image ) ) return '';
	
	$timthumburl = THEME_LIB_URI . '/scripts/thumb.php';
	$zc = 3;
	$quality = 80;
	
	$imgsrc = $timthumburl . '?src=' . $product_image . '&w=' . $sizes[ $imagesize ] . '&h=600&zc=' . $zc . '&q=' . $quality;
	
	//$out = '<img src="' . $product_image . '" width="' . $sizes[$imagesize] . '" />';
	$out = '<img src="' . $imgsrc . '" />';
	
	if( false == $echo ) {
		return $out;
	} else {
		echo $out;
	}
}

/**
 * Check if the product item number is valid
 *
 * @param string $item_number
 * @param return bool true if $item_number is valid else false.
 */
function bulldog_check_item_number( $item_number ){
	if( class_exists( 'Cart66Product' ) ){
		$product = new Cart66Product();
		$isValid = $product->loadByItemNumber( $item_number );//function returns null if invalid $item_number
		
		//return false if $isValid == NULL else return true for valid item_number
		return !is_null( $isValid );

	}
}

/**********************************
	ADMIN PAGE STUFF
**********************************/

/**
 * Enqueue Scripts
 */
function bulldog_product_print_scripts(){
	global $pagenow, $typenow;
	$pages = array('edit.php');
	$page = $_GET['page'];
	$action = $_GET['action'];
	
	wp_register_script('bulldog-product-uploadjs', trailingslashit( DOG_PRODUCT_JS ) . 'admin-product-upload.js', array('jquery','thickbox','media-upload' ), "1.0" );
	wp_register_script('ca-sort-product', trailingslashit( DOG_PRODUCT_JS ) . 'admin-product-sort.js', array( 'jquery-ui-sortable', 'jquery' ), "1.0" );
	
	//stop if not Admin page, or post_type != product
	if( !is_admin() || 'product' != get_post_type() ){ 
		return;
	}

	wp_enqueue_script('thickbox');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('bulldog-product-uploadjs');
	
	//load script sort-product-pages page
	if ('sort-product-pages' == $page ){
		wp_enqueue_script('ca-sort-product' );
	}
	
}

/**
 * Enqueue Styles
 */
function bulldog_product_print_styles(){
	global $pagenow, $typenow;
	$pages = array('edit.php');
	$page = $_GET['page'];
	
	//stop if not Admin page, $pagenow != "edit.php", post_type != "product"
	if( !is_admin() || 'product' != get_post_type() ) return;
	wp_enqueue_style( 'pogidude-admin-stylesheet' );
	
	//stop if not in sort-product-pages page
	if ('sort-product-pages' != $page ) return;

	wp_enqueue_style('ca-sort-product', trailingslashit( DOG_PRODUCT_CSS ) . 'admin-product-sort.css' );
}

/**
 * Modify display in product edit pages
 */
function bulldog_product_edit_columns($product_columns){
	$product_columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => _x('Product Name', 'column name'),
		//"thumbnail" => __('Thumbnail', 'cleanaire'),
		"product-category" => __('Category', 'cleanaire')

	);

	return $product_columns;
}


function bulldog_product_columns_display($product_columns, $post_id){

	switch ($product_columns)
	{
		// Code from: http://wpengineer.com/display-post-thumbnail-post-page-overview
		
		case "thumbnail":
			$width = (int) 35;
			$height = (int) 35;
			$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
			
			// Display the featured image in the column view if possible
			if ($thumbnail_id) {
				$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
			}
			if ( isset($thumb) ) {
				echo $thumb;
			} else {
				echo __('None', 'cleanaire');
			}
			break;	
			
		case "product-category":
			$tags = get_the_terms($post->ID, 'product-category');
			if ( !empty( $tags ) ) {
				$out = array();
				//http://dev.rbattle.com/cleanaire/wp-admin/edit-tags.php?action=edit&taxonomy=product-category&tag_ID=108&post_type=product
				foreach ( $tags as $c )
					$out[] = '<a href="edit.php?post_type=product&product-category=' . $c->slug . '">' . esc_html(sanitize_term_field('name', $c->name, $c->term_id, 'product-category', 'display')) . '</a>';
				echo join( ', ', $out );
			} else {
				_e('No category.');  //No Taxonomy term defined
			}
			break;
	}
}

/**
 * Order product pages by menu_order first, then date second
 */
function bulldog_product_edit_column_order( $query ){
	global $pagenow, $typenow;
	
	//do nothing if not admin or not product page in admin
	if( !is_admin || 'product' != $typenow ) return $query;

	set_query_var('orderby','menu_order');
	set_query_var('order','ASC');
}

// Sets posts displayed per portfolio page to 9 -- Feel free to change
function wpt_portfolio_custom_posts_per_page( &$q ) {
	if ( get_post_type() == 'portfolio' )
		$q->set( 'posts_per_page', 9 );
	return $q;
}


function wpt_portfolio_icons() {
    ?>
    <style type="text/css" media="screen">
        #menu-posts-portfolio .wp-menu-image {
            background: url(<?php echo get_template_directory_uri(); ?>/images/portfolio-icon.png) no-repeat 6px 6px !important;
        }
		#menu-posts-portfolio:hover .wp-menu-image, #menu-posts-portfolio.wp-has-current-submenu .wp-menu-image {
            background-position:6px -16px !important;
        }
		#icon-edit.icon32-posts-portfolio {background: url(<?php echo get_template_directory_uri(); ?> /images/portfolio-32x32.png) no-repeat;}
    </style>
<?php 
}

/**
 * Subpage for sorting Product pages by menu order
 */
function bulldog_product_sort_menu_page(){
	$modules = new WP_Query('post_type=product&posts_per_page=-1&orderby=menu_order&order=ASC');
	global $typenow;
?>
	<div class="wrap">
		<h3>Reorder Product Pages <img src="<?php bloginfo('url'); ?>/wp-admin/images/loading.gif" id="loading-animation" /></h3>

		<ul id="product-list">
		<?php while( $modules->have_posts() ) : $modules->the_post(); ?>
			<li id="<?php the_id(); ?>"><?php the_title(); ?></li>
		<?php endwhile; ?>
		</ul>
	</div>
<?php
}

/**
 * Function to handle sorting of Product pages using AJAX
 * Updates the menu order of individual Product Pages
 */
function bulldog_save_product_order(){
	global $wpdb;
	$order = explode( ',', $_POST['order']);
	$counter = 0;
	
	foreach ($order as $id){
		$wpdb->update($wpdb->posts, array( 'menu_order' => $counter ), array( 'ID' => $id) );
		$counter++;
	}
	die(1);
}

/**
 * Alter the request query for product category pages
 */
//add_filter( 'request', 'bulldog_alter_the_query' );
//add_filter( 'pre_get_posts', 'bulldog_alter_the_query' );
function bulldog_product_alter_the_query( $request ){
	
	$posts_per_page = of_get_option( 'shop_products_per_page' );
	
	if ( $request->is_tax( 'product-category' ) ){
        $request->query_vars['posts_per_page'] = $posts_per_page;
	}

    return $request;
}
