<?php
/*
 * Template Name: User profile
 */
get_header();
$user = wp_get_current_user();

?>
<div class="container profile">

<div id="content">
    <h5><?php echo $user->display_name; ?></h5>
    <ul>
        <li><strong>Epost:</strong> <?php echo $user->user_email; ?></li>
        <li><strong>Rolle på aktiv.neuf.no:</strong> <?php echo $user->roles[0]; ?></li>
    </ul>
    <h5>Profilbilde</h5>
    <img src="<?php echo get_grav_url($user); ?>" /><br>
    Endre profilbildet ditt på <a href="https://nb.gravatar.com">gravatar.com</a>.

    <h5>Passord</h5>
    Endre passordet ditt via <a href="https://brukerinfo.neuf.no">Brukerinfo</a>.

    <h5>Profil og medlemskap</h5>
    Se status på medlemskapet, endre navn, epost, med mer via <a href="https://inside.studentersamfundet.no">Inside</a>.
</div>

</div> <!-- .container -->
<?php
get_footer();
?>

