Crie neste repositório uma skill reutilizável chamada hotel-lp-wordpress para desenvolver landing pages de hotéis a partir do Figma e entregá-las como plugins WordPress independentes, instaláveis em ZIP.

Quero consolidar as regras e os comportamentos amadurecidos no projeto Hotel da Costa para não precisar repetir instruções sobre animações vinculadas ao scroll, formulários com UTMs, carrosséis, pop-ups, responsividade, SEO, desempenho, monitoramento e avaliações reais.

A entrega desta tarefa é a skill com suas referências, uma base de código reutilizável e ferramentas de validação. O Floral Inn é o próximo caso de aplicação; este pedido não autoriza publicar uma landing page ou alterar o Hotel da Costa em produção.

**1. Objetivo e forma de uso**

Depois de preparar a skill e a base, quero iniciar um projeto com uma instrução curta, por exemplo:

    Use hotel-lp-wordpress para desenvolver a landing page deste hotel:
    [LINK DO FIGMA]
    Use o perfil do hotel deste repositório e entregue o plugin instalável,
    o manual e os resultados das validações.

A skill deve ser reconhecível para pedidos de landing pages de hotéis feitas para WordPress e a partir do Figma. O AGENTS.md do projeto deve indicar seu uso explicitamente, para não depender exclusivamente da seleção implícita.

O link do Figma é suficiente para iniciar a análise e implementação independente. Dados ausentes de WhatsApp, perfil de avaliações, domínio, credenciais ou staging não podem ser inventados. Consulte primeiro a configuração e as fontes disponíveis, peça somente o que faltar de fato e continue as partes que não dependem da resposta.

Não quero somente um texto de boas práticas: transforme os comportamentos recorrentes em componentes reutilizáveis e verificações executáveis, evitando reconstruí-los a cada hotel.

**2. Fontes para extrair os aprendizados existentes**

Se o projeto original estiver acessível nesta máquina, leia estes arquivos em modo de consulta:

    C:\Users\henri\Documents\Programação\hotel-da-costa\AGENTS.md
    C:\Users\henri\Documents\Programação\hotel-da-costa\hotel-da-costa-landing-page\README.md
    C:\Users\henri\Documents\Programação\hotel-da-costa\hotel-da-costa-landing-page\MANUAL-DE-ENVIOS.md
    C:\Users\henri\Documents\Programação\hotel-da-costa\hotel-da-costa-landing-page\MANUAL-TRUSTINDEX.md
    C:\Users\henri\Documents\Programação\hotel-da-costa\hotel-da-costa-landing-page\MANUAL-PRIVACIDADE.md
    C:\Users\henri\Documents\Programação\hotel-da-costa\processo-figma-wordpress\README.md
    C:\Users\henri\Documents\Programação\hotel-da-costa\processo-figma-wordpress\PROMPTS.md
    C:\Users\henri\Documents\Programação\hotel-da-costa\processo-figma-wordpress\templates\

Inspecione também a implementação pertinente em templates/, includes/, assets/js/, assets/css/ e assets/data/ do plugin original, incluindo formulário, envios, campanhas, tracking, SEO, detalhes das acomodações, avaliações e Trustindex. Os testes existentes em tmp/ são referências de cenários; não copie essa pasta inteira.

O plugin consultado como referência estava na versão 1.25.2 em 14/10/2026. Confira o estado real antes de extrair código.

Figma do Hotel da Costa, como referência do projeto de origem:
https://www.figma.com/design/T0967OaYPkcFB4TuMviA9R/Todos-projetos?node-id=5308-3

Figma do próximo hotel, Floral Inn:
https://www.figma.com/design/T0967OaYPkcFB4TuMviA9R/Todos-projetos?node-id=5703-3

Não altere o projeto original para criar a skill. Extraia somente o que for necessário para o novo repositório. Remova dependências de caminhos locais do funcionamento normal da skill: a distribuição precisa conter os recursos reutilizáveis de que depende.

Se os arquivos originais não estiverem disponíveis, use as regras deste prompt como especificação, informe quais comportamentos não puderam ser conferidos no código e não afirme ter consultado o histórico completo. Não bloqueie o trabalho independente por falta dessa referência.

**3. Prioridade das regras e registro das decisões**

Siga, nesta ordem: instrução mais recente do usuário; regras vigentes do projeto; perfil e exceções explicitamente aprovadas; especificação reutilizável; fontes auxiliares.

O Figma é a fonte oficial do layout e da copy institucional de cada hotel, sujeito às regras e substituições explicitamente aprovadas pelo usuário.

Documentos históricos, código legado e README de versões antigas são evidências, não autorização para contrariar o AGENTS.md atual. Há registros antigos do Hotel da Costa com contêiner sem limite de 1280 px, calhas diferentes e tamanhos específicos de componentes; não os transforme automaticamente em regras gerais.

Registre separadamente:

- Padrões reutilizáveis que valem por default.
- Dados, imagens e integrações de cada hotel.
- Exceções aprovadas, com componente, valor, fonte e alcance.
- Divergências encontradas no legado que ainda não têm autorização demonstrada.

Não transforme uma correção pontual de um hotel em exigência universal. Se uma fonte divergir da regra vigente, mantenha a regra vigente, registre o conflito e avance no restante.

**4. Estrutura esperada no repositório**

Organize uma skill versionável e portátil. Uma estrutura inicial adequada é:

    AGENTS.md
    README.md
    .agents/skills/hotel-lp-wordpress/
        SKILL.md
        agents/openai.yaml
        references/
        assets/wordpress-starter/
        scripts/
    examples/
        hotel-profile.example.json
        floral-inn/
    tests/

Adapte os nomes e a quantidade de arquivos à necessidade real, sem criar diretórios vazios ou documentação duplicada.

