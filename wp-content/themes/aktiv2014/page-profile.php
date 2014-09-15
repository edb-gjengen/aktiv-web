<?php
/*
 * Template Name: User profile
 */
$user = null;
$logged_in_user = true;
if( isset($_GET['username']) && get_user_by( 'login', $_GET['username']) ) {
    $user = get_user_by('login', $_GET['username']);
    $logged_in_user = false;
}
if( !isset($_GET['username']) ) {
    $user = wp_get_current_user();

}
if($user === null) {
    header("HTTP/1.0 404 Not Found - Archive Empty");
    require TEMPLATEPATH.'/404.php';
    exit;
}
get_header();

?>
<div class="container profile">

<h1 class="page-title"><?php echo $logged_in_user ? "Min": $user->display_name." sin" ;?> aktivprofil</h1>

<div class="profile-details">
    <div class="inner">
        <h5><?php echo $user->display_name; ?></h5>
        <ul>
            <li><strong>Epost</strong>: <span class="user-email"><?php echo $user->user_email; ?></span></li>
            <li><strong>Medlemskapsstatus</strong>: <span class="is-member"></span></li>
            <li><strong>Rolle på aktiv.neuf.no</strong>: <?php echo $user->roles[0]; ?></li>
        </ul>
        <?php if($logged_in_user ): ?>
            <p>
                <em>Endre navn, epost, med mer via <a href="https://inside.studentersamfundet.no">Inside</a></em>.
            </p>
            <h5>Profilbilde</h5>
            <p><a href="https://nb.gravatar.com"><img src="<?php echo get_grav_url($user); ?>" title="Endre profilbildet ditt på gravatar.com" /> Endre profilbilde</a></p>
            <h5>Passord</h5>
            <p>Endre passordet ditt via <a href="https://brukerinfo.neuf.no">Brukerinfo</a>.</p>
            <p></p>
            <h5>Annet</h5>
            <?php
                $feedkey = get_usermeta($user->ID, 'feed_key');
                $feedurl = get_bloginfo('url').'/feed/?feedkey='.$feedkey;
            ?>
            <a href="<?php echo $feedurl; ?>">Privat RSS-feed</a>
        <?php else: ?>
            <a href="#" class="button radius js-inside-link">Vis i Inside</a>
        <?php endif; ?>
    </div>
</div>
<div class="account-details">
    <div class="inner">
        <h5>Gruppemedlemskap</h5>
        <ul class="group-list">
        </ul>
        <h5>Epostlistemedlemskap</h5>
        <ul class="email-list">
        </ul>
        <strong>Arvede epostlistemedlemskap</strong>
        <ul class="email-list-inherited">
        </ul>
    </div>
</div>

</div> <!-- .container -->
<?php
get_footer();
?>

