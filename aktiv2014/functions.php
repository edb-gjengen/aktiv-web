<?php
$AKTIV2016_VERSION = '1.0.0';

add_theme_support('menus');
add_theme_support('post-thumbnails');
add_theme_support('automatic-feed-links');
add_theme_support('title-tag');

$content_width = 770;

add_image_size( 'cover-photo' , 1440, 810, true );
add_image_size( 'feed' , 770, 433, array('center', 'top') );
/**
 * Register navigation menus.
 */
function neuf_register_nav_menus() {
    register_nav_menus(
        array( 'main-menu' => __( 'Hovedmeny' ) )
    );
}
add_action( 'init' , 'neuf_register_nav_menus' );

/** Enqueue various scripts we use. */
function neuf_enqueue_scripts() {
    global $AKTIV2016_VERSION;
    wp_deregister_script( 'jquery' );
    wp_register_script('vendor', get_template_directory_uri().'/dist/scripts/vendor.js', array(), $AKTIV2016_VERSION);
    wp_register_script('app', get_template_directory_uri().'/dist/scripts/main.js', array('vendor'), $AKTIV2016_VERSION);
    wp_enqueue_script( 'app' );
    wp_enqueue_style( 'app', get_stylesheet_directory_uri().'/dist/styles/main.css', array(), $AKTIV2016_VERSION);
}
add_action( 'wp_enqueue_scripts' , 'neuf_enqueue_scripts' );
/**
 * Force large uploaded images.
 * Denies uploads of images smaller (in pixels) than given width and height values.
 */
function neuf_handle_upload_prefilter( $file ) {
    /* Only check files with mime type 'image/*' */
    $is_not_image = strpos($file['type'], 'image/') !== 0;
    if( $is_not_image ) {
        return $file;
    }
    $errors = array();
    $minimum = array( 'width' => 640, 'height' => 480);

    $img = getimagesize( $file['tmp_name'] );
    $width = $img[0];
    $height = $img[1];


    if ( $width < $minimum['width'] ) {
        $errors[] = "Minimum width is {$minimum['width']} px. Uploaded image wid th is $width px";
    }
    if ($height < $minimum['height']) {
        $errors[] = "Minimum height is {$minimum['height']} px. Uploaded image h eight is $height px";
    }

    if( count($errors) > 0 ) {
        return array( "error" => "Image dimensions are too small: ".implode(", " , $errors).".");
    }

    return $file;
}
add_filter( 'wp_handle_upload_prefilter' , 'neuf_handle_upload_prefilter' );

/**
 * Adds more semantic classes to WP's body_class.
 *
 * Adds these classes:
 * i) For pages, adds 'page-slug'
 */
function neuf_body_class( $classes = '' ) {
    global $post;

    if ( $classes )
        $classes .= ' ';

    if ( is_page() )
        $classes .= 'page-' . $post->post_name ;

    body_class( $classes );
}

/**
 * Trims $text down to $length words.
 * If $text is truncated, then "[..]" is appended.
 */
function trim_excerpt($text, $length) {
    $org_length = strlen($text);
    $text = explode(" ", $text); // word boundary
    $text = array_slice($text, 0, $length);
    $text = implode(" ", $text);
    $shorter = $org_length != strlen($text) ? " [...]" : "";
    return $text . $shorter;
}

/**
 * Replaces the matching $pattern with $replacement in the string $subject.
 */
function linkify($subject, $pattern, $link) {
    $replacement = '<a href="'.$link.'">[...]</a>';
    $output = preg_replace($pattern, $replacement, $subject);
    return $output;
}

/**
 * Gets the name of a term.
 *
 * Original author: Justin Tadlock (theme Hybrid).
 */
function neuf_get_term_name() {
    $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
    return $term->name;
}

/* Widgets */
function neuf_widgets_init() {

    register_sidebar( array(
        'name' => 'Home right sidebar',
        'id' => 'main_sidebar',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
    ) );
}
add_action( 'widgets_init', 'neuf_widgets_init' );

/**
 * Redirect user after successful login.
 *
 * @param string $redirect_to URL to redirect to.
 * @param string $request URL the user is coming from.
 * @param object $user Logged user's data.
 * @return string
 */
