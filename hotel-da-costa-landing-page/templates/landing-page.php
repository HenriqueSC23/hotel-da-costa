<?php
/**
 * Landing page template.
 *
 * @package HotelDaCostaLandingPage
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


$hdc_lp_asset_url     = HDC_LP_URL . 'assets/images/';
$hdc_lp_location_map_url = 'https://maps.google.com/maps?q=Hotel%20da%20Costa%20by%20Nobile%2C%20Av.%20Santos%20Dumont%2C%201835%2C%20Aracaju%2C%20SE&z=15&output=embed';
$hdc_lp_location_points = array(
	array( 'title' => 'Praça de Eventos', 'distance' => 'A aproximadamente 300 m', 'icon' => 'lighthouse' ),
	array( 'title' => 'Passarela do Caranguejo', 'distance' => 'A 3 km', 'icon' => 'map-pin-area' ),
	array( 'title' => 'Aeroporto de Aracaju', 'distance' => 'A 7 km', 'icon' => 'airplane' ),
	array( 'title' => 'Bares e restaurantes', 'distance' => 'Na região', 'icon' => 'fork-knife' ),
);
$hdc_lp_gallery = array(
	array( 'key' => 'facade', 'alt' => 'Fachada e piscina do Hotel da Costa ao anoitecer' ),
	array( 'key' => 'room', 'alt' => 'Hóspede descansando em uma acomodação do Hotel da Costa' ),
	array( 'key' => 'gym', 'alt' => 'Academia do Hotel da Costa com equipamentos de musculação' ),
	array( 'key' => 'breakfast', 'alt' => 'Família aproveitando o café da manhã no Hotel da Costa' ),
	array( 'key' => 'restaurant', 'alt' => 'Salão do Restaurante Villa Ravello com mesas preparadas' ),
	array( 'key' => 'games', 'alt' => 'Criança jogando videogame no espaço de jogos do Hotel da Costa' ),
	array( 'key' => 'kids', 'alt' => 'Espaço infantil com escorregador e brinquedos do Hotel da Costa', 'height' => 686 ),
	array( 'key' => 'baby-kitchen', 'alt' => 'Copa Baby do Hotel da Costa com bancada e equipamentos', 'height' => 799 ),
	array( 'key' => 'pool', 'alt' => 'Mergulho na piscina do Hotel da Costa', 'height' => 422 ),
	array( 'key' => 'lobby', 'alt' => 'Lobby do Hotel da Costa com balcão e área de convivência' ),
	array( 'key' => 'gastronomy', 'alt' => 'Hóspede aproveitando uma refeição e uma taça de vinho no restaurante' ),
	array( 'key' => 'room-service', 'alt' => 'Família aproveitando o café da manhã servido na acomodação' ),
);
$hdc_lp_reviewers     = array( '01', '02', '03', '04', '05' );
$hdc_lp_promotions = HDC_LP_Campaigns::public_items();
$hdc_lp_services        = array(
	array(
		'title'       => 'Café da manhã',
		'image'       => 'services-breakfast-portrait.webp',
		'alt'         => 'Pães, bolos e café servidos no café da manhã do Hotel da Costa',
		'width'       => 960,
		'height'      => 1272,
	),
	array(
		'title'       => 'Restaurante Villa Ravello',
		'image'       => 'services-restaurant-portrait.webp',
		'alt'         => 'Salão do Restaurante Villa Ravello com mesas preparadas',
		'width'       => 960,
		'height'      => 1272,
	),
	array(
		'title'       => 'Piscinas e bar',
		'image'       => 'services-pool-portrait.webp',
		'alt'         => 'Piscina, espreguiçadeiras e bar do Hotel da Costa',
		'width'       => 960,
		'height'      => 1281,
	),
	array(
		'title'       => 'Espaço Kids',
		'image'       => 'services-kids-portrait.webp',
		'alt'         => 'Espaço Kids do hotel com brinquedos e cama elástica',
		'width'       => 960,
		'height'      => 1280,
	),
	array(
		'title'       => 'Academia',
		'image'       => 'services-gym-portrait.webp',
		'alt'         => 'Academia do hotel com esteiras e equipamentos de musculação',
		'width'       => 960,
		'height'      => 1280,
	),
	array(
		'title'       => 'Lobby Bar',
		'image'       => 'services-lobby-portrait.webp',
		'alt'         => 'Lobby Bar do Hotel da Costa com balcão e poltronas',
		'width'       => 960,
		'height'      => 1272,
	),
	array(
		'title'       => 'Eventos e reuniões',
		'image'       => 'services-events-portrait.webp',
		'alt'         => 'Ambiente interno do Hotel da Costa com espaços para encontros',
		'width'       => 960,
		'height'      => 1440,
	),
	array(
		'title'       => 'Copa Baby',
		'image'       => 'services-baby-portrait.webp',
		'alt'         => 'Bebê recebendo cuidados em um trocador',
		'width'       => 960,
		'height'      => 1280,
	),
);
$hdc_lp_service_pages = range( 1, count( $hdc_lp_services ) );
$hdc_lp_accommodations = array(
	array(
		'title'       => 'Suíte Premium',
		'tag'         => 'A joia da casa · 40 m²',
		'image'       => 'accommodation-suite-premium.webp',
		'alt'         => 'Suíte Premium do Hotel da Costa com cama de casal e varanda',
		'space'       => '40 m²',
		'bed'         => 'cama de casal e sofá-cama',
		'view'        => 'Varanda vista mar com rede',
		'description' => 'A joia da casa: aproximadamente 40 m² divididos em quarto, sala de estar e varanda com rede e vista para o mar.',
		'url'         => 'https://hoteldacosta.com.br/acomodacao/suite-premium/',
	),
	array(
		'title'       => 'Luxo Casal',
		'tag'         => 'Vista mar + varanda',
		'image'       => 'accommodation-luxo-casal.webp',
		'alt'         => 'Acomodação Luxo Casal do Hotel da Costa',
		'space'       => '22 m²',
		'bed'         => 'Cama queen size',
		'view'        => 'Varanda com vista mar',
		'description' => 'Uma acomodação para viver a Orla de Atalaia a dois, com varanda, vista para o mar e comodidades que deixam a experiência ainda mais especial.',
		'url'         => 'https://hoteldacosta.com.br/acomodacao/luxo-casal/',
	),
	array(
		'title'       => 'Luxo Triplo',
		'tag'         => 'Vista mar + varanda',
		'image'       => 'accommodation-luxo-triplo.webp',
		'alt'         => 'Acomodação Luxo Triplo do Hotel da Costa',
		'space'       => '22 m²',
		'bed'         => 'Casal + solteiro ou 3 de solteiro',
		'view'        => 'Varanda com vista mar',
		'description' => 'Conforto e vista para o mar para pequenas famílias ou grupos de até três pessoas.',
		'url'         => 'https://hoteldacosta.com.br/acomodacao/luxo-triplo/',
	),
	array(
		'title'       => 'Executivo',
		'tag'         => 'Vista mar',
		'image'       => 'accommodation-executivo.webp',
		'alt'         => 'Acomodação Executivo do Hotel da Costa',
		'space'       => '20 m²',
		'bed'         => 'Cama king size',
		'view'        => 'Vista mar, sem varanda',
		'description' => 'Uma opção para quem viaja a trabalho sem abrir mão de conforto, espaço e vista para o mar.',
		'url'         => 'https://hoteldacosta.com.br/acomodacao/executivo/',
	),
	array(
		'title'       => 'Superior Casal',
		'tag'         => 'Vista residencial',
		'image'       => 'accommodation-superior-casal.webp',
		'alt'         => 'Acomodação Superior Casal do Hotel da Costa',
		'space'       => '20 m²',
		'bed'         => 'Cama de casal',
		'view'        => 'Janela e vista residencial',
		'description' => 'O conforto essencial para aproveitar Aracaju a dois, com ambiente funcional e vista residencial.',
		'url'         => 'https://hoteldacosta.com.br/acomodacao/superior-casal/',
	),
	array(
		'title'       => 'Superior Twin',
		'tag'         => 'Vista residencial',
		'image'       => 'accommodation-superior-twin.webp',
		'alt'         => 'Acomodação Superior Twin do Hotel da Costa',
		'space'       => '25 m²',
		'bed'         => 'Duas camas de solteiro',
		'view'        => 'Janela e vista residencial',
		'description' => 'Espaço e praticidade para colegas de viagem ou de trabalho.',
		'url'         => 'https://hoteldacosta.com.br/acomodacao/superior-twin/',
	),
	array(
		'title'       => 'Superior Triplo Casal',
		'tag'         => 'Família',
		'image'       => 'accommodation-superior-triplo-casal.webp',
		'alt'         => 'Acomodação Superior Triplo Casal do Hotel da Costa',
		'space'       => '25 m²',
		'bed'         => 'Casal + uma cama de solteiro',
		'view'        => 'Janela com iluminação natural',
		'description' => 'Uma configuração versátil para famílias pequenas que desejam mais espaço durante a estadia.',
		'url'         => 'https://hoteldacosta.com.br/acomodacao/superior-triplo-casal/',
	),
	array(
		'title'       => 'Superior Triplo Solteiro',
		'tag'         => 'Entre amigos',
		'image'       => 'accommodation-superior-triplo-solteiro.webp',
		'alt'         => 'Acomodação Superior Triplo Solteiro do Hotel da Costa',
		'space'       => '20 m²',
		'bed'         => 'Três camas de solteiro',
		'view'        => 'Janela com iluminação natural',
		'description' => 'Uma escolha prática para viagens entre amigos ou colegas.',
		'url'         => 'https://hoteldacosta.com.br/acomodacao/superior-triplo-solteiro/',
	),
	array(
		'title'       => 'Superior Quádruplo',
		'tag'         => 'Família grande',
		'image'       => 'accommodation-superior-quadruplo.webp',
		'alt'         => 'Acomodação Superior Quádruplo do Hotel da Costa',
		'space'       => '30 m²',
		'bed'         => '4 de solteiro ou casal + 2 de solteiro',
		'view'        => 'Janela com iluminação natural',
		'description' => 'O maior apartamento da categoria Superior, pensado para acomodar famílias e grupos com conforto.',
		'url'         => 'https://hoteldacosta.com.br/acomodacao/superior-quadruplo/',
	),
	array(
		'title'       => 'Standard Casal',
		'tag'         => 'Essencial',
		'image'       => 'accommodation-standard-casal.webp',
		'alt'         => 'Acomodação Standard Casal do Hotel da Costa',
		'space'       => '12 m²',
		'bed'         => 'Cama de casal',
		'view'        => 'Janela com iluminação natural',
		'description' => 'Compacto e funcional, ideal para hospedagens curtas em que o quarto é o ponto de partida para aproveitar a cidade.',
		'url'         => 'https://hoteldacosta.com.br/acomodacao/standard-casal/',
	),
	array(
		'title'       => 'Standard Twin',
		'tag'         => 'Essencial',
		'image'       => 'accommodation-standard-twin.webp',
		'alt'         => 'Acomodação Standard Twin do Hotel da Costa',
		'space'       => '12 m²',
		'bed'         => 'Duas camas de solteiro',
		'view'        => 'Janela com iluminação natural',
		'description' => 'Uma opção essencial e prática para duas pessoas que preferem camas separadas.',
		'url'         => 'https://hoteldacosta.com.br/acomodacao/standard-twin/',
	),
);
$hdc_lp_accommodation_pages = range( 1, count( $hdc_lp_accommodations ) );
$hdc_lp_differentials = array(
	array(
		'key'         => 'orla',
		'background'  => 'differentials-orla-clean',
		'width'       => 1536,
		'height'      => 1024,
		'mobile_background' => 'differentials-orla-night-pool',
		'title'       => 'Na Orla de Atalaia',
		'description' => 'Hospede-se em uma das regiões mais conhecidas de Aracaju, com fácil acesso a restaurantes, opções de lazer e vida noturna.',
	),
	array(
		'key'         => 'accessibility',
		'background'  => 'differentials-accessibility',
		'width'       => 1920,
		'height'      => 1272,
		'mobile_background' => 'differentials-accessibility-chair',
		'title'       => 'Estrutura acessível',
		'description' => 'Rampas, instalações acessíveis e equipamento de acessibilidade na piscina ajudam a tornar os espaços mais confortáveis e inclusivos.',
	),
	array(
		'key'         => 'gastronomy',
		'background'  => 'differentials-gastronomy-salad',
		'width'       => 1920,
		'height'      => 1280,
		'mobile_background' => 'differentials-gastronomy-plate',
		'title'       => 'Gastronomia',
		'description' => 'Do café da manhã com tapioca preparada na hora ao jantar no Villa Ravello, você encontra sabor e praticidade sem precisar sair.',
	),
);
?>
<!doctype html>
<html lang="pt-BR">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<main class="hdc-lp" id="hdc-lp-main">
	<section class="hdc-lp-hero" aria-labelledby="hdc-lp-hero-title">
		<div class="hdc-lp-hero__visual">
			<img
				class="hdc-lp-hero__logo"
				src="<?php echo esc_url( $hdc_lp_asset_url . 'hotel-da-costa-logo-240.png' ); ?>"
				width="120"
				height="120"
				alt="<?php esc_attr_e( 'Hotel da Costa by Nobile', 'hdc-lp' ); ?>"
				fetchpriority="high"
				decoding="async"
			>

			<div class="hdc-lp-hero__content-shell">
				<div class="hdc-lp-container">
					<div class="hdc-lp-hero__content">
						<div class="hdc-lp-hero__text">
							<h1 class="hdc-lp-hero__title" id="hdc-lp-hero-title">A Orla de Atalaia na sua janela</h1>
							<p class="hdc-lp-hero__description">Hospede-se na Orla de Atalaia e viva Aracaju - SE com piscinas, gastronomia, conforto e uma estrutura completa para aproveitar cada momento da viagem.</p>
						</div>
						<button
							class="hdc-lp-button hdc-lp-button--whatsapp"
						 type="button" data-hdc-lp-lead-open aria-haspopup="dialog" aria-controls="hdc-lp-lead-modal" aria-label="Reservar via WhatsApp: preencher formulário">
							<span>RESERVAR VIA WHATSAPP</span>
							<img
								class="hdc-lp-button__icon"
								src="<?php echo esc_url( $hdc_lp_asset_url . 'whatsapp-logo.svg' ); ?>"
								width="24"
								height="24"
								alt=""
								aria-hidden="true"
							>
						</button>
					</div>
				</div>
			</div>

			<aside class="hdc-lp-hero__proof" aria-label="<?php esc_attr_e( 'Reconhecimentos e avaliações do hotel', 'hdc-lp' ); ?>">
				<div class="hdc-lp-hero__award">
					<div class="hdc-lp-hero__award-mark" aria-hidden="true">
						<img src="<?php echo esc_url( $hdc_lp_asset_url . 'tripadvisor-logo.svg' ); ?>" width="74" height="48" alt="">
						<strong>TOP 10%</strong>
					</div>
					<div class="hdc-lp-hero__award-copy">
						<strong>Travellers’ Choice 2026</strong>
						<span>Tripadvisor · entre os 10% melhores do mundo</span>
					</div>
				</div>

				<div class="hdc-lp-hero__reviews">
					<div class="hdc-lp-hero__reviewers" aria-hidden="true">
						<?php foreach ( $hdc_lp_reviewers as $hdc_lp_reviewer ) : ?>
							<img
								src="<?php echo esc_url( $hdc_lp_asset_url . 'reviewer-' . $hdc_lp_reviewer . '.png' ); ?>"
								width="58"
								height="58"
								alt=""
								decoding="async"
							>
						<?php endforeach; ?>
					</div>
					<p>Média 4.5 no Google<br>em mais de 2400 avaliações</p>
				</div>
			</aside>
		</div>
	</section>

	<section class="hdc-lp-welcome" aria-labelledby="hdc-lp-welcome-title">
		<div class="hdc-lp-container">
			<div class="hdc-lp-welcome__layout">
				<div class="hdc-lp-welcome__content">
					<h2 class="hdc-lp-welcome__title" id="hdc-lp-welcome-title" data-hdc-lp-scroll-reveal>Bem-vindo ao seu lugar na Orla de Atalaia</h2>
					<p class="hdc-lp-welcome__subtitle" data-hdc-lp-scroll-reveal>Conforto para descansar. Estrutura para aproveitar. Localização para viver o melhor de Aracaju.</p>

					<div class="hdc-lp-welcome__details">
						<p class="hdc-lp-welcome__description" data-hdc-lp-scroll-reveal>Na Orla de Atalaia, o Hotel da Costa by Nobile reúne tudo o que torna uma estadia mais leve: acomodações renovadas, piscinas para adultos e crianças, café da manhã completo, restaurante, academia e espaços pensados para toda a família. Você fica em uma das regiões mais conhecidas de Aracaju, próximo aos principais restaurantes e à vida noturna da cidade, com a hospitalidade e o padrão de qualidade Nobile em cada detalhe.</p>
						<button
							class="hdc-lp-button hdc-lp-button--whatsapp"
							data-hdc-lp-scroll-reveal
						 type="button" data-hdc-lp-lead-open aria-haspopup="dialog" aria-controls="hdc-lp-lead-modal" aria-label="Reservar via WhatsApp: preencher formulário">
							<span>RESERVAR VIA WHATSAPP</span>
							<img
								class="hdc-lp-button__icon"
								src="<?php echo esc_url( $hdc_lp_asset_url . 'whatsapp-logo.svg' ); ?>"
								width="24"
								height="24"
								alt=""
								aria-hidden="true"
							>
						</button>
					</div>
				</div>

				<div class="hdc-lp-welcome__gallery" role="group" aria-label="<?php esc_attr_e( 'Piscina e restaurante do Hotel da Costa', 'hdc-lp' ); ?>">
					<img
						class="hdc-lp-welcome__image hdc-lp-welcome__image--pool"
						data-hdc-lp-scroll-reveal
						src="<?php echo esc_url( $hdc_lp_asset_url . 'welcome-pool.webp' ); ?>"
						width="938"
						height="900"
						alt="<?php esc_attr_e( 'Piscina do Hotel da Costa com a fachada do hotel ao fundo', 'hdc-lp' ); ?>"
						loading="lazy"
						decoding="async"
					>
					<img
						class="hdc-lp-welcome__image hdc-lp-welcome__image--restaurant"
						data-hdc-lp-scroll-reveal
						src="<?php echo esc_url( $hdc_lp_asset_url . 'welcome-restaurant.webp' ); ?>"
						width="1000"
						height="853"
						alt="<?php esc_attr_e( 'Salão do restaurante do Hotel da Costa com mesas preparadas', 'hdc-lp' ); ?>"
						loading="lazy"
						decoding="async"
					>
				</div>
			</div>
		</div>
	</section>

	<?php if ( $hdc_lp_promotions ) : ?>
	<section class="hdc-lp-promotions" aria-labelledby="hdc-lp-promotions-title">
		<div class="hdc-lp-container hdc-lp-promotions__container">
			<header class="hdc-lp-promotions__header">
				<h2 class="hdc-lp-promotions__title" id="hdc-lp-promotions-title" data-hdc-lp-scroll-reveal>Reserve direto e aproveite condições especiais</h2>
				<p class="hdc-lp-promotions__subtitle" data-hdc-lp-scroll-reveal>Fale com a nossa central de reservas para encontrar a melhor opção para a sua estadia.</p>
			</header>

			<div
				class="hdc-lp-promotions__carousel"
				data-hdc-lp-carousel
				data-hdc-lp-scroll-reveal
				role="region"
				aria-roledescription="<?php esc_attr_e( 'carrossel', 'hdc-lp' ); ?>"
				aria-label="<?php esc_attr_e( 'Promoções e condições especiais', 'hdc-lp' ); ?>"
			>
				<button
					class="hdc-lp-promotions__control hdc-lp-promotions__control--previous"
					type="button"
					data-hdc-lp-carousel-previous
					aria-controls="hdc-lp-promotions-track"
					aria-label="<?php esc_attr_e( 'Ver promoção anterior', 'hdc-lp' ); ?>"
				>
					<img src="<?php echo esc_url( $hdc_lp_asset_url . 'promotion-arrow-previous.svg' ); ?>" width="48" height="48" alt="" aria-hidden="true">
				</button>

				<div
					class="hdc-lp-promotions__track"
					id="hdc-lp-promotions-track"
					data-hdc-lp-carousel-track
					role="list"
					tabindex="0"
				>
					<?php foreach ( $hdc_lp_promotions as $hdc_lp_promotion ) : ?>
						<div class="hdc-lp-promotions__card" role="listitem">
							<img
								class="hdc-lp-promotions__image"
								src="<?php echo esc_url( $hdc_lp_promotion['url'] ); ?>"
								srcset="<?php echo esc_attr( $hdc_lp_promotion['srcset'] ); ?>"
								sizes="<?php echo esc_attr( $hdc_lp_promotion['sizes'] ); ?>"
								width="<?php echo esc_attr( $hdc_lp_promotion['width'] ); ?>"
								height="<?php echo esc_attr( $hdc_lp_promotion['height'] ); ?>"
								alt="<?php echo esc_attr( $hdc_lp_promotion['alt'] ); ?>"
								loading="lazy"
								decoding="async"
								draggable="false"
							>
						</div>
					<?php endforeach; ?>
				</div>

				<button
					class="hdc-lp-promotions__control hdc-lp-promotions__control--next"
					type="button"
					data-hdc-lp-carousel-next
					aria-controls="hdc-lp-promotions-track"
					aria-label="<?php esc_attr_e( 'Ver próxima promoção', 'hdc-lp' ); ?>"
				>
					<img src="<?php echo esc_url( $hdc_lp_asset_url . 'promotion-arrow-next.svg' ); ?>" width="48" height="48" alt="" aria-hidden="true">
				</button>
			</div>

			<button
				class="hdc-lp-button hdc-lp-button--whatsapp"
				data-hdc-lp-scroll-reveal
			 type="button" data-hdc-lp-lead-open aria-haspopup="dialog" aria-controls="hdc-lp-lead-modal" aria-label="Reservar via WhatsApp: preencher formulário">
				<span>RESERVAR VIA WHATSAPP</span>
				<img
					class="hdc-lp-button__icon"
					src="<?php echo esc_url( $hdc_lp_asset_url . 'promotion-whatsapp.svg' ); ?>"
					width="24"
					height="24"
					alt=""
					aria-hidden="true"
				>
			</button>
		</div>
	</section>

	<?php endif; ?>
	<section class="hdc-lp-services" id="hdc-lp-services" aria-labelledby="hdc-lp-services-title">
		<div class="hdc-lp-container hdc-lp-services__container">
			<header class="hdc-lp-services__header">
				<h2 class="hdc-lp-services__title" id="hdc-lp-services-title" data-hdc-lp-scroll-reveal>Tudo o que você precisa, sem sair do hotel</h2>
				<p class="hdc-lp-services__subtitle" data-hdc-lp-scroll-reveal>Da primeira refeição do dia ao último momento de descanso, uma estrutura completa para tornar sua viagem mais prática e prazerosa.</p>
			</header>

			<div
				class="hdc-lp-services__carousel"
				data-hdc-lp-services-carousel
				data-hdc-lp-scroll-reveal
				role="region"
				aria-roledescription="<?php esc_attr_e( 'carrossel', 'hdc-lp' ); ?>"
				aria-label="<?php esc_attr_e( 'Serviços e estrutura do Hotel da Costa, 8 itens', 'hdc-lp' ); ?>"
			>
				<div
					class="hdc-lp-services__track"
					id="hdc-lp-services-track"
					data-hdc-lp-services-track
					role="list"
					tabindex="0"
				>
					<?php foreach ( $hdc_lp_services as $hdc_lp_service ) : ?>
						<article class="hdc-lp-services__card" role="listitem">
							<img
								class="hdc-lp-services__image"
								src="<?php echo esc_url( $hdc_lp_asset_url . $hdc_lp_service['image'] ); ?>"
								width="<?php echo esc_attr( (string) $hdc_lp_service['width'] ); ?>"
								height="<?php echo esc_attr( (string) $hdc_lp_service['height'] ); ?>"
								alt="<?php echo esc_attr( $hdc_lp_service['alt'] ); ?>"
								loading="lazy"
								decoding="async"
							>
							<h3 class="hdc-lp-services__card-title"><?php echo esc_html( $hdc_lp_service['title'] ); ?></h3>
						</article>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="hdc-lp-services__pagination" role="group" aria-label="<?php esc_attr_e( 'Navegação do carrossel de serviços', 'hdc-lp' ); ?>">
				<?php foreach ( $hdc_lp_service_pages as $hdc_lp_service_page ) : ?>
					<button
						class="hdc-lp-services__dot<?php echo 1 === $hdc_lp_service_page ? ' hdc-lp-services__dot--active' : ''; ?>"
						type="button"
						data-hdc-lp-services-dot
						data-hdc-lp-services-page="<?php echo esc_attr( (string) ( $hdc_lp_service_page - 1 ) ); ?>"
						aria-controls="hdc-lp-services-track"
						aria-label="<?php echo esc_attr( sprintf( 'Exibir o serviço %1$d de %2$d: %3$s', $hdc_lp_service_page, count( $hdc_lp_services ), $hdc_lp_services[ $hdc_lp_service_page - 1 ]['title'] ) ); ?>"
						<?php echo 1 === $hdc_lp_service_page ? 'aria-current="true"' : ''; ?>
					></button>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="hdc-lp-accommodations" id="hdc-lp-accommodations" aria-labelledby="hdc-lp-accommodations-title">
		<div class="hdc-lp-container hdc-lp-accommodations__container">
			<header class="hdc-lp-accommodations__header">
				<h2 class="hdc-lp-accommodations__title" id="hdc-lp-accommodations-title" data-hdc-lp-scroll-reveal>Escolha a sua acomodação ideal para você em Aracaju - SE</h2>
				<p class="hdc-lp-accommodations__intro" data-hdc-lp-scroll-reveal>11 opções renovadas para viagens a dois, em família, entre amigos ou a trabalho. Todas as acomodações contam com ar-condicionado, TV, cofre, amenities de cortesia e Wi-Fi gratuito.</p>
			</header>

			<div
				class="hdc-lp-accommodations__carousel"
				data-hdc-lp-accommodations-carousel
				data-hdc-lp-scroll-reveal
				role="region"
				aria-roledescription="<?php esc_attr_e( 'carrossel', 'hdc-lp' ); ?>"
				aria-label="<?php esc_attr_e( 'Acomodações do Hotel da Costa, 11 itens', 'hdc-lp' ); ?>"
			>
				<div
					class="hdc-lp-accommodations__track"
					id="hdc-lp-accommodations-track"
					data-hdc-lp-accommodations-track
					role="list"
					tabindex="0"
				>
					<?php foreach ( $hdc_lp_accommodations as $hdc_lp_accommodation ) : ?>
						<article class="hdc-lp-accommodations__card" role="listitem">
							<div class="hdc-lp-accommodations__media">
								<img
									class="hdc-lp-accommodations__image"
									src="<?php echo esc_url( $hdc_lp_asset_url . $hdc_lp_accommodation['image'] ); ?>"
									width="1600"
									height="1067"
									alt="<?php echo esc_attr( $hdc_lp_accommodation['alt'] ); ?>"
									loading="lazy"
									decoding="async"
								>
								<?php if ( '' !== $hdc_lp_accommodation['tag'] ) : ?>
									<span class="hdc-lp-accommodations__tag"><?php echo esc_html( $hdc_lp_accommodation['tag'] ); ?></span>
								<?php endif; ?>
							</div>

							<div class="hdc-lp-accommodations__body">
								<h3 class="hdc-lp-accommodations__card-title"><?php echo esc_html( $hdc_lp_accommodation['title'] ); ?></h3>
								<ul class="hdc-lp-accommodations__features" aria-label="<?php echo esc_attr( sprintf( 'Características da acomodação %s', $hdc_lp_accommodation['title'] ) ); ?>">
									<li class="hdc-lp-accommodations__feature">
										<span class="hdc-lp-accommodations__icon hdc-lp-accommodations__icon--space" aria-hidden="true"></span>
										<span><?php echo esc_html( $hdc_lp_accommodation['space'] ); ?></span>
									</li>
									<li class="hdc-lp-accommodations__feature">
										<span class="hdc-lp-accommodations__icon hdc-lp-accommodations__icon--bed" aria-hidden="true"></span>
										<span><?php echo esc_html( $hdc_lp_accommodation['bed'] ); ?></span>
									</li>
									<li class="hdc-lp-accommodations__feature">
										<span class="hdc-lp-accommodations__icon hdc-lp-accommodations__icon--view" aria-hidden="true"></span>
										<span><?php echo esc_html( $hdc_lp_accommodation['view'] ); ?></span>
									</li>
								</ul>
								<p class="hdc-lp-accommodations__description"><?php echo esc_html( $hdc_lp_accommodation['description'] ); ?></p>
								<a class="hdc-lp-accommodations__link" href="<?php echo esc_url( $hdc_lp_accommodation['url'] ); ?>">
									<span>VER DETALHES</span>
									<span class="hdc-lp-accommodations__icon hdc-lp-accommodations__icon--arrow" aria-hidden="true"></span>
								</a>
							</div>
						</article>
					<?php endforeach; ?>
				</div>

				<div class="hdc-lp-accommodations__pagination" role="group" aria-label="<?php esc_attr_e( 'Navegação do carrossel de acomodações', 'hdc-lp' ); ?>">
					<?php foreach ( $hdc_lp_accommodation_pages as $hdc_lp_accommodation_page ) : ?>
						<button
							class="hdc-lp-accommodations__dot<?php echo 1 === $hdc_lp_accommodation_page ? ' hdc-lp-accommodations__dot--active' : ''; ?>"
							type="button"
							data-hdc-lp-accommodations-dot
							aria-controls="hdc-lp-accommodations-track"
							aria-label="<?php echo esc_attr( sprintf( 'Exibir a acomodação %1$d de %2$d: %3$s', $hdc_lp_accommodation_page, count( $hdc_lp_accommodations ), $hdc_lp_accommodations[ $hdc_lp_accommodation_page - 1 ]['title'] ) ); ?>"
							<?php echo 1 === $hdc_lp_accommodation_page ? 'aria-current="true"' : ''; ?>
						></button>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<dialog class="hdc-lp-room-modal" id="hdc-lp-room-modal" data-hdc-lp-room-modal aria-labelledby="hdc-lp-room-modal-title">
			<header class="hdc-lp-room-modal__header">
				<h2 class="hdc-lp-room-modal__title" id="hdc-lp-room-modal-title" data-hdc-lp-room-title></h2>
				<button class="hdc-lp-room-modal__close" type="button" data-hdc-lp-room-close aria-label="Fechar detalhes da acomodação" autofocus><span class="hdc-lp-lightbox__close-icon" aria-hidden="true"></span></button>
			</header>
			<div class="hdc-lp-room-modal__content">
				<p class="hdc-lp-room-modal__description" data-hdc-lp-room-description></p>
				<section class="hdc-lp-room-modal__features" aria-labelledby="hdc-lp-room-features-title">
					<h3 class="hdc-lp-room-modal__heading" id="hdc-lp-room-features-title">Características:</h3>
					<ul class="hdc-lp-room-modal__feature-list" data-hdc-lp-room-features></ul>
				</section>
			</div>
			<section class="hdc-lp-room-modal__gallery" aria-labelledby="hdc-lp-room-gallery-title">
				<h3 class="hdc-lp-room-modal__heading" id="hdc-lp-room-gallery-title">Galeria:</h3>
				<div class="hdc-lp-room-modal__carousel" role="region" aria-roledescription="carrossel" aria-label="Fotos da acomodação">
					<button class="hdc-lp-room-modal__control hdc-lp-room-modal__control--previous" type="button" data-hdc-lp-room-previous aria-label="Foto anterior" aria-controls="hdc-lp-room-photo-track"><span class="hdc-lp-gallery__arrow hdc-lp-gallery__arrow--previous" aria-hidden="true"></span></button>
					<ul class="hdc-lp-room-modal__track" id="hdc-lp-room-photo-track" data-hdc-lp-room-track tabindex="0" aria-label="Fotos da acomodação; use as setas para navegar"></ul>
					<button class="hdc-lp-room-modal__control hdc-lp-room-modal__control--next" type="button" data-hdc-lp-room-next aria-label="Próxima foto" aria-controls="hdc-lp-room-photo-track"><span class="hdc-lp-gallery__arrow" aria-hidden="true"></span></button>
				</div>
				<div class="hdc-lp-room-modal__pagination" data-hdc-lp-room-pagination role="group" aria-label="Selecionar foto da acomodação"></div>
				<p class="hdc-lp-room-modal__status" data-hdc-lp-room-status aria-live="polite" aria-atomic="true"></p>
			</section>
		</dialog>
		<?php
		$hdc_lp_details_path = HDC_LP_DIR . 'assets/data/accommodation-details.json';
		$hdc_lp_details_data = json_decode( file_get_contents( $hdc_lp_details_path ), true );
		?>
		<script type="application/json" data-hdc-lp-room-data data-hdc-lp-room-assets="<?php echo esc_url( $hdc_lp_asset_url ); ?>"><?php echo wp_json_encode( $hdc_lp_details_data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?></script>
	</section>

	<section class="hdc-lp-differentials" id="hdc-lp-differentials" data-hdc-lp-differentials aria-labelledby="hdc-lp-differentials-title" aria-roledescription="apresentação de diferenciais">
		<div class="hdc-lp-differentials__backgrounds" aria-hidden="true">
			<?php foreach ( $hdc_lp_differentials as $hdc_lp_index => $hdc_lp_differential ) : ?>
				<picture>
					<source media="(max-width: 767px)" srcset="<?php echo esc_attr( $hdc_lp_asset_url . $hdc_lp_differential['mobile_background'] . '-mobile-480.webp 480w, ' . $hdc_lp_asset_url . $hdc_lp_differential['mobile_background'] . '-mobile-768.webp 768w' ); ?>" sizes="100vw" width="768" height="1152">
					<img class="hdc-lp-differentials__background<?php echo 0 === $hdc_lp_index ? ' hdc-lp-differentials__background--active' : ''; ?>" data-hdc-lp-differentials-background src="<?php echo esc_url( $hdc_lp_asset_url . $hdc_lp_differential['background'] . '-' . $hdc_lp_differential['width'] . '.webp' ); ?>" srcset="<?php echo esc_attr( $hdc_lp_asset_url . $hdc_lp_differential['background'] . '-960.webp 960w, ' . $hdc_lp_asset_url . $hdc_lp_differential['background'] . '-' . $hdc_lp_differential['width'] . '.webp ' . $hdc_lp_differential['width'] . 'w' ); ?>" sizes="100vw" width="<?php echo absint( $hdc_lp_differential['width'] ); ?>" height="<?php echo absint( $hdc_lp_differential['height'] ); ?>" alt="" loading="lazy" decoding="async">
				</picture>
			<?php endforeach; ?>
		</div>
		<div class="hdc-lp-container hdc-lp-differentials__container">
			<header class="hdc-lp-differentials__header">
				<h2 id="hdc-lp-differentials-title" class="hdc-lp-differentials__title">Mais motivos para escolher o Hotel da Costa</h2>
				<p class="hdc-lp-differentials__subtitle">Uma localização privilegiada, experiências para diferentes perfis de viagem e o cuidado que transforma hospedagem em boas lembranças.</p>
			</header>
			<div class="hdc-lp-differentials__mobile-captions" data-hdc-lp-differentials-captions aria-live="off" aria-atomic="true">
				<?php foreach ( $hdc_lp_differentials as $hdc_lp_index => $hdc_lp_differential ) : ?>
					<div class="hdc-lp-differentials__caption<?php echo 0 === $hdc_lp_index ? ' hdc-lp-differentials__caption--active' : ''; ?>" data-hdc-lp-differentials-caption aria-hidden="<?php echo 0 === $hdc_lp_index ? 'false' : 'true'; ?>">
						<h2 class="hdc-lp-differentials__title"><?php echo esc_html( $hdc_lp_differential['title'] ); ?></h2>
						<p class="hdc-lp-differentials__subtitle"><?php echo esc_html( $hdc_lp_differential['description'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="hdc-lp-differentials__items" role="group" aria-label="Escolha um diferencial do hotel">
				<?php foreach ( $hdc_lp_differentials as $hdc_lp_index => $hdc_lp_differential ) : ?>
					<article class="hdc-lp-differentials__item<?php echo 0 === $hdc_lp_index ? ' hdc-lp-differentials__item--active' : ''; ?>" data-hdc-lp-differentials-item>
						<div class="hdc-lp-differentials__thumbnail">
							<picture>
								<source media="(max-width: 767px)" srcset="<?php echo esc_url( $hdc_lp_asset_url . $hdc_lp_differential['mobile_background'] . '-mobile-thumbnail.webp' ); ?>" width="240" height="320">
							<img src="<?php echo esc_url( $hdc_lp_asset_url . ( 'gastronomy' === $hdc_lp_differential['key'] ? 'differentials-gastronomy-salad-thumbnail.webp' : 'differentials-' . $hdc_lp_differential['key'] . '-thumbnail.webp' ) ); ?>" width="480" height="320" alt="" loading="lazy" decoding="async">
							</picture>
							<button class="hdc-lp-differentials__eye" type="button" data-hdc-lp-differentials-select aria-label="<?php echo esc_attr( 'Exibir diferencial: ' . $hdc_lp_differential['title'] ); ?>" aria-pressed="<?php echo 0 === $hdc_lp_index ? 'true' : 'false'; ?>">
								<span class="hdc-lp-differentials__eye-disc"><span class="hdc-lp-differentials__eye-icon" aria-hidden="true"></span></span>
								<svg class="hdc-lp-differentials__ring" viewBox="0 0 64 64" aria-hidden="true" focusable="false"><circle class="hdc-lp-differentials__ring-track" cx="32" cy="32" r="30"/><circle class="hdc-lp-differentials__ring-progress" cx="32" cy="32" r="30" pathLength="100"/></svg>
							</button>
						</div>
						<div class="hdc-lp-differentials__item-copy">
							<h3 class="hdc-lp-differentials__item-title"><?php echo esc_html( $hdc_lp_differential['title'] ); ?></h3>
							<p class="hdc-lp-differentials__item-description"><?php echo esc_html( $hdc_lp_differential['description'] ); ?></p>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="hdc-lp-location" id="hdc-lp-location" aria-labelledby="hdc-lp-location-title">
		<div class="hdc-lp-container hdc-lp-location__layout">
			<div class="hdc-lp-location__content">
				<h2 class="hdc-lp-location__title" id="hdc-lp-location-title">Localizados na Orla de Atalaia em Aracaju - SE</h2>
				<p class="hdc-lp-location__description">Hospede-se na Orla de Atalaia, a aproximadamente 300 metros da Praça de Eventos, 3 km da Passarela do Caranguejo e 7 km do Aeroporto Internacional de Aracaju, perto de bares, restaurantes e atrações da região.</p>
				<ul class="hdc-lp-location__points">
					<?php foreach ( $hdc_lp_location_points as $hdc_lp_location_point ) : ?>
						<li class="hdc-lp-location__point">
							<span class="hdc-lp-location__point-icon hdc-lp-location__point-icon--<?php echo esc_attr( $hdc_lp_location_point['icon'] ); ?>" aria-hidden="true"></span>
							<span class="hdc-lp-location__point-copy"><strong class="hdc-lp-location__point-title"><?php echo esc_html( $hdc_lp_location_point['title'] ); ?></strong><span class="hdc-lp-location__point-distance"><?php echo esc_html( $hdc_lp_location_point['distance'] ); ?></span></span>
						</li>
					<?php endforeach; ?>
				</ul>
				<button class="hdc-lp-button hdc-lp-button--whatsapp hdc-lp-location__cta" type="button" data-hdc-lp-lead-open aria-haspopup="dialog" aria-controls="hdc-lp-lead-modal" aria-label="Reservar via WhatsApp: preencher formulário">
					<span>RESERVAR VIA WHATSAPP</span>
					<img class="hdc-lp-button__icon" src="<?php echo esc_url( $hdc_lp_asset_url . 'whatsapp-logo.svg' ); ?>" width="24" height="24" alt="" aria-hidden="true">
				</button>
			</div>
			<div class="hdc-lp-location__map">
				<iframe src="<?php echo esc_url( $hdc_lp_location_map_url ); ?>" title="Mapa interativo do Hotel da Costa by Nobile, Avenida Santos Dumont, 1835, Atalaia, Aracaju" width="720" height="570" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
			</div>
		</div>
	</section>

	<section class="hdc-lp-gallery" id="hdc-lp-gallery" data-hdc-lp-gallery aria-labelledby="hdc-lp-gallery-title">
		<div class="hdc-lp-container hdc-lp-gallery__container">
			<header class="hdc-lp-gallery__header">
				<h2 class="hdc-lp-gallery__title" id="hdc-lp-gallery-title">Um pouco do que espera por você</h2>
				<p class="hdc-lp-gallery__subtitle">Conheça os ambientes, as acomodações, as piscinas e os sabores que farão parte da sua estadia no Hotel da Costa.</p>
			</header>
			<div class="hdc-lp-gallery__carousel" role="region" aria-roledescription="carrossel" aria-label="Fotos do Hotel da Costa">
				<button class="hdc-lp-gallery__control hdc-lp-gallery__control--previous" type="button" data-hdc-lp-gallery-previous aria-controls="hdc-lp-gallery-track" aria-label="Ver fotos anteriores"><span class="hdc-lp-gallery__control-disc"><span class="hdc-lp-gallery__arrow hdc-lp-gallery__arrow--previous" aria-hidden="true"></span></span></button>
				<ul class="hdc-lp-gallery__track" id="hdc-lp-gallery-track" data-hdc-lp-gallery-track tabindex="0" aria-label="Galeria de fotos; use as setas para navegar">
					<?php foreach ( $hdc_lp_gallery as $hdc_lp_photo ) : ?>
						<li class="hdc-lp-gallery__card">
							<a class="hdc-lp-gallery__open" data-hdc-lp-gallery-open href="<?php echo esc_url( $hdc_lp_asset_url . 'gallery-' . $hdc_lp_photo['key'] . '-1920.webp' ); ?>" data-hdc-lp-gallery-thumbnail="<?php echo esc_url( $hdc_lp_asset_url . 'gallery-' . $hdc_lp_photo['key'] . '-160.webp' ); ?>" aria-label="<?php echo esc_attr( 'Abrir imagem em tela cheia: ' . $hdc_lp_photo['alt'] ); ?>">
								<img src="<?php echo esc_url( $hdc_lp_asset_url . 'gallery-' . $hdc_lp_photo['key'] . '-640.webp' ); ?>" srcset="<?php echo esc_attr( $hdc_lp_asset_url . 'gallery-' . $hdc_lp_photo['key'] . '-640.webp 640w, ' . $hdc_lp_asset_url . 'gallery-' . $hdc_lp_photo['key'] . '-960.webp 960w' ); ?>" sizes="(max-width: 767px) 320px, (max-width: 1280px) 400px, 384px" width="640" height="<?php echo absint( isset( $hdc_lp_photo['height'] ) ? $hdc_lp_photo['height'] : 427 ); ?>" alt="<?php echo esc_attr( $hdc_lp_photo['alt'] ); ?>" loading="lazy" decoding="async" draggable="false">
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
				<button class="hdc-lp-gallery__control hdc-lp-gallery__control--next" type="button" data-hdc-lp-gallery-next aria-controls="hdc-lp-gallery-track" aria-label="Ver próximas fotos"><span class="hdc-lp-gallery__control-disc"><span class="hdc-lp-gallery__arrow" aria-hidden="true"></span></span></button>
			</div>
		</div>
		<dialog class="hdc-lp-lightbox" id="hdc-lp-gallery-lightbox" data-hdc-lp-lightbox aria-labelledby="hdc-lp-lightbox-title">
			<div class="hdc-lp-lightbox__toolbar">
				<h2 id="hdc-lp-lightbox-title" class="hdc-lp-lightbox__title">Galeria de fotos</h2>
				<button class="hdc-lp-lightbox__button hdc-lp-lightbox__close" type="button" data-hdc-lp-lightbox-close aria-label="Fechar galeria" autofocus><span class="hdc-lp-lightbox__close-icon" aria-hidden="true"></span></button>
			</div>
			<div class="hdc-lp-lightbox__stage" data-hdc-lp-lightbox-stage>
				<button class="hdc-lp-lightbox__button hdc-lp-lightbox__previous" type="button" data-hdc-lp-lightbox-previous aria-label="Imagem anterior"><span class="hdc-lp-gallery__arrow hdc-lp-gallery__arrow--previous" aria-hidden="true"></span></button>
				<img class="hdc-lp-lightbox__image" data-hdc-lp-lightbox-image width="1920" height="1280" alt="" decoding="async">
				<button class="hdc-lp-lightbox__button hdc-lp-lightbox__next" type="button" data-hdc-lp-lightbox-next aria-label="Próxima imagem"><span class="hdc-lp-gallery__arrow" aria-hidden="true"></span></button>
			</div>
			<p class="hdc-lp-lightbox__caption" data-hdc-lp-lightbox-caption aria-live="polite" aria-atomic="true"></p>
			<div class="hdc-lp-lightbox__thumbnails" data-hdc-lp-lightbox-thumbnails role="group" aria-label="Escolher uma imagem da galeria"></div>
		</dialog>
	</section>
	<?php require HDC_LP_DIR . 'templates/reviews.php'; ?>
	<?php require HDC_LP_DIR . 'templates/lead-form.php'; ?>
</main>

<?php require HDC_LP_DIR . 'templates/footer.php'; ?>

<?php wp_footer(); ?>
</body>
</html>
