=== WooCommerce Moip Official ===

Contributors: daniloalvess, victorfreitas, apiki
Tags: woocommerce, checkout, cart, moip, gateway, payments
Requires at least: 4.0
Requires PHP: 5.6
Tested up to: 4.9.4
Stable tag: 1.2.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Official Moip plugin built with the best development practices.
Based on V2, new REST Moip’s API, providing more speed, safety and sales conversion.

== Description ==

Official Moip plugin built with the best development practices.
Based on V2, new REST Moip’s API, providing more speed, safety and sales conversion.

= Requirements =

- PHP version 5.6 or later.
- WooCommerce version 2.6.x or later.
- Moip client account, create one [here](https://moip.com.br/ "Moip").

= Payments methods =

- Credit card: Visa, Mastercard, Elo, Amex, Diners, Hiper e Hipercard.
- Itaú bank transfering.
- Billet banking.

= Benefits =

- Transparent/seamless checkout. No redirect to another page or pop up screen.
- One click buy, your client’s payment information can be reminded on a future purchase. Complete safe, PCI compliance procedure as the customer credit card information isn’t keep in the store.
- All payment status are synchronized in the store admin panel.
- Boleto bar code showed on the checkout to facilitate the payment using internet banking.
- Developed based on the best practices from Wordpress and Woocommerce  to avoid Plugin incompatibility.
- Immediate cancellation status return on the checkout page so the consumer can instantly opt to change the payment method without leaving the cart.
- Registration form adapted to the Brazilian requirements. No need to install other plugins.
- Redirect checkout as an option.

= Free support =

Free support followed by Moip integration and partnerships team.

= Transaction Fees =

Check out Moip’s site for transaction fees. If your store monthly volume is more than R$20.000,00, please contact us for a personalized proposal at parcerias@moip.com.br.

== Installation ==

1. Faça upload deste plugin em seu WordPress, e ative-o;
2. Entre no menu lateral "WooCommerce > Configurações";
3. Selecione a aba "Finalizar compra" e procure pelo menu "Moip Oficial";
4. Entre em sua conta Moip, e em seguida clique no botão "Autorizar"

== Screenshots ==
1. Home screen
2. Payment Settings
3. Credit Card Settings
4. Billet Banking Settings

== Changelog ==

= 1.2.1 - 09/10/2018 =
- Opção no admin do plugin para utilizar a mesma conta do Moip em vários sites.
- Botão no admin do plugin para reenviar webhooks caso esteja com o status do woocommerce/moip.
- Resolvendo o problema onde o desconto do Moip aparecia para outros meios de pagamento.
- Mostrando em pedidos do woocommerce no admin se a compra foi feita por cartão ou boleto.
- Deletando webhooks quando estiver autorizando o plugin.

= 1.2.0 - 13/08/2018 =
- Implementação de checkout transparente na página de seleção do tipo de pagamento (checkout).
- Implementando opção de definir o valor mínimo no carrinho para habilitar parcelamento.
- Implementando suporte a desconto no boleto (apenas no checkout transparente).

= 1.1.8 - 20/02/2018 =
- Ajuste na manipulação das tabs no checkout
- Implementando armazenamento da quantidade de parcelas selecionadas no checkout

= 1.1.7 - 09/02/2018 =
- Removendo jshint para atender as especificações do wordpress.org

= 1.1.6 - 11/01/2018 =
- Implementação de cancelamento do pedido após retorno do Moip;
- Ocultando opção de pagamento via débito online no checkout transparente;
- Exibindo informações sobre os webhooks (notificações) na administração.

= 1.1.5 - 12/12/2017 =
- Corrigindo bug quando selecionado apenas pessoa física no plugin WooCommerce Extra Checkout Fields For Brazil.
- Adicionando suporte a logs.

= 1.1.4 - 30/10/2017 =
- Adicionando suporte a guest checkout.

= 1.1.3 - 27/10/2017 =
- Corrigindo bug de javascript no checkout quando utilizado apenas boleto.

= 1.1.2 - 04/10/2017 =
- Renomeando arquivo principal do plugin
- Melhorias na internacionalização do arquivos

= 1.1.1 - 22/09/2017 =
- Correções de erros

= 1.1.0 - 20/09/2017 =
- Correções de erros e melhorias de performance

= 1.0.1 - 13/09/2017 =
- Implementando configuração de parcelamento por bandeira

= 1.0.0 - 30/08/2017 =
- Release inicial
