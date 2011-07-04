<?php
/**
 * Custom Navigation Menu Template Classes
 *
 * @package Pogidude
 * @subpackage Nav_Menus
 */


/**
 * CUstom Nav Menu Walker Class
 */
class Mega_Dropdown_Walker extends Walker_Nav_Menu{

	/**
	 * @see Walker_Nav_Menu::start_el()
	 * @since 1.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 * @param int $children_count number of direct children
	 */
	function start_el(&$output, $item, $depth, $args, $children_count ) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';
		
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		
		//add number of children to classes
		$classes[] = 'menu-item-children-' . $children_count;
		//add depth
		$classes[] = 'menu-item-depth-' . $depth;

		//check if element is a column container
		//use the Attribute Field to specify "column" containers
		$is_column_container = false;
		if( !empty( $item->attr_title ) && $item->attr_title == 'column' ){
			$is_column_container = true;
			$classes[] = 'mega-dropdown-column';
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
	
		if( $is_column_container ){
			$output .= $indent . '<ul' . $class_names . '>';
		} else {
	
			$output .= $indent . '<li' . $id . $value . $class_names .'>';
	
			$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
			$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
			$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
			$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
			
			//add "top-link" class to top-level nav item link
			$attributes .= ( $depth == 0 ) ? ' class="top-link"' : '';
			
			//add <h4> wrapping tags around items with class of "heading"
			if( in_array( 'heading', $classes ) ){
				$before = '<h4>';
				$after = '</h4>';
			} else {
				$before = $after = '';
			}
			
			$item_output = $args->before;
		//	$item_output .= $before; //outputs <h4> if class "heading" is specified
			$item_output .= '<a'. $attributes .'>';
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= '</a>';
			$numargs = func_get_arg(3);
		//	$item_output .= $after; //outputs <h4> if class "heading" is specified
		//	$item_output .= $args->after . var_export( func_get_arg( 4 ), true );
	
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}

	}

	/**
	 * @see Nav_Menu_Walker::end_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Page data object. Not used.
	 * @param int $depth Depth of page. Not Used.
	 */
	function end_el(&$output, $item, $depth) {
	
		//use the Attribute Field to specify "column" containers
		if( !empty( $item->attr_title ) && $item->attr_title == 'column' ){
			$output .= "</ul>\n";
		} else {
			$output .= "</li>\n";
		}
	}
	
	/**
	 * @see Nav_Menu_Walker::start_lvl()
	 * @since 1.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 * @param 
	 */
	function start_lvl(&$output, $depth, $element, $children_elements) {
		$indent = str_repeat("\t", $depth);
		
		$id_field = $this->db_fields['id'];
		
		if( $depth == 0 ){
			//get number of children
			$child_count = count( $children_elements[ $element->$id_field] );
			
			$output .= "\n$indent<div class=\"sub col{$child_count}\">\n" ;
			
		} elseif( $depth == 1){
			$output .= '';
		} else {
			$output .= "\n$indent<ul class=\"sub-menu\">\n";
		}
	}

	/**
	 * @see Nav_Menu_Walker::end_lvl()
	 * @since 1.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	function end_lvl(&$output, $depth) {
		$indent = str_repeat("\t", $depth);
		
		if( $depth == 0 ){
			$output .= "$indent</div><!-- $depth -->\n";
		} elseif( $depth == 1){
			$output .= '';
		} else {
			$output .= "$indent</ul>\n";
		}
	}

	/**
	 * @see Walker::end_lvl()
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth. It is possible to set the
	 * max depth to include all depths, see walk() method.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
		
		if ( !$element )
			return;

		$id_field = $this->db_fields['id'];

		//count number of children
		$num_children = count($children_elements[ $element->$id_field ]);
		$args['num_children'] = $num_children;
		
		//display this element
		if ( is_array( $args[0] ) )
			$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array(&$this, 'start_el'), $cb_args);

		$id = $element->$id_field;

		// descend only when the depth is right and there are childrens for this element
		if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

			foreach( $children_elements[ $id ] as $child ){

				if ( !isset($newlevel) ) {
					$newlevel = true;
					//start the child delimiter
					$cb_args = array_merge( array(&$output, $depth, $element, $children_elements), $args);
					call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
				}
				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}
			unset( $children_elements[ $id ] );
		}

		if ( isset($newlevel) && $newlevel ){
			//end the child delimiter
			$cb_args = array_merge( array(&$output, $depth), $args);
			call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
		}

		//end this element
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array(&$this, 'end_el'), $cb_args);
	}
}