O SKILL.md deve conter frontmatter válido, nome, descrição precisa, resultado esperado, regras essenciais e indicação de quando ler cada referência. Mantenha o conteúdo detalhado nas referências pertinentes.

O README do repositório deve explicar como disponibilizar a skill para outros projetos, como iniciar uma LP, como atualizar a base e como preservar os dados específicos dos hotéis. Confira os mecanismos de descoberta suportados pelo Codex atual; não pressuponha que manter a skill em um repositório separado a torna automaticamente disponível em qualquer projeto.

Use a skill-creator disponível para orientar a criação e validar o manifesto. Preserve o uso automático da skill quando aplicável, além da invocação explícita. Não instale configurações globais sem necessidade para entregar este repositório.

Crie recursos executáveis somente quando forem úteis: inicialização do projeto, verificação de configuração, empacotamento e validação dos componentes. Execute e confira os scripts criados.

**5. Perfil independente por hotel**

Defina um esquema de configuração sem credenciais, com validação e exemplo. Inclua:

- Nome público, slug do plugin, nomes dos templates, idioma e prefixos técnicos únicos.
- URL do Figma, página, nodes dos frames e data da consulta.
- Fontes da copy, dos assets e das informações comerciais.
- Marca, fontes licenciadas e tokens visuais por hotel.
- Destino e mensagem aprovados de WhatsApp.
- Identificação do formulário e política de atribuição de UTMs.
- Grid, padding, tipografia e exceções aprovadas por componente.
- Seções presentes, ordem e coleções de serviços, acomodações, diferenciais e galerias.
- Configuração pública de SEO e imagem de compartilhamento.
- ID e estado habilitado/desabilitado do Google Tag Manager.
- Fonte de avaliações, plataforma, identificação do estabelecimento, URL pública, filtros e origem dos dados.
- Política de privacidade publicada, quando existir.
- Termos obrigatórios/proibidos e dados comerciais que precisam de confirmação.
- Ambiente de desenvolvimento, versões verificadas e situação da homologação.

Credenciais e tokens devem ficar na configuração protegida do ambiente ou no mecanismo administrativo apropriado, fora do perfil versionado.

Campos opcionais não preenchidos precisam de estados coerentes. Não preencher WhatsApp, GTM, notas, prêmios, avaliações, imagens ou endereço de outro hotel como fallback.

O exemplo Floral Inn deve conter apenas identificação e referência ao Figma, além do que for efetivamente confirmado. Não produzir dados comerciais fictícios para completar o perfil.

**6. Consulta obrigatória pelo figma_bridge**

Use sempre o figma_bridge para ler o Figma. Não substitua silenciosamente por outro conector, screenshot isolado, código genérico ou memória de uma consulta anterior.

Liste os arquivos atualmente conectados e identifique o documento correto. O identificador do arquivo conectado pode ser diferente da chave encontrada na URL; não invente a correspondência. Converta node-id com hífen na URL para o formato com dois-pontos quando a ferramenta exigir.

Leia os nodes exatos, o contexto/árvore, os textos, estilos, dimensões e screenshots necessários. Use as ferramentas disponíveis na conexão atual; divida por seção quando a resposta for grande ou truncada.

Inspecione desktop, notebook, mobile e estados extras que existirem. Verifique filhos ocultos e conteúdo fora da área recortada de carrosséis: a screenshot do frame não representa necessariamente todos os itens.

Extraia a copy exata, respeitando pontuação, acentos, rótulos, ordem e substituições aprovadas. Obtenha logo, fotos, ícones, recortes, tipografia e componentes corretos.

Registre arquivo/página/nodes, data da consulta, mapa das seções, inventário de assets, screenshots e decisões para larguras sem frame. Evidências locais servem para rastreabilidade, sem substituir a fonte oficial.

Não modifique o arquivo do Figma como efeito colateral da leitura. Se a conexão não estiver disponível, preserve as evidências existentes, informe o impedimento e continue apenas o que pode ser feito com elas, sem alegar nova consulta.

Código retornado pelo conector é referência: traduza para a arquitetura PHP/HTML/CSS/JavaScript nativo autorizada.

**7. Arquitetura WordPress obrigatória**

Entregue cada LP como plugin independente e instalável em ZIP, com template próprio selecionável no editor de páginas.

Use HTML5 semântico, CSS3, JavaScript nativo e PHP compatível com as versões de WordPress/PHP verificadas no destino.

Não introduza React, Vue, Svelte, Tailwind, page builder ou dependência de runtime/build sem autorização explícita. Ferramentas de desenvolvimento não podem exigir Node.js no servidor WordPress. Se houver build autorizado, distribua os assets compilados.

Preserve tema ativo, functions.php, páginas existentes, menus, estilos globais e demais integrações. No Hotel da Costa, o tema a preservar é hoteldacosta2026; nos outros hotéis, use o tema real identificado no ambiente.

Carregue estilos, scripts, metadados, tracking e integrações da LP somente no template correspondente. Carregue recursos administrativos somente na tela pertinente.

Use prefixos únicos por projeto em classes, IDs técnicos, atributos data, funções, classes PHP, constantes, opções, tabelas, hooks, rotas REST e handles. hdc-lp/hdc_lp pertencem ao Hotel da Costa e não devem ser reaproveitados indiscriminadamente em plugins de hotéis diferentes.

Mantenha cabeçalho e rodapé próprios, escopo CSS na raiz da LP e código de comportamento independente do tema.

Atualizar, desativar e reativar o plugin deve preservar leads, campanhas e configurações salvas. Cancele agendamentos que pertencem ao plugin durante a desativação. Não apague dados de terceiros.

Migrações precisam ser idempotentes e preservar dados. Remover arquivos do plugin não deve apagar automaticamente a tabela de envios.

