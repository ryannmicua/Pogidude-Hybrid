<?php
/* This file contains Mobility 21's Theme Settings */

//get the theme prefix
$prefix = hybrid_get_prefix();
	
add_action( 'admin_menu', 'mobility21_theme_admin_setup' );


function mobility21_theme_admin_setup(){

	//get the theme prefix
	$prefix = hybrid_get_prefix();
	
	/* Create a settings meta box only on the theme settings page. */
	add_action( "load-appearance_page_theme-settings", 'mobility21_theme_settings_meta_boxes' );

	/* Add a filter to validate/sanitize your settings. */
	add_filter( "sanitize_option_{$prefix}_theme_settings", 'mobility21_theme_validate_settings' );
	
	//allows us to use media library with thickbox
	add_thickbox(); // load thickbox
	wp_enqueue_script('media-upload'); //displays media library in modal window. requires thickbox
}

function mobility21_theme_settings_meta_boxes(){
	global $hybrid; 
	
	//echo $hybrid->settings_page; this will return "appearance_page_theme-settings"
	
	/* Metabox for top right corner area */
	/*
	add_meta_box(
		"mobility21-topright-metabox",
		"Masthead Corner Area Settings",
		"mobility21_topright_metabox",
		$hybrid->settings_page,
		"normal",
		"high"
	);
	*/
	/* Metabox for slider/feature area*/
	add_meta_box(
		"mobility21-feature-metabox",
		"Frontpage Slider/Feature Area Settings",
		"mobility21_feature_metabox",
		$hybrid->settings_page,
		"normal",
		"high"
	);

	/* Metabox for subfeature area*/
	add_meta_box(
		"mobility21-subfeature-metabox",
		"Frontpage Subfeature Area Settings",
		"mobility21_subfeature_metabox",
		$hybrid->settings_page,
		"normal",
		"high"
	);
	
	/* Metabox for subfeature columns area*/
	add_meta_box(
		"mobility21-subfeaturecolumns-metabox",
		"Frontpage Subfeature Columns Settings",
		"mobility21_subfeaturecolumns_metabox",
		$hybrid->settings_page,
		"normal",
		"high"
	);
	
	/* Metabox for subsidiary columns area*/
	add_meta_box(
		"mobility21-subsidiary-metabox",
		"Frontpage Subsidiary Columns Settings",
		"mobility21_subsidiary_metabox",
		$hybrid->settings_page,
		"normal",
		"high"
	);
	
	/* Metabox for bottom feature area*/
	add_meta_box(
		"mobility21-bottomfeature-metabox",
		"Frontpage Bottom Feature Area Settings",
		"mobility21_bottomfeature_metabox",
		$hybrid->settings_page,
		"normal",
		"high"
	);
	
	/* Metabox for social media settings*/
	add_meta_box(
		"mobility21-socialmedia-metabox",
		"Social Media Settings",
		"mobility21_socialmedia_metabox",
		$hybrid->settings_page,
		"normal",
		"high"
	);
	
	/* Metabox for social media settings*/
	add_meta_box(
		"mobility21-popup-metabox",
		"Popup Settings",
		"mobility21_popup_metabox",
		$hybrid->settings_page,
		"normal",
		"high"
	);
}

function mobility21_topright_metabox(){ ?>
	
	<p>This is area refers to the top right corner of the masthead/header. This area shows an optin form by default.</p>
	<table class="form-table">
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'widgetize_topright' ); ?>"><?php _e( 'Widgetize This Area:', hybrid_get_textdomain() ); ?></label>
			</th>
			<td>
				<input type="checkbox" id="<?php echo hybrid_settings_field_id('widgetize_topright'); ?>" name="<?php echo hybrid_settings_field_name('widgetize_topright'); ?>" value="widgetize" <?php checked('widgetize',hybrid_get_setting('widgetize_topright')); ?>  />
			</td>
			<td>
				<p>Checking this option replaces this section with a widgetized area. Go to the <a href="<?php bloginfo('url'); ?>/wp-admin/widgets.php" >widgets page</a> to add widgets.</p>
			</td>
		</tr>
	</table>
	
	<?php  mobility21_submit_button(); ?>
	
<?php
}

