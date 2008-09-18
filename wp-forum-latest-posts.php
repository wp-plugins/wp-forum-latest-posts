<?php
/*
Plugin Name: WP-Forum Latest Posts
Version: 0.5.1
Plugin URI: http://www.schloebe.de/wordpress/wp-forum-latest-posts-plugin/
Description: Lists the latest posts from your WP-Forum.
Author: Oliver Schl&ouml;be
Author URI: http://www.schloebe.de/
*/

define("WPFLP_VERSION", "0.5.1");

function WPFLatestPosts($args = '') {
    global $wpdb, $PHP_SELF, $wp_version;
    setlocale(LC_ALL,WPLANG);
	$standard = array (
		'forumid' => 0, 'number' => 5, 'title' => __('')
	);
	
	$r = wp_parse_args( $args, $standard );
	extract( $r, EXTR_SKIP );

    $blogurl = get_bloginfo('url');
    
    $latestposts = $wpdb->get_results("SELECT * FROM $wpdb->wp_forum_posts ORDER BY date DESC LIMIT ".$r['number']."");
    
    if( $r['title'] )
    	$ausgabe .= $r['title'];
	
    $ausgabe .= "<ul>\n";
    foreach ($latestposts as $posts) {
    	$getforum = $wpdb->get_row("SELECT forum_id FROM $wpdb->wp_forum_threads WHERE id = " . $posts->thread_id . "", OBJECT, 0);
    	$getlinktopost = "?page_id=" . $r['forumid'] . "&amp;forumaction=showposts&amp;forum=" . $getforum->forum_id . "&amp;thread=" . $posts->thread_id . "&amp;start=0&amp;forumpost=" . $posts->id;
    	$ausgabe .= '<li><a href="' . $blogurl . $getlinktopost . '">' . $posts->subject . '</a></li>' . "\n";
	}
    $ausgabe .= "</ul>\n";
    
	(function_exists('wp_forum')) ? $ausgabe : $ausgabe = __('WP-Forum ist nicht installiert!');
	
	echo $ausgabe;
}

?>