**8. Grid, padding e tipografia de referência**

Estabeleça estes padrões por default, salvo substituição explícita aprovada no perfil do projeto:

- Conteúdo centralizado, com width: 100%, max-width: 1280px e margin-inline: auto.
- Seção externa em largura total e contêiner interno limitado ao grid.
- Fundos podem ocupar a viewport; textos, cards, galerias e CTAs permanecem dentro do contêiner.
- Todas as seções com 40 px de padding nos quatro lados no desktop.
- Todas as seções com 20 px de padding nos quatro lados no mobile.
- Mobile até 767 px; regras de tipografia e padding desktop a partir de 768 px.
- Desktop: título 40 px, subtítulo 24 px, texto 18 px.
- Mobile: título 22 px, subtítulo 18 px, texto 14 px.

Não remova padding de hero, galeria ou CTA final para acomodar conteúdo. Não diminua a fonte obrigatória para esconder problemas de layout.

Todo conteúdo textual, inclusive controles, erros, legendas, rodapé, pop-ups e conteúdo dinâmico, deve receber text-wrap. Títulos/subtítulos: balance. Textos corridos: pretty. overflow-wrap: anywhere somente para conteúdo realmente sujeito a overflow, como URLs longas.

Representar os tamanhos obrigatórios por tokens, sobrescritos no breakpoint mobile. A família tipográfica vem do projeto/Figma; não universalize as fontes do Hotel da Costa.

Se o novo Figma apresentar tamanhos diferentes do padrão, registre a diferença. O desenho sozinho não revoga a regra explícita do projeto.

**9. Tokens, ícones e qualidade visual**

Armazene todas as cores em variáveis CSS semânticas escopadas na raiz da LP. Componentes não podem conter cores literais fora das declarações dos tokens, inclusive em gradientes, overlays, sombras, bordas, focus, hover e estados de erro.

Centralize também espaçamentos recorrentes, tipografia, pesos, largura, raios, sombras, durações, easing e transições. Prefira nomes pelo papel, como color-action e color-text-primary.

Não use estilos inline, salvo valores dinâmicos estritamente necessários e sanitizados. Não use !important como solução padrão.

Use exclusivamente a distribuição oficial do Phosphor Icons, empacotada localmente e com peso/tamanho/alinhamento consistentes. Não misture emojis ou outras bibliotecas como ícones de interface.

Ícones decorativos devem ter aria-hidden. Controles importantes precisam de rótulo textual acessível. Hover e foco devem manter contraste; texto e seta de VER DETALHES recebem o mesmo estado visual.

Preserve a linguagem visual do Figma. Não substitua a composição aprovada por um layout genérico de landing page.

**10. Copy e informações comerciais**

Não reescreva, resuma, complete ou substitua a copy institucional sem autorização explícita. Rótulos funcionais necessários devem ser coerentes e não acrescentar promessas comerciais.

Todos os CTAs de conversão devem exibir exatamente RESERVAR VIA WHATSAPP, inclusive o botão de envio do formulário.

Use o destino da campanha aprovado para o hotel. Não transportá-lo do hotel anterior.

Fotos, descrições, categorias de acomodação, características e vista para o mar precisam de fonte por item. Se o Figma repetir a copy de uma acomodação, não atribua essa descrição às demais: preserve descrições específicas já aprovadas ou registre a falta de conteúdo.

Notas, totais de avaliações, prêmios, distâncias, horários, políticas, estacionamento e capacidade de eventos devem ter fonte e data de confirmação. Não publicar informação conflitante ou não confirmada como promessa.

Depoimentos e números exemplificativos do Figma não são avaliações autênticas. Dados simulados são permitidos somente em testes identificados.

Regras de localização e termos proibidos pertencem ao perfil do hotel. Não transferir automaticamente a restrição de hotel pé na areia para um estabelecimento diferente.

**11. Animação de texto vinculada ao scroll**

Crie um componente reutilizável para a animação já aplicada no Hotel da Costa, parametrizado por tokens/configuração:

- Entrada por opacidade e deslocamento vertical inicial de 30 px.
- Ponto de revelação quando o topo do elemento alcança 90% da altura da viewport.
- Entrada de 800 ms; retorno ao estado inicial de 600 ms.
- Easing de referência: cubic-bezier(0.215, 0.61, 0.355, 1).
- Ao subir a página e o elemento voltar abaixo da linha de ativação, reinicie o estado para permitir nova entrada ao descer.
- Ao atingir o final do documento, garanta visibilidade dos elementos restantes.
- Sincronize scroll/resize com requestAnimationFrame e listeners passivos quando aplicável.
- Sem JavaScript ou com prefers-reduced-motion, deixe o conteúdo visível sem movimento.

Este comportamento é revelação disparada pela posição de scroll. Não descreva como interpolação contínua/scrub, nem introduza esse outro efeito sem pedido.

Aplique a animação aos elementos previstos no layout, sem impedir leitura, foco ou acionamento por teclado. Documente quais elementos recebem o comportamento.

Separe as animações de entrada da hero das revelações de scroll. Extraia os tokens e a sequência aprovados quando forem pertinentes ao desenho do novo hotel; não imponha a sequência de elementos do Hotel da Costa a um frame diferente.

Se o layout tiver um componente de diferenciais com troca automática, preserve o padrão existente quando aplicável: intervalo de 5 segundos, fade sincronizado, seleção manual reinicia a contagem, pausa fora da viewport/aba oculta e troca automática desativada com movimento reduzido. Não introduza autoplay em carrosséis que não o possuem.

**12. Formulário, pop-up de conversão e WhatsApp**

Todos os CTAs de conversão abrem o mesmo pop-up de captação. Campos obrigatórios: nome, e-mail e celular.

