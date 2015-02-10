<?php if( have_posts() ) : while( have_posts() ) : the_post();
    $post_category = get_category_formatted();
    ?>
    
    <article <?php post_class(); ?>>
    <div class="text-body">
        <div>
            <?php if( !is_page() ): ?>
                <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <?php endif; ?>


            <?php if(  !is_page() ): ?>
                <div class="entry-meta byline"><span class="meta-prep meta-prep-author">av </span><span class="author vcard"><?php the_author_link(); ?></span>, <span class="entry-date"><?php the_time( get_option( 'date_format' ) ." ". get_option( 'time_format' ) ); ?></span></div>
            <?php endif; ?>

            <?php if( !is_page() ): ?>
                <span class="entry-category"><?php echo $post_category; ?></span>
            <?php endif; ?>
        </div>

    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('feed'); ?></a>

    <?php if( is_single() || is_page() ): ?>
        <div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->
    <?php else: ?>
        <div class="entry-content excerpt"><?php the_excerpt(); ?></div> <!-- .entry-content -->
    <?php endif; ?>

    <?php if( is_single() ): ?>
        <div class="entry-comments"><?php comments_template(); ?></div>
    <?php elseif( !is_page() ): ?>
        <div class="actions-container">
            <a class="button radius read-more" href="<?php echo get_permalink( get_the_ID() ); ?>">Les hele innlegget</a>
            <span class="comments-link"><?php comments_popup_link(); ?></span>
        </div>
    <?php endif; ?>


    </div>
    </article> <!-- .post -->

<?php endwhile; endif; ?>