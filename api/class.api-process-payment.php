<?php
// Certifique-se de que o autoload da biblioteca Mercado Pago está sendo incluído corretamente
require PATH . 'vendor/autoload.php';

use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\Client\Preference\PreferenceClient;

if (!class_exists('Api_Process_Payment')) {
    class Api_Process_Payment
    {

        function __construct()
        {
            add_action('rest_api_init', array($this, 'register_routes_api'));
        }

        function register_routes_api()
        {
            register_rest_route(
                'mercadopago/v1',
                'process-payment/',
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'process_payment_with_mercadopago'), // Callback ajustado para o contexto da classe
                    'permission_callback' => '__return_true',
                )
            );
        }

        public function process_payment_with_mercadopago($req)
        {
            // Autenticação
            $this->authenticate();
            
            // Mock de usuário para teste
            $user = array("name" => "Daniel", "surname" => "Daniel", "email" => "daniel@hotmail.com");

            
            // Criação da preferência de pagamento

            $product2 = array(
                "id" => "9012",
                "title" => "My product",
                "description" => "Test product",
                "picture_url" => "http://i.mlcdn.com.br/portaldalu/fotosconteudo/48029_01.jpg",
                "category_id" => "electronics",
                "quantity" => 1,
                "currency_id" => "BRL",
                "unit_price" => 100
            );

            $items = array($product2);

            $payer = array(
                "name" => $user['name'],
                "surname" => $user['surname'],
                "email" => $user['email'],
            );

            $paymentMethods = [
                "excluded_payment_types" => array(
                    array(
                        "id" => "visa"
                    )
                    ),
                    "excluded_payment_methods" => array(
                        array(
                            "id" => ""
                        )
                    ),
                    "installments" => 5,
                    "default_installments" => 1
            ];

            $backUrls = array(
                "success" => "https://localhost/success",
                "failure" => "https://localhost/failure",
                "pending" => "https://localhost/pending"
            );

            $request = [
                "items" => $items,
                "payer" => $payer,
                "payment_methods" => $paymentMethods,
                "back_urls" => $backUrls,
                "statement_descriptor" => "NAME_DISPLAYED_IN_USER_BILLING",
                "external_reference" => "1234567890",
                "expires" => false,
                "auto_return" => 'approved',
            ];

            $client = new PreferenceClient();

            try {
                $preference = $client->create($request);
                if ($preference) {
                    return new WP_REST_Response(
                        array(
                            'status' => 'success',
                            'preference_id' => $preference->id,
                            'init_point' => $preference->init_point
                        ),
                        200
                    );
                } else {
                    return new WP_REST_Response(
                        array(
                            'status' => 'error',
                            'message' => 'Failed to create payment preference',
                        ),
                        500
                    );
                }
            } catch (MPApiException $error) {
                return $error;
            }
        }

        protected function authenticate()
        {
            MercadoPagoConfig::setAccessToken("PROD_ACCESS_TOKEN");
            MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
            MercadoPagoConfig::setIntegratorId("dev_cod");
        }
    }
}
