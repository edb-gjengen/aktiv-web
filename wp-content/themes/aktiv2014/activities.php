<h1>Siste nytt</h1>
<?php
$args = array(
	'post_type'			=> 'post',
	'posts_per_page' 	=> 25,
	'order'				=> 'DESC'
);

$posts = new WP_Query( $args );

if( $posts->have_posts() ) : while( $posts->have_posts() ) : $posts->the_post(); ?>
    
    <article <?php post_class(); ?>>
    <div class="text-body">
        <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><div class="entry-category"><?php the_category(); ?></div> <!-- .entry-category --></h3>
        <div class="entry-meta byline"><span class="meta-prep meta-prep-author">av </span><span class="author vcard"><?php the_author_link(); ?></span>, <span class="entry-date"><?php the_time( get_option( 'date_format' ) ." ". get_option( 'time_format' ) ); ?></span></div>
        <?php the_post_thumbnail(); ?>
        <div class="entry-content"><?php the_content(); ?></div> <!-- .entry-content -->
        <span class="comments-link"><?php comments_popup_link(); ?></span>
    </div>
    </article> <!-- .post -->

<?php endwhile; endif; ?>

