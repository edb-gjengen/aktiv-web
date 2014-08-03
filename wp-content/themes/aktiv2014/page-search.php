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
            <div class="filters">
                <div class="groups">
                    <label>Grupper:</label>
                    <div class="groups-select-wrap"></div>
                </div>
                <div class="roles">
                    <label>Roller:</label>
                    <div class="button-group">
                        <button class="tiny">Styremedlem</button>
                        <button class="tiny">Aktiv</button>
                        <button class="tiny">Gyldig medlemskap</button>
                    </div>
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
