<?php get_header(); ?>

<div id="content">
    <h1 class="page-title"><?php wp_title("", true); ?></h1>

    <?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
    
    <article <?php neuf_post_class(); ?>>
        <div class="text-body">
            <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

            <div class="entry-meta byline"><span class="meta-prep meta-prep-author">av </span><span class="author vcard"><?php the_author_link(); ?></span>, <span class="entry-date"><?php the_date('l d. M Y'); ?> kl <?php the_time('H.i'); ?></span></div>

            <div class="entry-content"><?php the_excerpt(); ?></div> <!-- .entry-content -->

            <?php the_post_thumbnail(); ?>
        </div>
    </article> <!-- .post -->

    <?php endwhile; endif; ?>

</div> <!-- #content -->

<?php get_footer(); ?>
