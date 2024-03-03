<?php
/*
Plugin Name: Signature
Plugin URI: http://srijit.vercel.app
Description: This is a simple plugin, just to test a few things. 
Author: Srijit Ghosh
Version: 1
Author URI: http://srijit.vercel.app
*/


function follow_us($content) {

    // Only do this when a single post is displayed
    if ( is_single() ) { 
    
    // Message you want to display after the post
    // Add URLs to your own Twitter and Facebook profiles
    
    $content .= '<p class="follow-us">please follow me on <a href="https://twitter.com/nishadal/" title="Srijit on Twitter" target="_blank" rel="nofollow">Twitter</a> and <a href="https://www.instagram.com/shri_nityananda/" title="Srijit on Insta" target="_blank" rel="nofollow">Instagram</a>.</p>';
    
    }
    // Return the content
    return $content; 
    
    }
    // Hook our function to WordPress the_content filter
    add_filter('the_content', 'follow_us'); 
