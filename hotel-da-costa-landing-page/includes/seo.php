<?php
/** SEO isolated to requests using the landing page template. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Metadata derived from the approved copy; never from campaign query strings. */
function hdc_lp_seo_metadata() {
	return array(
		'title'       => 'Hotel da Costa by Nobile | Orla de Atalaia, Aracaju - SE',
		'description' => 'Hospede-se na Orla de Atalaia, em Aracaju - SE, com acomodações renovadas, piscinas, café da manhã e gastronomia. Reserve via WhatsApp.',
		'url'         => get_permalink( get_queried_object_id() ),
		'image'       => HDC_LP_URL . 'assets/images/seo-hotel-da-costa-1200x630.jpg',
		'image_alt'   => 'Hotel da Costa by Nobile com piscina na Orla de Atalaia, em Aracaju - SE',
	);
}

/** Consistent browser title, including themes without title-tag support. */
function hdc_lp_seo_document_title( $title ) {
	return hdc_lp_is_landing_page() ? hdc_lp_seo_metadata()['title'] : $title;
}
add_filter( 'pre_get_document_title', 'hdc_lp_seo_document_title', 99 );

/** Keep the LP as the single metadata owner without changing other pages. */
function hdc_lp_seo_prepare_head() {
	if ( ! hdc_lp_is_landing_page() ) {
		return;
	}

	remove_action( 'wp_head', '_wp_render_title_tag', 1 );
	remove_action( 'wp_head', '_block_template_render_title_tag', 1 );
	remove_action( 'wp_head', 'rel_canonical' );
	// Rank Math's head is replaced only for this template, not its sitemap or admin.
	remove_all_actions( 'rank_math/head' );
	add_action( 'wp_head', 'wp_robots', 1 );
	add_action( 'wp_head', 'hdc_lp_seo_render_head', 1 );
}
// Run after template resolution: block themes register their title after `wp`.
add_action( 'wp_head', 'hdc_lp_seo_prepare_head', 0 );

/** Yoast metadata presenters must not duplicate the template's metadata. */
function hdc_lp_seo_presenters( $presenters ) {
	return hdc_lp_is_landing_page() ? array() : $presenters;
}
add_filter( 'wpseo_frontend_presenters', 'hdc_lp_seo_presenters', 99 );

/** Preserve indexing restrictions, including staging and per-page SEO settings. */
function hdc_lp_seo_robots( $robots ) {
	if ( ! hdc_lp_is_landing_page() ) {
		return $robots;
	}

	$post        = get_queried_object();
	$host        = strtolower( (string) wp_parse_url( home_url( '/' ), PHP_URL_HOST ) );
	$local       = in_array( $host, array( 'localhost', '127.0.0.1', '[::1]', '::1' ), true ) || preg_match( '/\.(test|localhost|local)$/', $host );
	$rank_robots = (array) get_post_meta( get_queried_object_id(), 'rank_math_robots', true );
	$restricted  = ! get_option( 'blog_public' ) || 'production' !== wp_get_environment_type() || $local || is_preview()
		|| ! $post || 'publish' !== $post->post_status || ! empty( $post->post_password )
		|| '1' === (string) get_post_meta( get_queried_object_id(), '_yoast_wpseo_meta-robots-noindex', true )
		|| in_array( 'noindex', $rank_robots, true );

	if ( $restricted ) {
		unset( $robots['index'] );
		$robots['noindex'] = true;
	}
	if ( '1' === (string) get_post_meta( get_queried_object_id(), '_yoast_wpseo_meta-robots-nofollow', true ) || in_array( 'nofollow', $rank_robots, true ) ) {
		unset( $robots['follow'] );
		$robots['nofollow'] = true;
	}
	$robots['max-image-preview'] = 'large';
	return $robots;
}
add_filter( 'wp_robots', 'hdc_lp_seo_robots', 99 );

/** Search and social metadata rendered on the server, with escaped outputs. */
function hdc_lp_seo_render_head() {
	if ( ! hdc_lp_is_landing_page() ) {
		return;
	}

	$meta = hdc_lp_seo_metadata();
	echo '<title>' . esc_html( $meta['title'] ) . "</title>\n";
	echo '<link rel="canonical" href="' . esc_url( $meta['url'] ) . "\">\n";
	$tags = array(
		array( 'name', 'description', $meta['description'] ),
		array( 'property', 'og:type', 'website' ),
		array( 'property', 'og:locale', 'pt_BR' ),
		array( 'property', 'og:site_name', 'Hotel da Costa by Nobile' ),
		array( 'property', 'og:title', $meta['title'] ),
		array( 'property', 'og:description', $meta['description'] ),
		array( 'property', 'og:url', $meta['url'] ),
		array( 'property', 'og:image', $meta['image'] ),
		array( 'property', 'og:image:type', 'image/jpeg' ),
		array( 'property', 'og:image:width', '1200' ),
		array( 'property', 'og:image:height', '630' ),
		array( 'property', 'og:image:alt', $meta['image_alt'] ),
		array( 'name', 'twitter:card', 'summary_large_image' ),
		array( 'name', 'twitter:title', $meta['title'] ),
		array( 'name', 'twitter:description', $meta['description'] ),
		array( 'name', 'twitter:image', $meta['image'] ),
		array( 'name', 'twitter:image:alt', $meta['image_alt'] ),
	);
	foreach ( $tags as $tag ) {
		printf( '<meta %1$s="%2$s" content="%3$s">' . "\n", esc_attr( $tag[0] ), esc_attr( $tag[1] ), esc_attr( $tag[2] ) );
	}

	$hotel_id = 'https://hoteldacosta.com.br/#hotel';
	$schema   = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(
			array(
				'@type'       => 'Hotel',
				'@id'         => $hotel_id,
				'name'        => 'Hotel da Costa by Nobile',
				'url'         => 'https://hoteldacosta.com.br/',
				'description' => $meta['description'],
				'image'       => $meta['image'],
				'address'     => array( '@type' => 'PostalAddress', 'addressLocality' => 'Aracaju', 'addressRegion' => 'SE', 'addressCountry' => 'BR' ),
			),
			array(
				'@type'      => 'WebPage',
				'@id'        => $meta['url'] . '#webpage',
				'url'        => $meta['url'],
				'name'       => $meta['title'],
				'description' => $meta['description'],
				'inLanguage' => 'pt-BR',
				'mainEntity' => array( '@id' => $hotel_id ),
				'primaryImageOfPage' => array( '@type' => 'ImageObject', 'url' => $meta['image'], 'width' => 1200, 'height' => 630 ),
			),
		),
	);
	echo '<script type="application/ld+json" id="hdc-lp-seo-schema">' . wp_json_encode( $schema, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . "</script>\n";
}
