# Hotel da Costa — Landing Page

## Fundo da seção de avaliações — versão 1.25.2

A seção **Quem se hospeda, recomenda** usa a foto fornecida `IMG_1806.jpg`. As versões WebP para desktop têm 1280, 1920 e 2560 px de largura; as versões mobile têm 480 × 720, 768 × 1152 e 1152 × 1728 px, com recorte vertical da fachada e da piscina. O navegador escolhe o arquivo pelo tamanho da tela e sua densidade de pixels, usando `picture`, `srcset` e `sizes`. O carregamento continua tardio, com dimensões declaradas e a sobreposição escura existente.

Para atualizar, envie `hotel-da-costa-landing-page.zip` em **Plugins → Adicionar plugin → Enviar plugin**, substitua a versão instalada e limpe o cache da LP ou CDN. Os arquivos usados ficam em `assets/images/reviews-background-desktop-*.webp` e `assets/images/reviews-background-mobile-*.webp`; as referências ficam em `templates/reviews.php`. A foto original é preservada fora do ZIP, na pasta de imagens do projeto.

## Correção do texto de avaliações — versão 1.25.1

O texto da seção principal passa a exibir exatamente “Média 4.5 no Google” e, na linha seguinte, “em mais de 2400 avaliações”. A correção remove o “em” extra antes de “no Google”. O trecho fica em `templates/landing-page.php`.

Para atualizar, envie `hotel-da-costa-landing-page.zip` em **Plugins → Adicionar plugin → Enviar plugin** e substitua a versão instalada. Se houver cache de página ou CDN, limpe o cache da LP após atualizar.

## Trustindex gratuito temporário — versão 1.25.0

O painel **Configurações → Hotel da Costa — Avaliações** permite escolher **Trustindex gratuito** enquanto a API Google Business Profile aguarda aprovação. Instale separadamente o plugin oficial **Widgets for Google Reviews**, da Trustindex.io, conecte o hotel e importe suas avaliações. Depois confirme nome e endereço no painel da LP e clique em **Usar avaliações do Trustindex**. Não é necessário inserir shortcode. Veja o [manual de instalação, atualização e migração](MANUAL-TRUSTINDEX.md).

A integração lê os registros importados por meio das funções públicas `getPageDetails()` e `getReviews()` do Trustindex 14.2. O carrossel original e seus filtros permanecem; registros ocultados no Trustindex são respeitados, e a troca de perfil exige nova confirmação para evitar avaliações de outra empresa. A LP não renderiza o widget ou carrega seu CSS/loader. Não usa os métodos do fornecedor que geram avaliações de demonstração e não mantém outra cópia dos registros.

A versão gratuita importa inicialmente até dez avaliações e exige novos downloads manuais no painel do Trustindex. A LP não promete sincronização automática nessa fonte. A expiração de sete dias continua aplicada exclusivamente ao cache da API Business Profile; os registros do Trustindex permanecem sob o gerenciamento desse plugin. A sincronização diária da API fica suspensa quando Trustindex está selecionado. Para migrar após a aprovação, escolha **Usar API oficial do Google**, configure a conexão e baixe as avaliações. A seleção explícita de fonte não altera o tema nem outras páginas.

## Google Tag Manager — versão 1.24.0

O contêiner **GTM-KWXBNWVF** fica ativo por padrão somente nas páginas com o template **Landing Page — Hotel da Costa**. O script assíncrono entra no início de `wp_head`, e o trecho `noscript` é o primeiro conteúdo de `wp_body_open`. A página comum, a política de privacidade e o painel administrativo não recebem esses códigos pelo plugin. O iframe de monitoramento usa o atributo HTML `hidden`, equivalente ao ocultamento do código fornecido, sem estilo inline.

Para atualizar o plugin, envie `hotel-da-costa-landing-page.zip` em **Plugins → Adicionar plugin → Enviar plugin** e substitua a versão instalada. Depois, abra **Hotel da Costa → Monitoramento**, altere o **ID do contêiner** e clique em **Salvar monitoramento**. Os códigos de Head e Body são atualizados juntos. Para interromper o carregamento, desmarque **Ativar Google Tag Manager na landing page** e salve; o ID pode ser mantido para reativação posterior. Se houver cache de página ou CDN, limpe o cache da LP após salvar.

O painel usa a Settings API nativa do WordPress, com nonce e permissão `manage_options`. Um ID inválido é recusado e a configuração anterior é preservada. Nenhum JavaScript arbitrário é armazenado. Desativar ou substituir o plugin preserva a configuração salva. Novas tags e eventos dentro do contêiner são gerenciados no Google Tag Manager.