O padrão amadurecido usa formulário com ID form_popup, pedido para identificação em ferramentas de acompanhamento. Registre-o como exceção deliberada ao prefixo dos IDs, garanta ocorrência única na página e mantenha os seletores internos prefixados. Uma alteração desse identificador precisa ser registrada no perfil e nas integrações.

Use rótulos, autocomplete, inputmode, mensagens por campo e estado geral acessível. O fade de abertura/fechamento é de 200 ms, desativado com movimento reduzido.

Nome: limite de 150 caracteres. E-mail: 254. Celular brasileiro: máscara (XX) XXXXX-XXXX, onze dígitos com DDD e maxlength de 15 caracteres formatados. Normalize a colagem com +55 antes de aplicar o limite. Valide também no servidor. Alterações para telefone fixo ou internacional devem ser explícitas no perfil.

Capture estas cinco UTMs:

    utm_source
    utm_medium
    utm_campaign
    utm_content
    utm_term

Política de atribuição de referência:

- Ler os parâmetros da URL atual.
- Preservar o conjunto na aba via sessionStorage, com chave isolada por projeto/campanha conforme o escopo.
- Recarregar ou voltar sem parâmetros mantém o conjunto salvo.
- Uma URL que contenha qualquer nova UTM substitui o conjunto inteiro; os parâmetros ausentes nessa nova URL ficam vazios.
- Não combinar UTMs de campanhas diferentes.
- Se o armazenamento estiver indisponível, usar apenas a URL atual.
- Validar e limitar cada UTM a 200 caracteres no servidor.

Persistir o lead no WordPress antes de abrir o WhatsApp, na mesma aba. Aceitar somente os destinos HTTPS de WhatsApp autorizados e mensagem aprovada.

Implementar idempotência: repetir o mesmo envio com o mesmo identificador e dados retorna o cadastro existente. Prevenir envio concorrente por cliques repetidos.

Em erro, manter os campos para nova tentativa e informar o problema. Fechar o pop-up cancela um redirecionamento pendente; não promete desfazer um envio já recebido pelo servidor.

Validar nonce, página/template, campos, honeypot e limite de tentativas. O padrão existente limita a dez tentativas em dez minutos por origem usando hash temporário, sem gravar IP bruto no cadastro. Nonce não substitui autorização administrativa.

Não expor leads em endpoint público. Sem JavaScript, mostrar uma orientação clara; não simular captação bem-sucedida.

**13. Gestão de envios no WordPress**

Disponibilize uma tela administrativa de envios com acesso restrito:

- Lista do mais recente ao mais antigo, com paginação de vinte itens.
- Busca por ID, nome, e-mail ou telefone.
- Filtros por formulário, página, período e leitura.
- Detalhes do registro e cinco UTMs.
- Marcar lido/não lido, mover para lixeira e restaurar.
- Exclusão definitiva com confirmação, somente na lixeira.
- Exportação de todos os resultados filtrados e dos registros selecionados.

CSV de referência: UTF-8, separador ponto e vírgula, telefone formatado e datas no fuso do WordPress. Neutralize valores que possam executar fórmulas ao abrir em planilhas.

Use permissões, nonces, sanitização e escape. Preserve a tabela e seus registros em atualizações, desativação e remoção dos arquivos; exclusão de dados exige ação específica.

Documente acesso, retenção e edição sem inventar política comercial ou jurídica.

**14. Carrosséis e dots calculados pelos dados**

Crie um componente compartilhado, configurável, com quantidade de itens, largura/gap reais da trilha, navegação e indicador ativo derivados do conteúdo.

Nos carrosséis com indicadores, o padrão aprovado é um dot por item real: serviço, acomodação, imagem do pop-up ou avaliação. Não fixe oito, onze ou qualquer outra quantidade no código.

Não crie dots em componentes cujo layout aprovado não possui dots, como certas galerias e campanhas.

Todos os dots devem continuar acionáveis e identificáveis mesmo quando vários cards/imagens aparecem simultaneamente. Trate corretamente os últimos itens e posições que compartilham o mesmo limite físico de scroll.

Mantenha indicador ativo e item selecionado coerentes após clique, setas, arraste, toque, mudança de largura, carregamento de imagens e alteração dos dados. Teste os últimos dots; não use somente uma regra de arredondamento do scroll que torne itens finais inacessíveis.

Use ResizeObserver ou mecanismo equivalente quando pertinente, dimensões reais e tratamento do estado com zero ou um item. Não calcular largura apenas com a viewport ignorando a trilha ou a barra de rolagem.

Permita mouse/caneta com arraste horizontal e touch com rolagem nativa, preservando rolagem vertical, zoom e clique quando não houver arraste. Reconheça direção e limiar de gesto. Não encerre o arraste por um lostpointercapture que veio de um filho da trilha.

Inclua teclado com setas, Home/End, foco visível e rótulos acessíveis. Circularidade depende do componente e da regra aprovada; não é universal.

Setas ficam desabilitadas quando não há deslocamento possível, preservando a apresentação aprovada. Navegação suave respeita prefers-reduced-motion.

Coleções maiores que a tela, dots e textos extensos não podem causar overflow na página. Rolagem horizontal dentro da trilha é permitida e não equivale a overflow horizontal do documento.

**15. Pop-ups de acomodações e lightbox**

Use preferencialmente dialog nativo, com nome acessível, Escape, fechamento explícito, foco restrito ao diálogo, retorno ao acionador e bloqueio de scroll de fundo. Preserve posição de leitura ao fechar.

O pop-up de cada acomodação usa seus próprios dados: título, descrição, características e imagens corretas. Não preencher categorias diferentes com a mesma descrição por conveniência.

Carregue imagens maiores e galerias quando o pop-up correspondente abrir, em vez de antecipar todas as fotos da página.

