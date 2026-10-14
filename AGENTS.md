# AGENTS.md — Landing Page do Hotel da Costa

## Escopo

Estas instruções se aplicam a todo o projeto desta pasta. A landing page deve ser desenvolvida somente depois de respeitar este arquivo, a copy aprovada e o contexto de integração com o WordPress existente.

## Fontes de verdade e prioridades

1. As instruções mais recentes do usuário têm prioridade.
2. Este `AGENTS.md` define os padrões permanentes de implementação e design.
3. A copy da página **LP - Hotel da Costa** no [Figma](https://www.figma.com/design/T0967OaYPkcFB4TuMviA9R/Todos-projetos?node-id=5308-3) é a fonte oficial dos textos institucionais da landing page, por decisão do usuário em 30/09/2026. Consultar a conexão atual pelo `figma_bridge`. O arquivo `copy-landing-page-hotel-da-costa.md` fica preservado como referência histórica e não deve substituir os textos do Figma.
4. O site público e a ficha técnica podem servir como referência factual e visual, mas não substituem a copy aprovada.
5. Informações conflitantes, dinâmicas ou não confirmadas não devem ser publicadas como promessa.

Não reescrever, resumir, completar ou substituir a copy por texto genérico sem autorização explícita.

## Arquitetura e integração com WordPress

- Entregar a landing page como um plugin WordPress independente e instalável em `.zip`.
- O plugin deve registrar um template próprio para a landing page.
- Não editar o tema `hoteldacosta2026`, `functions.php`, páginas existentes, menus ou estilos globais.
- Carregar os assets somente na página que usa o template da landing page.
- Prefixar classes CSS, IDs técnicos, atributos de dados, funções e handles com `hdc-lp` ou `hdc_lp`, conforme a sintaxe permitir.
- Manter cabeçalho, rodapé, estilos, scripts, imagens, metadados e integrações da LP isolados do restante do site.
- A desativação do plugin deve remover o funcionamento da LP sem comprometer o site existente.
- Não depender de um page builder para renderizar a página.

### Tecnologias permitidas

- HTML5 semântico para a estrutura.
- CSS3 para layout, responsividade e apresentação.
- JavaScript nativo para interações.
- PHP compatível com WordPress para registrar o plugin, o template, assets, campos e integrações necessárias.
- Não introduzir React, Vue, Svelte, Tailwind ou outra dependência de runtime/build sem autorização explícita.
- Se uma ferramenta de build for realmente necessária, o `.zip` final deve conter assets compilados e funcionar sem Node.js no servidor WordPress.

## Layout

### Grid e contêiner

- Todo o conteúdo deve ficar dentro de um contêiner centralizado com largura máxima de `1280px`.
- O padrão estrutural é uma seção externa com largura total e um contêiner interno de conteúdo.
- O contêiner interno deve usar, no mínimo:
  - `width: 100%`;
  - `max-width: 1280px`;
  - `margin-inline: auto`.
- Fundos de seção podem ocupar a largura total da viewport, mas o conteúdo deve continuar limitado ao grid de `1280px`.
- Não ultrapassar o grid para textos, cards, galerias ou CTAs sem autorização explícita.

### Espaçamento das seções

- Desktop: todas as seções devem ter `40px` de padding nos quatro lados.
- Mobile: todas as seções devem ter `20px` de padding nos quatro lados.
- Não remover ou reduzir esse padding em hero, galeria, CTA final ou qualquer outra seção sem autorização explícita.
- Espaçamentos internos entre elementos devem ser definidos por tokens CSS e manter ritmo visual consistente.

### Breakpoint

- Considerar mobile até `767px` de largura.
- A partir de `768px`, aplicar as regras tipográficas e de padding de desktop.
- Garantir comportamento fluido também em larguras intermediárias, sem overflow horizontal.

## Tipografia

### Desktop

- Título: `40px`.
- Subtítulo: `24px`.
- Texto: `18px`.

### Mobile

- Título: `22px`.
- Subtítulo: `18px`.
- Texto: `14px`.

### Quebra de texto

- Todo conteúdo textual deve declarar `text-wrap`.
- Títulos e subtítulos devem usar `text-wrap: balance`.
- Parágrafos, listas, legendas, depoimentos e demais textos corridos devem usar `text-wrap: pretty`.
- Usar `overflow-wrap: anywhere` somente em conteúdo que possa gerar overflow, como URLs ou identificadores longos.
- Não reduzir arbitrariamente o tamanho da fonte para resolver problemas de layout.

## Variáveis e tokens CSS

- Todas as cores devem ser armazenadas em variáveis CSS semânticas.
- Não usar valores de cor fixos diretamente em regras de componentes, incluindo hexadecimal, `rgb()`, `rgba()`, `hsl()` ou `hsla()`.
- As variáveis devem ficar escopadas no elemento raiz da landing page, como `.hdc-lp`, para não alterar o tema existente.
- Preferir nomes que expressem função, não aparência ou posição, por exemplo:
  - `--hdc-lp-color-background-page`;
  - `--hdc-lp-color-background-section`;
  - `--hdc-lp-color-background-accent`;
  - `--hdc-lp-color-text-primary`;
  - `--hdc-lp-color-text-secondary`;
  - `--hdc-lp-color-action`;
  - `--hdc-lp-color-action-hover`;
  - `--hdc-lp-color-border`;
  - `--hdc-lp-color-focus`.
- Cores usadas em gradientes, overlays, sombras e estados interativos também devem vir de variáveis.
- Centralizar também tokens recorrentes de tipografia, espaçamento, largura, raio, sombra e transição.
- Os tamanhos tipográficos obrigatórios devem ser representados por tokens e sobrescritos no breakpoint mobile.

## Ícones

- Usar exclusivamente ícones do Phosphor Icons: <https://phosphoricons.com/>.
- Não misturar Font Awesome, Material Icons, Bootstrap Icons, emojis ou ícones de outra biblioteca.
- Usar a distribuição oficial do Phosphor compatível com a implementação web e empacotá-la de modo seguro no plugin.
- Manter peso, tamanho e alinhamento visual consistentes.
- Ícones decorativos devem usar `aria-hidden="true"`.
- Ícones que comunicam ação ou informação precisam de nome acessível no controle correspondente.
- Não usar ícone como único meio de comunicar uma ação importante sem rótulo textual acessível.

## Copy e regras comerciais

- Usar a copy atual da página **LP - Hotel da Costa** no Figma. Textos iguais ao arquivo histórico continuam válidos quando também constam no Figma.
- Por autorização explícita do usuário, manter as descrições específicas já aprovadas das onze acomodações: o Figma repete a Suíte Premium e não contém copy própria das outras dez categorias. Preservar os dados verificados dos pop-ups e as avaliações reais da integração; não copiar depoimentos exemplificativos do layout como avaliações autênticas.
- Todos os CTAs de conversão devem exibir exatamente: `RESERVAR VIA WHATSAPP`.
- O destino dos CTAs deve ser a integração de WhatsApp aprovada para a campanha.
- Descrever o hotel como localizado **na Orla de Atalaia**.
- O Hotel da Costa não é um hotel pé na areia.
- Não usar expressões que sugiram acesso direto à areia ou localização pé na areia, como “em frente à praia”, “à beira-mar” ou “a poucos passos do mar”.
- Referências a vista para o mar só podem ser usadas nas acomodações em que essa característica esteja confirmada.
- Avaliações, notas, prêmios, distâncias, horários, políticas, estacionamento, capacidade de eventos e demais dados sujeitos a alteração devem ser confirmados antes da publicação.

## Responsividade

- A página deve funcionar em mobile, tablet e desktop.
- Layouts com múltiplas colunas devem reorganizar o conteúdo sem cortar texto, imagem ou controles.
- Não permitir overflow horizontal em nenhuma largura suportada.
- Imagens devem ser responsivas e preservar a proporção adequada.
- Controles interativos devem continuar legíveis e fáceis de tocar no mobile.
- A ordem visual no mobile deve preservar a ordem lógica e semântica do documento.

## Acessibilidade

- Usar estrutura semântica com hierarquia correta de headings e landmarks.
- Fornecer texto alternativo adequado para imagens informativas; imagens decorativas devem ter `alt=""`.
- Todos os controles devem funcionar por teclado.
- Estados de foco devem ser visíveis e usar token de cor próprio.
- Manter contraste adequado entre texto, fundo, bordas e estados interativos.
- Respeitar `prefers-reduced-motion` em animações e transições não essenciais.
- Links de WhatsApp devem indicar claramente a ação e não depender apenas do ícone.

## Performance e assets

- Otimizar imagens e servir tamanhos adequados ao contexto.
- Usar lazy loading nas imagens fora da primeira dobra.
- Evitar bibliotecas pesadas para interações que podem ser implementadas com JavaScript nativo.
- Evitar CSS e JavaScript bloqueantes ou carregados globalmente.
- Não carregar recursos remotos desnecessários.
- Preservar a estabilidade visual da página definindo dimensões ou proporções dos assets.

## Qualidade de implementação

- Construir componentes e seções com nomes claros, consistentes e prefixados.
- Evitar estilos inline, salvo valores dinâmicos estritamente necessários e sanitizados pelo WordPress.
- Não usar `!important` como solução padrão.
- Escapar saídas, sanitizar entradas e aplicar nonces e checagem de permissões em qualquer funcionalidade administrativa.
- Não inserir segredos, tokens ou credenciais no código-fonte.
- Não publicar ou modificar o site em produção sem autorização explícita.

## Critérios mínimos antes da entrega

- Conferir visualmente desktop e mobile.
- Validar o limite de `1280px` do conteúdo.
- Validar padding de `40px` no desktop e `20px` no mobile em todas as seções.
- Validar os três tamanhos tipográficos em cada breakpoint.
- Procurar cores fixas fora da declaração de tokens CSS.
- Confirmar que todos os ícones usados são do Phosphor.
- Confirmar que todos os CTAs de conversão usam a copy aprovada.
- Procurar linguagem que possa sugerir equivocadamente um hotel pé na areia.
- Testar teclado, foco, contraste e `prefers-reduced-motion`.
- Testar que CSS e JavaScript carregam somente no template da LP.
- Testar instalação, ativação, renderização e desativação do plugin em um ambiente de staging.
- Gerar o `.zip` instalável e um manual curto de instalação e edição.
