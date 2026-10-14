<?php
/**
 * Plugin Name: Hotel da Costa — Landing Page
 * Description: Template isolado para a landing page do Hotel da Costa by Nobile.
 * Version: 1.25.2
 * Author: Hotel da Costa
 * Text Domain: hdc-lp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HDC_LP_VERSION', '1.25.2' );
define( 'HDC_LP_FILE', __FILE__ );
define( 'HDC_LP_DIR', plugin_dir_path( __FILE__ ) );
define( 'HDC_LP_URL', plugin_dir_url( __FILE__ ) );
define( 'HDC_LP_TEMPLATE_SLUG', 'hdc-lp-landing-page.php' );
define( 'HDC_LP_PRIVACY_TEMPLATE_SLUG', 'hdc-lp-privacy-policy.php' );

require_once HDC_LP_DIR . 'includes/google-reviews.php';
require_once HDC_LP_DIR . 'includes/trustindex-reviews.php';
require_once HDC_LP_DIR . 'includes/google-reviews-admin.php';
require_once HDC_LP_DIR . 'includes/campaigns.php';
require_once HDC_LP_DIR . 'includes/campaigns-admin.php';
require_once HDC_LP_DIR . 'includes/submissions.php';
require_once HDC_LP_DIR . 'includes/submissions-admin.php';
require_once HDC_LP_DIR . 'includes/seo.php';
require_once HDC_LP_DIR . 'includes/tracking.php';
require_once HDC_LP_DIR . 'includes/tracking-admin.php';
register_activation_hook( HDC_LP_FILE, array( 'HDC_LP_Submissions', 'install' ) );
register_activation_hook( HDC_LP_FILE, array( 'HDC_LP_Google_Reviews', 'schedule' ) );
register_deactivation_hook( HDC_LP_FILE, array( 'HDC_LP_Google_Reviews', 'deactivate' ) );

/**
 * Adds the landing page template to the page editor.
 *
 * @param array<string, string> $templates Available page templates.
 * @return array<string, string>
 */
function hdc_lp_register_page_template( $templates ) {
	$templates[ HDC_LP_TEMPLATE_SLUG ] = __( 'Landing Page — Hotel da Costa', 'hdc-lp' );
	$templates[ HDC_LP_PRIVACY_TEMPLATE_SLUG ] = __( 'Política de Privacidade — Hotel da Costa', 'hdc-lp' );

	return $templates;
}
add_filter( 'theme_page_templates', 'hdc_lp_register_page_template' );

/**
 * Loads the plugin template without changing the active theme.
 *
 * @param string $template Resolved template path.
 * @return string
 */
function hdc_lp_load_page_template( $template ) {
	if ( is_page() && HDC_LP_PRIVACY_TEMPLATE_SLUG === get_page_template_slug() ) {
		return HDC_LP_DIR . 'templates/privacy-policy.php';
	}
	if ( is_page() && HDC_LP_TEMPLATE_SLUG === get_page_template_slug() ) {
		return HDC_LP_DIR . 'templates/landing-page.php';
	}

	return $template;
}
add_filter( 'template_include', 'hdc_lp_load_page_template', 99 );

/**
 * Checks whether the current request uses the plugin template.
 */
function hdc_lp_is_landing_page() {
	return is_page() && HDC_LP_TEMPLATE_SLUG === get_page_template_slug();
}

/**
 * Loads assets only on the landing page.
 */
