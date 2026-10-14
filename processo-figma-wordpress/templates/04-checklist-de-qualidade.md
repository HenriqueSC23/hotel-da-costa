# Checklist de qualidade — [projeto / versão]

Ambiente e URL: [preencher] · WordPress/PHP/tema/plugins: [versões]

Pacote/revisão/hash: [preencher] · Executado por/em: [nome e data]

Preencher cada resultado com `PASSOU`, `FALHOU`, `PENDENTE` ou `NÃO SE APLICA`. Para cada teste, registrar evidência: screenshot, log, caminho do relatório ou descrição do resultado observado. Justificar itens não aplicáveis. As linhas abaixo começam pendentes e não comprovam execução.

## Conteúdo e Figma

| Verificação | Critério | Resultado | Evidência |
| --- | --- | --- | --- |
| Fonte consultada | Nodes, extração e screenshots identificados por data/versão | PENDENTE | [registro] |
| Copy | Textos, títulos e CTAs conferidos contra fonte e exceções aprovadas | PENDENTE | [comparação] |
| Fatos e prova social | Dados variáveis confirmados; exemplos restritos aos testes | PENDENTE | [fonte/data] |
| Completude | Seções, itens ocultos, pop-ups e estados incluídos | PENDENTE | [mapa] |
| Imagens e ícones | Assets corretos, recortes conferidos, alt apropriado e biblioteca aprovada | PENDENTE | [capturas/inventário] |
| Tokens | Cores fora dos tokens, tipografia, grid, padding e espaçamentos auditados | PENDENTE | [medidas/busca] |

## Visual, responsividade e acessibilidade

Matriz sugerida: 320, 390, 767, 768, 1024, 1280, 1440 e 1920 px, mais larguras dos frames. Ajustar ao projeto; testar os dois lados de cada breakpoint real.

| Verificação | Critério | Resultado | Evidência |
| --- | --- | --- | --- |
| Desktop e mobile | Comparação visual com os frames nas mesmas larguras | PENDENTE | [capturas lado a lado] |
| Intermediárias | Sem sobreposição, cortes ou overflow horizontal | PENDENTE | [larguras/resultados] |
| Grid e padding | Medidas reais por seção obedecem às regras do projeto | PENDENTE | [medições] |
| Texto | Fontes/tamanhos corretos; quebra adequada; conteúdo extenso legível | PENDENTE | [capturas/estilos] |
| Tela baixa e zoom | Conteúdo e controles acessíveis com rolagem adequada | PENDENTE | [cenários] |
| Teclado e foco | Ordem lógica; controles acionáveis; foco visível | PENDENTE | [roteiro executado] |
| Modais e menus | Abrir/fechar, Escape quando aplicável e retorno do foco | PENDENTE | [cenários] |
| Semântica e contraste | Landmarks/headings/rótulos corretos; texto/controle/foco legíveis | PENDENTE | [medições/inspeção] |
| Movimento reduzido | Interações continuam funcionais sem movimento não essencial | PENDENTE | [emulação/resultado] |
| Mouse e toque | Gestos não bloqueiam rolagem vertical; controles fáceis de usar | PENDENTE | [dispositivos/cenários] |

## Conversão e integrações

| Verificação | Critério | Resultado | Evidência |
| --- | --- | --- | --- |
| CTAs | Rótulo exato, destino e mensagem/parametrização aprovados | PENDENTE | [links inspecionados] |
| Formulário | Validação, envio, duplicidade, erro e confirmação coerentes | PENDENTE | [dados de teste/resultado] |
| Recebimento | Persistência e/ou entrega ao destino confirmadas de fato | PENDENTE | [registro sem dados sensíveis] |
| Integração externa | Sucesso real ou limitação explicitamente registrada | PENDENTE | [ambiente/status] |
| Estados dinâmicos | Vazio, indisponibilidade, cache e conteúdo extenso tratados | PENDENTE | [cenários] |
| Rastreamento | Eventos/UTMs autorizados registrados sem duplicação nem dados indevidos | PENDENTE | [verificação] |
| Sem JavaScript | Conteúdo essencial e destino de conversão acessíveis, quando previsto | PENDENTE | [cenário] |

## WordPress e conteúdo editável

| Verificação | Critério | Resultado | Evidência |
| --- | --- | --- | --- |
| Instalação do ZIP | Pacote candidato instalado no staging de destino | PENDENTE | [URL/versão/hash] |
| Template/tema | Seleção e renderização corretas na arquitetura do destino | PENDENTE | [editor/frontend] |
| Assets condicionais | Recursos da LP não carregam em páginas fora do seu template | PENDENTE | [rede nas duas páginas] |
| Isolamento | Tema, menus, estilos e metadados preservados fora do escopo | PENDENTE | [comparação antes/depois] |
| Edição | Papel previsto consegue editar e ver a mudança no frontend | PENDENTE | [campo/conteúdo] |
| Atualização | Conteúdo editado persiste após atualizar/reinstalar o pacote | PENDENTE | [antes/depois] |
| Permissões e entradas | Escrita indevida bloqueada; entradas inválidas tratadas e saídas escapadas | PENDENTE | [cenários] |
| Ciclo de vida | Ativação/desativação/reativação ou troca de tema sem quebra inesperada | PENDENTE | [passos/resultados] |
| SEO e cache existentes | Compatibilidade com plugins reais conferida | PENDENTE | [versões/cenários] |

Em sites de blocos, verificar se templates/partes personalizados no banco estão prevalecendo sobre os arquivos entregues. Registrar como serão transportados e preservados.

## Performance, SEO e pacote

| Verificação | Critério | Resultado | Evidência |
| --- | --- | --- | --- |
| Imagens/fontes | Tamanhos adequados, fontes necessárias, dimensões e lazy loading corretos | PENDENTE | [rede/inventário] |
| Desempenho | Medição reproduzível com ferramenta, condições e metas do projeto registradas | PENDENTE | [relatório] |
| Metadados | Título, descrição, canonical e compartilhamento corretos e sem duplicação | PENDENTE | [head] |
| Indexação | Staging restrito; produção segue intenção aprovada; respeitar restrições existentes | PENDENTE | [robots/configuração] |
| Dados estruturados | Apenas fatos confirmados, quando usados | PENDENTE | [validação] |
| Distribuição | ZIP íntegro, versão correta, assets finais e dependências documentadas | PENDENTE | [lista/hash] |
| Higiene do pacote | Sem backups, testes, dumps, perfis de navegador ou segredos | PENDENTE | [inventário] |
| Manual e reversão | Instalação, edição, limitações e recuperação descritas | PENDENTE | [documentos] |

## Resultado da homologação

- Situação: [em validação / pronto para entrega / pendências impeditivas].
- Falhas e correções: [referências].
- Limitações ou integrações que dependem de acesso: [lista].
- Responsável por cada pendência: [nome e ação].
- Aprovação recebida: [evidência e escopo; deixar pendente se não recebida].

## Após publicação autorizada

- [ ] URL/template corretos e HTTPS funcionando.
- [ ] CTAs e formulário verificados no ambiente publicado.
- [ ] Assets, metadados e indexação conferidos.
- [ ] Cache atualizado apenas no alcance necessário.
- [ ] Página fora do escopo verificada.
- [ ] Versão publicada e backup/reversão registrados.

Se publicação não fizer parte da entrega, marcar essa etapa como não aplicável e identificar quem a executará.
