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
    <div class="lists-list-wrap">
        <div class="meta"></div>
        <div id="mailinglists">
            <div class="list-manipulation">
                <div class="search-field">
                    <label>Søk i listene:</label>
                    <input class="search" type="text" />
                </div>
                <div class="sort-buttons">
                    <label>Sortér på:</label>
                    <span class="button-alt sort" data-sort="list-num-members">Antall medlemmer</span>
                    <span class="button-alt sort" data-sort="list-name">Navn</span>
                 </div>
            </div>
            <ul class="lists-list list"></ul>
        </div>
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
