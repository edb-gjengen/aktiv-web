<nav id="menu">
    <a href="#" class="menu-toggle" data-toggle-menu><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/app/images/icon-menu.svg" class="icon-menu" /></a>
    <?php wp_nav_menu( array(
        'theme_location' => 'main-menu',
        'container' => 'false',
        'menu_class' => 'main-menu') ); ?>
</nav> <!-- #menu -->
