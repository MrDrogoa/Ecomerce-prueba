<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="./output.css" rel="stylesheet" />
    <script src="https://www.paypal.com/sdk/js?client-id=AUaNt84P4MdkdxuFNTLzghx9Sa08w527Bew3392NU7hovmKp9tYCPHzN_tAhMK3FAH2h98Z_k7dvYYeK&currency=USD"></script>

</head>

<body>
    <div id="paypal-button-container"></div>


    <script>
        paypal.Buttons({
            style: {
                // estos estilos se modifican para el boton de paypal
                color: "blue",
                shape: "pill",
                label: "pay"
            },
            // esta funcion crea cuanto va a pagar el usuario
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: 100
                        }
                    }]
                })
            },
            // funciona para aprobar los pagos
            onApprove: function(data, actions) {
                actions.order.capture().then(function(detalles) {
                    window.location.href = "pago.html"
                })
            },
            // esto funciona para cancelar los pagos
            onCancel: function(data) {
                alert("Pago cancelado")
                console.log(data);
            }
        }).render("#paypal-button-container");
    </script>
</body>

</html>