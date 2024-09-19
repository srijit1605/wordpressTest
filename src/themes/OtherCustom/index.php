    <?php get_header();?>
    <div class="content">
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post(); ?>
                <article>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p><?php the_excerpt(); ?></p>
                    <p><small>Posted on <?php the_date(); ?> in <?php the_category(', '); ?></small></p>
                </article>
            <?php endwhile;
        else :
            echo '<p>No posts found.</p>';
        endif;
        ?>
    </div>

    <?php get_footer();?>
