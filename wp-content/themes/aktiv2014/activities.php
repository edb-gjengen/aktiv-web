<h1>Siste nytt</h1>
<?php
$args = array(
	'post_type'			=> 'post',
	'posts_per_page' 	=> 25,
	'order'				=> 'DESC'
);

$posts = new WP_Query( $args );

if( $posts->have_posts() ) : while( $posts->have_posts() ) : $posts->the_post();
    $post_category = "";
    if( count(get_the_category()) >= 1 ) {
        $cat = get_the_category();
        $cat = $cat[0];
        $post_category = '<a href="'. get_category_link($cat->term_id) .'" class="label-category"><span class="dashicons dashicons-tag"></span>'. $cat->name .'</a>';
    }
    ?>
    <article <?php post_class(); ?>>
    <div class="text-body">
        <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <div class="entry-meta byline">
            <span class="meta-prep meta-prep-author">av </span>
            <span class="author vcard"><?php the_author_link(); ?></span>,
            <span class="entry-date"><?php the_time( get_option( 'date_format' ) ." ". get_option( 'time_format' ) ); ?></span>
        </div>
        <span class="entry-category"><?php echo $post_category; ?></span> <!-- .entry-category -->
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('feed'); ?></a>
        <div class="entry-content excerpt"><?php the_excerpt(); ?></div> <!-- .entry-content -->
        <div class="actions-container">
            <a class="button radius read-more" href="<?php echo get_permalink( get_the_ID() ); ?>">Les hele innlegget</a>
            <span class="comments-link"><?php comments_popup_link(); ?></span>
        </div>
    </div>
    </article> <!-- .post -->

<?php endwhile; endif; ?>

