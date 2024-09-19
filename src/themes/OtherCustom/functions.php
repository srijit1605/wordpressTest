<?php
// Theme setup
function autoblogger_theme_setup() {
    // Adds default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'autoblogger_theme_setup');

function mytheme_register_menu() {
    register_nav_menu('primary-menu', __('Primary Menu'));
}
add_action('init', 'mytheme_register_menu');

