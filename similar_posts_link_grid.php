<?php
/*
Plugin Name: Similar Posts Link Grid
Description: Displays a list of similar posts to keep users engaged.
*/

// Include necessary files
include_once(plugin_dir_path(__FILE__) . 'includes/similarity.php');
include_once(plugin_dir_path(__FILE__) . 'includes/display.php');

// Shortcode to display similar posts
add_shortcode('engaging_similar_posts', 'display_similar_posts');

// Function to add similar posts after content (adjust as needed)
function add_similar_posts_after_content($content) {
    if (is_single()) { // Only on single post pages
        $content .= display_similar_posts(get_the_ID());
    }
    return $content;
}
add_filter('the_content', 'add_similar_posts_after_content');