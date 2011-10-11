<?php
/**
 * The following set of functions prints individual form fields to the page (e.g. text fields, radio button )
 * This was adapted from Hybrid Theme Framework
 * 
 */

/**
 * Outputs a text input box with the given arguments for use with the post meta box.
 *
 * @since 0.7.0
 * @param array $args 
 * @param string|bool $value Custom field value.
 */
function pogi_meta_box_text( $args = array(), $value = false ) { 
	$size = empty( $args['options']['size'] ) ? 'size="30"' : 'size="' . $args['options']['size'] . '"';
	$tabindex = empty( $args['options']['tabindex'] ) ? '' : 'tabindex="' . $args['options']['tabindex'] . '"';?>
	<p>
		<label for="<?php echo $args['id']; ?>"><?php echo $args['title']; ?></label>
		<br />
		<input type="text" name="<?php echo $args['id']; ?>" id="<?php echo $args['id']; ?>" value="<?php echo esc_attr( $value ); ?>" <?php echo $size; ?> />
		<?php if ( !empty( $args['description'] ) ) echo '<br /><span class="howto">' . $args['description'] . '</span>'; ?>
	</p>
	<?php
}

/**
 * Outputs a select box with the given arguments for use with the post meta box.
 *
 * @since 0.7.0
 * @param array $args
 * @param string|bool $value Custom field value.
 */
function pogi_meta_box_select( $args = array(), $value = false ) { ?>
	<p>
		<label for="<?php echo $args['id']; ?>"><?php echo $args['title']; ?></label>
		<br />
		<select name="<?php echo $args['id']; ?>" id="<?php echo $args['id']; ?>">
			<option value="">--</option>
			<?php if( !empty($args['options']) ){ ?>
				<?php foreach ( $args['options'] as $option => $val ) { ?>
					
					<option value="<?php echo esc_attr( $option ); ?>" 
						<?php 
							//check if $value is empty and if standard value is set
							if( ($value == '') && isset( $args['std'] ) ){
								selected( esc_attr( $args['std'] ), esc_attr( $option ) );
							} else {
								selected( esc_attr( $value ), esc_attr( $option ) ); 
							}
						?>><?php echo ( !empty( $args['use_key_and_value'] ) ? $option : $val ); ?></option>
					
				<?php } ?>
			<?php } ?>
		</select>
		<?php if ( !empty( $args['description'] ) ) echo '<br /><span class="howto">' . $args['description'] . '</span>'; ?>
	</p>
	<?php
}

/**
 * Outputs a textarea with the given arguments for use with the post meta box.
 *
 * @since 0.7.0
 * @param array $args
 * @param string|bool $value Custom field value.
 */
function pogi_meta_box_textarea( $args = array(), $value = false ) {  
	$cols = empty( $args['options']['cols'] ) ? '' : 'cols="' . $args['options']['cols'] . '"'; 
	$rows = empty( $args['options']['rows'] ) ? '' : 'rows="' . $args['options']['rows'] . '"'; 
	$tabindex = empty( $args['options']['tabindex'] ) ? '' : 'tabindex="' . $args['options']['tabindex'] . '"'; ?>
	
	<p>
		<label for="<?php echo $args['id']; ?>"><?php echo $args['title']; ?></label>
		<br />
		<textarea name="<?php echo $args['id']; ?>" id="<?php echo $args['id']; ?>" <?php echo $cols; ?> <?php echo $rows; ?> <?php echo $tabindex; ?> ><?php echo esc_html( $value ); ?></textarea>
		<?php if ( !empty( $args['description'] ) ) echo '<br /><span class="howto">' . $args['description'] . '</span>'; ?>
	</p>
	<?php
}

/**
 * Outputs radio inputs with the given arguments for use with the post meta box.
 *
 * @since 0.8.0
 * @param array $args
 * @param string|bool $value Custom field value.
 */
function pogi_meta_box_radio( $args = array(), $value = false ) { ?>
	<p>
		<?php echo $args['title']; ?>
		<?php foreach ( $args['options'] as $option => $val ) { ?>
			<br />
			<input type="radio" id="<?php echo $args['id']; ?>" name="<?php echo $args['id']; ?>" value="<?php echo esc_attr( $option ); ?> <?php checked( esc_attr( $value ), esc_attr( $option ) ); ?> />
		<?php } ?>
		<?php if ( !empty( $args['description'] ) ) echo '<br /><span class="howto">' . $args['description'] . "</span>"; ?>
	</p>
	<?php
}