function hdc_lp_enqueue_assets() {
	if ( ! hdc_lp_is_landing_page() ) {
		return;
	}

	$stylesheet_path = HDC_LP_DIR . 'assets/css/landing-page.css';
	$stylesheet_version = file_exists( $stylesheet_path ) ? (string) filemtime( $stylesheet_path ) : HDC_LP_VERSION;
	$script_path = HDC_LP_DIR . 'assets/js/landing-page.js';
	$script_version = file_exists( $script_path ) ? (string) filemtime( $script_path ) : HDC_LP_VERSION;

	wp_enqueue_style(
		'hdc-lp-styles',
		HDC_LP_URL . 'assets/css/landing-page.css',
		array(),
		$stylesheet_version
	);

	wp_enqueue_script(
		'hdc-lp-interactions',
		HDC_LP_URL . 'assets/js/landing-page.js',
		array(),
		$script_version,
		true
	);

	wp_enqueue_style(
		'hdc-lp-accommodation-details',
		HDC_LP_URL . 'assets/css/accommodation-details.css',
		array( 'hdc-lp-styles' ),
		(string) filemtime( HDC_LP_DIR . 'assets/css/accommodation-details.css' )
	);
	wp_enqueue_script(
		'hdc-lp-accommodation-details',
		HDC_LP_URL . 'assets/js/accommodation-details.js',
		array( 'hdc-lp-interactions' ),
		(string) filemtime( HDC_LP_DIR . 'assets/js/accommodation-details.js' ),
		true
	);
	wp_enqueue_style( 'hdc-lp-reviews', HDC_LP_URL . 'assets/css/reviews.css', array( 'hdc-lp-styles' ), (string) filemtime( HDC_LP_DIR . 'assets/css/reviews.css' ) );
	wp_enqueue_script( 'hdc-lp-reviews', HDC_LP_URL . 'assets/js/reviews.js', array(), (string) filemtime( HDC_LP_DIR . 'assets/js/reviews.js' ), true );
	wp_enqueue_style( 'hdc-lp-footer', HDC_LP_URL . 'assets/css/footer.css', array( 'hdc-lp-styles' ), (string) filemtime( HDC_LP_DIR . 'assets/css/footer.css' ) );
	wp_enqueue_style( 'hdc-lp-lead-form', HDC_LP_URL . 'assets/css/lead-form.css', array( 'hdc-lp-styles' ), (string) filemtime( HDC_LP_DIR . 'assets/css/lead-form.css' ) );
	wp_enqueue_script( 'hdc-lp-lead-form', HDC_LP_URL . 'assets/js/lead-form.js', array(), (string) filemtime( HDC_LP_DIR . 'assets/js/lead-form.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'hdc_lp_enqueue_assets' );

/**
 * Preloads the first-fold photograph only on the landing page.
 */
function hdc_lp_preload_hero_image() {
	if ( ! hdc_lp_is_landing_page() ) {
		return;
	}

	printf(
		'<link rel="preload" href="%1$s" as="image" type="image/webp" media="%2$s">' . "\n",
		esc_url( HDC_LP_URL . 'assets/images/hero-hotel-mobile.webp' ),
		esc_attr( '(max-width: 767px)' )
	);

	printf(
		'<link rel="preload" href="%1$s" as="image" type="image/webp" media="%2$s">' . "\n",
		esc_url( HDC_LP_URL . 'assets/images/hero-hotel.webp' ),
		esc_attr( '(min-width: 768px)' )
	);
}
add_action( 'wp_head', 'hdc_lp_preload_hero_image', 2 );

/**
 * Adds a page-specific body class for a clean theme reset.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function hdc_lp_add_body_class( $classes ) {
	if ( hdc_lp_is_landing_page() ) {
		$classes[] = 'hdc-lp-page';
	}

	return $classes;
}
add_filter( 'body_class', 'hdc_lp_add_body_class' );

/** Loads the privacy document stylesheet exclusively on its own template. */
function hdc_lp_enqueue_privacy_assets() {
	if ( ! is_page() || HDC_LP_PRIVACY_TEMPLATE_SLUG !== get_page_template_slug() ) {
		return;
	}
	wp_enqueue_style( 'hdc-lp-privacy', HDC_LP_URL . 'assets/css/privacy-policy.css', array(), HDC_LP_VERSION );
}
add_action( 'wp_enqueue_scripts', 'hdc_lp_enqueue_privacy_assets' );
