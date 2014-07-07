<?php
/*
 * Template Name: User search
 */
?>

<?php get_header(); ?>

<div class="container page-user-search">

<div id="content">
    <div class="search-form">
        <h1 class="page-title"><?php wp_title("", true); ?></h1>
        <form method="get">
            <input type="text" id="id_user_query" class="search-field" name="q" placeholder="Søk på navn, epost, telefonnummer, brukernavn, medlemsnummer eller kortnummer" />
        <div class="search-results"></div>
        </form>
    </div>

    <div class="search-about">
        <?php the_post(); the_content(); ?>
    </div>

</div> <!-- #content -->

</div> <!-- .container -->

<?php get_footer(); ?>
