<?php
// Fetch and process an individual RSS feed
function auto_blogger_fetch_single_feed( $feed ) {
    $rss_feed_url = $feed['url'];
    $category = $feed['category'];
    $post_format = $feed['post_format'];
    $use_first_image = $feed['use_first_image'];

    $response = wp_safe_remote_get( $rss_feed_url );
    if ( is_wp_error( $response ) ) {
        error_log( 'Auto Blogger: Failed to fetch RSS feed: ' . $response->get_error_message() );
        return;
    }

    $body = wp_remote_retrieve_body( $response );
    $rss = simplexml_load_string( $body );

    if ( ! $rss || ! isset( $rss->channel->item ) ) {
        error_log( 'Auto Blogger: Invalid RSS feed format: ' . $rss_feed_url );
        return;
    }

    foreach ( $rss->channel->item as $item ) {
        $post_title = (string) $item->title;
        $post_content = (string) $item->description;
        $post_date = (string) $item->pubDate;

        // Extract first image if required
        $first_image_url = null;
        if ( $use_first_image ) {
            preg_match( '/<img[^>]+src="([^">]+)"/i', $post_content, $matches );
            if ( ! empty( $matches[1] ) ) {
                $first_image_url = $matches[1];
                $image_response = wp_safe_remote_get( $first_image_url );
                if ( is_wp_error( $image_response ) || wp_remote_retrieve_response_code( $image_response ) != 200 ) {
                    $first_image_url = null;
                }
            }
        }

        $new_post = array(
            'post_title'    => wp_strip_all_tags( $post_title ),
            'post_content'  => $post_content,
            'post_status'   => 'publish',
            'post_date'     => date( 'Y-m-d H:i:s', strtotime( $post_date ) ),
            'post_category' => array( $category ),
            'post_format'   => $post_format,
        );

        // Insert post
        $post_id = wp_insert_post( $new_post );

        // Set featured image if available
        if ( $post_id && $first_image_url ) {
            $image_id = auto_blogger_set_featured_image( $first_image_url, $post_id );
            if ( $image_id ) {
                set_post_thumbnail( $post_id, $image_id );
            }
        }
    }
}

// Helper function to set featured image
function auto_blogger_set_featured_image( $image_url, $post_id ) {
    $tmp = download_url( $image_url );
    if ( is_wp_error( $tmp ) ) {
        return false;
    }

    $file_array = array(
        'name' => basename( $image_url ),
        'tmp_name' => $tmp,
    );

    $attachment_id = media_handle_sideload( $file_array, $post_id );

    if ( is_wp_error( $attachment_id ) ) {
        @unlink( $file_array['tmp_name'] );
        return false;
    }

    return $attachment_id;
}
?>