No padrão de composição existente, a galeria mostra uma foto no mobile, duas entre 768 e 1023 px e três a partir de 1024 px, quando o layout comportar. O número total de fotos não é limitado a essas quantidades.

O título fica no fluxo normal, sem cabeçalho sticky. Em telas baixas, adapte altura de fotos e espaçamento interno sem reduzir fontes ou remover o padding obrigatório. Preserve rolagem quando o conteúdo não couber, inclusive com zoom.

Como referência do componente original: limite de 1280 x 800 px no desktop, fotos entre 120 e 320 px de altura e pop-up em viewport completa no mobile. Esses limites não podem cortar conteúdo e devem ser adaptados às regras do projeto.

Galeria geral: lightbox com imagem completa sem recorte, miniaturas geradas pelos dados, navegação, Home/End, setas e gesto horizontal. Não ampliar artificialmente um original de baixa resolução.

Quando existir página de acomodação ou arquivo de imagem como fallback aprovado, preserve o link sem JavaScript/dialog. Não invente destinos.

**16. Campanhas e ofertas editáveis**

Reaproveite o gerenciamento de campanhas quando a seção existir no novo Figma:

- Cadastro, ativação/desativação e ordenação.
- Imagem pela Biblioteca de Mídia nativa e texto alternativo.
- Ordenação por arraste e controles acessíveis de mover acima/abaixo.
- JPEG, PNG e WebP válidos; proporção recomendada de referência 4:5.
- Imagem completa, sem cortar texto das artes ou inventar tarifas.
- IDs estáveis, validação dos anexos e rejeição de submissões inválidas/truncadas.
- Remover campanha não apaga o arquivo da Biblioteca de Mídia.
- Imagem excluída invalida somente o item e gera orientação no painel.
- Sem campanhas ativas válidas, ocultar toda a seção correspondente.
- Uma lista salva vazia permanece vazia, sem restaurar campanhas padrão.
- Atualizar/desativar/reativar não sobrescreve a lista editada.

Se houver endpoint público de campanhas, ele deve ser somente leitura e retornar apenas dados públicos dos itens ativos válidos, sem nomes internos ou itens inativos.

Não introduza automaticamente agendamento, links individuais ou edição da copy institucional: essas extensões precisam pertencer ao escopo do hotel.

**17. Avaliações reais: Trustindex com Google ou Tripadvisor**

Esta skill deve contemplar duas integrações Trustindex distintas:

    trustindex_google
    trustindex_tripadvisor

Google e Tripadvisor são plataformas de origem; Trustindex é o fornecedor/intermediário. Mantenha esses conceitos separados no modelo de dados e nos rótulos.

Referências oficiais para consulta da versão e recursos:

- Widgets for Google Reviews, por Trustindex:
  https://wordpress.org/plugins/wp-reviews-plugin-for-google/
- WP Tripadvisor Review Widgets, por Trustindex:
  https://wordpress.org/plugins/review-widgets-for-tripadvisor/
- Atualização de avaliações no Trustindex:
  https://trustindex.zohodesk.eu/portal/en/kb/articles/my-reviews-aren-t-updating

Instale/configure o plugin oficial correspondente no staging conforme a fonte escolhida. Declare a dependência externa; não inclua o plugin de terceiros dentro do ZIP da LP.

Preserve os cards, tipografia, fundo, ícones, carrossel e pop-up da LP. O Trustindex fornece os registros importados; não renderize automaticamente seu widget/shortcode nem seu CSS/loader na LP.

No projeto original, o adaptador Google usava getPageDetails() e getReviews() da versão 14.2. Isso é uma referência de implementação, não garantia de interface estável nem evidência de suporte ao Tripadvisor.

Para cada adaptador, inspecione a versão efetivamente instalada, os pontos públicos de acesso, o formato dos dados e as condições de uso. Não invente classes/globais/métodos do Tripadvisor copiando nomes do Google. Evite acoplamento a tabelas privadas quando existir um ponto de acesso apropriado.

Se não existir acesso compatível e permitido aos dados para preservar o desenho próprio, registre a limitação do adaptador. Não alegue suporte completo, não faça scraping direto do Google/Tripadvisor e não substitua por widget de terceiros sem aprovação da mudança visual.

O painel da LP deve permitir escolher a fonte disponível e confirmar explicitamente nome, endereço e identificador do estabelecimento. Não selecionar automaticamente o primeiro perfil.

Uma troca do perfil conectado no Trustindex suspende a exibição até nova confirmação na LP. Identificação deve considerar plataforma e estabelecimento, evitando misturar hotéis ou fontes.

Leia apenas avaliações reais importadas. Respeite ocultações e preferências aplicáveis definidas no fornecedor. Nunca use métodos que criem avaliações de demonstração.

Use um contrato de dados neutro, com validação, por exemplo:

    provider
    platform
    businessId
    reviewId
    author
    avatarUrl
    rating
    originalRatingScale
    comment
    publishedAt
    publicReviewUrl
    publicProfileUrl
    lastSyncedAt
    status

Não invente nome, foto, nota, texto, link ou data ausente. Preserve o conteúdo original com escape seguro. Só informe sincronização quando houver um dado real e confiável do fornecedor.

Imagens de avatar devem ter validação HTTPS/domínio adequada à plataforma. Não reutilize a allowlist de googleusercontent.com para rejeitar ou aceitar indevidamente fotos do Tripadvisor. Na ausência de avatar válido, use inicial do autor.

Não manter uma cópia adicional dos registros Trustindex sem necessidade e respaldo do fornecedor. O armazenamento/importação permanecem sob o gerenciamento do plugin correspondente. Não aplicar automaticamente a política de expiração do adaptador Google Business Profile a essa fonte.

