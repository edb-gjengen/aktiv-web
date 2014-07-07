<?php
/*
 * Template Name: Login
 */
?>

<?php get_header(); ?>

<div class="container page-login">

<div id="content">
    <div class="introduction">
        <h3>Logg inn for å fortsette</h3>
        <?php the_post(); the_content(); ?>
    </div>

    <div class="login-form">
    <?php
    $args = array(
        'form_id' => 'loginform-custom',
        'label_username' => __( 'Brukernavn' ),
        'label_password' => __( 'Passord' ),
        'label_remember' => __( 'Husk meg' ),
        'label_log_in' => __( 'Logg inn' ),
        'remember' => true
    );
    wp_login_form( $args );
    ?>
    </div>

</div> <!-- #content -->

</div> <!-- .container -->

<?php get_footer(); ?>
