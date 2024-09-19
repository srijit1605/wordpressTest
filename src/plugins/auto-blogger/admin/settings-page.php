<?php
function auto_blogger_options_page() {
    ?>
    <div class="wrap">
        <h1>Auto Blogger Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'auto_blogger' );
            do_settings_sections( 'auto_blogger' );

            // Add a new RSS feed with name, URL, refetch interval, category, and post format
            ?>
            <h3>Add New RSS Feed</h3>
            <input type="text" name="new_rss_feed_name" placeholder="Enter Feed Name" size="30">
            <input type="text" name="new_rss_feed_url" placeholder="Enter RSS Feed URL" size="50">
            <input type="number" name="new_rss_refetch_interval" placeholder="Refetch interval in hours" size="5" min="1">
            
            <!-- Select category -->
            <h4>Select Category for Fetched Posts</h4>
            <?php
            wp_dropdown_categories(array(
                'show_option_all'    => 'All Categories',
                'name'               => 'new_rss_feed_category',
                'class'              => 'rss-feed-category',
                'hide_empty'         => 0,
            ));
            ?>

            <!-- Select post format -->
            <h4>Select Post Format</h4>
            <select name="new_rss_feed_post_format">
                <option value="standard">Standard</option>
                <option value="aside">Aside</option>
                <option value="gallery">Gallery</option>
            </select>

            <!-- Option to use the first image as featured image -->
            <h4>Use First Image as Featured Image</h4>
            <input type="checkbox" name="new_rss_feed_use_first_image" value="1"> Yes

            <?php submit_button( 'Add Feed' ); ?>
        </form>

        <h2>RSS Feed URLs</h2>
        <table class="form-table">
            <thead>
                <tr>
                    <th>Feed Name</th>
                    <th>Feed URL</th>
                    <th>Fetch Interval (hours)</th>
                    <th>Category</th>
                    <th>Post Format</th>
                    <th>Use First Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $feed_urls = get_option( 'auto_blogger_rss_feed_urls', array() );

                if ( ! is_array( $feed_urls ) ) {
                    $feed_urls = array();
                }

                foreach ( $feed_urls as $key => $feed_url ) {
                    if ( is_array( $feed_url ) ) {
                        $feed_name = isset( $feed_url['name'] ) ? sanitize_text_field( $feed_url['name'] ) : 'Unnamed Feed';
                        $interval = isset( $feed_url['refetch_interval'] ) ? intval( $feed_url['refetch_interval'] ) : 1;
                        $category = isset( $feed_url['category'] ) ? intval( $feed_url['category'] ) : 0;
                        $post_format = isset( $feed_url['post_format'] ) ? $feed_url['post_format'] : 'standard';
                        $use_first_image = isset( $feed_url['use_first_image'] ) && $feed_url['use_first_image'] ? 'Yes' : 'No';
                        ?>
                        <tr>
                            <td><?php echo esc_html( $feed_name ); ?></td>
                            <td><?php echo esc_url( $feed_url['url'] ); ?></td>
                            <td><?php echo $interval; ?> hours</td>
                            <td><?php echo get_cat_name( $category ); ?></td>
                            <td><?php echo esc_html( $post_format ); ?></td>
                            <td><?php echo esc_html( $use_first_image ); ?></td>
                            <td>
                                <!-- Manually Fetch Button -->
                                <form method="post" action="" style="display:inline;">
                                    <input type="hidden" name="fetch_rss_feed" value="<?php echo esc_attr( $key ); ?>" />
                                    <input type="submit" name="manual_fetch" value="Fetch Now" class="button button-primary" />
                                </form>
                                <!-- Edit Feed Button -->
                                <form method="post" action="" style="display:inline;">
                                    <input type="hidden" name="edit_rss_feed" value="<?php echo esc_attr( $key ); ?>" />
                                    <input type="submit" name="edit_feed" value="Edit" class="button button-secondary" />
                                </form>
                                <!-- Remove Feed Button -->
                                <form method="post" action="" style="display:inline;">
                                    <input type="hidden" name="remove_rss_feed" value="<?php echo esc_attr( $key ); ?>" />
                                    <input type="submit" name="remove_feed" value="Remove" class="button button-secondary" />
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>

        <?php if ( isset( $_POST['edit_feed'] ) ): ?>
            <h3>Edit RSS Feed</h3>
            <?php
            $edit_key = intval( $_POST['edit_rss_feed'] );
            $feed_urls = get_option( 'auto_blogger_rss_feed_urls', array() );
            if ( isset( $feed_urls[ $edit_key ] ) ) {
                $feed_to_edit = $feed_urls[ $edit_key ];
                $edit_feed_name = $feed_to_edit['name'];
                $edit_feed_url = $feed_to_edit['url'];
                $edit_refetch_interval = $feed_to_edit['refetch_interval'];
                $edit_category = $feed_to_edit['category'];
                $edit_post_format = $feed_to_edit['post_format'];
                $edit_use_first_image = $feed_to_edit['use_first_image'];
                ?>
                <form method="post" action="">
                    <input type="text" name="edit_rss_feed_name" value="<?php echo esc_attr( $edit_feed_name ); ?>" placeholder="Feed Name" size="30">
                    <input type="text" name="edit_rss_feed_url" value="<?php echo esc_url( $edit_feed_url ); ?>" placeholder="RSS Feed URL" size="50">
                    <input type="number" name="edit_rss_refetch_interval" value="<?php echo intval( $edit_refetch_interval ); ?>" placeholder="Refetch interval in hours" min="1">
                    
                    <!-- Select category -->
                    <h4>Select Category for Fetched Posts</h4>
                    <?php
                    wp_dropdown_categories(array(
                        'show_option_all'    => 'All Categories',
                        'name'               => 'edit_rss_feed_category',
                        'selected'           => $edit_category,
                        'class'              => 'rss-feed-category',
                        'hide_empty'         => 0,
                    ));
                    ?>

                    <!-- Select post format -->
                    <h4>Select Post Format</h4>
                    <select name="edit_rss_feed_post_format">
                        <option value="standard" <?php selected( $edit_post_format, 'standard' ); ?>>Standard</option>
                        <option value="aside" <?php selected( $edit_post_format, 'aside' ); ?>>Aside</option>
                        <option value="gallery" <?php selected( $edit_post_format, 'gallery' ); ?>>Gallery</option>
                    </select>

                    <!-- Option to use the first image as featured image -->
                    <h4>Use First Image as Featured Image</h4>
                    <input type="checkbox" name="edit_rss_feed_use_first_image" value="1" <?php checked( $edit_use_first_image, 1 ); ?>> Yes

                    <input type="hidden" name="rss_feed_key" value="<?php echo esc_attr( $edit_key ); ?>">
                    <?php submit_button( 'Update Feed' ); ?>
                </form>
                <?php
            }
            ?>
        <?php endif; ?>
    </div>
    <?php
}

// Handle RSS feed addition, editing, and actions
add_action( 'admin_init', 'auto_blogger_handle_rss_feed_actions' );
function auto_blogger_handle_rss_feed_actions() {
    // Add RSS feed with name, refetch interval, category, and post format
    if ( isset( $_POST['new_rss_feed_url'] ) && ! empty( $_POST['new_rss_feed_url'] ) ) {
        $rss_feed_name = sanitize_text_field( $_POST['new_rss_feed_name'] );
        $rss_feed_url = esc_url_raw( $_POST['new_rss_feed_url'] );
        $refetch_interval = intval( $_POST['new_rss_refetch_interval'] );
        $rss_feed_category = intval( $_POST['new_rss_feed_category'] );
        $rss_feed_post_format = sanitize_text_field( $_POST['new_rss_feed_post_format'] );
        $rss_feed_use_first_image = isset( $_POST['new_rss_feed_use_first_image'] ) ? 1 : 0;

        if ( $refetch_interval < 1 ) {
            $refetch_interval = 1;
        }

        $rss_feed_urls = get_option( 'auto_blogger_rss_feed_urls', array() );

        $new_feed = array(
            'name' => $rss_feed_name,
            'url' => $rss_feed_url,
            'refetch_interval' => $refetch_interval,
            'category' => $rss_feed_category,
            'post_format' => $rss_feed_post_format,
            'use_first_image' => $rss_feed_use_first_image,
            'last_fetch' => 0,
        );

        $rss_feed_urls[] = $new_feed;
        update_option( 'auto_blogger_rss_feed_urls', $rss_feed_urls );

        wp_safe_redirect( admin_url( 'options-general.php?page=auto_blogger' ) );
        exit;
    }

    // Edit existing RSS feed
    if ( isset( $_POST['edit_rss_feed_url'] ) && isset( $_POST['rss_feed_key'] ) ) {
        $key = intval( $_POST['rss_feed_key'] );
        $rss_feed_urls = get_option( 'auto_blogger_rss_feed_urls', array() );

        if ( isset( $rss_feed_urls[ $key ] ) ) {
            $rss_feed_urls[ $key ]['name'] = sanitize_text_field( $_POST['edit_rss_feed_name'] );
            $rss_feed_urls[ $key ]['url'] = esc_url_raw( $_POST['edit_rss_feed_url'] );
            $rss_feed_urls[ $key ]['refetch_interval'] = intval( $_POST['edit_rss_refetch_interval'] );
            $rss_feed_urls[ $key ]['category'] = intval( $_POST['edit_rss_feed_category'] );
            $rss_feed_urls[ $key ]['post_format'] = sanitize_text_field( $_POST['edit_rss_feed_post_format'] );
            $rss_feed_urls[ $key ]['use_first_image'] = isset( $_POST['edit_rss_feed_use_first_image'] ) ? 1 : 0;

            update_option( 'auto_blogger_rss_feed_urls', $rss_feed_urls );

            wp_safe_redirect( admin_url( 'options-general.php?page=auto_blogger' ) );
            exit;
        }
    }

    // Manually fetch RSS feed
    if ( isset( $_POST['manual_fetch'] ) && isset( $_POST['fetch_rss_feed'] ) ) {
        $key = intval( $_POST['fetch_rss_feed'] );
        auto_blogger_fetch_single_feed( $key );
    }

    // Remove RSS feed
    if ( isset( $_POST['remove_feed'] ) && isset( $_POST['remove_rss_feed'] ) ) {
        $key = intval( $_POST['remove_rss_feed'] );
        $rss_feed_urls = get_option( 'auto_blogger_rss_feed_urls', array() );

        if ( isset( $rss_feed_urls[ $key ] ) ) {
            unset( $rss_feed_urls[ $key ] );
            update_option( 'auto_blogger_rss_feed_urls', array_values( $rss_feed_urls ) );

            wp_safe_redirect( admin_url( 'options-general.php?page=auto_blogger' ) );
            exit;
        }
    }
}
?>