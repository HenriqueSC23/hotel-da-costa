# Avaliações temporárias pelo Trustindex gratuito

## Instalação e primeira importação

1. Atualize o plugin **Hotel da Costa — Landing Page** com o ZIP **1.25.0**: em **Plugins → Adicionar plugin → Enviar plugin**, selecione `hotel-da-costa-landing-page.zip` e substitua a versão instalada. Faça a conferência em staging antes da publicação.
2. Em **Plugins → Adicionar plugin**, procure **Widgets for Google Reviews**, do autor **Trustindex.io**. Instale e ative o plugin oficial: <https://wordpress.org/plugins/wp-reviews-plugin-for-google/>. Ele é instalado separadamente e não está dentro do ZIP da landing page.
3. Abra o menu **Trustindex.io**. No configurador gratuito, localize o **Hotel da Costa**, confira o endereço do perfil em Aracaju e conclua a conexão e a importação inicial. Se a busca pedir um link, use o link da ficha correta no Google Maps. A versão gratuita importa inicialmente até dez avaliações reais.
4. Abra **Configurações → Hotel da Costa — Avaliações**. Na área **Fonte das avaliações**, confira novamente o nome, o endereço e a quantidade importada. Clique em **Usar avaliações do Trustindex**.
5. Em **Filtros do carrossel**, selecione as estrelas, a exigência de comentário e a quantidade. Clique em **Salvar perfil e filtros**. O padrão continua sendo quatro e cinco estrelas com comentário. A seleção pode resultar em menos de dez avaliações.
6. Confira a landing page em desktop e mobile. Se a hospedagem tiver cache, limpe o cache da LP e mantenha sua URL excluída do cache HTML. O endpoint `/wp-json/hdc-lp/v1/reviews` também deve ficar fora desse cache.

O carrossel e os pop-ups continuam usando as famílias de fontes, o fundo e os ícones da landing page. A seção de avaliações também foi ajustada aos tamanhos de texto, espaçamentos e limite de 1280 px definidos no projeto. Não insira shortcode na página e não precisa configurar o visual de um widget do Trustindex para esta integração. O plugin da LP lê somente os registros já importados pelas funções públicas `getPageDetails()` e `getReviews()` da versão 14.2 do Trustindex. Não insere o widget, CSS, loader ou scripts de terceiros para renderizar as avaliações. Novas versões do Trustindex devem ser conferidas em staging; versões incompatíveis exibem indisponibilidade e uma orientação no painel, sem avaliações exemplificativas.

## Novos downloads

A versão gratuita **não atualiza as avaliações automaticamente**. No Trustindex, abra **Minhas avaliações / My Reviews** e use a atualização manual quando o serviço liberar. O fornecedor informa um intervalo aproximado de uma a duas semanas: <https://trustindex.zohodesk.eu/portal/en/kb/articles/my-reviews-aren-t-updating>.

A LP passa a ler os registros que estiverem disponíveis no Trustindex, incluindo seu cache interno. Não cria uma cópia adicional das avaliações, não inventa uma data de sincronização e não agenda downloads pelo Trustindex. Avaliações ocultadas nesse plugin não aparecem na LP. As datas, nomes, estrelas e textos vêm dos registros importados. Avatares só são exibidos quando o endereço HTTPS pertence ao Google; caso contrário, aparece a inicial do autor.

Se o Trustindex for desconectado ou desativado, a seção informa indisponibilidade. Se outro perfil for conectado, a exibição fica suspensa até um administrador confirmar o novo perfil no painel da LP. Isso evita apresentar avaliações de outra empresa.

## Migrar para a API oficial após a aprovação

1. Em **Configurações → Hotel da Costa — Avaliações**, clique em **Usar API oficial do Google**.
2. Configure as credenciais OAuth, conecte a conta que administra o hotel, selecione o perfil correto e salve.
3. Clique em **Baixar novas avaliações** e confira o resultado na landing page.
4. A atualização diária volta a ser agendada pelo WordPress quando a conta e o perfil estiverem conectados. O agendamento depende do WP-Cron ou do agendamento externo da hospedagem.

Enquanto Trustindex está selecionado, a sincronização diária da API oficial fica suspensa. Credenciais e seleção anteriores da API são preservadas para facilitar a migração. A desativação do plugin da LP preserva a seleção de fonte e os dados do Trustindex e não modifica o tema, páginas comuns, menus ou demais configurações do WordPress.
