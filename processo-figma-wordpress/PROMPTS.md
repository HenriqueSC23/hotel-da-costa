# Prompts reutilizáveis — Figma → WordPress

Usar na pasta do projeto, após copiar e preencher os modelos pertinentes. Substituir os campos entre colchetes. As instruções e autorizações já existentes continuam valendo. Estes prompts não autorizam publicação em produção.

## 1. Iniciar e definir a arquitetura

```text
Leia o AGENTS.md e o briefing deste projeto. Quero produzir [landing page/site] a partir de [link do Figma] para o WordPress [novo/existente].

Inspecione o que já existe e registre a arquitetura, o escopo, o modelo de edição pelo cliente, as fontes oficiais e o que precisa ser preservado. Prepare o registro do projeto e identifique apenas as lacunas que afetam essas decisões. Faça o trabalho independente enquanto aguarda informações necessárias.

Use a arquitetura e as tecnologias autorizadas. Não introduza frameworks, page builder ou dependências por conveniência. Não altere produção.
```

## 2. Preparar a entrega do Figma

```text
Consulte os nodes [links/IDs] do Figma pela conexão configurada. Obtenha estrutura, textos, medidas, screenshots, assets e estados necessários. Se a resposta for grande, divida por seção.

Preencha o mapa do Figma para desenvolvimento com fonte/data de consulta, ordem das seções, copy exata, tokens, imagens, itens fora da área visível e comportamento responsivo. Distinga conteúdo aprovado, dados variáveis e exemplos do layout. Registre conflitos com AGENTS.md e exceções já autorizadas.

Não reescreva a copy. Se algo estiver inacessível, preserve as evidências anteriores e descreva a limitação, sem inventar conteúdo.
```

## 3. Criar a base e implementar

```text
Implemente o escopo aprovado com base no AGENTS.md, briefing e mapa do Figma. Comece pela estrutura WordPress, modelo de conteúdo, tokens, contêiner e componentes recorrentes; depois conclua as seções.

Use o WordPress local/staging como preview da mesma implementação que será entregue. Se precisar de preview estático, gere-o da mesma fonte ou identifique-o como protótipo. Valide cada seção em desktop/mobile contra os frames e preserve o conteúdo editado pelo cliente nas atualizações.

Continue o trabalho autorizado sem pedir confirmação a cada etapa. Registre decisões para estados/larguras sem frame. Faça perguntas apenas quando a resposta for necessária para preservar a fonte aprovada, o escopo ou a arquitetura. Não publique em produção.
```

## 4. Revisar uma alteração

```text
Ajuste [componente/página] conforme [pedido e fonte]. Leia as regras atuais e o registro de decisões. Preserve o escopo restante e a copy que não foi autorizada a mudar.

Atualize a implementação usada no WordPress, compare o resultado nas larguras afetadas e execute testes proporcionais à mudança, incluindo integrações envolvidas. Atualize o registro do projeto com mudança, fonte, versão, evidências e limitações.
```

## 5. Homologar e preparar a entrega

```text
Execute o checklist de qualidade no pacote candidato e no WordPress de staging [ambiente]. Confira copy/Figma, responsividade, acessibilidade, conversão, edição, persistência, assets, SEO e preservação das páginas fora do escopo.

Teste instalação, atualização e o ciclo de vida aplicável à arquitetura. Registre resultados e evidências; diferencie mocks, integração real, ambiente local e staging de destino. Não marque testes não executados como concluídos.

Gere ZIP(s) instalável(is), versão/hash, manual curto de instalação/edição, relatório de pendências e plano de reversão. Exclua arquivos de desenvolvimento e segredos do pacote. Corrija falhas dentro do escopo autorizado. Deixe a entrega concreta e revisável antes de solicitar qualquer autorização ainda necessária para publicar.
```

## 6. Retomar o trabalho

```text
Leia AGENTS.md, briefing, mapa do Figma e registro do projeto. Continue de onde o trabalho parou, preservando as decisões e autorizações vigentes. Confira o estado real dos arquivos antes de agir.

Não refaça etapas já verificadas sem nova mudança ou evidência de problema. Conclua a próxima ação registrada e atualize o estado, testes, versão e pendências.
```
