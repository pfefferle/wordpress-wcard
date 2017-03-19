<?php

if ( ! function_exists( 'wcard_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook. The init hook is too late for some features, such as indicating
	 * support post thumbnails.
	 *
	 * To override wcard_setup() in a child theme, add your own wcard_setup to your child theme's
	 * functions.php file.
	 */
	function wcard_setup() {

		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 * If you're building a theme based on wcard, use a find and replace
		 * to change 'wcard' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'wcard', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// This theme uses post thumbnails
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1500, 1000 ); // Unlimited height, soft crop

		// Register custom image size for image post formats.
		add_image_size( 'wcard-image-post', 1500, 1000 );

		// Switches default core markup for search form to output valid HTML5.
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

		/**
		 * This theme supports the "title-tag" feature
		 *
		 * @see https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
		 */
		add_theme_support( 'title-tag' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => __( 'Primary Menu', 'wcard' ),
		) );
	}
endif; // wcard_setup

/**
 * Tell WordPress to run wcard_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'wcard_setup' );

if ( ! function_exists( 'wcard_enqueue_scripts' ) ) :
	/**
	 * Enqueue theme scripts
	 *
	 * @uses wp_enqueue_scripts() To enqueue scripts
	 *
	 * @since wCard 1.1.1
	 */
	function wcard_enqueue_scripts() {
		// Add HTML5 support to older versions of IE
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) &&
			( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) ) &&
			( false === strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 9' ) ) ) {

			wp_enqueue_script( 'html5', get_template_directory_uri() . '/js/html5.js', false, '3.6' );
		}

		wp_enqueue_script( 'supersized', get_template_directory_uri() . '/js/supersized.3.2.7.min.js', array( 'jquery' ), '3.2.7' );
		wp_enqueue_script( 'supersized-shutter', get_template_directory_uri() . '/js/supersized.shutter.min.js', array( 'jquery', 'supersized' ), '3.2.7' );

		// Loads our main stylesheet.
		wp_enqueue_style( 'wcard-style', get_stylesheet_uri() );

		?>
		<?php
	}
endif;

add_action( 'wp_enqueue_scripts', 'wcard_enqueue_scripts' );

function wcard_extra_profile_fields( $user ) {
?>
	<table class="form-table">
		<tr>
			<th><label for="skills">Skills</label></th>

			<td>
				<textarea type="text" name="skills" id="skills" rows="5" cols="30"><?php echo get_user_meta( $user->ID, 'skills', true ); ?></textarea><br />
				<span class="description">Please enter your "skills".</span>
			</td>
		</tr>
	</table>
<?php
}
add_action( 'show_user_profile', 'wcard_extra_profile_fields' );
add_action( 'edit_user_profile', 'wcard_extra_profile_fields' );

function wcard_save_extra_profile_fields( $user_id ) {

	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'skills', trim( $_POST['skills'] ) );
}
add_action( 'personal_options_update', 'wcard_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'wcard_save_extra_profile_fields' );

/**
 * Adds microformats v2 support to the get_avatar() method.
 *
 * @since wCard 1.0.0
 */
function wcard_get_avatar( $tag ) {
	// Adds a class for microformats v2
	return preg_replace( '/(class\s*=\s*[\"|\'])/i', '${1}u-photo ', $tag );
}
add_filter( 'get_avatar', 'wcard_get_avatar' );
