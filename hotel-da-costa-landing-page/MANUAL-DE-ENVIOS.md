# Formulário de reservas — versão 1.21.1

## Instalar ou atualizar

1. No WordPress, abra **Plugins → Adicionar plugin → Enviar plugin** e envie `hotel-da-costa-landing-page.zip`.
2. Se a versão anterior estiver instalada, confirme a substituição e mantenha o plugin ativo. Os envios e as configurações existentes são preservados.
3. A página deve usar o template **Landing Page — Hotel da Costa** e estar publicada, sem proteção por senha. Use uma página publicada em staging para testar.
4. Clique em **RESERVAR VIA WHATSAPP** e envie um cadastro de teste. Confirme o registro em **Hotel da Costa → Envios**.

O plugin funciona com PHP e JavaScript nativo, sem Node.js ou Elementor no servidor. Os visitantes precisam de JavaScript habilitado para usar o formulário.

## Fluxo do visitante

Todos os CTAs abrem o mesmo pop-up. Nome, e-mail e celular são obrigatórios. O telefone aceita o formato `(XX) XXXXX-XXXX`, limitado a onze dígitos com DDD ou quinze caracteres incluindo a máscara. A colagem com `+55` é normalizada antes de aplicar o limite. Telefones fixos e números internacionais de outros países não são aceitos. O botão de envio tem texto branco.

O WhatsApp abre na mesma aba somente após salvar o registro. O destino padrão é **5579998738067**, com a mensagem aprovada na campanha. Em caso de erro, os campos permanecem preenchidos para nova tentativa. Fechar o pop-up cancela o redirecionamento automático, mas um envio que já chegou ao servidor pode ter sido salvo.

## Consultar e exportar

- Acesse **Hotel da Costa → Envios** como administrador. A lista mostra vinte registros por página, do mais recente ao mais antigo.
- Busque por ID, nome, e-mail ou telefone. Combine os filtros de formulário, página, período e leitura. Clique no nome para ver os detalhes e as cinco UTMs.
- **Exportar resultados filtrados em CSV** inclui todos os resultados do filtro, inclusive outras páginas da listagem. Para exportar parte deles, marque as caixas e escolha **Exportar selecionados em CSV** em Ações em lote.
- O CSV usa UTF-8, separador `;`, telefone formatado e datas no fuso do WordPress. No Excel, se necessário, use **Dados → De Texto/CSV**, com UTF-8 e ponto e vírgula. Valores que poderiam executar fórmulas recebem um apóstrofo de proteção.
- Marque registros como lidos ou não lidos, mova para a lixeira ou restaure. A exclusão definitiva exige confirmação e funciona apenas na lixeira. Os registros ficam armazenados até uma exclusão manual; desativar ou remover os arquivos do plugin não apaga a tabela.

## UTMs e proteção contra envios inválidos

São capturados `utm_term`, `utm_campaign`, `utm_content`, `utm_source` e `utm_medium`. A atribuição permanece nesta aba após recarregar ou voltar à LP sem parâmetros. Uma URL com novas UTMs substitui o conjunto inteiro. Se o navegador bloquear o armazenamento, são usadas somente as UTMs da URL atual.

Nome aceita até 150 caracteres; e-mail, 254; cada UTM, 200. O plugin valida também no servidor, exige nonce e página com o template correto, inclui honeypot e limita a dez tentativas por dez minutos por origem. Tentativas de repetir um envio já salvo, com os mesmos dados e identificador, retornam o registro existente.

Os registros contêm dados de contato, UTMs, formulário, página e data. O plugin não grava IP bruto nos envios; o limite usa um hash temporário. Não existe consulta pública dos cadastros.

## Edição e cache

- Textos e estrutura do pop-up: `templates/lead-form.php`; aparência: `assets/css/lead-form.css`; interações: `assets/js/lead-form.js`.
- Armazenamento e destino: `includes/submissions.php`; painel e exportação: `includes/submissions-admin.php`.
- O filtro existente `hdc_lp_whatsapp_url` continua disponível para integrações aprovadas e aceita destinos HTTPS em `api.whatsapp.com` ou `wa.me`. Ele altera o destino após salvar; não remove a coleta.
- Exclua a LP e os endpoints `/hdc-lp/v1/lead-form` e `/hdc-lp/v1/submissions` do cache HTML da hospedagem/CDN. Os assets podem continuar em cache.

O preview local também grava no WordPress de staging, por proxy limitado às rotas do formulário. Seus arquivos de desenvolvimento e testes não entram no ZIP.
