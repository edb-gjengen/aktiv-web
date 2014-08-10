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
            <input type="text" id="id_user_query" class="search-field" name="q" placeholder="Søk på navn, epost, eller brukernavn" autofocus />
            <div class="filters">
                <div class="groups">
                    <label>Søk på gruppe (forening):</label>
                    <div class="groups-select-wrap"></div>
                </div>
                <div class="roles">
                    <label>Filtrér på...</label>
                    <ul class="button-group">
                        <!--<li><a href="#" class="button-alt">Styremedlem</a></li>
                        <li><a href="#" class="button-alt">Aktiv</a></li>-->
                        <li><a href="#" class="button-alt" data-search-filter="has_valid_membership">Gyldig medlemskap</a></li>
                    </ul>
                </div>
            </div>
        <div class="search-results">
            <div class="meta"></div>
            <div class="search-result-list"></div>
        </div>
        </form>
    </div>

    <div class="search-about">
        <?php the_post(); the_content(); ?>
    </div>

</div> <!-- #content -->

</div> <!-- .container -->

<?php get_footer(); ?>
