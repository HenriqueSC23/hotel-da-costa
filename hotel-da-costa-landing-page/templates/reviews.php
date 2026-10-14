<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$hdc_lp_reviews = HDC_LP_Google_Reviews::public_data();
?>
<section class="hdc-lp-reviews" id="hdc-lp-reviews" data-hdc-lp-reviews aria-labelledby="hdc-lp-reviews-title">
	<picture class="hdc-lp-reviews__background" aria-hidden="true">
		<source
			media="(max-width: 767px)"
			type="image/webp"
			srcset="<?php echo esc_url( $hdc_lp_asset_url . 'reviews-background-mobile-480.webp' ); ?> 480w, <?php echo esc_url( $hdc_lp_asset_url . 'reviews-background-mobile-768.webp' ); ?> 768w, <?php echo esc_url( $hdc_lp_asset_url . 'reviews-background-mobile-1152.webp' ); ?> 1152w"
			sizes="100vw"
			width="768"
			height="1152"
		>
		<img
			src="<?php echo esc_url( $hdc_lp_asset_url . 'reviews-background-desktop-1920.webp' ); ?>"
			srcset="<?php echo esc_url( $hdc_lp_asset_url . 'reviews-background-desktop-1280.webp' ); ?> 1280w, <?php echo esc_url( $hdc_lp_asset_url . 'reviews-background-desktop-1920.webp' ); ?> 1920w, <?php echo esc_url( $hdc_lp_asset_url . 'reviews-background-desktop-2560.webp' ); ?> 2560w"
			sizes="100vw"
			width="1920"
			height="1280"
			alt=""
			loading="lazy"
			decoding="async"
		>
	</picture>
	<div class="hdc-lp-container hdc-lp-reviews__container">
		<header class="hdc-lp-reviews__header">
			<h2 class="hdc-lp-reviews__title" id="hdc-lp-reviews-title">Quem se hospeda, recomenda</h2>
			<p class="hdc-lp-reviews__subtitle">Atendimento atencioso, localização privilegiada e um café da manhã que conquista os hóspedes. Descubra por que o Hotel da Costa está entre os hotéis mais bem avaliados pelos viajantes.</p>
		</header>
		<div class="hdc-lp-reviews__carousel" role="region" aria-roledescription="carrossel" aria-label="Avaliações selecionadas do Google" <?php echo empty( $hdc_lp_reviews['items'] ) ? 'hidden' : ''; ?>>
			<button class="hdc-lp-reviews__control hdc-lp-reviews__control--previous" type="button" data-hdc-lp-reviews-previous aria-controls="hdc-lp-reviews-track" aria-label="Avaliação anterior" hidden><span class="hdc-lp-reviews__disc"><span class="hdc-lp-reviews__arrow hdc-lp-reviews__arrow--previous" aria-hidden="true"></span></span></button>
			<ul class="hdc-lp-reviews__track" id="hdc-lp-reviews-track" tabindex="0" aria-label="Avaliações; use as setas para navegar">
				<?php foreach ( $hdc_lp_reviews['items'] as $hdc_lp_review_index => $hdc_lp_review ) : ?>
					<li class="hdc-lp-review" data-hdc-lp-review-index="<?php echo absint( $hdc_lp_review_index ); ?>">
						<article aria-label="<?php echo esc_attr( 'Avaliação de ' . $hdc_lp_review['author'] ); ?>">
							<header class="hdc-lp-review__header">
								<span class="hdc-lp-review__avatar" aria-hidden="true"><span><?php echo esc_html( mb_substr( $hdc_lp_review['author'], 0, 1 ) ); ?></span><?php if ( $hdc_lp_review['avatar'] ) : ?><img src="<?php echo esc_url( $hdc_lp_review['avatar'] ); ?>" width="40" height="40" alt="" loading="lazy" decoding="async" referrerpolicy="no-referrer"><?php endif; ?></span>
								<div class="hdc-lp-review__identity"><h3 class="hdc-lp-review__author" title="<?php echo esc_attr( $hdc_lp_review['author'] ); ?>"><?php echo esc_html( $hdc_lp_review['author'] ); ?></h3><time class="hdc-lp-review__date" datetime="<?php echo esc_attr( $hdc_lp_review['date'] ); ?>"><?php echo esc_html( $hdc_lp_review['dateLabel'] ); ?></time></div>
							</header>
							<div class="hdc-lp-review__stars" role="img" aria-label="<?php echo esc_attr( $hdc_lp_review['rating'] . ' de 5 estrelas' ); ?>"><?php for ( $hdc_lp_star = 1; $hdc_lp_star <= 5; $hdc_lp_star++ ) : ?><span class="hdc-lp-review__star<?php echo $hdc_lp_star > $hdc_lp_review['rating'] ? ' hdc-lp-review__star--muted' : ''; ?>" aria-hidden="true"></span><?php endfor; ?></div>
							<div class="hdc-lp-review__body"><p class="hdc-lp-review__text"><?php echo esc_html( $hdc_lp_review['comment'] ?: 'Avaliação sem comentário escrito.' ); ?></p><button class="hdc-lp-review__more" type="button" data-hdc-lp-review-more="<?php echo absint( $hdc_lp_review_index ); ?>" aria-haspopup="dialog" aria-label="<?php echo esc_attr( 'Ler avaliação completa de ' . $hdc_lp_review['author'] ); ?>" hidden>Ler mais</button></div>
						</article>
					</li>
				<?php endforeach; ?>
			</ul>
			<button class="hdc-lp-reviews__control hdc-lp-reviews__control--next" type="button" data-hdc-lp-reviews-next aria-controls="hdc-lp-reviews-track" aria-label="Próxima avaliação" hidden><span class="hdc-lp-reviews__disc"><span class="hdc-lp-reviews__arrow" aria-hidden="true"></span></span></button>
		</div>
		<p class="hdc-lp-reviews__empty" role="status" <?php echo ! empty( $hdc_lp_reviews['items'] ) ? 'hidden' : ''; ?>><?php echo 'empty' === $hdc_lp_reviews['status'] ? 'Nenhuma avaliação disponível com os filtros atuais. Veja todas no Google.' : 'As avaliações estão indisponíveis no momento. Você pode consultá-las diretamente no Google.'; ?></p>
		<div class="hdc-lp-reviews__dots" role="group" aria-label="Escolher uma avaliação" hidden></div>
		<p class="hdc-lp-visually-hidden" data-hdc-lp-reviews-announcement aria-live="polite" aria-atomic="true"></p>
		<footer class="hdc-lp-reviews__source"><span>Avaliações selecionadas do Google</span><a data-hdc-lp-reviews-google href="<?php echo esc_url( $hdc_lp_reviews['googleUrl'] ); ?>" target="_blank" rel="noopener noreferrer">Ver todas no Google<span class="hdc-lp-visually-hidden"> (abre em nova aba)</span></a></footer>
	</div>
	<dialog class="hdc-lp-review-modal" data-hdc-lp-review-modal aria-labelledby="hdc-lp-review-modal-title">
		<div class="hdc-lp-review-modal__toolbar"><h2 id="hdc-lp-review-modal-title">Avaliação do Google</h2><button class="hdc-lp-review-modal__close" type="button" data-hdc-lp-review-close aria-label="Fechar avaliação" autofocus><span aria-hidden="true"></span></button></div>
		<article data-hdc-lp-review-full></article>
		<p class="hdc-lp-review-modal__source">Avaliação publicada no Google</p>
	</dialog>
	<script type="application/json" data-hdc-lp-reviews-data><?php echo wp_json_encode( $hdc_lp_reviews, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?></script>
</section>
