<?php
/**
 * Get Youtube Feed
 *
 * @ref http://code.google.com/apis/youtube/2.0/developers_guide_protocol_video_feeds.html#User_Uploaded_Videos
 * @param string $user userid to retrieve videos from
 * @param array $args 
 * @return bool|obj false on failure, the xml object on success
 */
function pogidude_get_yt_feed_by_user( $user = '', $args = array() ){
	$defaults = array(
		'max-results' => 10,
		'orderby' => 'published',
		'v' => 2
	);
	$args = wp_parse_args( $args, $defaults );
	
	if( empty( $user ) ) return false;
	
	$feedUrl = "https://gdata.youtube.com/feeds/api/users/{$user}/uploads?max-results={$args['max-results']}&orderby={$args['orderby']}&v={$args['v']}";
	
	$xml = simplexml_load_file( $feedUrl );
	
	return $xml;
}

/**
 * Get Youtube Video Entries - returns an array of video entries with common properties
 *
 * @ref http://www.ibm.com/developerworks/xml/library/x-youtubeapi/#c1
 * @param obj $feed
 * @return array
 */
function pogidude_get_yt_entries( $feed ){

	$entries = array();

	//iterate over entries in the feed
	foreach( $feed->entry as $entry ){
		//get nodes in media: namespace for media info
		$media = $entry->children( 'http://search.yahoo.com/mrss/' );
		
		//get video title
		$vtitle = $media->group->title;
		
		//get video player URL
		$attrs = $media->group->player->attributes();
		$watch = $attrs['url'];
		
		//get <yt:videoid> node for video ID
		$vid = $media->children( 'http://gdata.youtube.com/schemas/2007' )->videoid;
		
		//Store info into $entries array
		$entries[ (string) $vid ] = array(
			'title' => (string) $vtitle,
			'url' => (string) $watch,
			'id' => (string) $vid
		);
	}
	
	return $entries;
}

/**
 * Display video using Iframe API
 *
 * @param string $vid unique video id
 * @param array $args settings for width and height
 */
function pogidude_yt_player( $vid, $args = array() ){

	$defaults = array(
		'width' => 480,
		'height' => 274,
		'id' => '',
		'class' => 'yt-player',
		'frameborder' => 0,
		'allowfullscreen' => true
	);
	$args = wp_parse_args( $args, $defaults );
	
	$width = intval( $args['width'] );
	$height = intval( $args['height'] );
	$fborder = intval( $args['frameborder'] );
	$allow_fs = ($args['allowfullscreen'] === true ) ? 'allowfullscreen' : '';
	$id = empty( $args['id'] ) ? '' : 'id="' . $args['id'] . '"';
	
	$iframe = '<iframe ' . $id . ' class="' . $args['class'] . '" width="' . $width . '" height="' . $height . '" frameborder="' . $fborder . '" ' . $allow_fs . ' src="http://www.youtube.com/embed/' . $vid . '"></iframe>';
	
	return $iframe;
}