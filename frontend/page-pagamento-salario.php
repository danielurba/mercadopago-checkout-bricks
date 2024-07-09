<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pagamento Mercado Pago</title>
  <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>

<body>
  <div id="wallet_container"></div>

  <script>

    const mp = new MercadoPago('YOUR_PUBLIC_KEY', {
      locale: 'pt'
    });
    const bricksBuilder = mp.bricks();


    fetch("http://localhost/wp-json/mercadopago/v1/process-payment/", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({"nome": "daniel"}),
      })
      .then((response) => response.json())
      .then((response) => {
        console.log(response)

        mp.bricks().create("wallet", "wallet_container", {
          initialization: {
              preferenceId: response.preference_id,
          },
          customization: {
          texts: {
            valueProp: 'smart_option',
          },
          },
          });
      })
      .catch((error) => {
        console.log(error)
        
      });

    // let paymentId = 0


    // const renderPaymentBrick = async (bricksBuilder) => {
    //   const settings = {
    //     initialization: {
    //       amount: 39.99, // Valor total a pagar
    //       preferenceId: "<PREFERENCE_ID>", // ID de preferência (opcional)
    //       payer: {
    //         firstName: "Daniel",
    //         lastName: "Urbaneki",
    //         email: "daniel@hotmail.com",
    //       },
    //     },
    //     customization: {
    //       visual: {
    //         style: {
    //           theme: "default",
    //         },
    //       },
    //       paymentMethods: {
    //         creditCard: "all",
    //         bankTransfer: "all",
    //         atm: "all",
    //         onboarding_credits: "all",
    //         wallet_purchase: "all",
    //         maxInstallments: 1
    //       },
    //     },
    //     callbacks: {
    //       onReady: () => {

    //       },
    //       onSubmit: ({
    //         selectedPaymentMethod,
    //         formData
    //       }) => {
    //         return new Promise((resolve, reject) => {
    //           fetch("http://localhost/wp-json/mercadopago/v1/process-payment/", {
    //               method: "POST",
    //               headers: {
    //                 "Content-Type": "application/json",
    //               },
    //               body: JSON.stringify(formData),
    //             })
    //             .then((response) => response.json())
    //             .then((response) => {
    //               console.log(response)
    //               paymentId = response.payment.id
    //               renderStatusScreenBrick(bricksBuilder);
    //               resolve();
    //             })
    //             .catch((error) => {
    //               console.log(error)
    //               reject();
    //             });
    //         });
    //       },
    //       onError: (error) => {
    //         console.error(error);
    //       },
    //     },
    //   };

    //   window.paymentBrickController = await bricksBuilder.create(
    //     "payment",
    //     "paymentBrick_container",
    //     settings
    //   );
    // };

    // renderPaymentBrick(bricksBuilder);
  </script>

  <script>
    // const renderStatusScreenBrick = async (bricksBuilder) => {
    //   const settings = {
    //     initialization: {
    //       paymentId: paymentId, // Payment identifier, from which the status will be checked
    //     },
    //     customization: {
    //       visual: {
    //         hideStatusDetails: true,
    //         hideTransactionDate: true,
    //         style: {
    //           theme: 'default', // 'default' | 'dark' | 'bootstrap' | 'flat'
    //         }
    //       },
    //       backUrls: {
    //         'error': 'https://localhost',
    //         'return': 'https://localhost'
    //       }
    //     },
    //     callbacks: {
    //       onReady: () => {
    //         // Callback called when Brick is ready
    //       },
    //       onError: (error) => {
    //         // Callback called for all Brick error cases
    //       },
    //     },
    //   };
    //   window.statusScreenBrickController = await bricksBuilder.create('statusScreen', 'statusScreenBrick_container', settings);
    // };
  </script>
</body>

</html>