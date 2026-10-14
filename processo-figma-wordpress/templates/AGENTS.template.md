# AGENTS.md — [nome do projeto]

MODELO: preencher os campos, escolher a arquitetura e salvar como `AGENTS.md` na raiz do novo projeto. Não substituir instruções existentes sem preservar as regras ainda válidas. Este arquivo de modelo não altera o AGENTS.md do Hotel da Costa.

## Escopo e fontes

Estas regras se aplicam a [diretório e escopo]. Seguir as instruções mais recentes do usuário, as regras deste projeto e as fontes aprovadas.

- Briefing: [caminho].
- Figma oficial: [URL, página e nodes].
- Fonte da copy: [fonte definida pelo usuário].
- Mapa de conteúdo/assets: [caminho].
- Registro de decisões e exceções: [caminho].

Consultar os nodes relevantes pela conexão disponível, com estrutura e screenshots. Se houver acesso apenas a evidências locais anteriores, registrar a limitação. Não afirmar que uma fonte foi consultada sem consulta real.

Não reescrever, resumir ou completar a copy sem autorização. Preservar exceções aprovadas. Não tratar avaliações e números demonstrativos do Figma como dados reais. Confirmar fatos variáveis antes da publicação.

## Arquitetura escolhida

- Forma de entrega: [plugin com template / tema + plugin / extensão de site existente].
- Tecnologia permitida: [preencher].
- Modelo de edição: [campos / blocos / código / builder exigido].
- Dependências e licenças autorizadas: [lista].
- Páginas e templates incluídos: [lista].
- Tema, menus, URLs e demais recursos a preservar: [lista].
- Prefixos CSS/JS/PHP/handles: [valores válidos e únicos].

Não introduzir framework, page builder ou dependência de runtime/build apenas porque o conector do Figma retorna código dessa tecnologia. Adaptar o design à arquitetura escolhida.

Para LP isolada: template próprio, raiz CSS própria, assets/metadados/integrações condicionais e preservação do restante do site. Não editar o tema ativo ou estilos globais fora do escopo autorizado.

Para site completo: definir templates e componentes compartilhados; manter em plugin as funcionalidades que precisam sobreviver à troca de tema. Usar recursos nativos para edição quando previstos no briefing. Não impor alterações globais a um site existente como efeito colateral de uma página nova.

## Design e conteúdo

- Grid máximo: [valor].
- Padding de seção desktop/mobile: [valores, incluindo os quatro lados].
- Breakpoints: [valores].
- Título/subtítulo/texto por breakpoint: [valores].
- Biblioteca de ícones e peso: [biblioteca aprovada].
- CTAs: [rótulos exatos e destinos aprovados].
- Termos obrigatórios/proibidos: [lista].

Usar contêiner centralizado e fundos externos conforme o layout. Aplicar cores por tokens semânticos, incluindo overlays, sombras e estados. Centralizar os valores recorrentes de fonte, espaçamento, largura, raio e transição.

Declarar `text-wrap`: títulos/subtítulos com `balance` e textos corridos com `pretty`. Restringir `overflow-wrap: anywhere` a conteúdos com risco de overflow. Não reduzir tipografia obrigatória para disfarçar problemas de layout.

Manter o design aprovado e registrar conflitos antes de substituí-lo. Novos estados e larguras sem frame devem respeitar os componentes, a copy e as regras existentes.

## Implementação e edição

Usar HTML semântico, hierarquia de headings e controles com rótulos acessíveis. Garantir teclado, foco visível, contraste, retorno do foco em modais e respeito a `prefers-reduced-motion`.

O preview oficial deve usar a mesma implementação da entrega, preferencialmente no WordPress local/staging. Um preview estático precisa ser gerado da mesma fonte ou identificado como protótipo; não usar HTML duplicado como única validação final.

Separar apresentação, conteúdo e integrações. Alterações no código não podem sobrescrever conteúdo salvo pelo cliente. Definir estados vazio/erro, permissões de edição e validações dos campos. Documentar migrações de dados.

Escapar saídas, sanitizar entradas e verificar permissões/nonces em operações administrativas. Endpoints públicos devem ter validação e tratamento de abuso compatíveis com a função; nonce não substitui autorização.

Evitar estilos inline, `!important` como solução padrão e bibliotecas pesadas. Otimizar imagens, definir dimensões/proporções e usar lazy loading fora da primeira dobra. Não adicionar recursos remotos sem necessidade.

Nunca incluir segredos, tokens ou credenciais no código, screenshots de relatório ou pacotes de distribuição.

## Validação e entrega

Conferir visualmente as larguras do Figma e mobile/tablet/desktop, limites dos breakpoints, overflow, textos e recortes. Testar interações, edição e persistência no WordPress real do projeto.

Instalar o pacote candidato em staging. Para plugin, testar ativação, atualização e desativação; para tema, testar instalação/ativação e comportamento dos conteúdos/templates. Conferir preservação das páginas fora do escopo.

Registrar evidências e distinguir teste local, staging de destino e integração real. Não marcar teste pendente como concluído. Repetir verificações proporcionais às mudanças e os testes de integração afetados.

Entregar ZIP(s) instalável(is), manual de instalação/edição, versões compatíveis verificadas, relatório de qualidade e plano de reversão. Excluir desenvolvimento, fixtures, backups e dados sensíveis da distribuição. Se houver build autorizado, incluir assets compilados e evitar Node.js no servidor.

## Autonomia e produção

Continuar tarefas reversíveis e já autorizadas sem pedir confirmação a cada etapa. Resolver decisões rotineiras com as regras e registrar as premissas. Esclarecer somente lacunas que afetem conteúdo aprovado, arquitetura, escopo ou uma ação que precise de autorização.

Não publicar nem modificar produção sem autorização explícita. Se já concedida, respeitar seu escopo. Antes de pedir aprovação de publicação, concluir pacote, homologação, relatório e reversão para que a decisão seja sobre uma entrega concreta.
