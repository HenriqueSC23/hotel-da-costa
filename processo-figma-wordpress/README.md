# Processo de produção — Figma → WordPress

Versão 1.0 · 01/10/2026

Este processo organiza a produção de landing pages e sites: definição do escopo, entrega do Figma para desenvolvimento, implementação, validação no WordPress e manutenção. Foi elaborado a partir do projeto Hotel da Costa. Os modelos desta pasta podem ser usados em outros projetos; as regras específicas do hotel continuam no [AGENTS.md original](../AGENTS.md).

**Princípio central: definir a arquitetura WordPress antes de desenvolver a interface e usar a mesma implementação no preview e na entrega.** Isso evita manter um HTML de apresentação e um template PHP manualmente em paralelo.

## Como começar um projeto

1. Copie a pasta `templates/` para a documentação do novo projeto.
2. Preencha o [briefing](templates/01-briefing.md) e o [mapa do Figma](templates/02-entrega-figma.md).
3. Adapte [AGENTS.template.md](templates/AGENTS.template.md), resolva os campos pendentes e salve como `AGENTS.md` na raiz do novo projeto. Preserve instruções existentes que continuem aplicáveis.
4. Use o [registro do projeto](templates/05-registro-do-projeto.md) para acompanhar a etapa, decisões, alterações e entregas.
5. Execute as etapas abaixo com os [prompts reutilizáveis](PROMPTS.md) e registre evidências no [checklist](templates/04-checklist-de-qualidade.md).

Os modelos contêm campos a preencher. Eles não autorizam instalação ou publicação em um site real.

## 1. Definir escopo e arquitetura

**Responsável:** você confirma objetivo e limites; quem desenvolve registra a solução técnica.

Definir público, ação principal, páginas, integrações e conteúdo que o cliente poderá editar. Levantar a instalação de destino: versões de WordPress e PHP, tema clássico ou de blocos, plugins relevantes, hospedagem e staging. Registrar o que deve ser preservado.

### Escolher a forma de entrega

| Situação | Arquitetura proposta | Edição pelo cliente |
| --- | --- | --- |
| LP de campanha dentro de um site existente | Plugin independente com template próprio e assets condicionais | Campos administrativos apenas para os conteúdos combinados |
| Site novo, com várias páginas e edição frequente | Tema próprio, preferencialmente de blocos quando adequado, e plugin para funcionalidades que precisam sobreviver à troca de tema | Blocos, padrões e conteúdo estruturado |
| Evolução de um site existente | Reutilizar a arquitetura atual; plugin para funções e tema filho para mudanças visuais quando necessário e autorizado | Manter o modelo editorial existente ou migrar apenas o escopo combinado |
| Projeto que exige um page builder | Implementação no builder definido no briefing, com dependências, licença e limites documentados | Edição no builder |

