<?php
/**
 * Pogidude Twitter Feed Widget
 */

class Pogidude_Twitter_Feed_Widget extends WP_Widget{

	/**
	 * Prefix for the widget.
	 * @since 0.1
	 */
	var $prefix;

	/**
	 * Textdomain for the widget.
	 * @since 0.1
	 */
	var $textdomain;
	
	function Pogidude_Twitter_Feed_Widget(){

		/* Check if Hybrid-core framework is active */
		if( class_exists( 'Hybrid' ) ){
			/* Set the widget prefix */
			$this->prefix = hybrid_get_prefix();
			
			/* Set the widget textdomain. */
			$this->textdomain = hybrid_get_parent_textdomain();
		} else {
			$this->prefix = 'pogidude';
			$this->textdomain = 'pogidude';
		}
		
		$widget_options = array(
						'classname' => 'pogidude-twitter-feed',
						'description' => esc_html__( 'Add your Twitter feed to your sidebar with this widget.', $this->textdomain )
					);
		
		$control_options = array(
						'height' => '300',
						'width' => '240',
						'id_base' => "{$this->prefix}-twitter-feed"
					);
		
		$this->WP_Widget( "{$this->prefix}-twitter-feed", esc_attr__( 'Twitter Stream', $this->textdomain ), $widget_options, $control_options );
		
	}
	
	function form( $instance ){
		//setup default values
		$defaults = array( 'limit' => 3, 'show_bird' => 'checked' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = esc_attr($instance['title']);
		$limit = esc_attr($instance['limit']);
		$username = esc_attr($instance['username']);
		$show_bird = $instance['show_bird'];
		$textdomain = $this->textdomain;
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):', $textdomain); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username:', $textdomain); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('username'); ?>"  value="<?php echo $username; ?>" class="widefat" id="<?php echo $this->get_field_id('username'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit:', $textdomain); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('limit'); ?>"  value="<?php echo $limit; ?>" class="" size="3" id="<?php echo $this->get_field_id('limit'); ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('show_bird'); ?>" name="<?php echo $this->get_field_name('show_bird'); ?>" <?php checked( 'checked', $show_bird ); ?> value="checked" />
			<label for="<?php echo $this->get_field_id('show_bird'); ?>">Show Twitter Icon</label>
		</p>
		
		<?php
	}
	
	function update( $new_instance, $old_instance ){
		$new_instance['show_bird'] = ( $new_instance['show_bird'] == 'checked' ) ? $new_instance['show_bird'] : '';
		return $new_instance;
	}
	
	function widget( $args, $instance){
		extract( $args );
		$title = $instance['title'];
		$limit = $instance['limit'];
		$username = $instance['username'];
		$unique_id = $args['widget_id'];
		$show_bird = $instance['show_bird'];
		$textdomain = $this->textdomain;
		?>
		<?php echo $before_widget; ?>
		<?php if ($title){ echo $before_title . $title . $after_title;} ?>
		
		<?php $twitter_args = array(
							'username' => $username,
							'num' => $limit,
							'img' => 'no',
							'liclass' => 'tweet',
							'linklove' => 'no',
							'followlink' => 'no',
							'linktotweet' => 'yes'
						);
		?>
		
		<?php if( $show_bird == 'checked' ) : ?>
		<img class="twitter-icon" src="<?php echo get_template_directory_uri(); ?>/images/twitter-bird.png" />
		<?php endif; ?>
		
		<div id="twitter_feed_<?php echo $unique_id; ?>" class="twitter-feed-wrap">
		
			<?php echo $this->pogidude_twitterfeed( $twitter_args ); ?>

			<p class="follow-author"><?php _e('Follow', $textdomain ); ?> <a href="http://twitter.com/<?php echo $username; ?>"><strong>@<?php echo $username; ?></strong></a> <?php _e('on Twitter', $textdomain ); ?></p>
			
		</div><!-- #twitter_feed_<?php echo $unique_id; ?> -->
		<div class="clear"></div>
		<?php echo $after_widget; ?>
		
	<?php
	}
	
