<?php
// Include the header
get_header(); 
?>

<div class="page-content container">
    <main class="main-content">
        <?php
        // Start the Loop
        if ( have_posts() ) :
            while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    </header>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>

                    <?php
                    // Display comments if comments are open or if there are comments
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                    ?>
                </article>

            <?php endwhile;
        else :
            echo '<p>No content found</p>';
        endif;
        ?>
    </main>
</div>

<?php
// Include the footer
get_footer(); 
?>
