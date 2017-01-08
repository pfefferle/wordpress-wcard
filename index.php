<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package SemPress
 * @since SemPress 1.0.0
 */

get_header();

$user = get_user_by( 'slug', 'pfefferle' );
?>

<div class="author contact vcard hcard h-card"
	itemscope itemtype="http://schema.org/Person"
	vocab="http://schema.org/"
	typeof="Person"
	about="#pfefferle"
	id="profile">

	<?php echo get_avatar( $user->ID, '100', null, 'Image of Matthias Pfefferle' ); ?>

	<h1 class="entry-title p-entry-title">
		Mein Name ist<br />
		<strong class="fn p-fn p-name" itemprop="name" property="name"><?php echo $user->first_name . ' ' . $user->last_name; ?></strong>
	</h1>
	<div class="e-content entry-content e-entry-content">
		<p id="summary" class="note p-note summary p-entry-summary entry-summary" itemprop="description" property="description">
			<?php echo get_user_meta( $user->ID, 'description', true ); ?>
		</p>
		<p id="skills" class="skills e-skills e-reputation reputation">
			<?php echo get_user_meta( $user->ID, 'skills', true ); ?>
		</p>
	</div>
</div>

<div id="slidecaption">

</div>

<?php get_footer(); ?>
