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
        <?php the_post(); the_content(); ?>
        <a href="#dismiss-introduction" data-toggle-introduction class="button dismiss-button" title="Lukk">Oki doki!</a>
    </div>

    <div class="events-today-wrap">
        <h1>Kommende program</h1>
        <div class="events-today">
            <table class="program-list"><!-- JS magic --></table>
            <div class="text-right"><a href="https://studentersamfundet.no/program/">Vis programmet på s.no</a></div>
        </div>
    </div>

    <div class="activities-wrap">
    <?php get_template_part( 'activities' ); ?>
    </div>

</div> <!-- #content -->

<?php get_template_part( 'sidebar' ); ?>

</div> <!-- .container -->

<?php get_footer(); ?>
