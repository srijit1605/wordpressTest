<nav class="main-navigation">
    <?php
    wp_nav_menu(array(
        'theme_location' => 'primary-menu',
        'container' => false,
        'menu_class' => 'menu',
        'fallback_cb' => false
    ));
    ?>
</nav>
