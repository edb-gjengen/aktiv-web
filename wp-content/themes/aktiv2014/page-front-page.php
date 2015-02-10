<?php
/*
 * Template Name: Front page
 */
?>

<?php get_header(); ?>

<?php get_template_part( 'cover' ); ?>

<div class="container front-page">

<div id="content">
    <div class="introduction" style="display: none;">
        <?php echo get_theme_mod( 'home_introduction_text',""); ?>
        <a href="#dismiss-introduction" data-toggle-introduction class="button dismiss-button" title="Lukk">Oki doki!</a>
    </div>

    <div class="activities-wrap">
    <?php
        // Load up some posts
        $args = array(
            'post_type'         => 'post',
            'posts_per_page'    => 10,
            'order'             => 'DESC'
        );
        query_posts($args);
        get_template_part( 'entries' );
    ?>
    </div>

</div> <!-- #content -->

<?php get_template_part( 'sidebar' ); ?>

</div> <!-- .container -->

<?php get_footer(); ?>
