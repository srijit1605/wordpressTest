<?php
/*
Plugin Name: Auto Blogger
Plugin URI: https://srijit.vercel.app
Description: Automatically fetches content from external sources and posts it to your blog.
Version: 3.1
Author: Srijit Ghosh
Author URI: https://srijit.vercel.app
License: GPL2
*/

// Register the settings page and API fetcher
require_once plugin_dir_path( __FILE__ ) . 'admin/settings-page.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/api-fetcher.php';

// Add the settings page to the admin menu
add_action( 'admin_menu', 'auto_blogger_add_admin_menu' );
function auto_blogger_add_admin_menu() {
    add_options_page( 'Auto Blogger', 'Auto Blogger', 'manage_options', 'auto_blogger', 'auto_blogger_options_page' );
}

// Register settings for RSS feed URLs
add_action( 'admin_init', 'auto_blogger_settings_init' );
function auto_blogger_settings_init() {
    register_setting( 'auto_blogger', 'auto_blogger_rss_feed_urls' );
}

// Plugin activation: Initialize options and schedule cron job
function auto_blogger_activate() {
    if ( false === get_option( 'auto_blogger_rss_feed_urls' ) ) {
        update_option( 'auto_blogger_rss_feed_urls', array() );
    }
    if ( ! wp_next_scheduled( 'auto_blogger_cron_job' ) ) {
        wp_schedule_event( time(), 'hourly', 'auto_blogger_cron_job' );
    }
}
register_activation_hook( __FILE__, 'auto_blogger_activate' );

// Plugin deactivation: Remove scheduled cron job
function auto_blogger_deactivate() {
    wp_clear_scheduled_hook( 'auto_blogger_cron_job' );
}
register_deactivation_hook( __FILE__, 'auto_blogger_deactivate' );

// Cron job to fetch RSS feeds
add_action( 'auto_blogger_cron_job', 'auto_blogger_handle_cron_fetch' );
?>