function neuf_login_redirect( $redirect_to, $request, $user ) {
    //is there a user to check?
    global $user;
    if ( isset($user) ) {
        // Redirect to homepage
        return home_url()."?yourin";
    } else {
        return $redirect_to;
    }
}
add_filter( 'login_redirect', 'neuf_login_redirect', 10, 3 );

/* No admin bar for mortals */
function neuf_show_admin_bar($content) {
    return ( current_user_can( 'administrator' ) ) ? $content : false;
}
add_filter( 'show_admin_bar' , 'neuf_show_admin_bar');

function get_grav_url($user) {
    $default = "mm";
    $size = 50;
    $grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $user->user_email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;

    return $grav_url;
}
/* Load dash icons */
add_action( 'wp_enqueue_scripts', 'neuf_load_dashicons' );
function neuf_load_dashicons() {
    wp_enqueue_style( 'dashicons' );
}

/* Read more link */
function neuf_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'neuf_excerpt_more' );

/* Save user meta options */
function neuf_save_user_meta() {
    $user_id = (int) $_POST['user_id'];
    $meta_key = $_POST['meta_key']; // TODO: sanitize
    $meta_value = $_POST['meta_value']; // TODO: sanitize
    $unique = (bool) $_POST['unique'];

    if( !get_userdata($user_id) ) {
        die(json_encode(array('error' => 'Invalid user_id: \''. $_POST['user_id'] .'\'')));
    }
    if( !is_user_logged_in() ) {
        die(json_encode(array('error' => 'Log in first')));
    }

    if( !wp_verify_nonce( $_POST['_wpnonce'], 'user-meta' ) ) {
        die(json_encode(array('error' => 'Invalid nonce')));
    }

    $meta_id = add_user_meta($user_id, $meta_key, $meta_value, $unique);

    if( !$meta_id ) {
        die(json_encode(array('result' => 'User meta NOT updated!')));
    }
    else{
        die(json_encode(array('result' => 'User meta updated.')));
    }
}
add_action( 'wp_ajax_neuf_save_user_meta', 'neuf_save_user_meta' );

/* Save user meta options */
function neuf_get_user_meta() {
    $user_id = (int) $_GET['user_id'];
    $meta_key = $_GET['meta_key']; // TODO: sanitize
    $single = (bool) $_GET['single'];

    if( !get_userdata($user_id) ) {
        die(json_encode(array('error' => 'Invalid user_id: \''. $_GET['user_id'] .'\'')));
    }
    if( !is_user_logged_in() ) {
        die(json_encode(array('error' => 'Log in first')));
    }

    if( !wp_verify_nonce( $_GET['_wpnonce'], 'user-meta' ) ) {
        die(json_encode(array('error' => 'Invalid nonce')));
    }

    $meta_value = get_user_meta($user_id, $meta_key, $single);
    die(json_encode(array('result' => $meta_value)));

}
add_action( 'wp_ajax_neuf_get_user_meta', 'neuf_get_user_meta' );

function neuf_rsa_feed_override( $is_restricted ) {
    global $wp;
    // check query variables to see if this is the feed
    if ( ! empty( $wp->query_vars['feed'] ) )
        $is_restricted = false;

    return $is_restricted;
}
add_filter( 'restricted_site_access_is_restricted', 'neuf_rsa_feed_override' );

function get_feed_url() {
    $url = get_bloginfo('url');
    $feedurl = "$url/feed/";

    if(is_user_logged_in()) {
        $user = wp_get_current_user();
        $feedkey = get_user_meta($user->ID, 'feed_key', true);
        $feedurl .= "?feedkey=$feedkey";
    }
    return $feedurl;
}

remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed

function get_category_formatted() {
    if( count(get_the_category()) >= 1 ) {
        $cat = get_the_category();
        $cat = $cat[0];
        return '<a href="'. get_category_link($cat->term_id) .'" class="label-category"><span class="dashicons dashicons-tag"></span>'. $cat->name .'</a>';
    }
    return "";
}

/* Customize theme options
 * Ref: http://ottopress.com/2012/making-a-custom-control-for-the-theme-customizer/
 */
