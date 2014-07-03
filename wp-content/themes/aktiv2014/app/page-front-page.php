<?php
/*
 * Template Name: Front page
 */
?>

<?php get_header(); ?>

<?php get_template_part( 'cover' ); ?>

<div id="content">
    <div class="introduction"><?php the_post(); ?></div>
    <?php the_content(); ?>
    <?php get_template_part( 'program' , 'upcoming' ); ?>
    <?php get_template_part( 'digest' ); ?>

</div> <!-- #content -->

<?php get_footer(); ?>
