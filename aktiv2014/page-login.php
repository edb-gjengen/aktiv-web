<?php
/*
 * Template Name: Login
 */
$restricted_post = get_restricted_post();
    ?>

<?php get_header(); ?>

<div class="container page-login">

<div id="content">
    <div class="introduction">
        <?php if($restricted_post && $restricted_post->post_type == 'post'): ?>
            <div class="restricted-post">
                <div class="text-body">
                    <div>
                        <h3 class="entry-title"><a href="#"><?php echo $restricted_post->post_title; ?></a></h3>
                        <div class="entry-meta byline"><span class="meta-prep meta-prep-author">av </span><span class="author vcard"><a href="<?php echo get_author_posts_url($restricted_post->post_author); ?>"><?php echo the_author_meta( 'display_name' , $restricted_post->post_author); ?></a></span>, <span class="entry-date"><?php echo get_the_time(get_option( 'date_format' ) ." ". get_option( 'time_format' ), $restricted_post->ID ); ?></span></div>
                    </div>
                    <?php echo get_the_post_thumbnail($restricted_post->ID, 'full'); ?>
                    <?php echo wp_trim_words( $restricted_post->post_content ) ?>
                </div>
            </div>
        <?php endif; ?>
            <div class="text-body">
                <?php if($restricted_post && $restricted_post->post_type == 'post'): ?>
                    <h4>Logg inn for å lese resten</h4>
                <?php else: ?>
                    <h4>Logg inn for å fortsette</h4>
                <?php endif; ?>
                <?php the_post(); the_content(); ?>
            </div>
    </div>

    <div class="login-form">
        <h4>Logg inn</h4>
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
    <a href="https://brukerinfo.neuf.no/accounts/password/reset">Problemer med å logge inn?</a>
    </div>

</div> <!-- #content -->

</div> <!-- .container -->

<?php get_footer(); ?>