A integração fica em `includes/tracking.php` e o painel em `includes/tracking-admin.php`. Referências: [instalação do contêiner web pelo Google](https://support.google.com/tagmanager/answer/14847097?hl=pt-BR) e [Settings API do WordPress](https://developer.wordpress.org/plugins/settings/using-settings-api/).

Validação em WordPress local: instalação do ZIP 1.24.0, sintaxe de todos os arquivos PHP, troca do ID, ativação/desativação, rejeição de entradas inválidas, nonce, bloqueio de acesso por editor, iframe com JavaScript desligado e isolamento da LP. A aba foi conferida em 320, 400 e 1280 px; as capturas da landing page em 400 e 1440 px ficaram idênticas às anteriores. A desativação e reativação preservaram a configuração, o tema, as páginas, menus, campanhas e envios. As requisições ao Google foram interceptadas nos testes para não enviar acessos fictícios; as tags publicadas dentro do contêiner devem ser verificadas no Tag Assistant após instalar no site. Nenhuma alteração foi aplicada em produção.

## Altura do pop-up das acomodações — versão 1.23.3

O título de cada quarto participa do fluxo normal do pop-up, sem cabeçalho fixo ou sticky. A galeria ocupa o espaço disponível e suas fotos variam entre 120 e 320 px de altura no desktop. Os textos, tamanhos de fonte, características e fotografias aprovados permanecem preservados.

Em janelas de até 800 px de altura, os espaçamentos internos e as margens externas são mais compactos; a partir de 1024 px de largura, a descrição e as características ficam lado a lado. Até 600 px de altura, os espaçamentos se ajustam novamente. O padding interno continua em 40 px no desktop e 20 px no mobile. A composição mobile mantém uma foto por vez. Em telas muito pequenas ou com texto ampliado, a rolagem permanece disponível para acessar todo o conteúdo.

Para atualizar, envie o ZIP em **Plugins → Adicionar plugin → Enviar plugin** e substitua a versão instalada. Os ajustes ficam em `assets/css/accommodation-details.css`; os dados das onze acomodações continuam em `assets/data/accommodation-details.json`.

## Altura responsiva da seção principal — versão 1.23.2

A hero no desktop usa a altura da janela (`100vh`, com `100dvh` nos navegadores compatíveis), descontando a barra administrativa do WordPress quando presente. Os limites fixos de 680, 820 e 900 px foram removidos. O cálculo inclui os espaçamentos da seção, mantendo o texto, o botão e os reconhecimentos na primeira tela em notebooks e computadores com altura suficiente para o conteúdo.

O texto participa do fluxo do layout: em janelas muito baixas ou com texto ampliado, a seção cresce para preservar todas as informações e evitar sobreposição com o logo. O mobile mantém sua composição própria. Para atualizar, envie o ZIP em **Plugins → Adicionar plugin → Enviar plugin** e substitua a versão instalada. A edição do comportamento fica em `assets/css/landing-page.css`.

## Imagens mobile, SEO e formulário — versão 1.22.0

Até `767px`, os fundos e as miniaturas de **Na Orla de Atalaia**, **Estrutura acessível** e **Gastronomia** usam, respectivamente, as fotos fornecidas `magnific__-__88073.jpeg`, `magnific__-__50857.jpeg` e `IMG_1865.jpg`. Os fundos têm versões WebP de 480 × 720 e 768 × 1152 px, selecionadas por `picture`/`srcset`; as miniaturas têm 240 × 320 px. As imagens continuam com carregamento tardio e dimensões declaradas. As fontes são preservadas na pasta de imagens do projeto, fora do ZIP.

O formulário do pop-up tem o ID exato **`form_popup`**, por solicitação do usuário, e pode ser identificado por `#form_popup` em ferramentas de acompanhamento. Os seletores técnicos existentes do JavaScript continuam prefixados.

O SEO fica em `includes/seo.php` e só funciona no template da LP:

- **Título:** Hotel da Costa by Nobile | Orla de Atalaia, Aracaju - SE
- **Descrição:** Hospede-se na Orla de Atalaia, em Aracaju - SE, com acomodações renovadas, piscinas, café da manhã e gastronomia. Reserve via WhatsApp.
- Canonical e `og:url` usam o permalink da página, sem parâmetros de campanha. A URL se adapta à instalação do WordPress, sem fixar o endereço do preview.
- Open Graph e Twitter Card compartilham título, descrição e fotografia da hero em JPEG de 1200 × 630 px.
- O idioma do documento é `pt-BR`, correspondente à copy, independentemente do idioma administrativo do WordPress.
- JSON-LD identifica o hotel e a página, usando nome, imagem e cidade/estado/país. Não inclui preços, classificações, avaliações, prêmios, quantidade de quartos ou endereço de rua.
- Restrições de indexação do WordPress são preservadas; preview, localhost, ambiente de staging/desenvolvimento, páginas não publicadas e protegidas por senha usam `noindex`. Restrições `noindex`/`nofollow` por página do Yoast e Rank Math também são respeitadas.
- A LP controla seu próprio head: os presenters do Yoast e o head do Rank Math são substituídos somente nesta página para evitar metadados duplicados. As demais páginas, sitemaps e configurações administrativas continuam sob os responsáveis anteriores. Outros plugins de SEO precisam ser conferidos na instalação de destino.

Para editar o título e a descrição, ajuste os valores em `hdc_lp_seo_metadata()`; para trocar a imagem de compartilhamento, substitua `assets/images/seo-hotel-da-costa-1200x630.jpg`, mantendo as dimensões. O preview estático mantém uma cópia dos metadados com `noindex` para conferência local. A configuração não envia a página ao Search Console nem altera o sitemap ou a opção global de indexação do WordPress.

Referências técnicas: [títulos no Google](https://developers.google.com/search/docs/appearance/title-link), [descrições](https://developers.google.com/search/docs/appearance/snippet), [canonical](https://developers.google.com/search/docs/crawling-indexing/consolidate-duplicate-urls), [Schema.org Hotel](https://schema.org/Hotel), [presenters do Yoast](https://developer.yoast.com/customization/apis/metadata-api/) e [hooks do Rank Math](https://rankmath.com/kb/filters-hooks-api-developer/).

Validação local: preview e WordPress conferidos em 320, 400, 767, 768, 1280 e 1920 px, incluindo seleção das três fotos por teclado, miniaturas, ausência de overflow, ID único do formulário e retorno do foco ao fechar o pop-up. O head foi verificado com parâmetros de campanha: um título, uma descrição, uma canonical, um robots e JSON-LD válido. As regras de indexação e o isolamento nas demais páginas foram testados no WordPress de staging.

## Hover das acomodações — versão 1.21.3

O hover de **VER DETALHES** usa azul mais claro, definido pelo token `--hdc-lp-color-accommodation-link-hover` em `assets/css/landing-page.css`. O texto e a seta recebem a mesma cor, com contraste adequado sobre o fundo da seção.

## Quebra de textos — versão 1.21.2

Todo elemento da landing page recebe `text-wrap: pretty` por uma regra escopada em `.hdc-lp`. Títulos, subtítulos, títulos dos pontos de localização e seus elementos internos usam `text-wrap: balance`. A cobertura inclui controles, formulário, rodapé, legendas, estados de erro e conteúdo inserido pelos pop-ups e pela integração de avaliações. As regras de `nowrap` que impediam a aplicação foram removidas. Os textos, tamanhos de fonte e espaçamentos existentes permanecem preservados. A edição fica centralizada em `assets/css/landing-page.css`.

## Ajustes de conteúdo e formulário — versão 1.21.1

Por solicitação do usuário em 01/10/2026, a abertura das acomodações passa a usar **11 opções renovadas**, e os dois trechos indicados nas capturas usam **Aracaju - SE**: o parágrafo da hero e o título das acomodações. Esses ajustes pontuais prevalecem sobre a copy anterior do Figma; os demais textos aprovados permanecem preservados. As onze tags foram conferidas em [Acomodações do site oficial](https://hoteldacosta.com.br/acomodacoes/) nesta data e usam a apresentação já existente da LP.

Gastronomia usa a foto `magnific__enhance__86827.png`, com derivados WebP locais e recortes verticais próprios para mobile. A fachada diurna do background de **Na Orla de Atalaia** foi editada pelo ImageGen integrado para remover os fios; o arquivo mestre e o prompt ficam na pasta de fontes do projeto, fora do ZIP. A imagem noturna da miniatura desse item permanece a mesma. Os backgrounds usam `picture`, `srcset`, dimensões explícitas e lazy loading. Novos nomes de arquivo evitam reutilizar as fotos anteriores do cache.

O telefone do formulário tem `maxlength="15"` e limita a máscara a onze dígitos nacionais. A colagem com `+55` é normalizada antes do limite nativo do navegador. A validação de celular e DDD continua ativa no servidor. O botão do formulário usa texto branco e um verde com contraste mínimo de 4,5:1, incluindo os estados interativos.

Plugin WordPress independente com o template da landing page do Hotel da Costa by Nobile. Na versão 1.21.0, os CTAs de reserva abrem um formulário com registro de envios no WordPress. A hero e as seções de boas-vindas, promoções, serviços, acomodações, diferenciais, localização, galeria, avaliações e rodapé preservam o layout do Figma, a animação de entrada vinculada ao scroll e os carrosséis acessíveis. O carrossel de serviços contém os oito itens definidos no Figma, e o de acomodações apresenta as onze categorias do hotel com imagens locais. Cada link VER DETALHES abre um pop-up próprio com descrição, características e galeria da acomodação. A galeria geral contém as doze fotos do Figma e abre um lightbox em tela cheia com setas e miniaturas. As avaliações têm integração própria com a API oficial Google Business Profile, filtros e atualização diária no WordPress. A versão 1.25.0 também permite ler avaliações importadas pelo Trustindex gratuito como fonte temporária, com atualização manual pelo fornecedor. O rodapé usa o logo e o crédito da Checkin aprovados no Figma; a política de privacidade permanece sem link até sua página ser desenvolvida.

## Formulário e envios — versão 1.21.0

Todos os CTAs **RESERVAR VIA WHATSAPP** abrem o formulário com nome, e-mail e celular, máscara brasileira, validação no navegador e no servidor, fade de 200 ms e suporte a movimento reduzido. O registro é salvo antes do redirecionamento ao WhatsApp na mesma aba. As cinco UTMs são capturadas da URL e preservadas na aba; uma nova campanha substitui o conjunto anterior. A perda da resposta e a repetição do mesmo envio não duplicam o cadastro.

Administradores acessam **Hotel da Costa → Envios** para buscar, filtrar, consultar detalhes, controlar leitura, usar a lixeira e exportar CSV. Os dados são preservados ao atualizar ou desativar o plugin. O [manual curto de instalação, uso e edição](MANUAL-DE-ENVIOS.md) explica o fluxo completo, os limites dos campos e a configuração de cache.

Os testes `tmp/check-leads.js` e `tmp/check-leads-frontend.js` usam cadastros e usuários temporários de staging. Validam persistência, erros, idempotência, permissões, filtros, exportação, máscara, UTMs, teclado, animações, contraste e larguras de 320 a 2560 px. A conexão atual do Figma estava sem arquivos disponíveis nesta atualização; a copy institucional e os assets anteriores foram preservados. As imagens fornecidas pelo usuário orientam exclusivamente o formulário e a lista de envios.

## Fonte da copy

A versão **1.18.0** adota como fonte oficial dos textos institucionais a página **LP - Hotel da Costa**, frame [`5308:3` no Figma](https://www.figma.com/design/T0967OaYPkcFB4TuMviA9R/Todos-projetos?node-id=5308-3). A conexão atual do `figma_bridge` foi verificada e os textos de todas as seções foram extraídos, com screenshots, em 30/09/2026. O arquivo `copy-landing-page-hotel-da-costa.md` permanece preservado como histórico e não prevalece sobre o Figma. Os trechos que coincidem entre as fontes foram mantidos porque também constam no layout.

- Campanhas: “Fale com a nossa central de reservas para encontrar a melhor opção para a sua estadia.”
- Acomodações: “Escolha a sua acomodação ideal para você em Aracaju”. A Suíte Premium usa as características textuais do Figma.
- Serviços: Café da manhã, Restaurante Villa Ravello, Piscinas e bar, Espaço Kids, Academia, Lobby Bar, Eventos e reuniões e Copa Baby, nesta ordem. As descrições ocultas do arquivo histórico foram removidas. Copa Baby usa a fotografia vertical extraída do Figma para serviços; a fotografia da galeria permanece preservada.
- Diferenciais: “Gastronomia” substitui o título anterior; os demais textos já coincidem com o Figma.
- Localização: “Localizados na Orla de Atalaia em Aracaju - SE”, com o parágrafo e a pontuação do layout.

As descrições específicas das onze acomodações foram mantidas por autorização explícita do usuário nesta atualização. Os pop-ups continuam usando os dados verificados do site oficial. A integração de avaliações mantém conteúdo real e estado vazio sem conexão; os depoimentos exemplificativos do Figma não foram incorporados. Textos funcionais e nomes acessíveis continuam identificando controles, estados vazios e origem dos dados. CTAs permanecem **RESERVAR VIA WHATSAPP**, e a política de privacidade continua sem link.

O teste `tmp/check-figma-copy.js` confere os textos diretamente contra a extração dos nodes do Figma, verifica as oito larguras de 320 a 2560 px e compara preview e WordPress. A fonte extraída e os testes ficam somente em `tmp/`, fora do ZIP. Layouts, scripts de interação e integrações foram preservados. O token `--hdc-lp-font-weight-body: 400` fixa o peso regular na raiz da LP, impedindo a herança de peso 300 do tema local e mantendo paridade com o preview.

## Instalação

1. No painel do WordPress, acesse **Plugins → Adicionar plugin → Enviar plugin**.
2. Envie `hotel-da-costa-landing-page.zip`, instale e ative.
3. Crie ou edite uma página e selecione o template **Landing Page — Hotel da Costa**.
4. Publique ou visualize a página.

O plugin não altera o tema ativo, menus, `functions.php` ou estilos globais. Os assets só são carregados na página que usa o template.

Para o preview local, execute `node tmp/serve-preview.js` na raiz do projeto e abra `http://127.0.0.1:8765/preview/index.html`. Use HTTP em vez de abrir o HTML por `file://`, pois o navegador bloqueia as máscaras SVG dos ícones nesse protocolo. Esse servidor é apenas para desenvolvimento; o plugin funciona sem Node.js no WordPress.

## Edição

- Copy e marcação das seções: `templates/landing-page.php`.
- Aparência e responsividade: `assets/css/landing-page.css`.
- Interações e animações: `assets/js/landing-page.js`.
- Imagens: `assets/images/`.
- Fontes locais: `assets/fonts/`.
- Conteúdo dos pop-ups das acomodações: `assets/data/accommodation-details.json`.
- Layout e interações dos pop-ups: `assets/css/accommodation-details.css` e `assets/js/accommodation-details.js`.
- Integração e painel de avaliações: `includes/google-reviews.php` e `includes/google-reviews-admin.php`.
- Dados e painel de campanhas: `includes/campaigns.php`, `includes/campaigns-admin.php`, `assets/css/campaigns-admin.css` e `assets/js/campaigns-admin.js`.
- Seção de avaliações: `templates/reviews.php`, `assets/css/reviews.css` e `assets/js/reviews.js`.
- Rodapé: `templates/footer.php`, `assets/css/footer.css` e `assets/images/footer-logo.png`.

A hero usa uma fotografia horizontal em telas a partir de `768px` e uma versão vertical otimizada no mobile. A seção de boas-vindas preserva duas colunas no notebook, empilha o conteúdo no tablet e usa uma composição vertical própria no mobile. A landing page ocupa toda a largura disponível, com calhas laterais fluidas ancoradas nos frames de `400px`, `1280px` e `1920px` do Figma. Os carrosséis mantêm a largura projetada dos cards e revelam progressivamente mais itens, ou parte do item seguinte, conforme a viewport aumenta. A seção de serviços contém oito indicadores correspondentes aos seus oito itens, e a trilha aceita clique e arraste com mouse ou caneta, além do gesto de arrastar no touchscreen. Os carrosséis também respondem às setas do teclado. Os ajustes estruturais preservam o breakpoint mobile em `767px` e usam `clamp()` e `calc()` para suavizar as larguras intermediárias.

### Campanhas e ofertas

Na versão **1.17.0**, os frames `5424:51` (mobile), `5401:414` (notebook) e `5321:171` (desktop) foram consultados na página **LP - Hotel da Costa** pela conexão atual do `figma_bridge`, com medidas e screenshots. Os cards mantêm a proporção 4:5, intervalo de 20 px e referências de 320 × 400 px, 360 × 450 px e 384 × 480 px. O cálculo agora considera a largura real da trilha: a partir de 1200 px cabem três cards inteiros e, a partir de 1800 px, quatro, mesmo com barra de rolagem vertical. Abaixo de 360 px, o card encolhe para caber nas calhas. Acima de 1920 px, as quatro imagens permanecem centralizadas com largura máxima de 384 px; a landing page continua fluida, sem restaurar um limite global de 1280 px.

As quatro artes fornecidas pelo usuário substituem os espaços cinza, na ordem: Independência do Brasil, Dia Mundial do Turismo, Dia Nacional do Turismo e Independência de Sergipe. Os arquivos foram renomeados para `campanha-independencia-brasil-*`, `campanha-dia-mundial-turismo-*`, `campanha-dia-nacional-turismo-*` e `campanha-independencia-sergipe-*`, em WebP de 640 × 800 e 960 × 1200 px. `srcset` seleciona o tamanho adequado; as artes aparecem completas, com dimensões reservadas, texto alternativo, lazy loading e arraste nativo da imagem desativado. Os JPGs originais não foram modificados nem incluídos no pacote. O script de conversão fica em `tmp/prepare-campaign-assets.py`, exclusivamente para desenvolvimento.

Na versão **1.20.0**, a edição das campanhas passa a ser feita em **Hotel da Costa → Campanhas**, no painel WordPress. A copy institucional da seção e o CTA **RESERVAR VIA WHATSAPP** permanecem os aprovados. Não foram acrescentadas tarifas ou condições comerciais às artes comemorativas.

#### Gerenciar campanhas

1. Entre como administrador e abra **Hotel da Costa → Campanhas**.
2. Clique em **Adicionar campanha** ou edite uma existente. Preencha o nome interno e o texto alternativo.
3. Use **Selecionar ou substituir imagem** para selecionar uma imagem ou enviar um arquivo pela [Biblioteca de Mídia nativa](https://developer.wordpress.org/reference/functions/wp_enqueue_media/). São aceitos JPEG, PNG e WebP. Recomendamos proporção 4:5; outras proporções aparecem completas dentro do card, sem recorte. O upload usa os tamanhos responsivos do WordPress e não exige conversão automática para WebP.
4. Marque **Ativa** para exibir a campanha. Reordene pelo botão **Arrastar para reordenar** ou pelos botões **Mover para cima/baixo**, também acessíveis pelo teclado.
5. Clique em **Salvar campanhas** para aplicar. A tela informa sucesso ou erro. **Remover campanha** retira somente o item da lista; sua imagem continua na Biblioteca de Mídia.

A lista ordenada é salva na opção `hdc_lp_campaigns`, com identificadores estáveis. As quatro artes incluídas no plugin são a configuração inicial apenas enquanto nenhuma lista tiver sido salva. Uma lista salva vazia permanece vazia. Sem campanhas ativas com imagem válida, toda a seção de ofertas — título, texto, carrossel e CTA — fica oculta. Se uma imagem for excluída da Biblioteca de Mídia, a campanha é ignorada na LP e recebe um aviso no painel; substitua a imagem ou remova a campanha. Ainda é possível salvar as outras campanhas nesse estado.

O acesso e a gravação exigem `manage_options`. Os dados passam por nonce, sanitização de textos, validação dos identificadores e dos anexos; uma submissão inválida não substitui a lista salva. O formulário tem um marcador final para rejeitar submissões truncadas pelo limite de campos do servidor. Os assets administrativos e a Biblioteca de Mídia são carregados somente nessa tela. A tela de avaliações permanece em **Configurações → Hotel da Costa — Avaliações**, no endereço `options-general.php?page=hdc-lp-reviews`. Atualizar, desativar ou reativar o plugin preserva a configuração e as imagens das campanhas. Não há agendamento, links individuais ou editor da copy institucional.

O endpoint público, somente leitura, `GET /wp-json/hdc-lp/v1/campaigns` (ou `?rest_route=/hdc-lp/v1/campaigns`) retorna `{ "items": [...] }` com apenas as imagens ativas e válidas, na ordem de exibição: `url`, `width`, `height`, `alt`, `srcset` e `sizes`. Não expõe nomes internos ou campanhas inativas. A resposta usa `Cache-Control: no-store`; exclua esse endpoint e a LP do cache HTML da hospedagem/CDN.

O preview usa `preview/campaigns.js` e consulta `/preview/api/campaigns` no proxy local `tmp/serve-preview.js`, com destino fixo no WordPress da porta 8787 e somente GET. A atualização recalcula as setas e mantém as interações existentes. Sem WordPress disponível, o HTML conserva as quatro artes de referência; uma resposta válida vazia oculta a seção. Reinicie o servidor de preview após atualizar o proxy. O proxy, o script do preview e os testes não entram no ZIP; a página WordPress renderiza as campanhas diretamente no PHP, inclusive sem JavaScript.

O teste `tmp/check-campaigns-admin.js` valida cadastro, upload e troca pela Biblioteca de Mídia, ordenação, ativação, remoção, persistência, permissões, nonce inválido, anexos inválidos, textos maliciosos, imagem excluída e fallback do preview. Compara zero, uma, quatro e sete campanhas em preview e WordPress nas oito larguras de 320 a 2560 px. Fixtures e usuários de teste são removidos e a opção original é restaurada ao final. O backup da entrega 1.19.0 fica em `tmp/backups/`, exclusivamente para desenvolvimento.

A trilha aceita clique e arraste com mouse ou caneta, captura o ponteiro após reconhecer o movimento horizontal e encaixa o card ao soltar. No celular usa rolagem horizontal nativa por toque, preservando rolagem vertical e zoom. Há setas, ←/→, Home e End; a navegação é circular pelas setas quando existem imagens fora da trilha. Quando todas as quatro cabem, as setas permanecem visíveis e ficam inativas. Foco visível e `prefers-reduced-motion` são preservados. Não foram adicionados dots a esse componente, conforme os frames.

O teste `tmp/check-campaigns.js` compara preview e WordPress em 320, 400, 768, 1024, 1280, 1440, 1920 e 2560 px, incluindo barra de rolagem vertical, imagens completas, teclado, foco, mouse, caneta, toque e movimento reduzido. O foco desta seção usa azul com contraste sobre o fundo claro e um contorno de apoio nas setas sobre as artes. O ZIP foi instalado, desativado e reativado no WordPress local; a página comum, o tema, os menus e os metadados das páginas foram preservados. Os arquivos de desenvolvimento não fazem parte do ZIP.

### Imagens dos serviços

Na versão **1.19.0**, os frames `5424:70` (mobile), `5403:433` (notebook) e `5323:203` (desktop) foram consultados novamente pelo `figma_bridge`, com medidas, screenshots e exportação dos assets originais. Os oito cards agora usam as fotografias verticais incorporadas ao Figma, em vez das versões horizontais que provocavam ampliação e recortes diferentes. O fundo também usa a imagem do frame, com posição central nos três formatos. Os arquivos anteriores e as imagens das demais seções foram preservados.

Os arquivos `services-*-portrait.webp` têm 960 px de largura e preservam a proporção original, sem distorção ou recorte prévio. O template e o preview declaram as dimensões reais, lazy loading e textos alternativos. O preenchimento continua com `object-fit: cover` central: cards quadrados de 320 × 320 px no mobile, 360 × 450 px no notebook e 384 × 480 px no desktop. As larguras intermediárias, calhas fluidas, oito dots, teclado, foco e movimento reduzido permanecem os existentes. Os nomes novos dos arquivos evitam reutilizar as imagens antigas do cache.

Foi corrigido um encerramento indevido do arraste por toque: a perda da captura implícita de um card se propagava para a trilha durante a transferência do ponteiro e interrompia o gesto. O handler compartilhado dos carrosséis com dots agora encerra o arraste somente quando a própria trilha perde a captura. Mouse, caneta, toque horizontal e rolagem vertical da página foram verificados.

Para editar imagens, atualize o array `$hdc_lp_services` no template e a marcação correspondente do preview; mantenha `width` e `height` iguais às dimensões do WebP. O script `tmp/prepare-services-figma-assets.py` extrai os bytes das imagens incorporadas aos SVGs exportados pelo bridge e converte somente os assets em WebP. SVGs, originais e scripts de desenvolvimento ficam fora do ZIP.

O teste `tmp/check-services-figma.js` confere as oito larguras de 320 a 2560 px, dimensões, carregamento das oito imagens, enquadramento, overflow, dots, teclado, foco, mouse, caneta, toque, movimento reduzido e paridade entre preview e WordPress. O ZIP foi validado com instalação, ativação, renderização e desativação no WordPress local, preservando o tema, menus, outras páginas e integrações.

### Acomodações

A seção de acomodações usa cards fluidos de `360px` no mobile, `400px` no notebook e `410px` no desktop do Figma. Cada uma das onze acomodações tem um indicador correspondente, três características com ícones Phosphor e descrição limitada visualmente a três linhas. O carrossel aceita dots, setas do teclado, clique e arraste com mouse ou caneta e gesto horizontal em telas sensíveis ao toque.

### Pop-up de detalhes das acomodações

Os frames `5531:2` (desktop) e `5534:152` (mobile), da página **LP - Hotel da Costa**, foram consultados pelo `figma_bridge`. No desktop, o pop-up fica centralizado, com até 1280 × 800 px, padding de 40 px e três fotos de 320 px de altura. Entre 768 e 1023 px, mostra duas fotos; até 767 px, ocupa a tela, com padding de 20 px e uma foto na proporção 3:2. O conteúdo pode rolar em telas baixas, sem cortar textos, controles ou dots. A tipografia específica do pop-up segue os frames: título de 48 px no desktop e 24 px no mobile, descrição de 18/14 px e características de 18 px. Os demais componentes da página não foram alterados.

Descrições, destaques e galerias foram verificados em 30/09/2026 nas páginas individuais do [site oficial](https://hoteldacosta.com.br/acomodacoes/). As características correspondem aos destaques do quadro de reserva, como no exemplo do Figma e nas referências fornecidas. Por decisão explícita do usuário, as descrições específicas já aprovadas dos cards das onze acomodações foram preservadas, pois o Figma repete a Suíte Premium e não contém as demais categorias; o pop-up mantém a descrição verificada no site. A expressão de localização foi ajustada para “vista para o mar”, preservando a regra comercial do projeto.

| Acomodação | Fotos e dots |
| --- | ---: |
| Suíte Premium | 12 |
| Luxo Casal | 9 |
| Luxo Triplo | 12 |
| Executivo | 8 |
| Superior Casal | 8 |
| Superior Twin | 7 |
| Superior Triplo Casal | 7 |
| Superior Triplo Solteiro | 9 |
| Superior Quádruplo | 8 |
| Standard Casal | 7 |
| Standard Twin | 4 |

São 91 imagens associadas às categorias, com 72 arquivos únicos locais em WebP. Fotografias compartilhadas entre categorias no site foram preservadas, sem inventar outras imagens. Na Suíte Premium, as três primeiras fotos foram organizadas conforme o Figma; as demais fotografias também estão disponíveis. Cada imagem tem seu próprio dot, inclusive no desktop. A seleção dos últimos dots permanece correta mesmo quando três imagens ficam visíveis juntas. Imagens das galerias só são inseridas/carregadas ao abrir a acomodação correspondente.

O visitante pode usar os dots, arrastar com mouse/caneta ou toque e navegar com ←/→, Home e End. No desktop/tablet também há setas sobre as fotos. Feche pelo X, por Escape ou clicando no fundo no desktop. O foco fica restrito ao pop-up e volta ao link original; a página ao fundo não rola. Com `prefers-reduced-motion`, a navegação não usa rolagem animada. Sem JavaScript ou suporte a `dialog`, VER DETALHES continua abrindo a página da acomodação no site oficial.

Para editar, use o JSON com `slug`, `source`, `title`, `description`, `features` e `images`. Cada foto declara o arquivo local, texto alternativo e dimensões reais. O template WordPress incorpora esses dados com `wp_json_encode` e proteção de caracteres HTML; não há consulta ao site externo durante a visita. Mantenha o bloco JSON do preview sincronizado. Os scripts `tmp/inspect-room-source.js`, `tmp/room-details-source.json` e `tmp/prepare-room-details-assets.js` registram as fontes e preparam os assets para desenvolvimento; não são necessários no servidor. O plugin inclui o ícone regular `check` da distribuição oficial Phosphor e reutiliza as setas e o X existentes.

A seção de diferenciais apresenta os três itens do layout: Orla de Atalaia, estrutura acessível e gastronomia. O fundo muda com fade a cada 5 segundos, e cada botão de olho permite selecionar diretamente um item e reiniciar sua contagem. O olho selecionado fica dourado e recebe um círculo de progresso; o hover usa 80% de opacidade. No mobile, o título e a descrição do item selecionado aparecem no cabeçalho e também mudam com fade. Os seletores funcionam por toque, clique, Tab/Enter, setas, Home e End. Não há botão de pausar/reproduzir; o tempo congela automaticamente ao sair da tela ou ocultar a aba. Com `prefers-reduced-motion`, a troca automática fica desativada e as trocas manuais não usam animação. O frame notebook não pôde ser consultado devido ao limite da API do Figma; esse intervalo foi adaptado dos frames mobile e desktop, com os itens empilhados dentro das três colunas no tablet.

A seção de localização usa um mapa interativo do Google Maps, carregado sob demanda em um `iframe` no contêiner do layout. O marcador abre a ficha do Hotel da Costa; o visitante pode mover, ampliar e abrir o mapa no Google. A incorporação pública dispensa chave de API e não carrega bibliotecas JavaScript do Google na página principal. O mapa tem 360 × 360 px no frame mobile, 500 × 500 px no notebook e 720 × 570 px no desktop; nos intervalos, a composição e as calhas variam de forma fluida. O título e o parágrafo seguem a copy atual do Figma. O parágrafo informa aproximadamente 300 m para a Praça de Eventos, 3 km para a Passarela do Caranguejo e 7 km para o aeroporto. Caso o Google Maps esteja indisponível, o conteúdo textual e o CTA permanecem acessíveis.

Para atualizar o destino do mapa, altere `$hdc_lp_location_map_url` em `templates/landing-page.php` e mantenha o preview em sincronia. A incorporação é externa ao plugin, então a disponibilidade, os termos de uso e os dados exibidos dentro do mapa dependem do Google.

A galeria contém as doze fotos da página **LP - Hotel da Costa** do Figma, na ordem: fachada com piscina ao anoitecer, hóspede na acomodação, academia, café da manhã em família, restaurante, videogame, espaço infantil, Copa Baby, mergulho na piscina, lobby, gastronomia e café da manhã na acomodação. Os originais locais correspondentes, na mesma ordem, são `hero-fachada-noite.jpg`, `IMG_2939.jpg`, `IMG_3244.jpg`, `IMG_2476.jpg`, `IMG_2046.jpg`, `IMG_2986.jpg`, `WhatsApp Image 2022-01-03 at 14.41.56.jpeg`, `copa baby.jpeg`, `G0081888.jpg`, `IMG_1768.jpg`, `IMG_2624.jpg` e `IMG_2875.jpg`. O script `tmp/prepare-gallery-assets.py` prepara as versões WebP para distribuição.

Os cards são quadrados: 320 px no frame mobile de 400 px, aproximadamente 400 px no notebook de 1280 px e 384 px no desktop de 1920 px, com larguras intermediárias fluidas. Abaixo de 400 px eles encolhem para caber na área disponível. A partir de 1200 px, o cálculo usa a largura real da trilha para garantir três fotos inteiras; a partir de 1800 px, garante quatro. Assim, a barra de rolagem vertical e os arredondamentos do navegador não cortam a última foto do grupo. Acima de 1920 px, os cards mantêm seu tamanho máximo e mais fotos podem aparecer. As setas continuam disponíveis enquanto existirem fotos fora da área visível. A trilha permite arraste com mouse/caneta, scroll horizontal nativo por toque e setas do teclado.

Ao selecionar uma foto, um `dialog` nativo ocupa toda a viewport, mostra a imagem completa sem recorte, mantém miniaturas na parte inferior e permite navegação circular. Use as setas, miniaturas, teclas ←/→, Home/End ou gesto horizontal no celular. Feche pelo botão ou por Escape. O foco fica restrito ao lightbox e volta à foto selecionada após fechar; a página ao fundo não rola. As imagens maiores só carregam quando selecionadas: até 1920 px, mantendo a fachada no seu original de 1280 px sem ampliação artificial. Os cards usam WebP responsivo de 640/960 px e as miniaturas usam WebP de 160 px. Sem JavaScript ou sem suporte a `dialog`, os links continuam abrindo as imagens em alta resolução. As transições respeitam `prefers-reduced-motion`.

Para adicionar, remover ou reordenar fotos, edite `$hdc_lp_gallery` no template e mantenha o preview atualizado. Para fotos com proporção diferente de 3:2, informe também `height`, correspondente à versão de 640 px de largura. As miniaturas do lightbox são geradas automaticamente com base nesses links, sem biblioteca externa. O fechamento usa o ícone regular `x` da distribuição oficial Phosphor; as setas reutilizam o `arrow-right` local. A galeria segue a estrutura visual dos frames, sem CTA adicional abaixo das fotos. Os três frames e suas doze camadas de imagem foram consultados pelo `figma_bridge`, incluindo os itens fora da área recortada do carrossel; as fotos ocultas foram verificadas com os assets originais do Figma.

Para editar textos e a ordem dos diferenciais, altere o array `$hdc_lp_differentials` no template. Os assets locais `differentials-*` estão em `assets/images/`. O intervalo está definido em `hdcLpInitDifferentials()` e no token `--hdc-lp-differentials-duration`; mantenha ambos em 5000ms para sincronizar o círculo e a troca.

O destino após salvar o formulário pode ser alterado pela integração existente `hdc_lp_whatsapp_url`, com HTTPS em `api.whatsapp.com` ou `wa.me`. O destino padrão desta versão inclui o número **5579998738067** e a mensagem aprovada pelo usuário. Veja o manual de envios para edição.

## Avaliações dinâmicas do Google

### Configurar a conexão

1. Obtenha autorização de um responsável que tenha acesso ao perfil **verificado** do Hotel da Costa. A autorização do responsável não substitui a aprovação do projeto pelo Google.
2. Siga os [pré-requisitos do Google Business Profile](https://developers.google.com/my-business/content/prereqs) e solicite acesso/aprovação ao projeto Google Cloud. Uma quota de zero indica que esse acesso ainda não está liberado. Habilite as APIs **Google My Business**, **My Business Account Management** e **My Business Business Information** necessárias aos endpoints da integração.
3. Configure o consentimento OAuth e crie um cliente do tipo **Aplicativo Web**, com o escopo `https://www.googleapis.com/auth/business.manage`. Apesar de o escopo do Google ser amplo, este plugin apenas consulta contas, perfis e avaliações: não responde a avaliações nem modifica o perfil. Não use credenciais de terceiros no ZIP. Consulte os [requisitos OAuth](https://developers.google.com/my-business/content/implement-oauth).
4. No WordPress, abra **Configurações → Hotel da Costa — Avaliações**. Copie a URI de redirecionamento exibida para o cliente OAuth no Google Cloud. O domínio e o protocolo devem coincidir exatamente. Produção requer HTTPS; localhost é somente para desenvolvimento.
5. Salve o **Client ID** e o **Client secret**, clique em **Conectar conta Google** e confirme o consentimento. O acesso offline permite atualizar os dados sem pedir login diariamente. Projetos OAuth em modo de teste podem emitir refresh tokens que expiram após sete dias; configure o aplicativo para produção e cumpra a verificação exigida pelo Google antes de lançar a campanha.
6. Selecione explicitamente o perfil correto pelo nome e endereço, salve e clique em **Baixar novas avaliações**. Nunca há seleção automática. Reconectar a conta limpa a seleção anterior para impedir a mistura entre perfis.

O painel é exclusivo de administradores (`manage_options`). O segredo é mostrado somente durante sua digitação; deixar o campo vazio mantém o segredo anterior se o client ID não mudou. Credenciais, tokens, listas temporárias de perfis e avaliações são criptografados com AES-256-GCM e uma chave derivada dos salts do WordPress (`wp-config.php`); não são incluídos no HTML, JavaScript ou pacote instalável. OpenSSL é obrigatório. A alteração dos salts invalida os dados criptografados e exige nova conexão. Restrinja também o acesso administrativo, à base de dados, ao `wp-config.php` e aos backups.

### Filtros, cache e atualização

- Inicialmente: **4 e 5 estrelas**, comentário escrito obrigatório e até **20 avaliações**. É possível selecionar estrelas de 1 a 5, incluir avaliações sem comentário e definir de 1 a 50 itens. É obrigatório selecionar ao menos uma quantidade de estrelas.
- Cada atualização consulta até **100 avaliações recentes**, em até duas páginas de 50, ordenadas por última atualização. Uma avaliação antiga fora dessa janela pode não aparecer mesmo que passe pelos filtros. Consulte a [API oficial de avaliações](https://developers.google.com/my-business/reference/rest/v4/accounts.locations.reviews/list).
- O cache só é substituído após a consulta completa das páginas necessárias. Avaliações atualizadas ou removidas são refletidas no novo snapshot. Mudanças de filtro não fazem chamadas ao Google, e a nota média do Google não é recalculada a partir da seleção.
- **Baixar novas avaliações** atualiza manualmente. Há bloqueio de sincronizações concorrentes e intervalo mínimo de um minuto entre tentativas.
- A sincronização diária é feita pelo evento `hdc_lp_reviews_daily_sync`. A limpeza do conteúdo temporário usa `hdc_lp_reviews_daily_cleanup`. O painel mostra a última sincronização completa e mensagens de erro sem expor respostas brutas do provedor.
- Em falhas transitórias, o último cache válido permanece disponível por no máximo **sete dias**, abaixo do limite de armazenamento temporário de 30 dias nas [políticas do Google](https://developers.google.com/my-business/content/policies). Depois disso, o conteúdo é removido e a seção mostra indisponibilidade. Autorização revogada, desconexão ou troca de perfil removem o cache; não há avaliações fictícias como fallback. A lista de perfis expira em 24 horas.
- **Desconectar e remover cache** revoga o token quando possível e remove localmente tokens, perfis e avaliações. Desativar o plugin cancela seus eventos e remove avaliações em cache, mas preserva a configuração criptografada para uma futura reativação; não afeta o tema ou outras páginas.

O WP-Cron depende de visitas. Em sites de pouco tráfego, configure um agendamento no servidor para executá-lo pontualmente, por exemplo a cada hora via WP-CLI: `wp cron event run --due-now --path=/caminho/do/wordpress`. Só desative o WP-Cron disparado por visitas depois de configurar e verificar esse agendamento externo. A expiração é verificada também ao renderizar a seção ou ler o endpoint. A LP envia cabeçalhos `no-cache/no-store` e sinaliza `DONOTCACHEPAGE`; **exclua também a URL da LP e o endpoint do cache HTML da hospedagem/CDN**, principalmente se esse cache responder antes de executar o WordPress. Os assets estáticos podem continuar em cache. No navegador, uma aba mantida aberta remove as avaliações ao vencerem os sete dias. Nenhuma chamada à API do Google é feita durante uma visita à landing page.

### Layout, leitura e origem

Os frames `5427:366`, `5434:699` e `5380:279`, da página **LP - Hotel da Costa**, foram consultados pelo `figma_bridge`. A fotografia local corresponde ao fundo do Figma, otimizado para 800/1600 px. O overlay foi escurecido para garantir contraste com o texto branco mesmo nas áreas claras da imagem, preservando os cards translúcidos e o blur de 16 px. Nomes, avatares e comentários exemplificativos do Figma não foram copiados.

A seção limita o conteúdo a **1280 px** e usa padding de **40 px** no desktop e **20 px** até 767 px. O título tem 40/22 px, o subtítulo 24/18 px e os textos 18/14 px, respectivamente. A largura dos cards é calculada pela trilha disponível: um no mobile, dois no tablet, três a partir de 1200 px e quatro a partir de 1800 px, sem cortar o último card completo. Nomes extensos aumentam a altura comum dos cabeçalhos e cards para manter estrelas e textos alinhados. Dots e identificação de origem acrescentam altura à seção sem cortar o conteúdo.

Cada avaliação tem seu dot. Há setas, Home/End, ←/→, arraste com mouse ou caneta e scroll horizontal nativo por toque. Não há reprodução automática. Os textos maiores mostram um trecho e **Ler mais**, que abre um `dialog` com o depoimento completo, autor, data e estrelas. Feche pelo X, Escape ou clique no fundo; o foco retorna ao botão original. O movimento respeita `prefers-reduced-motion`. Sem JavaScript ou suporte ao diálogo, o texto completo permanece legível. Avatares ausentes ou com erro usam a inicial do autor, sem inventar fotografias.

A legenda **Avaliações selecionadas do Google** e o link **Ver todas no Google** deixam clara a origem e a seleção; não há alegação de que a seleção representa todas as avaliações. Avatares, quando fornecidos, são carregados por HTTPS de `googleusercontent.com`, sem referenciador. Informe esse carregamento externo na política de privacidade e cumpra as regras de atribuição/uso do Google.

### Preview e validação

O endpoint público `GET /wp-json/hdc-lp/v1/reviews` (ou `?rest_route=/hdc-lp/v1/reviews`) fornece somente avaliações filtradas e atuais do cache, URL pública do Google, status e horário de sincronização. Não expõe contas técnicas, credenciais, tokens, permissões administrativas ou operações de atualização. O endpoint responde com `Cache-Control: no-store`.

No desenvolvimento, o servidor `tmp/serve-preview.js` usa `/preview/api/reviews` para ler esse endpoint no WordPress local da porta **8787**, com destino fixo e somente GET. Reinicie o servidor de preview após atualizá-lo. Sem WordPress/conexão configurada, o preview mostra o estado vazio. Esse proxy não é necessário nem incluído no plugin; o WordPress renderiza o conteúdo diretamente no servidor.

Os scripts `tmp/check-reviews.js`, `tmp/check-reviews-backend.php` e `tmp/check-wordpress-reviews.js` usam dados **simulados somente nos testes**. Testam larguras de 320, 400, 768, 1024, 1280, 1440, 1920 e 2560 px, filtros, paginação, cache, erros, revogação, teclado, dots, arraste, pop-up, SSR e paridade. As fixtures temporárias e o usuário administrativo de teste são removidos ao finalizar o teste local. Os arquivos de teste não entram no ZIP. A sincronização real só pode ser validada após configurar credenciais aprovadas e concluir a autorização Google.

## Rodapé

Os frames `5428:428` (mobile), `5434:761` (notebook) e `5380:348` (desktop), da página **LP - Hotel da Costa**, foram consultados pelo `figma_bridge`. O logo branco foi exportado do próprio Figma em PNG transparente de 240 × 240 px. O rodapé é um landmark separado, fora de `<main>`, e não carrega nem modifica o rodapé do tema ativo.

Até 767 px, a composição é vertical e centralizada, com logo de 100 px, intervalo de 20 px e texto de 16 px. Usa padding de 40 px na vertical e 20 px nas laterais, como no frame mobile. A partir de 768 px, logo de 120 px à esquerda e texto de 18 px alinhado à direita. O padding vertical cresce de 20 px em 1280 px até 40 px em 1920 px; as calhas horizontais acompanham as da landing page, de 20 até 80 px. A altura é definida pelo conteúdo: aproximadamente 273 px em 400 px, 160 px em 1280 px e 200 px em 1920 px, sem cortar o texto em telas menores. Não há JavaScript ou animação no rodapé.

Por autorização explícita, a copy deste componente segue o Figma: **Site desenvolvido e criado pela agência Checkin © 2026. Todos os direitos reservados.** O ano permanece o aprovado no layout. **Política de privacidade** é, por enquanto, apenas um texto sublinhado, sem link, cursor de ação ou parada de foco. Nenhuma página de privacidade é criada ou publicada pelo plugin.

Quando a página estiver pronta e aprovada, configure o filtro opcional `hdc_lp_privacy_policy_url` por meio de uma integração própria do site. Por exemplo, para usar a página publicada definida em **Configurações → Privacidade**:

```php
add_filter(
	'hdc_lp_privacy_policy_url',
	static function () {
		return get_privacy_policy_url();
	}
);
```

Somente URLs HTTP/HTTPS são aceitas e escapadas. Se o filtro retornar uma URL vazia, o texto continuará sem link. O futuro link terá foco visível e área de toque ampliada. Mantenha a marcação do preview sincronizada quando configurar esse destino; atualmente ela reproduz o estado padrão, sem link.

O teste local `tmp/check-footer.js` compara preview e WordPress em 320, 400, 768, 1024, 1280, 1440, 1920 e 2560 px, incluindo dimensões, alinhamento, logo, copy, ausência de overflow, privacidade sem link, funcionamento sem JavaScript e isolamento dos assets. Os arquivos de teste não são incluídos no ZIP.

## Antes de publicar

- Confirme o destino oficial da campanha de WhatsApp.
- Confirme o número do endereço exibido na ficha do Google Maps: o mapa atualmente mostra **1807**, enquanto o destino configurado e o site oficial informam **1835**. O marcador abre a ficha do Hotel da Costa.
- Reconfirme o selo `Travellers’ Choice 2026`, a nota do Google e a quantidade de avaliações, pois são informações dinâmicas.
- Valide a página em staging antes de substituir qualquer campanha em produção.

## Política de privacidade — versão 1.23.0

Novo modelo independente Política de Privacidade — Hotel da Costa, sem cabeçalho e rodapé do tema. O conteúdo permanece editável no WordPress. CSS e fontes locais carregam apenas nesse modelo. Consulte MANUAL-PRIVACIDADE.md.