function mobility21_feature_metabox(){ ?>

	<p>This is the featured section on the frontpage. This area shows a content slider by default.</p>
	<p>You can add and edit the slider content by adding widgets to this area from the <a href="<?php bloginfo('url'); ?>/wp-admin/widgets.php" >widgets page</a>. </p>
	<table class="form-table">
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'disable_slider' ); ?>"><?php _e( 'Disable Frontpage Slider:', hybrid_get_textdomain() ); ?></label>
			</th>
			<td>
				<input type="checkbox" id="<?php echo hybrid_settings_field_id('disable_slider'); ?>" name="<?php echo hybrid_settings_field_name('disable_slider'); ?>" value="disable" <?php checked('disable',hybrid_get_setting('disable_slider')); ?> />
			</td>
		</tr>
	</table>
	
	<?php  mobility21_submit_button(); ?>
	
<?php
}

function mobility21_subfeature_metabox(){ ?>

	<p>The subfeature section is right below the feature section. This area shows an image strip of icons by default.</p>

	<table class="form-table">
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'disable_subfeature' ); ?>"><?php _e( 'Disable Subfeature Area:', hybrid_get_textdomain() ); ?></label>
			</th>
			<td>
				<input type="checkbox" id="<?php echo hybrid_settings_field_id('disable_subfeature'); ?>" name="<?php echo hybrid_settings_field_name('disable_subfeature'); ?>" value="disable" <?php checked('disable',hybrid_get_setting('disable_subfeature')); ?> />
			</td>
			<td>
				<p>Checking this setting stops this area from displaying.</p>
			</td>
		</tr>
		
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( "widgetize_subfeature" ); ?>"><?php _e( 'Widgetize Subfeature Area:', hybrid_get_textdomain() ); ?></label>
			</th>
			<td>
				<input type="checkbox" id="<?php echo hybrid_settings_field_id("widgetize_subfeature"); ?>" name="<?php echo hybrid_settings_field_name("widgetize_subfeature"); ?>" value="widgetize" <?php checked('widgetize',hybrid_get_setting("widgetize_subfeature")); ?> />
			</td>
			<td>
				<p>Checking this option activates the Subfeature Widget Area. You can then add widgets by going to the <a href="<?php bloginfo('url'); ?>/wp-admin/widgets.php" >widgets page</a>.</p>
			</td>
		</tr>
			
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'subfeature_content' ); ?>"><?php _e( 'Subfeature Content:', hybrid_get_textdomain() ); ?></label>
			</th>
			<td colspan="2">
				<textarea id="<?php echo hybrid_settings_field_id('subfeature_content'); ?>" name="<?php echo hybrid_settings_field_name('subfeature_content'); ?>" cols="60" rows="5" style="width=98%;"><?php echo wp_htmledit_pre( stripslashes( hybrid_get_setting( 'subfeature_content' ) ) ); ?></textarea>
				<p><strong>HTML Code accepted.</strong> Leave this field empty to show default content. <em>Setting this field has no effect when the column is widgetized by checking the checkbox above.</em> <a href="<?php echo admin_url('/media-upload.php?type=image&amp;TB_iframe=1' ); ?>" class="thickbox">Open media library</a></p>
			</td>
		</tr>
	</table>
	
	<?php  mobility21_submit_button(); ?>

<?php
}

