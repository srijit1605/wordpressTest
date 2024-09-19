<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php single_cat_title(); ?> - <?php bloginfo('name'); ?></title>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
</head>
<body>
    <header>
        <h1><?php bloginfo('name'); ?></h1>
        <p><?php bloginfo('description'); ?></p>
    </header>

    <div class="content">
        <h2>Category: <?php single_cat_title(); ?></h2>
        <p><?php echo category_description(); ?></p>


        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post(); ?>
                <article>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p><?php the_excerpt(); ?></p>
                    <p><small>Posted on <?php the_date(); ?> in <?php the_category(', '); ?></small></p>
                </article>
            <?php endwhile;

            // Pagination
            the_posts_pagination(array(
                'prev_text' => __('Previous'),
                'next_text' => __('Next'),
            ));

        else :
            echo '<p>No posts found in this category.</p>';
        endif;
        ?>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> - Powered by WordPress</p>
    </footer>
</body>
</html>
