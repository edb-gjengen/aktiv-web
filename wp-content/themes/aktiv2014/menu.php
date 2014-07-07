<nav id="menu">
    <a href="#" class="menu-toggle" data-toggle-menu><?php include(get_stylesheet_directory()."/dist/images/icon-menu.svg"); ?></a>
    <?php wp_nav_menu( array(
        'theme_location' => 'main-menu',
        'container' => 'false',
        'menu_class' => 'main-menu') ); ?>

    <!-- TODO: User menu (logout, more) -->
    <?php if ( is_user_logged_in() ):
        $user = wp_get_current_user();
        $default = "mm";
        $size = 50;
        $grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $user->user_email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
    ?>
    <div class="user-menu-wrap">
        <a href="#" class="profile-badge"><img src="<?php echo $grav_url; ?> " class="profile-picture" /><span class="profile-inner"><?php echo $user->user_firstname." ".$user->user_lastname; ?> <i class="icon-down">&#x25B6;</i></span></a>
        <ul class="user-menu">
            <li><a href="<?php echo "/profil/" ?>">Profil</a></li>
            <li><a href="<?php echo wp_logout_url("/"); ?>">Logg ut</a></li>
        </ul>
    </div>
    <?php endif; ?>

</nav> <!-- #menu -->
