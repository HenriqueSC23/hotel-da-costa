<?php
/** Standalone privacy document; content remains editable in WordPress. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'hdc-lp hdc-lp-privacy-page' ); ?>>
<?php wp_body_open(); ?>
<main class="hdc-lp-privacy" id="hdc-lp-privacy">
	<div class="hdc-lp-privacy__container">
		<?php while ( have_posts() ) : the_post(); ?>
		<article class="hdc-lp-privacy__document" aria-labelledby="hdc-lp-privacy-title">
			<h1 id="hdc-lp-privacy-title"><?php echo esc_html( get_the_title() ); ?></h1>
			<div class="hdc-lp-privacy__content"><?php the_content(); ?></div>
		</article>
		<?php endwhile; ?>
	</div>
</main>
<?php wp_footer(); ?>
</body>
</html>
