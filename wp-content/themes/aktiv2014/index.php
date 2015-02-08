<?php get_header(); ?>
<div class="container">

<div id="content">
<?php if( !is_single() ): ?>
    <h1 class="page-title"><?php wp_title("", true); ?></h1>
<?php endif; ?>
<?php get_template_part('entries'); ?>
</div> <!-- #content -->

<?php if( !is_page() ) {
    get_template_part( 'sidebar' );
    // TODO Level 2 navigation
} ?>

</div> <!-- .container-->


<?php get_footer(); ?>
