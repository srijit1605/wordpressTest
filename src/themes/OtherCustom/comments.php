<?php if ( have_comments() ) : ?>
    <h3 class="comments-title">
        <?php
        $comment_count = get_comments_number();
        echo $comment_count . ' ' . _n( 'Comment', 'Comments', $comment_count );
        ?>
    </h3>

    <ul class="comment-list">
        <?php wp_list_comments( array( 'style' => 'ul', 'short_ping' => true ) ); ?>
    </ul>
<?php endif; ?>

<?php
comment_form();
?>