Filtros de referência: quatro e cinco estrelas, comentário obrigatório e até vinte itens desejados, respeitando o número realmente disponibilizado pelo plano. Não completar a quantidade com dados fictícios. Validar escala de nota antes de normalizar.

Nos plugins gratuitos consultados, a referência é importação de até dez avaliações e atualização manual; confirme limites e frequência atuais por plugin/plano. Não prometer sincronização automática ou quantidade ilimitada sem recurso contratado e verificado. A LP não deve contornar esses limites nem agendar downloads não suportados.

Estados obrigatórios: pronto, vazio, sem conexão, plugin ausente, versão incompatível, perfil alterado e dados inválidos/indisponíveis. Um problema nessa fonte não pode quebrar o restante da LP.

Exibir atribuição fiel: Avaliações selecionadas do Google / Ver todas no Google ou Avaliações selecionadas do Tripadvisor / Ver todas no Tripadvisor, conforme a plataforma real e o link confirmado.

Não rotular avaliações do Tripadvisor como Google. Não calcular a nota global da empresa a partir dos comentários filtrados. Não misturar notas de plataformas distintas ou inventar uma média conjunta.

A seleção de fontes combinadas não é requisito padrão. Se futuramente autorizada, cada avaliação deve manter a plataforma explícita e estatísticas separadas.

Uma fonte desconectada não pode ser silenciosamente substituída pela outra. Enquanto Trustindex estiver selecionado, suspenda os agendamentos próprios da API Google que deixaram de ser necessários.

**18. API oficial do Google como fonte opcional**

Preserve o caminho já implementado para Google Business Profile como alternativa separada:

    google_business_profile

Não confunda essa API com Trustindex nem com avaliações do Tripadvisor.

O uso depende de credenciais, autorização do responsável e acesso aprovado no Google. Implemente somente consulta das contas/perfis/avaliações necessárias, sem responder ou editar avaliações.

Credenciais e tokens protegidos no servidor, sem exposição em HTML, JavaScript, REST público, logs ou ZIP. Reaproveite o armazenamento criptografado já existente quando compatível, verificando requisitos e tratamento da troca de salts.

Selecione o perfil explicitamente. Reconexão/troca de perfil limpa seleção e dados incompatíveis. Desconexão remove cache e tokens locais, com revogação quando disponível.

Como referência do componente original:

- Filtros de quatro/cinco estrelas, comentário obrigatório, limite desejado de vinte.
- Limite configurável de um a cinquenta itens e seleção válida de estrelas.
- Consulta limitada a até cem avaliações recentes, conforme paginação suportada.
- Cache substituído somente após a consulta completa bem-sucedida.
- Sincronização diária, controle de concorrência e intervalo mínimo entre tentativas.
- Falhas transitórias mantêm cache válido por até sete dias; depois, estado indisponível.
- Nenhuma chamada à API do Google durante uma visita à LP.

Confira documentação e políticas atuais antes de consolidar detalhes dependentes do fornecedor:
https://developers.google.com/my-business/content/prereqs
https://developers.google.com/my-business/content/implement-oauth
https://developers.google.com/my-business/reference/rest/v4/accounts.locations.reviews/list
https://developers.google.com/my-business/content/policies

WP-Cron depende de tráfego. Documente agendamento externo quando necessário, sem mudar a configuração global do site automaticamente.

**19. Apresentação e testes das avaliações**

Renderize os dados públicos no WordPress usando o mesmo componente independentemente do adaptador, preservando a identificação da plataforma.

Se houver endpoint de avaliações, exponha somente os itens públicos filtrados, origem, status, links públicos e informações de atualização disponíveis. Não permita sincronização administrativa pelo endpoint público.

Um dot por avaliação. Sem autoplay. Texto longo pode usar Ler mais com diálogo contendo o comentário completo, autor, data e nota reais.

Nomes e comentários extensos não devem cortar estrelas, controles ou conteúdo. Use cards com alinhamento consistente e contraste verificado no fundo real. Imagens/overlays precisam ser adequados à leitura.

Teste cada adaptador separadamente: perfil correto, troca de perfil, plugin desativado, versão incompatível, dados ocultados, conjunto vazio, filtros, limite do plano, avatares, UTF-8, comentário longo, atribuição e persistência da seleção.

Mock testa o contrato e a interface; somente uma importação/leitura real confirma a conexão. Indique claramente quais fontes foram testadas com dados reais e quais ainda dependem de acesso.

**20. SEO isolado e monitoramento**

Inclua uma configuração de SEO própria da LP:

- Um título e uma descrição coerentes com a copy e os fatos aprovados do hotel.
- Canonical e og:url com permalink real, sem UTMs ou endereço fixo do preview.
- Open Graph e Twitter Card com imagem de referência de 1200 x 630 px.
- Idioma do documento correspondente ao conteúdo; pt-BR para os projetos de referência.
- JSON-LD válido para hotel/página com fatos confirmados.
- Sem preços, prêmios, classificações, aggregateRating ou estrelas de rich results inventados.
- Sem duplicação com Yoast, Rank Math ou outros plugins de SEO instalados.
- Alterações de hooks/presenters restritas à LP, preservando sitemaps e demais páginas.
- Respeito a noindex/nofollow existentes, páginas não publicadas ou protegidas e ambientes local/staging.

Não alterar indexação global, sitemap ou Search Console como efeito colateral. Não prometer posicionamento ou pontuação perfeita.

Reaproveite o painel de Google Tag Manager com ID configurável por hotel, validação do formato, ativação/desativação e persistência.

Quando ativado, inserir o script assíncrono no wp_head e o noscript no início de wp_body_open, somente na LP. Não permitir JavaScript arbitrário no campo de ID.