function mobility21_subfeaturecolumns_metabox(){ ?>
	
	<!--p>The subfeature columns section is right below the subfeature section. This area shows three big Call-To-Action Buttons by default. For advanced users, it is possible to replace the default content by widgetizing the columns. <em>(See advanced settings for individual columns below)</em></p-->
	
	<p>The subfeature columns section is right below the subfeature section. This area shows three big Call-To-Action Buttons by default. You can replace the buttons with custom image buttons. <strong>Download a PSD Template for your custom buttons by clicking <a href="<?php bloginfo('template_url'); ?>/resources/button-image-template.psd" >this link</a></strong>.</p>
	
	<table class="form-table subfeature-columns">
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'disable_subfeature_columns' ); ?>"><?php _e( 'Disable Subfeature Column Area:', hybrid_get_textdomain() ); ?></label>
			</th>
			<td>
				<input type="checkbox" id="<?php echo hybrid_settings_field_id('disable_subfeature_columns'); ?>" name="<?php echo hybrid_settings_field_name('disable_subfeature_columns'); ?>" value="disable" <?php checked('disable',hybrid_get_setting('disable_subfeature_columns')); ?> />
			</td>
			<td>
				<p>Checking this setting will stop this area from displaying.</p>
			</td>
		</tr>
		

		<?php
			$col_array = array( 'Column 1', 'Column 2', 'Column 3' );
			foreach( $col_array as $key => $column ) : 
				$count = $key + 1;
		?>
		
			<tr>
				<th colspan="3">
					<h3>Subfeature <?php echo $column; ?></h3>
				</th>
			</tr>
			<!--tr>
				<th>
					<label for="<?php echo hybrid_settings_field_id( "widgetize_subfeature_column_{$count}" ); ?>"><?php _e( 'Widgetize This Column:', hybrid_get_textdomain() ); ?></label>
				</th>
				<td>
					<input type="checkbox" id="<?php echo hybrid_settings_field_id("widgetize_subfeature_column_{$count}"); ?>" name="<?php echo hybrid_settings_field_name("widgetize_subfeature_column_{$count}"); ?>" value="widgetize" <?php checked('widgetize',hybrid_get_setting("widgetize_subfeature_column_{$count}")); ?> />
				</td>
				<td>
					<p>Checking this option activates the widget area for this column, replacing its contents. You can then add widgets by going to the <a href="<?php bloginfo('url'); ?>/wp-admin/widgets.php" >widgets page</a>.</p>
				</td>
			</tr-->
			<tr class="subfeature-column-<?php echo $count; ?>-content">
				<th>
					<label for="<?php echo hybrid_settings_field_id( "subfeature_column_{$count}_button_link" ); ?>"><?php _e( 'Default Button Link:', hybrid_get_textdomain() ); ?></label>
				</th>
				<td>
					<textarea id="<?php echo hybrid_settings_field_id( "subfeature_column_{$count}_button_link" ); ?>" name="<?php echo hybrid_settings_field_name( "subfeature_column_{$count}_button_link" ); ?>" ><?php echo hybrid_get_setting("subfeature_column_{$count}_button_link" ); ?></textarea>
				</td>
				<td>
					<p>URL the button will link to when clicked. <br /><em>Setting this field has no effect when the column is widgetized by checking the checkbox above.</em></p>
				</td>
			</tr>
			
			<tr>
				<th>
					<label for="<?php echo hybrid_settings_field_id( "subfeature_column_{$count}_default_image" ); ?>"><?php _e( 'Default Image URL location:', hybrid_get_textdomain() ); ?></label>
				</th>
				<td>
					<textarea id="<?php echo hybrid_settings_field_id( "subfeature_column_{$count}_default_image" ); ?>" name="<?php echo hybrid_settings_field_name( "subfeature_column_{$count}_default_image" ); ?>" ><?php echo hybrid_get_setting("subfeature_column_{$count}_default_image" ); ?></textarea><br />
					<span class="description"></span>
				</td>
				<td>
					<p>URL location of the default image used for the button.<em>Setting this field has no effect when the column is widgetized by checking the checkbox above.</em>  <a href="<?php echo admin_url('/media-upload.php?type=image&amp;TB_iframe=1' ); ?>" class="thickbox">Open media library</a><br /><strong>For good results, image must be 286 pixels wide.</strong></p>
				</td>
			</tr>
			
			<tr>
				<th>
					<label for="<?php echo hybrid_settings_field_id( "subfeature_column_{$count}_hover_image" ); ?>"><?php _e( 'Hover Image URL location:', hybrid_get_textdomain() ); ?></label>
				</th>
				<td>
					<textarea id="<?php echo hybrid_settings_field_id( "subfeature_column_{$count}_hover_image" ); ?>" name="<?php echo hybrid_settings_field_name( "subfeature_column_{$count}_hover_image" ); ?>" ><?php echo hybrid_get_setting( "subfeature_column_{$count}_hover_image" ); ?></textarea>
				</td>
				<td>
					<p>URL location of the image used when a user hovers over the button. <br /><em>Setting this field has no effect when the column is widgetized by checking the checkbox above.</em>  <a href="<?php echo admin_url('/media-upload.php?type=image&amp;TB_iframe=1' ); ?>" class="thickbox">Open media library</a><br /><strong>For good results, image must be 286 pixels wide.</strong></p>
				</td>
			</tr>
			
			<tr><TD colspan="3"><?php  mobility21_submit_button(); ?></TD></tr>
		
		<?php endforeach; ?>
		
		</table>
	
<?php
}