Estas são decisões deste processo, não uma obrigação de usar a mesma arquitetura em todos os projetos. A distinção entre temas clássicos e de blocos está na [documentação WordPress](https://developer.wordpress.org/block-editor/how-to-guides/themes/). Funcionalidades que precisam continuar disponíveis com outra aparência devem ficar em plugin, conforme a [orientação do WordPress](https://developer.wordpress.org/themes/classic-themes/basics/theme-functions/).

O padrão proposto para novos projetos é edição por campos e blocos nativos, conforme a arquitetura. Não adicionar um page builder, ACF ou outras dependências automaticamente. Uma LP com conteúdo fixo não exige desenvolver um painel completo.

**Saída:** briefing, arquitetura, lista de páginas/templates, matriz de conteúdo editável e regras do projeto.

**Pronto para avançar:** ação principal, fonte da copy, arquitetura, preservação do site existente e forma de edição definidos. Pendências independentes podem continuar em paralelo.

## 2. Preparar o Figma para desenvolvimento

**Responsável:** quem desenha entrega o mapa; quem desenvolve verifica se há informações suficientes para implementar.

Organizar no Figma, como convenção sugerida:

- `00 — Briefing e conteúdo`: objetivo, links para textos e restrições comerciais.
- `01 — Fundamentos`: cores, fontes, grid, espaçamentos e ícones.
- `02 — Componentes`: botões, campos, cards, navegação e estados.
- `03 — Páginas aprovadas`: desktop e mobile com nomes claros e links dos frames.
- `04 — Estados e fluxos`: menus, modais, formulários, carregamento, vazio e erro.

Não reorganizar um arquivo existente sem necessidade. Um mapa com links pode cumprir a mesma função.

Consultar os nodes exatos pela conexão Figma configurada no projeto. Obter estrutura, textos, medidas e screenshots; quando disponíveis, usar `get_design_context` e `get_screenshot`. Se o retorno for grande, consultar o mapa/metadados e dividir por seção. Exportar imagens e SVGs necessários, incluindo itens fora da área visível de carrosséis.

Registrar URL do arquivo, IDs dos nodes, data da consulta e versão nomeada do Figma, quando houver. Salvar a extração e as imagens de referência com esse registro. Uma cópia local é evidência da consulta; não substitui a fonte oficial definida pelo usuário. Atualizações posteriores do Figma devem ter seu impacto registrado antes de alterar a implementação.

Definir comportamento entre frames: empilhamento, largura dos cards, recortes, navegação, conteúdo variável e estados interativos. Se existir apenas desktop, desenvolver uma proposta mobile com os mesmos textos e registrar as decisões para revisão visual.

**Saída:** mapa de páginas/seções, copy vinculada à fonte, tokens, assets e comportamento responsivo.

**Pronto para avançar:** cada seção tem fonte de texto, imagem e regra de comportamento; lacunas estão registradas. Uma integração dinâmica pode usar estado vazio durante o desenvolvimento. Dados demonstrativos ficam identificados e restritos ao ambiente de teste.

## 3. Modelar conteúdo e preparar o WordPress local

**Responsável:** quem desenvolve.

Separar conteúdo, apresentação e integrações. Para cada item, definir onde será armazenado, quem pode editar, como será validado e o que acontece quando estiver vazio.

| Conteúdo | Tratamento possível | Cuidado na atualização |
| --- | --- | --- |
| Copy fixa de uma campanha | Template ou campos específicos, conforme briefing | Preservar o texto aprovado; não trocar por conteúdo genérico |
| Texto/imagem que o cliente precisa editar | Blocos nativos, metadados ou opções por página | Não sobrescrever valores salvos ao atualizar o código |
| Coleção recorrente, como serviços ou acomodações | Conteúdo estruturado quando houver necessidade editorial | Definir ordem, visibilidade e estado vazio |
| Avaliações, disponibilidade e outros dados externos | Integração com cache e estado de indisponibilidade | Nunca apresentar fixtures como dados reais |
| Credenciais e configurações de ambiente | Configuração protegida no ambiente apropriado | Não incluir segredos no repositório, manual ou ZIP |

Preparar uma instalação local ou staging equivalente ao destino e registrar versões e plugins relevantes. Criar a estrutura mínima de plugin ou tema e verificar seleção do template, renderização e carregamento dos assets antes de construir todas as seções.

Na LP isolada, a seleção do template e o enqueue devem depender da página correta. O WordPress fornece filtros para a [lista de templates](https://developer.wordpress.org/reference/hooks/theme_page_templates/) e para o [template renderizado](https://developer.wordpress.org/reference/hooks/template_include/), além do hook de [carregamento dos assets](https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/). A compatibilidade precisa ser testada com o tema e editor reais do destino.

**Saída:** instalação de desenvolvimento, estrutura de entrega e modelo de conteúdo.

**Pronto para avançar:** a implementação inicial renderiza no WordPress escolhido, e o conteúdo que precisa de edição tem seu mecanismo definido.

## 4. Implementar por componentes e seções

**Responsável:** quem desenvolve; você revisa os resultados nos marcos combinados.

Implementar primeiro tokens, contêiner, tipografia e componentes recorrentes. Depois, concluir as seções em ordem de leitura. Para cada seção: estrutura semântica, conteúdo oficial, imagens corretas, responsividade, interação e comparação visual com o Figma.

Usar HTML, CSS, JavaScript nativo e PHP quando essa for a arquitetura aprovada. Código sugerido pelo conector do Figma é referência de design; ele deve ser adaptado à tecnologia do projeto. Não introduzir React, Tailwind ou ferramenta de build apenas porque vieram no retorno do conector.

Reutilizar assets e componentes comuns. Aplicar prefixo próprio do projeto aos seletores e funções da LP. Escopar tokens e estilos; controlar carregamento de scripts, metadados e integrações conforme o alcance autorizado.

### Preview com a mesma fonte da entrega

Preferir a URL da página no WordPress local/staging como preview oficial. Se houver preview estático, gerá-lo a partir dos mesmos templates e dados ou tratá-lo como protótipo descartável. Evitar atualizar dois HTMLs manualmente. Toda validação final deve usar a renderização WordPress.

### Marcos de revisão sugeridos

1. **Base visual:** hero e uma seção representativa em desktop/mobile.
2. **Página completa:** todas as seções, copy e interações.
3. **Candidato de entrega:** conteúdo editável, integrações, pacote e testes no staging.

Marcos são pontos de revisão, não pedidos automáticos de confirmação. Continuar o trabalho autorizado quando regras e fontes estiverem claras. Pedir esclarecimento apenas quando uma lacuna afetar a fidelidade, o escopo ou uma ação que exija autorização; avançar nas tarefas independentes enquanto isso.

**Saída:** página/site funcional no WordPress e evidências de comparação visual.

**Pronto para avançar:** seções e estados previstos implementados, divergências justificadas e escopo preservado.

## 5. Validar a experiência e a integração

**Responsável:** quem desenvolve executa e registra; você ou o cliente confere a experiência editorial e o resultado visual.

Usar o checklist em quatro frentes:

1. **Conteúdo e fidelidade:** copy, ordem das seções, tokens, imagens, recortes e comportamento nos frames.
2. **Experiência:** mobile, tablet, desktop, teclado, foco, formulários, modais, movimento reduzido e ausência de overflow.
3. **WordPress:** instalação do pacote, edição, persistência, atualização, desativação quando aplicável, conflitos com tema/plugins e preservação das demais páginas.
4. **Entrega:** assets, desempenho, SEO, rastreamento autorizado, dependências e ausência de arquivos de desenvolvimento no pacote.

Matriz sugerida: 320, 390, 767, 768, 1024, 1280, 1440 e 1920 px; acrescentar as larguras exatas dos frames e telas baixas. Usar conteúdo extenso e estados vazio/erro nas partes dinâmicas. Ferramentas automáticas complementam a inspeção visual e os testes de teclado.

Registrar ambiente, versão do pacote, teste executado, resultado e evidência. Usar os estados `PASSOU`, `FALHOU`, `PENDENTE` e `NÃO SE APLICA`, com motivo para o último. Não registrar teste planejado como executado, nem inferir acesso real a uma integração a partir de mocks.

**Saída:** relatório de qualidade e pacote candidato.

**Pronto para entrega:** sem falhas que impeçam conteúdo, conversão, edição, acessibilidade essencial ou funcionamento do site. Limitações e validações dependentes de acesso devem estar explícitas. Validação local deve ser identificada como local; não substitui homologação no ambiente de destino.

## 6. Empacotar, homologar e publicar

**Responsável:** quem desenvolve prepara; o responsável pelo site autoriza a publicação quando necessário.

Gerar ZIP(s) instalável(is) a partir de uma lista controlada de arquivos de distribuição. Excluir backups, perfis de navegador, fixtures, dumps, credenciais, fontes de referência e ferramentas de desenvolvimento. Incluir assets compilados quando houver build autorizado; o servidor deve executar a entrega sem Node.js.

Identificar versão, revisão do código e hash do arquivo. Instalar o ZIP candidato no staging de destino e repetir os testes essenciais. Se o pacote mudar depois disso, gerar nova versão e revalidar as partes afetadas.

Entregar o pacote, manual curto de instalação/edição, relatório de testes, limitações, dependências e plano de reversão. Para sites completos, incluir mapa de templates/páginas e instruções de transporte dos conteúdos. Revisar alterações de templates salvas no banco pelo editor: elas podem prevalecer sobre arquivos do tema e precisam participar da entrega.

Antes da publicação, preparar backup de arquivos e banco, confirmar URL/template e documentar como restaurar a versão anterior. Não sobrescrever todo o banco de produção com o staging quando existirem novos leads, pedidos ou edições. Fazer migrações de conteúdo/configuração dentro do escopo definido.

Publicar somente com autorização explícita já existente ou obtida para a ação concreta. Autorizações continuam válidas dentro do seu escopo; não pedir novamente sem motivo. Depois, conferir CTAs, formulário, imagens, HTTPS, metadados, indexação e uma página fora do escopo. Invalidar apenas os caches necessários e registrar a versão publicada.

**Saída:** entrega homologada e, quando autorizada, publicação verificada.

**Pronto para encerrar:** entrega identificada, manual disponível, evidências registradas e pendências com responsável definido.

## Manutenção e mudanças

Toda alteração registra pedido, fonte, impacto e versão. Mudança de copy exige conferir a fonte aprovada; mudança de layout exige atualizar os frames ou registrar a exceção; mudança de integração exige testar também os estados de erro.

Atualizações preservam conteúdo editado pelo cliente. Migrações de dados devem ser versionadas e poder ser reaplicadas sem duplicar ou apagar dados. Reversão de código não implica automaticamente reversão de banco: verificar a compatibilidade da versão anterior antes de executar o rollback.

Repetir testes proporcionais à alteração e as verificações de integração afetadas. Não refazer toda a bateria sem motivo, nem dispensar staging porque uma mudança passou no preview.

## Aplicação no Hotel da Costa

O [AGENTS.md](../AGENTS.md) permanece a regra operacional deste projeto. Este processo não altera o plugin atual.

| Item | Regra vigente do hotel |
| --- | --- |
| Entrega | Plugin independente em ZIP; template próprio; preservar tema, menus e outras páginas |
| Tecnologia | HTML, CSS, JavaScript nativo e PHP; sem page builder ou novo runtime sem autorização |
| Fonte institucional | Copy atual da página LP - Hotel da Costa no Figma, consultada pela conexão configurada |
| Exceções de conteúdo | Preservar descrições aprovadas das onze acomodações e dados verificados dos pop-ups; avaliações reais da integração |
| CTA de conversão | `RESERVAR VIA WHATSAPP`, com destino aprovado para a campanha |
| Grid e seções | Conteúdo até 1280 px; padding de 40 px nos quatro lados no desktop e 20 px no mobile |
| Breakpoint | Mobile até 767 px; desktop a partir de 768 px |
| Tipografia | Título/subtítulo/texto: 40/24/18 px no desktop e 22/18/14 px no mobile, sujeitos apenas a exceções explicitamente autorizadas |
| Tokens e ícones | Cores semânticas escopadas, tokens recorrentes e ícones Phosphor oficiais |
| Informação comercial | Localização na Orla de Atalaia; evitar promessas que sugiram hotel pé na areia |

O projeto mantém preview estático e template WordPress, e seu manual orienta sincronizar algumas alterações nos dois. Para novos projetos, o processo propõe usar o WordPress como preview oficial. Alterar essa estrutura no hotel é uma tarefa separada, com seu próprio escopo.
