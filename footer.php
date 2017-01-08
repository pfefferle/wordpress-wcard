<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package pfefferle
 * @since pfefferle 1.0.0
 */
?>

	</main><!-- #main -->

	<footer id="colophon" role="contentinfo">
		<a id="nextslide" class="load-item"></a>

		<div id="progress-back">
			<div id="progress-bar"></div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<script type="text/javascript">
	jQuery(function($){
		$.supersized({
			// Functionality
			slide_interval		: 15000,	// Length between transitions
			transition			: 1, 		// 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
			transition_speed	: 500,		// Speed of transition
			start_slide			: 0,
			horizontal_center	: true,
			vertical_center		: false,
			// Components
			slide_links			: 'blank',	// Individual links for each slide (Options: false, 'num', 'name', 'blank')
			slides 				: [			// Slideshow Images
		<?php
		if ( have_posts() ) :
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'post-thumbnail' );

				if ( ! $img ) {
					$img = '';
				} else {
					$img = $img[0];
				}

				$url = get_post_meta( get_the_ID(), 'external', true );
		?>
			{
				image : '<?php echo $img; ?>', title : '<?php echo trim( preg_replace( '/[\s\n]+/', ' ', addslashes( apply_filters( 'the_content', get_the_content() ) ) ) ); ?>', url : '<?php echo $url; ?>'
			},

			<?php endwhile; ?>
		<?php endif; ?>
			]
		});
	});

</script>

<?php wp_footer(); ?>

</body>
</html>
