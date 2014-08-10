<nav id="menu">
    <?php if ( is_user_logged_in() ): ?>
    <a href="#" class="menu-toggle"><?php include(get_stylesheet_directory()."/dist/images/icon-menu.svg"); ?></a>
    <?php
    // Main Menu
    wp_nav_menu( array(
        'theme_location' => 'main-menu',
        'container' => 'false',
        'menu_class' => 'main-menu') );

    // User menu
    $user = wp_get_current_user();
    $grav_url = get_grav_url($user);
    ?>
    <div class="user-menu-wrap">
        <a href="#" class="profile-badge"><img src="<?php echo $grav_url; ?> " class="profile-picture" /><span class="profile-inner"><?php echo $user->display_name; ?> <i class="icon-down">&#x25B6;</i></span></a>
        <ul class="user-menu">
            <li><a href="<?php echo "/profil/" ?>">Profil</a></li>
            <?php if(!current_user_can('subscriber') ) { ?><li><a href="<?php echo "/wp-admin/" ?>">Admin</a></li><?php } ?>
            <li><a href="<?php echo wp_logout_url("/"); ?>">Logg ut</a></li>
        </ul>
    </div>
    <?php endif; ?>

</nav> <!-- #menu -->
