<?php get_header();?>
<div class="content">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post(); ?>
                <article>
                    <h2><?php the_title(); ?></h2>
                    <p><small>Posted on <?php echo get_the_date(); ?> by <?php the_author(); ?></small></p>

                    <div class="post-content">
                        <?php the_content(); ?>
                    </div>

                    <div class="post-meta">
                        <p>Filed under: <?php the_category(', '); ?></p>
                        <p><?php the_tags('Tags: ', ', ', '<br>'); ?></p>
                    </div>

                    <div class="comments-section">
                    <?php
if (!comments_open() && get_comments_number() == 0) {
    echo '<p>Comments are closed.</p>';
} else {
    comments_template();
}
?>
                    </div>
                </article>
            <?php endwhile;
        else :
            echo '<p>No posts found.</p>';
        endif;
        ?>
    </div>
<?php get_footer();?>