<?php
/**
 * Theme Functions 
 */

/**
 * Get Comment Count
 * 
 * Use within the loop to return the proper comment string. Use %1$s to output the number of comments. use %2$s to output the post title.
 *
 * @param $zero string Used when there are 0 comments
 * @param $one string used when there is exactly 1 comment
 * @param $many string used when there is more than 1 comment
 * @return string
 */
function pogidude_get_comment_count( $zero = 'Leave a comment', $one = '1 comment', $many = '%1$s Comments' ){
	global $post;
	$post_title = $post->post_title;
	$comment_count = get_comments_number();
	$comment_count = pogidude_get_tweet_count( get_permalink() );

	if( $comment_count == 0 ){ //0 comment
		$comment_text = sprintf( $zero, $comment_count, $post_title );
	} elseif ( $comment_count == 1 ){ //1 comment
		$comment_text = sprintf( $one, $comment_count, $post_title );
	} else { //more than 1 comment
		$comment_text = sprintf( $many, $comment_count, $post_title );
	}
	
	return $comment_text;
}

/**
 * Get Tweet Count
 *
 * @param string $url - url to get tweet count on.
 * @return int - return 0 on failure, number of tweets on success
 */
function pogidude_get_tweet_count( $url = ''){
	if( empty( $url ) ){
		$url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	}
	
	$count = 0;
	
	//check count in cache
	$meta = get_post_meta( $post_id, '_tweet_count', true );
	if ( isset( $meta['timeout'], $meta['count'] ) && time() < $meta['timeout'] ){
		return $meta['count'];
	}
	
	$data = wp_remote_get( 'http://urls.api.twitter.com/1/urls/count.json?url=' . urlencode( $url ) );
	if ( ! is_wp_error( $data ) ) {
		$response = json_decode( $data['body'], true );
		if ( $response['count'] ) $count = $response['count'];
	} else {
		return '*';
	}
	
	$meta = array(
		'count' => $count,
		'timeout' => time() + 3600
	);
	update_post_meta( $post_id, '_tweet_count', $meta );

	return $count;
}
