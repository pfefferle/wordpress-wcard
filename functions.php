<?php

if ( ! function_exists( 'pfefferle_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override sempress_setup() in a child theme, add your own sempress_setup to your child theme's
 * functions.php file.
 */
function pfefferle_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on sempress, use a find and replace
	 * to change 'sempress' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'pfefferle', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head
	//add_theme_support( 'automatic-feed-links' );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1500, 1000 ); // Unlimited height, soft crop

	// Register custom image size for image post formats.
	add_image_size( 'pfefferle-image-post', 1500, 1000 );

	// Switches default core markup for search form to output valid HTML5.
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'sempress' ),
	) );

	// Add support for the Aside, Gallery Post Formats...
	//add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'status', 'image', 'video', 'audio', 'quote' ) );
	//add_theme_support( 'structured-post-formats', array( 'image', 'video', 'audio', 'quote' ) );

	/**
	 * This theme supports jetpacks "infinite-scroll"
	 *
	 * @see http://jetpack.me/support/infinite-scroll/
	 */
	//add_theme_support( 'infinite-scroll', array('container' => 'content', 'footer' => 'colophon') );

	// This theme uses its own gallery styles.
	//add_filter( 'use_default_gallery_style', '__return_false' );
}
endif; // sempress_setup

/**
 * Tell WordPress to run sempress_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'pfefferle_setup' );

/**
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since 1.3.1
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function pfefferle_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'sempress' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'pfefferle_wp_title', 10, 2 );

if ( ! function_exists( 'pfefferle_enqueue_scripts' ) ) :
/**
 * Enqueue theme scripts
 *
 * @uses wp_enqueue_scripts() To enqueue scripts
 *
 * @since SemPress 1.1.1
 */
function pfefferle_enqueue_scripts() {
	// Add HTML5 support to older versions of IE
	if ( isset( $_SERVER['HTTP_USER_AGENT'] ) &&
		 ( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) ) &&
		 ( false === strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 9' ) ) ) {

		wp_enqueue_script('html5', get_template_directory_uri() . '/js/html5.js', false, '3.6');
	}

	wp_enqueue_script('supersized', get_template_directory_uri() . '/js/supersized.3.2.7.min.js', array("jquery"), '3.2.7');
	wp_enqueue_script('supersized-shutter', get_template_directory_uri() . '/js/supersized.shutter.min.js', array("jquery", "supersized"), '3.2.7');

	// Loads our main stylesheet.
	wp_enqueue_style( 'pfefferle-style', get_stylesheet_uri() );

	?>
	<?php
}
endif;

add_action( 'wp_enqueue_scripts', 'pfefferle_enqueue_scripts' );

function pfefferle_extra_profile_fields( $user ) { ?>

	<table class="form-table">
		<tr>
			<th><label for="skills">Skills</label></th>

			<td>
				<textarea type="text" name="skills" id="skills" rows="5" cols="30"><?php echo get_user_meta($user->ID, "skills", true); ?></textarea><br />
				<span class="description">Please enter your "skills".</span>
			</td>
		</tr>
	</table>
<?php
}
add_action( 'show_user_profile', 'pfefferle_extra_profile_fields' );
add_action( 'edit_user_profile', 'pfefferle_extra_profile_fields' );

function pfefferle_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'skills', trim($_POST['skills']) );
}
add_action( 'personal_options_update', 'pfefferle_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'pfefferle_save_extra_profile_fields' );

/**
 * Adds microformats v2 support to the get_avatar() method.
 *
 * @since SemPress 1.0.0
 */
function pfefferle_get_avatar( $tag ) {
	// Adds a class for microformats v2
	return preg_replace('/(class\s*=\s*[\"|\'])/i', '${1}u-photo ', $tag);
}
add_filter( 'get_avatar', 'pfefferle_get_avatar' );
