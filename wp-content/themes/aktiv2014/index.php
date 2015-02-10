<?php get_header(); ?>
<div class="container">
    <div id="content">

        <?php if( is_home()): ?>
            <div class="introduction" style="display: none;">
                <?php echo get_theme_mod( 'home_introduction_text',""); ?>
                <a href="#dismiss-introduction" data-toggle-introduction class="button dismiss-button" title="Lukk">Oki doki!</a>
            </div>
            <h2>Siste nytt</h2>
        <?php elseif( !is_single() ): ?>
            <h1 class="page-title"><?php wp_title("", true); ?></h1>
        <?php endif; ?>

        <?php get_template_part('entries'); ?>

        <div class="navigation"><p><?php posts_nav_link(); ?></p></div>

    </div> <!-- #content -->

    <?php if( !is_page() ) {
        get_template_part( 'sidebar' );
        // TODO Level 2 navigation
    } ?>

</div> <!-- .container-->
<?php get_footer(); ?>