function mobility21_subsidiary_metabox(){ ?>

	<p>The subsidiary columns area is displayed after the subfeatured columns section. By default, the columns show Events, News and Advertising from partner companies. It is also possible to replace the default content by widgetizing the columns. <br /><em>(See settings for individual columns below)</em></p>
	
	<table class="form-table subsidiary-columns">
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'disable_subsidiary_columns' ); ?>"><?php _e( 'Disable Subsidiary Columns:', hybrid_get_textdomain() ); ?></label>
			</th>
			<td>
				<input type="checkbox" id="<?php echo hybrid_settings_field_id('disable_subsidiary_columns'); ?>" name="<?php echo hybrid_settings_field_name('disable_subsidiary_columns'); ?>" value="disable" <?php checked('disable',hybrid_get_setting("disable_subsidiary_columns")); ?> />
			</td>
			<td>
				<p>Checking this setting stops this area from displaying.</p>
			</td>
		</tr>
		
		<?php
			$col_array = array( 'Column 1', 'Column 2', 'Column 3' );
			foreach( $col_array as $key => $column ) : 
				$count = $key + 1;
		?>
			
			<tr>
				<th colspan="3">
					<h3>Subsidiary <?php echo $column; ?></h3>
				</th>
			</tr>
			<tr>
				<th>
					<label for="<?php echo hybrid_settings_field_id( "widgetize_subsidiary_column_{$count}" ); ?>"><?php _e( 'Widgetize This Column:', hybrid_get_textdomain() ); ?></label>
				</th>
				<td>
					<input type="checkbox" id="<?php echo hybrid_settings_field_id("widgetize_subsidiary_column_{$count}"); ?>" name="<?php echo hybrid_settings_field_name("widgetize_subsidiary_column_{$count}"); ?>" value="widgetize" <?php checked('widgetize',hybrid_get_setting("widgetize_subsidiary_column_{$count}")); ?> />
				</td>
				<td>
					<p>Checking this option activates the widget area for this column, replacing its contents. You can then add widgets by going to the <a href="<?php bloginfo('url'); ?>/wp-admin/widgets.php" >widgets page</a>.</p>
				</td>
			</tr>
			<tr class="subsidiary-column-<?php echo $count; ?>-content">
				<th>
					<label for="<?php echo hybrid_settings_field_id( "subsidiary_column_{$count}_title" ); ?>"><?php _e( 'Column Title:', hybrid_get_textdomain() ); ?></label>
				</th>
				<td>
					<input type="text" id="<?php echo hybrid_settings_field_id( " subsidiary_column_{$count}_title" ); ?>" name="<?php echo hybrid_settings_field_name( "subsidiary_column_{$count}_title" ); ?>" value="<?php echo hybrid_get_setting( "subsidiary_column_{$count}_title" ); ?>" />
				</td>
				<td>
					<p>Title for this column. Leave this blank to show default title.</p>
				</td>
			</tr>
			<tr class="subsidiary-column-<?php echo $count; ?>-content">
				<th>
					<label for="<?php echo hybrid_settings_field_id( "subsidiary_column_{$count}_content" ); ?>"><?php _e( 'Column Content:', hybrid_get_textdomain() ); ?></label>
				</th>
				<td colspan="2">
					<textarea id="<?php echo hybrid_settings_field_id( "subsidiary_column_{$count}_content" ); ?>" name="<?php echo hybrid_settings_field_name( "subsidiary_column_{$count}_content" ); ?>" cols="60" rows="5" ><?php echo hybrid_get_setting("subsidiary_column_{$count}_content" ); ?></textarea>
					<p><strong>HTML Code accepted.</strong> Content for this column. Leave this blank to show default content.<br /> <em>Setting this field has no effect when the column is widgetized by checking the checkbox above.</em></p>
				</td>
			</tr>
			
			<tr><TD colspan="3"><?php  mobility21_submit_button(); ?></TD></tr>
			
		<?php endforeach; ?>

	</table>
	
	<?php  //mobility21_submit_button(); ?>
	
<?php
}