/**
 * Outputs a checkbox for use with the post meta box
 *
 * @param array $args
 * @param string|bool $value Custom field value
 */
function pogi_meta_box_checkbox( $args = array(), $value = false ){ 
?>
	<p>
		<label for="<?php echo $args['id']; ?>" ><?php echo $args['title']; ?></label>
		<br />
		<input type="checkbox" id="<?php echo $args['id']; ?>" name="<?php echo $args['id']; ?>" value="checked" <?php checked( 'checked', $value ); ?> />
		<?php if( !empty( $args['description']) ) : ?><label for="<?php echo $args['id']; ?>" style="display: inline;" class="howto"><?php echo $args['description']; ?></label><?php endif; ?>
	</p>

<?php
}

/**
 * Outputs a header element commonly used for creating option subheadings
 *
 * @param array $args
 * @param string|bool $value Not needed. Only here for conformance with other Print Field functions.
 */
function pogi_meta_box_header( $args = array(), $value = false ){ ?>
	
	<h3 id="<?php echo $args['id']; ?>" class="pogi-header" <?php if( !empty( $args['style'] ) ) : ?> style="<?php echo $args['style']; ?>" <?php endif; ?> ><?php echo $args['title']; ?></h3>
	
	<?php
}

/**
 * Outputs an opening div used for grouping option settings
 *
 * @param array $args
 * @param string|bool $value Not needed. Only here for conformance with other Print Field functions.
 */
function pogi_meta_box_groupopen( $args = array(), $value = false ){  ?>
	
	<div id="<?php echo $args['id'];?>" class="pogi-subgroup" <?php if( !empty( $args['style'] ) ) : ?> style="<?php echo $args['style']; ?>" <?php endif; ?> >
	
	<?php
}

/**
 * Outputs a closing div used for grouping option settings
 *
 * @param array $args Not needed. Only here for conformance with other Print Field Functions.
 * @param string|bool $value Not needed. Only here for conformance with other Print Field functions.
 */
function pogi_meta_box_groupclose( $args = array(), $value = false ){ ?>
	
	</div>
	
	<?php
}

/**
 * Wrapper function for pogi_meta_box_{field-type}. Prints out the fields.
 *
 * @param array $fieldgroup a 1D array of arrays with each array containing the setting for each form field
 * @param int $post_id (optional) defaults to current Post object id
 * @return none - fields are echoed directly to the standard output
 */
function pogi_print_fields( $fieldgroup = array(), $post_id = '' ){
	
	global $post;
	
	if( empty( $post_id )){
		$post_id = $post->ID;
	} else {
		$post_id = intval( $post_id ); //sanitize as int
	}

	if( empty( $fieldgroup ) ){
		echo 'Field group empty.';
	}
	
	foreach ( $fieldgroup as $option ) {
		if ( function_exists( "pogi_meta_box_{$option['type']}" ) ){
			$value = get_post_meta( $post_id, $option['id'], true );
			call_user_func( "pogi_meta_box_{$option['type']}", $option, $value );
		}
	}
}

/**
 * This function loops through the passed associative array and saves each as individual post meta
 * 
 * @param array $meta_array
 * @return none
 * @uses add_post_meta(), update_post_meta(), delete_post_meta()
 */
function pogi_save_meta_array( $meta_array = array(), $post_id = '' ){
	
	global $post;
	
	if( empty( $post_id )){
		$post_id = $post->ID;
	} else {
		$post_id = intval( $post_id ); //sanitize as int
	}
	
	foreach ( $meta_array as $meta ) {

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta['id'], true );

		/* Get the meta value the user input. */
		$new_meta_value = stripslashes( $_POST[ $meta['id'] ] );

		
		/* If a new meta value was added and there was no previous value, add it. */
		if ( $new_meta_value && '' == $meta_value )
			add_post_meta( $post_id, $meta['id'], $new_meta_value, true );

		/* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value && $new_meta_value != $meta_value )
			update_post_meta( $post_id, $meta['id'], $new_meta_value );

		/* If there is no new meta value but an old value exists, delete it. */
		elseif ( '' == $new_meta_value && $meta_value )
			delete_post_meta( $post_id, $meta['id'], $meta_value );
	}
}
?>
