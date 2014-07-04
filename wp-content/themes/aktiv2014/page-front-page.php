<?php
/*
 * Template Name: Front page
 */
?>

<?php get_header(); ?>

<?php get_template_part( 'cover' ); ?>

<div class="container front-page">

<div id="content">
    <div class="introduction">
        <?php the_post(); the_content(); ?>
    </div>

    <div class="events-today-wrap">
        <h1>Dagens program</h1>
        <div class="events-today">
            <table class="program-list"><!-- JS magic --></table>
        </div>
    </div>

    <div class="activities-wrap">
    <?php get_template_part( 'activities' ); ?>
    </div>

</div> <!-- #content -->

<?php get_template_part( 'sidebar' ); ?>

</div> <!-- .container -->

<?php get_footer(); ?>