function mobility21_bottomfeature_metabox(){ ?>

	<p>The bottom feature area is displayed right before the footer. It is made up of two columns and by default, <strong>Column 1</strong> shows a Featured Video while <strong>Column 2</strong> shows the Twitter Feed and Social Media Buttons. It is also possible to replace the default content by widgetizing the columns. <br /><em>(See settings for individual columns below)</em></p>
	
	<table class="form-table bottom-feature">
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'disable_bottom_feature' ); ?>"><?php _e( 'Disable Bottom Feature Area:', hybrid_get_textdomain() ); ?></label>
			</th>
			<td>
				<input type="checkbox" id="<?php echo hybrid_settings_field_id('disable_bottom_feature'); ?>" name="<?php echo hybrid_settings_field_name('disable_bottom_feature'); ?>" value="disable" <?php checked('disable',hybrid_get_setting("disable_bottom_feature")); ?> />
			</td>
			<td>
				<p>Checking this setting stops this area from displaying.</p>
			</td>
		</tr>
		
		<?php
			$col_array = array( 'Column 1', 'Column 2' );
			foreach( $col_array as $key => $column ) : 
				$count = $key + 1;
		?>
			
			<tr>
				<th colspan="3">
					<h3>Bottom Feature <?php echo $column; ?></h3>
				</th>
			</tr>
			<tr>
				<th>
					<label for="<?php echo hybrid_settings_field_id( "widgetize_bottomfeature_column_{$count}" ); ?>"><?php _e( 'Widgetize This Column:', hybrid_get_textdomain() ); ?></label>
				</th>
				<td>
					<input type="checkbox" id="<?php echo hybrid_settings_field_id("widgetize_bottomfeature_column_{$count}"); ?>" name="<?php echo hybrid_settings_field_name("widgetize_bottomfeature_column_{$count}"); ?>" value="widgetize" <?php checked('widgetize',hybrid_get_setting("widgetize_bottomfeature_column_{$count}")); ?> />
				</td>
				<td>
					<p>Checking this option activates the widget area for this column, replacing its contents. You can then add widgets by going to the <a href="<?php bloginfo('url'); ?>/wp-admin/widgets.php" >widgets page</a>.</p>
				</td>
			</tr>
			<tr class="bottomfeature-column-<?php echo $count; ?>-content">
				<th>
					<label for="<?php echo hybrid_settings_field_id( "bottomfeature_column_{$count}_title" ); ?>"><?php _e( 'Column Title:', hybrid_get_textdomain() ); ?></label>
				</th>
				<td>
					<input type="text" id="<?php echo hybrid_settings_field_id( "bottomfeature_column_{$count}_title" ); ?>" name="<?php echo hybrid_settings_field_name( "bottomfeature_column_{$count}_title" ); ?>" value="<?php echo hybrid_get_setting( "bottomfeature_column_{$count}_title" ); ?>" />
				</td>
				<td>
					<p>Title for this column. Leave this blank to show default title.<br /> <em>Setting this field has no effect when the column is widgetized by checking the checkbox above.</em></p>
				</td>
			</tr>
			<tr class="bottomfeature-column-<?php echo $count; ?>-content">
				<th>
					<label for="<?php echo hybrid_settings_field_id( "bottomfeature_column_{$count}_content" ); ?>"><?php _e( 'Column Content:', hybrid_get_textdomain() ); ?></label>
				</th>
				<td colspan="2">
					<textarea id="<?php echo hybrid_settings_field_id( "bottomfeature_column_{$count}_content" ); ?>" name="<?php echo hybrid_settings_field_name( "bottomfeature_column_{$count}_content" ); ?>" cols="60" rows="5" ><?php echo hybrid_get_setting( "bottomfeature_column_{$count}_content" ); ?></textarea>
					<p><strong>HTML Code accepted.</strong> Content for this column. Leave this blank to show default content.<br /> <em>Setting this field has no effect when the column is widgetized by checking the checkbox above.</em></p>
				</td>
			</tr>
			
			<tr><TD colspan="3"><?php  mobility21_submit_button(); ?></TD></tr>
			
		<?php endforeach; ?>
	</table>
	
	<?php //mobility21_submit_button(); ?>

<?php
}

