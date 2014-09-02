<?php get_header(); ?>
<div class="container">

<div id="content">
<?php if( !is_single() ): ?>
    <h1 class="page-title"><?php wp_title("", true); ?></h1>
<?php endif; ?>
<?php if( have_posts() ) : while( have_posts() ) : the_post();
    $post_category = "";
    if( count(get_the_category()) >= 1 ) {
        $cat = get_the_category();
        $cat = $cat[0];
        $post_category = '<a href="'. get_category_link($cat->term_id) .'" class="label-category"><span class="dashicons dashicons-tag"></span>'. $cat->name .'</a>';
    } ?>
    
    <article <?php post_class(); ?>>
    <div class="text-body">
    <?php if( !is_page() ): ?>
        <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <?php endif; ?>


    <?php if(  !is_page() ): ?>
        <div class="entry-meta byline"><span class="meta-prep meta-prep-author">av </span><span class="author vcard"><?php the_author_link(); ?></span>, <span class="entry-date"><?php the_time( get_option( 'date_format' ) ." ". get_option( 'time_format' ) ); ?></span></div>
    <?php endif; ?>

    <?php if( !is_page() ): ?>
        <span class="entry-category"><?php echo $post_category; ?></span>
    <?php endif; ?>

    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('feed'); ?></a>

    <?php if( is_single() || is_page() || is_home() ): ?>
        <div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->
    <?php else: ?>
        <div class="entry-content excerpt"><?php the_excerpt(); ?></div> <!-- .entry-content -->
    <?php endif; ?>

    <?php if( is_single() ): ?>
        <?php comments_template(); ?>
    <?php elseif( !is_page() ): ?>
        <div class="actions-container">
            <a class="button radius read-more" href="<?php get_permalink( get_the_ID() ) ?>'">Les hele innlegget</a>
            <span class="comments-link"><?php comments_popup_link(); ?></span>
        </div>
    <?php endif; ?>


    </div>
    </article> <!-- .post -->

<?php endwhile; endif; ?>

</div> <!-- #content -->

<?php if( !is_page() ) {
    get_template_part( 'sidebar' );
    // TODO Level 2 navigation
} ?>

</div> <!-- .container-->


<?php get_footer(); ?>