	/**
	 * Adapted from Alex Moss's Twitter Feed Plugin
	 *
	 * @link http://pleer.co.uk/wordpress/plugins/wp-twitter-feed/
	 * @param array $atts
	 * @return string twitter feed html or error string
	 */
	function pogidude_twitterfeed($atts) {
		$default = array(
			"username" => 'rmicua',
			"mode" => 'feed',
			"other" => 'yes',
			"num" => '3',
			"img" => 'yes',
			"imgclass" => '',
			"auth" => 'no',
			"encoding" => '',
			"term" => 'twitter',
			"hashtag" => 'WordPress',
			"followlink" => 'yes',
			"searchlink" => 'yes',
			"anchor" => '',
			"userlinks" => 'yes',
			"hashlinks" => 'yes',
			"timeline" => 'yes',
			"smalltime" => 'no',
			"smalltimeclass" => '',
			"timeclass" => 'tweet-date',
			"conditional" => 'yes',
			"phptime" => 'j F Y \a\t h:ia',
			"linktotweet" => 'yes',
			"divid" => '',
			"ulclass" => '',
			"liclass" => '',
			"linklove" => 'no',
		);
		$atts = wp_parse_args( $atts, $default );
		
		extract( $atts, EXTR_SKIP );
		
		//MODES
	
		if ($mode == "fav") { $twitter_rss = "http://twitter.com/favorites/".$username.".atom?rpp=".$num; }
		if ($mode == "feed") {
			if ($other == "yes") {
				$twitter_rss = "http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=".$username."&count=".$num;
				$img="no";
			} else {
				$twitter_rss = "http://search.twitter.com/search.rss?q=from%3A".$username."&rpp=".$num;
			}
		}
		if ($mode == "mentions") { $twitter_rss = "http://search.twitter.com/search.rss?q=%40".$username."&rpp=".$num; }
		if ($mode == "retweets") { $twitter_rss = "http://search.twitter.com/search.rss?q=RT%20%40".$username."&rpp=".$num; }
		if ($mode == "public") { $twitter_rss = "http://search.twitter.com/search.rss?q=".$username."&rpp=".$num; }
		if ($mode == "hashtag") { $twitter_rss = "http://search.twitter.com/search.rss?q=%23".$hashtag."&rpp=".$num; }
		if ($mode == "search") { $twitter_rss = "http://search.twitter.com/search.rss?q=".$term."&rpp=".$num; }
	
	
		//SETUP FEED
		include_once(ABSPATH . WPINC . '/feed.php');
		$rss = fetch_feed($twitter_rss);
		
		//check if fetch_feed() returns an error
		if( $rss->errors ){
			foreach( $rss->errors as $errors ){
				foreach( $errors as $error ){
					echo $error . "\n";
				}
			}
			return false;
		}
		
		$maxitems = $rss->get_item_quantity($num);
		$rss_items = $rss->get_items(0, $maxitems);
		ob_start();
		$now = time();
		$page = get_bloginfo('url');
	
		//START OUTPUT
		if ($divid != "") {
			$divstart = "<div id=\"".$divid."\">\n";
			$divend = "</div>";
		}
	
		if ($ulclass != "") {
			$ulstart = "<ul class=\"".$ulclass."\">";
		} else {
			$ulstart = "<ul>";
		}
	
		//POPULATE TWEET
		foreach ( $rss_items as $item ) {
			if ($mode == "fav") {
				$tweet = $item->get_description();
			} else {
				$tweet = $item->get_title();
			}
			if ($encoding == "fix") {$tweet = htmlentities($tweet);}
			if ($page != "") {if (!strpos($tweet, $page) === false) {continue;}}
			$when = ($now - strtotime($item->get_date()));
			$posted = "";
			if ($timeline != "no") {
				$when = ($now - strtotime($item->get_date()));
				$posted = "";
				if ($conditional == "yes") {
					if ($when < 60) {
						$posted = $when . " seconds ago";
					}
					if (($posted == "") & ($when < 3600)) {
						$posted = "about " . (floor($when / 60)) . " minutes ago";
					}
					if (($posted == "") & ($when < 7200)) {
						$posted = "about 1 hour ago";
					}
					if (($posted == "") & ($when < 86400)) {
						$posted = "about " . (floor($when / 3600)) . " hours ago";
					}
					if (($posted == "") & ($when < 172800)) {
						$posted = "about 1 day ago";
					}
					if ($posted == "") {
						$posted = (floor($when / 86400)) . " days ago";
					}
				} else {
					$date = date($phptime, strtotime($item->get_date()));
					$posted = $date;
				}
			$entry = $entry."\n<br />".$pubtext.$posted;
			}
				if ($anchor == "") {
					$tweet = preg_replace("/(http:\/\/)(.*?)\/([\w\.\/\&\=\?\-\,\:\;\#\_\~\%\+]*)/", "<a href=\"\\0\" rel=\"external nofollow\">\\0</a>", $tweet);
				} else {
					$tweet = preg_replace("/(http:\/\/)(.*?)\/([\w\.\/\&\=\?\-\,\:\;\#\_\~\%\+]*)/", "<a href=\"\\0\" rel=\"external nofollow\">".$anchor."</a>", $tweet);
				}
			if ($mode != "fav") {
				//SETUP SPECIAL ATTRIBUTES
				$author_tag = $item->get_item_tags('','author');
				$author = $author_tag[0]['data'];
				$author = substr($author, 0, stripos($author, "@") );
				if ($other != "yes"){$tweet = "@".$author.": ".$tweet;}
				if ($img == "yes"){
					$avatar_tag = $item->get_item_tags('http://base.google.com/ns/1.0','image_link');
					$avatar = $avatar_tag[0]['data'];
					if ($imgclass == "") {
						$preimgclass = "style=\"";
						$imgclass = "float: left;";
					} else {
						$preimgclass = "class=\"";
					}
					$avatar = "<img src=\"".$avatar."\" height=\"48\" width=\"48\" alt=\"".$author."\" title=\"".$author."\" ".$preimgclass.$imgclass."\">";
					if ( $userlinks == "yes" ) {
						$avatar = "<div style=\"float: left; margin: 0px 10px 10px 0px;\"><a href=\"http://twitter.com/".$author."\" rel=\"external nofollow\">".$avatar."</a></div>";
					}
				}
			} else {
				$tweet = "@".$tweet;
			}
			if ($auth == "no") {
				if ($other != "yes"){
					$tweet = preg_replace("(@([a-zA-Z0-9\_]+))", "", $tweet, 1);
					$tweet = substr($tweet, 2);
				} else {
					$tweet = preg_replace("(([a-zA-Z0-9\_]+):)", "", $tweet, 1);
				}
			}
			if ( $userlinks == "yes" ) {
				$tweet = preg_replace("(@([a-zA-Z0-9\_]+))", "<a href=\"http://twitter.com/\\1\" rel=\"external nofollow\">\\0</a>", $tweet);
			}
			if ( $hashlinks == "yes" ) {
				$tweet = preg_replace("(#([a-zA-Z0-9\_]+))", "<a href=\"http://twitter.com/search?q=%23\\1\" rel=\"external nofollow\">\\0</a>", $tweet);
			}
			
			/* Surround tweet with paragraph (p) tags */
			$tweet = '<p>' . $tweet . '</p>';
			
			if ($timeline == "yes") {
			if ($linktotweet == "yes") {
					if ($smalltime == "yes") {
						if ($smalltimeclass == "") {
							$presmalltimeclass = "style=\"";
							$smalltimeclass = "font-size: 85%;";
						} else {
							$presmalltimeclass = "class=\"";
						}
						$posted = "<font ".$presmalltimeclass.$smalltimeclass."\">".$posted."</font>";
						$smalltimeclass='';
					}
					$tweet = $tweet."\n<span class=\"" . $timeclass . "\"><a href=\"".$item->get_permalink()."\" rel=\"external nofollow\">".$posted."</a></span>";
				} else {
					$tweet = $tweet. '<span class="' . $timeclass . '">' . $posted . "</span>";
				}
			}
			if ($liclass != ""){
				$entry = "\n<li class=\"".$liclass."\">".$avatar.$tweet."</li>";
			} else {
				$entry = "\n<li style=\"display: inline-block; list-style: none; border-bottom: 1px #ccc dotted; margin-bottom: 5px; padding-bottom: 5px;\">".$avatar.$tweet."</li>";
			}
			$wholetweet = $wholetweet."".$entry;
			$imgclass='';
		}
	
	
		ob_end_flush();
		if ($followlink == "yes"){
			if ($mode == "feed" || $mode == "mentions" || $mode == "retweets" || $mode == "public") {
				$linktofeed = ("<a href=\"http://twitter.com/".$username."\" rel=\"external nofollow\">follow ".$username." on twitter</a><br />\n");
			}
			if ($mode == "fav") {
				$linktofeed = ("<a href=\"http://twitter.com/".$username."/favorites\" rel=\"external nofollow\">view all favourites for ".$username."</a><br />\n");
			}
			if ($mode == "search") {
				$linktofeed = ("<a href=\"http://twitter.com/search?q=".$term."\" rel=\"external nofollow\">view search results for \"".$term."\" on twitter</a><br />\n");
			}
			if ($mode == "hashtag") {
				$linktofeed = ("<a href=\"http://twitter.com/search?q=%23".$hashtag."\" rel=\"external nofollow\">view search results for \"#".$hashtag."\" on twitter</a><br />\n");
			}
		}
		if ($linklove != "no"){ $pleer = "\nPowered by <a href=\"http://pleer.co.uk/wordpress/plugins/wp-twitter-feed/\">Twitter Feed</a><br />\n"; }
		$whole = "\n<!-- WordPress Twitter Feed Plugin: http://pleer.co.uk/wordpress/plugins/wp-twitter-feed/ -->\n".$divstart.$ulstart.$wholetweet."\n</ul>\n".$linktofeed.$pleer.$divend."\n";
		return $whole;
	}
}
register_widget('Pogidude_Twitter_Feed_Widget');