function mobility21_socialmedia_metabox(){ ?>
	<p>Settings here setup the social media buttons found at the bottom part of the Front Page and Single Pages.</p>
	<table class="form-table">
		
		<?php
			$social_media_array = array(
									'facebook' => array('label' => 'Facebook Account Name',
														'description' => '<em>Example: Mobility21</em>'
													),
									'twitter' => array( 'label' => 'Twitter Account ID',
														'description' => 'Enter your Twitter Account ID here. <em>Example: Mobility21</em>'
													),
									'youtube' => array( 'label' => 'Youtube User Account',
														'description' => 'Enter your Youtube User ID here. <em>Example: Mobility21Coalition</em>'
													),
									'email' => array( 'label' => 'Email Address',
														'description' => 'Mobility 21 email address for people who want to contact the organization.'
													),
									/*'connect_button' => array( 'label' => 'Connect with us Button',
																'description' => 'Provide a link URL that the browser will go to when  this button is clicked.'
													),*/
									'rss' => array( 'label' => 'Custom RSS Feed URL',
													'description' => 'Fill this in to provide a custom RSS Feed. Default feed URL will be used if this is blank.'
													)
									);
									
			foreach( $social_media_array as $key => $option) :
		?>
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( "socialmedia_{$key}" ); ?>"><?php echo $option['label']; ?></label>
			</th>
			<td>
				<input type="text" id="<?php echo hybrid_settings_field_id( "socialmedia_{$key}" ); ?>" name="<?php echo hybrid_settings_field_name( "socialmedia_{$key}" ); ?>" value="<?php echo hybrid_get_setting( "socialmedia_{$key}" ); ?>" />
			</td>
			<td>
				<p><?php echo $option['description']; ?></em></p>
			</td>
		</tr>
		
		<?php endforeach; ?>
		
	</table>
	
	<?php  mobility21_submit_button(); ?>

<?php
}

function mobility21_popup_metabox(){
?>
	<p>Settings for the popup when a visitor visits the site. This area shows an optin form by default.</p>
	<p>You can add and edit the slider content by adding widgets to this area from the <a href="<?php bloginfo('url'); ?>/wp-admin/widgets.php" >widgets page</a>. </p>
	<table class="form-table">
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'disable_popup' ); ?>"><?php _e( 'Check to disable the Popup:', hybrid_get_textdomain() ); ?></label>
			</th>
			<td>
				<input type="checkbox" id="<?php echo hybrid_settings_field_id('disable_popup'); ?>" name="<?php echo hybrid_settings_field_name('disable_popup'); ?>" value="disable" <?php checked('disable',hybrid_get_setting("disable_popup")); ?> />
			</td>
		</tr>
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'widgetize_popup' ); ?>"><?php _e( 'Check to widgetize the Popup', hybrid_get_textdomain() ); ?></label>
			</th>
			<td>
				<input type="checkbox" id="<?php echo hybrid_settings_field_id('widgetize_popup'); ?>" name="<?php echo hybrid_settings_field_name('widgetize_popup'); ?>" value="widgetize" <?php checked('widgetize',hybrid_get_setting("widgetize_popup")); ?> />
				<p>Checking this option allows you to add custom content to the popup by using widgets. You can add and edit the popup content by adding widgets to this area from the <a href="<?php bloginfo('url'); ?>/wp-admin/widgets.php" >widgets page</a>.</p>
			</td>
		</tr>
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'popup_delay' ); ?>"><?php _e( 'Popup Delay:', hybrid_get_textdomain() ); ?></label>
			</th>
			<td>
				<input type="text" id="<?php echo hybrid_settings_field_id('popup_delay'); ?>" name="<?php echo hybrid_settings_field_name('popup_delay'); ?>" value="<?php echo hybrid_get_setting( "popup_delay" ); ?>" size="6"/> <em>milliseconds</em>
				<p>Delay value is in <strong><em>milliseconds</em></strong>. Set how long to wait before showing the popup. <br /><em>Example value: 2000 (wait 2 seconds)</em></p>
			</td>
		</tr>
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'test_popup' ); ?>"><?php _e( 'Check to test the Popup:', hybrid_get_textdomain() ); ?></label>
			</th>
			<td>
				<input type="checkbox" id="<?php echo hybrid_settings_field_id('test_popup'); ?>" name="<?php echo hybrid_settings_field_name('test_popup'); ?>" value="test" <?php checked('test',hybrid_get_setting("test_popup")); ?> />
				<em>(For testing purposes) Checking this option will always turn the popup on everytime you reload the page.</em>
			</td>
		</tr>
	</table>
	
	<?php  mobility21_submit_button(); ?>
<?php
}

function mobility21_theme_validate_settings( $input ){
	return $input;
}

/**
 * Display a submit button. What else did you think it'd do?
 *
 * @return none
 */
function mobility21_submit_button(){ ?>
	<input class="button-primary" type="submit" value="Update Settings" name="Submit" />
<?php 
}

