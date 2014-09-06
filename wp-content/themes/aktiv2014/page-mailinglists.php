<?php
/*
 * Template Name: Mailinglists
 */
?>

<?php get_header(); ?>

<div class="container page-mailinglists">

    <h1 class="page-title"><?php wp_title("", true); ?></h1>

    <div class="search-about">
        <?php the_post(); the_content(); ?>
    </div>
       <!--<div class="sort-by">
            <label>Sortér på:</label>
            <ul class="button-group">
                <li><a href="#" class="button-alt" data-sort-by="num">Antall medlemmer</a></li>
                <li><a href="#" class="button-alt" data-sort-by="name">Navn</a></li>
            </ul>
        </div>-->
        <div class="lists-list-wrap">
            <div class="meta"></div>
            <div class="lists-list"></div>
        </div>
        <div class="list-members-wrap">
            <div class="meta"></div>
            <div class="list-members"></div>
        </div>

    <div class="toptoptop">
        <a href="#site-header" class="button-alt">Til toppen</a>
    </div>

</div> <!-- .container -->

<?php get_footer(); ?>