if (class_exists('WP_Customize_Control')) {
    class Customize_Textarea_Control extends WP_Customize_Control {
        public $type = 'textarea';

        public function render_content() {
            ?>
            <label>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
            </label>
            <?php
        }
    }
}

function neuf_customize_register( $wp_customize ) {
    /* Introduction */
    $wp_customize->add_setting( 'home_introduction_text' , array('default' => '') );
    $wp_customize->add_control(
        new Customize_Textarea_Control( $wp_customize, 'home_introduction_text', array(
            'label'    => __( 'Introduksjonstekst', 'neuf-web' ),
            'section'  => 'static_front_page',
            'settings' => 'home_introduction_text',
        ) )
    );


   /* Footer Section */
    $wp_customize->add_section( 'footer_section' , array(
        'title'      => __( 'Footer', 'neuf-web' ),
        'priority'   => 30,
    ) );
    $wp_customize->add_setting( 'footer_about_text' , array('default' => '') );
    $wp_customize->add_control(
        new Customize_Textarea_Control( $wp_customize, 'footer_about_text', array(
            'label'    => __( 'Footer-tekst', 'neuf-web' ),
            'section'  => 'footer_section',
            'settings' => 'footer_about_text',
        ) )
    );
}
add_action( 'customize_register', 'neuf_customize_register' );

require_once('neuf_widgets.php');


/* The following allows the Facebook bot and the login page to leak
   the title, excerpt and image info to users that are not logged in.
*/
add_action( 'restrict_site_access_handling', 'my_restrict_site_access_handling' );

function my_restrict_site_access_handling( $rsa_restrict_approach) {
    global $wp;
    $rsa_options = (array) get_option( 'rsa_options' );
    if( $rsa_restrict_approach == 4 && !empty($rsa_options['page']) && ($page_id = get_post_field('ID', $rsa_options['page']))) {
        $wp->query_vars_restricted = $wp->query_vars; // Backup for later
    }
}
function get_restricted_post() {
    global $wp;

    if( !property_exists($wp, 'query_vars_restricted') || $wp->query_vars_restricted == null ) {
        return null;
    }
    $q = new WP_Query($wp->query_vars_restricted);
    if (!$q->have_posts()) {
        return null;
    }

    return $q->posts[0];
}

function restricted_title($title){
    $post = get_restricted_post();
    if($post == null) {
        return $title;
    }
    return $post->post_title;
}
add_filter('wp_title', 'restricted_title');
add_filter('opengraph_title', 'restricted_title');

/* Mostly copied from opengraph plugin */
function restricted_image($image){
    $post = get_restricted_post();
    if($post == null) {
        return $image;
    }

    $id = $post->ID;
    $image_ids = array();

    // As of July 2014, Facebook seems to only let you select from the first 3 images
    $max_images = 3;

    // list post thumbnail first if this post has one
    if ( has_post_thumbnail($id) ) {
        $image_ids[] = get_post_thumbnail_id($id);
    }

    // then list any image attachments
    $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit',
        'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC',
        'orderby' => 'menu_order ID') );
    foreach ( $attachments as $attachment ) {
        if ( !in_array($attachment->ID, $image_ids) ) {
            $image_ids[] = $attachment->ID;
            if (sizeof($image_ids) >= $max_images) {
                break;
            }
        }
    }

    // get URLs for each image
    $image = array();
    foreach ( $image_ids as $id ) {
        $thumbnail = wp_get_attachment_image_src( $id, 'medium');
        if ($thumbnail) {
            $image[] = $thumbnail[0];
        }
    }
    return $image;
}
add_filter('opengraph_image', 'restricted_image');

function restricted_url($url){
    $post = get_restricted_post();
    if($post == null) {
        return $url;
    }

    if ( is_singular() ) {
        $url = get_permalink($post->ID);
    }
    return $url;
}
add_filter('opengraph_url', 'restricted_url');

function restricted_description( $description ) {
    $post = get_restricted_post();
    if($post == null) {
        return $description;
    }

    if (is_singular()) {
        if (!empty($post->post_excerpt)) {
            $description = $post->post_excerpt;
        } else {
            $description = $post->post_content;
        }
    }

    $description =  wp_trim_words($description);

    return $description;
}
add_filter('opengraph_description', 'restricted_description');

?>
