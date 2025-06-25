<?php

require "config/config.php";
// creo que funciona igual que include
require "config/database.php";
$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

print_r($_SESSION);

$lista_carrito = array();

if ($productos != null) {
    // la clave es el id del producto y cantidad es la cantidad de los productos
    foreach ($productos as $clave => $cantidad) {

        $sql = $con->prepare("SELECT id, nombre, descripcion, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $lista_carrito = $sql->fetch(PDO::FETCH_ASSOC);
    }
}


// session_destroy();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>E-commerce Website</title>
    <link href="./output.css" rel="stylesheet" />
</head>

<body class="bg-gray-100">
    <header class="bg-white shadow-md">
        <div
            class="container mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">E-commerce Store</h1>
            <nav>
                <ul class="flex space-x-6">
                    <li>
                        <a href="carrito.php">
                            <button
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                Carrito <span id="num_cart" class="badge bg-secondary"><?= $num_cart; ?></span>
                            </button></a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <section class="mb-12">
            <div class="table">
                <table class="table-auto">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($lista_carrito == null) {
                            echo '<tr><td colspan="5" class="text-center">Lista vaciaaaa</td></tr>';
                        } else {
                            $total = 0;
                            foreach ($lista_carrito as $producto) {
                                // extraemos los elementos de la consulta
                                $_id = $producto['id'];
                                $nombre = $producto['nombre'];
                                $precio = $producto['precio'];
                                $descuento = $producto['descuento'];
                                $precio_desc = $precio - (($precio * $descuento) / 100);
                                $subtotal = $cantidad * $precio_desc;
                                $total += $subtotal;
                           ?>
                      <!-- PENDIENTEEEEEEEE -->

                        <tr>
                            <td>The Sliding Mr. Bones (Next Stop, Pottersville)</td>
                            <td>Malcolm Lockyer</td>
                            <td>1961</td>
                        </tr>
                        <tr>
                            <td>Witchy Woman</td>
                            <td>The Eagles</td>
                            <td>1972</td>
                        </tr>
                        <tr>
                            <td>Shining Star</td>
                            <td>Earth, Wind, and Fire</td>
                            <td>1975</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <div id="paypal-button-container"></div>

    <!-- <footer class="bg-gray-800 text-white py-8">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <h3 class="text-xl font-semibold mb-4">About Us</h3>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam
              condimentum, nisl eget.
            </p>
          </div>
          <div>
            <h3 class="text-xl font-semibold mb-4">Quick Links</h3>
            <ul class="space-y-2">
              <li><a href="#" class="hover:underline">Privacy Policy</a></li>
              <li><a href="#" class="hover:underline">Terms of Service</a></li>
              <li><a href="#" class="hover:underline">Contact Us</a></li>
            </ul>
          </div>
          <div>
            <h3 class="text-xl font-semibold mb-4">Connect With Us</h3>
            <div class="flex space-x-4">
              <a href="#" class="hover:text-blue-400">Facebook</a>
              <a href="#" class="hover:text-blue-400">Twitter</a>
              <a href="#" class="hover:text-blue-400">Instagram</a>
            </div>
          </div>
        </div>
        <div class="mt-8 text-center">
          <p>&copy; 2025 E-commerce Store. All rights reserved.</p>
        </div>
      </div>
    </footer> -->

    <script>
        const {
            json
        } = require("express");

        function addProducto(id, token) {
            let url = "clases/carrito.php";
            let formData = new FormData();

            formData.append("id", id);
            formData.append("token", token);

            // enviamos los datos mediante post
            fetch(url, {
                    method: "POST",
                    body: formData,
                    mode: "cors",
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.ok) {
                        let elemento = document.getElementById("num_cart");
                        elemento.innerHTML = data.numero;
                    }
                });
        }
    </script>

</body>

</html>