Não copiar o contêiner GTM do Hotel da Costa como default de novos hotéis. Evitar duplicação com tracking existente. Se houver eventos aprovados, usar nomes/parâmetros definidos e não incluir dados de contato por conveniência.

Durante testes automatizados, interceptar recursos de monitoramento quando necessário para não enviar tráfego fictício. A configuração do contêiner não comprova disparo real das tags; registre a verificação correspondente.

**21. Rodapé e privacidade**

O rodapé é um landmark separado de main, próprio da LP e independente do tema. Marca, crédito e ano devem seguir a copy aprovada de cada projeto.

Use a página publicada configurada em Configurações → Privacidade quando existir, com possibilidade de integração específica aprovada. Não criar link vazio ou destino fictício.

Se não houver página aprovada/publicada, documente a pendência e mantenha apresentação não interativa coerente. Não voltar a um estado antigo sem link quando o projeto já possui destino válido.

Se solicitado um template de política de privacidade, ele deve ser isolado e preservar o conteúdo editável da página existente. Não inventar texto jurídico nem criar/publicar uma nova página sem fazer parte do pedido.

O template de privacidade não recebe automaticamente formulário, avaliações ou GTM da LP. Fontes/CSS necessários carregam somente no seu próprio template.

**22. Responsividade e acessibilidade**

Verifique widescreen, desktop, notebook, tablet e mobile, com comportamento fluido entre larguras e alturas.

Matriz mínima de referência:

    320, 360, 390, 400, 767, 768, 1024, 1280, 1366, 1440, 1920 e 2560 px

Inclua também as larguras exatas dos frames, os dois lados de cada breakpoint adicional, orientação horizontal, janelas de 600/800 px de altura e zoom/texto ampliado.

Os padrões de fonte/padding desktop começam em 768 px; mudanças intermediárias no número de colunas não revogam esse breakpoint.

Nenhum overflow horizontal do documento. Não esconder erros de layout com overflow-x: hidden como única correção. Reorganizar colunas sem cortar textos, imagens ou controles.

Imagens responsivas com proporção adequada e recorte conferido. Ordem visual mobile deve preservar ordem lógica do DOM.

Hero de referência desktop considera a altura disponível, com fallback 100vh e 100dvh quando suportado, descontando a barra administrativa do WordPress. Em telas baixas/zoom, deixar a seção crescer em vez de sobrepor conteúdo ou reduzir fontes.

HTML semântico, hierarquia correta de headings, landmarks e um h1 principal coerente. Alt informativo para imagens de conteúdo e alt vazio para decoração.

Teclado completo, foco visível por token, áreas de toque adequadas, mensagens acessíveis e retorno de foco em diálogos.

Medir contraste nos fundos/estados reais, incluindo botão do formulário e overlays. Respeitar prefers-reduced-motion em todas as interações não essenciais.

**23. Assets, desempenho e cache**

Otimizar imagens para o contexto, com formatos adequados, picture/srcset/sizes, dimensões ou aspect-ratio e lazy loading fora da primeira dobra.

Usar fonte e recorte vertical próprios para mobile quando o Figma/original exigir; não distorcer imagens nem ampliar sem necessidade. Preservar arquivos mestres fora do ZIP.

Priorizar a imagem realmente responsável pela primeira dobra, sem marcar todas como prioridade alta. Carregar imagens grandes de galerias/pop-ups sob demanda.

Fontes locais licenciadas, somente pesos utilizados, estratégias adequadas de carregamento e sem herdar pesos/estilos indevidos do tema.

Não incluir bibliotecas pesadas para interações nativas. Manter estabilidade visual e recursos remotos apenas quando necessários à integração.

Versionar assets e documentar invalidação de cache de LP/CDN após atualizações, sem limpar indiscriminadamente todo o site.

Formulários, campanhas e avaliações dinâmicas devem ter política de cache explícita. Excluir LP/endpoints do cache HTML quando necessário para nonce, atualização e expiração; cabeçalhos do PHP não bastam se a CDN responde antes do WordPress. Assets estáticos podem permanecer em cache.

Medir desempenho com ferramenta, versão, dispositivo/perfil, URL e condições registradas. Diferenciar medições de laboratório de dados reais de usuários. Corrigir problemas observados; não declarar desempenho validado só por ter otimizado arquivos.

**24. Segurança, edição e preservação dos dados**

Escape de saídas, sanitização de entradas, validação de tipos/IDs/URLs, nonces e permissões nas operações administrativas. Use APIs nativas do WordPress onde apropriado.

Endpoint público não equivale a acesso administrativo. Bloquear consultas públicas de leads e exposição de tokens, credenciais, contas técnicas ou configurações privadas.

Nada de segredos, dumps, leads reais ou tokens no repositório, screenshots de relatório, exemplos ou ZIP.

Conteúdo/configuração editados no WordPress têm prioridade sobre defaults iniciais em atualizações. Não restaurar automaticamente campanhas removidas ou lista vazia.

Antes de rollback, verificar compatibilidade do banco e da versão anterior. Não substituir banco de produção por staging quando houver novos leads ou edições.

**25. Preview, validação e critérios de entrega**

Preferir a página real no WordPress local/staging como preview oficial, usando os mesmos templates, dados e assets do pacote final.

Se existir preview estático, gerá-lo da mesma fonte ou identificá-lo como protótipo. Não exigir manutenção manual de dois HTMLs independentes. Validação final deve usar WordPress.

Crie verificações automatizadas úteis para:

- Grid, padding, tokens tipográficos, cores fora dos tokens e overflow.
- Copy, CTAs, ícones e conteúdo completo das coleções.
- Revelação pelo scroll, repetição, final da página e movimento reduzido.
- Dots para zero, um, vários e muitos itens, incluindo últimos indicadores.
- Mouse, caneta, toque, teclado e resize.
- Pop-ups, tela baixa, conteúdo extenso, Escape e retorno de foco.
- UTMs novas/parciais/ausentes, armazenamento bloqueado, validação e idempotência.
- Persistência, permissões, filtros e CSV de envios.
- Campanhas vazias, edição, imagem excluída e preservação na atualização.
- Trustindex Google e Tripadvisor, perfil trocado, indisponibilidade e atribuição.
- SEO sem duplicidade, robots/canonical, tracking e ausência de recursos da LP em página comum.
- Instalação, ativação, atualização, renderização, desativação e reativação do ZIP em staging.

Use fixtures somente no ambiente de teste, limpe dados/usuários temporários e não inclua esses recursos no plugin distribuído.

Conferência visual desktop/mobile e das larguras intermediárias é obrigatória. Testes automatizados complementam essa revisão.

Registre PASSOU, FALHOU, PENDENTE ou NÃO SE APLICA por cenário, com ambiente, versão e evidência. Não marcar plano de teste como executado. Local, staging de destino, mocks e conexão real são resultados diferentes.

Uma falha que impede conteúdo, captação, acessibilidade essencial ou instalação impede declarar a entrega pronta. Se depender de credenciais/ambiente indisponível, entregar o que estiver concluído com a pendência explícita, sem afirmar homologação integral.

**26. Empacotamento e manuais**

Gere cada ZIP a partir de uma lista controlada de arquivos, com pasta raiz correta, cabeçalho de plugin, versão, assets finais e dependências documentadas.

O plugin funciona sem ferramentas de desenvolvimento no servidor. Excluir testes, backups, originais pesados, referências do Figma, perfis de navegador, node_modules, caches, dumps e segredos.

Registrar versão, revisão e hash do pacote. Instalar o ZIP candidato efetivo no staging; alterações posteriores exigem novo pacote e revalidação proporcional.

Manual curto deve cobrir instalação/atualização, seleção do template, edição, campanhas, leads/CSV, WhatsApp, GTM, SEO, privacidade, Trustindex Google/Tripadvisor, limites reais do plano, cache, desativação e reversão.

Descrever como configurar o fornecedor de avaliações e confirmar o hotel, sem prometer que o ZIP pode incluir credenciais ou conectar contas automaticamente.

**27. Contexto particular do Hotel da Costa a preservar como referência**

Registre estes fatos no documento de origem/exceções, sem transformá-los em defaults de outros hotéis:

- A copy institucional oficial está na página LP - Hotel da Costa do Figma por decisão de 30/09/2026; o arquivo histórico de copy não a substitui.
- Foram aprovadas descrições próprias das onze acomodações porque o Figma repete a Suíte Premium. Preservar descrições e dados verificados dos pop-ups.
- Há oito serviços, onze acomodações, doze fotos na galeria geral e galerias específicas de quartos. Esses números exemplificam o cálculo dinâmico; não são constantes de uma nova LP.
- Hotel na Orla de Atalaia, em Aracaju - SE, sem promessa de hotel pé na areia. Não usar em frente à praia, à beira-mar ou a poucos passos do mar nesse hotel.
- Referências de vista para o mar dependem da categoria confirmada.
- O destino de WhatsApp, o contêiner GTM, a marca, fontes, fotos e endereço são exclusivos desse projeto.
- Há correções aprovadas com 11 opções renovadas e Aracaju - SE, que prevalecem sobre textos anteriores.
- O ID form_popup foi explicitamente pedido para acompanhamento.
- O pop-up original possui título de 48/24 px como exceção específica documentada; não substituir os tokens globais de 40/22 px.
- Créditos e ano do rodapé seguem a copy aprovada, sem troca automática do ano por conveniência.
- O estado antigo de Política de privacidade sem link foi atualizado: consultar o manual e código atuais, que usam a página publicada configurada no WordPress.
- A nota 4.5, o texto sobre mais de 2400 avaliações e o selo Travellers’ Choice 2026 são referências históricas, sujeitas a confirmação antes da publicação.
- O README registra divergência entre números de endereço no mapa e no site. Não assumir resolução desse conflito nem transportar o endereço para outro hotel.
- Trustindex Google já tinha adaptador no projeto de origem. Trustindex Tripadvisor é uma extensão requerida neste novo padrão e precisa de implementação/validação próprias.

Nenhuma dessas particularidades autoriza reescrever a copy do Floral Inn. Ele terá fatos, imagens, regras comerciais e exceções próprios.

**28. Autonomia e resultado esperado desta tarefa**

Faça o trabalho reversível e necessário neste repositório sem pedir confirmação a cada etapa. Não crie aprovações recorrentes para escolhas já definidas neste prompt.

Pergunte apenas por lacunas que realmente afetem o resultado e não possam ser resolvidas pelas fontes, indicando o que já pode avançar.

Não publicar ou modificar produção sem autorização explícita. Uma autorização já recebida continua válida dentro de seu escopo.

Entregue:

- Skill funcional com manifesto validado e referências focadas.
- Base WordPress neutra, com componentes reutilizáveis e configuração por hotel.
- AGENTS.md de projeto/modelo e perfil de exemplo validável.
- Adaptadores de avaliações com situação de compatibilidade/teste claramente indicada.
- Ferramentas executadas de inicialização, validação e empacotamento, conforme necessárias.
- Testes dos comportamentos recorrentes e resultados observados.
- Manual do repositório para usar a skill em novas LPs.
- Exemplo de comando curto para iniciar o Floral Inn.
- Registro de fontes, exceções, decisões e pendências reais.

Não encerrar somente com uma proposta de arquitetura. Crie os arquivos e recursos concretos desta skill, valide o que for possível no ambiente e apresente o resultado revisável.
