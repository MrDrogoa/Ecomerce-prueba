<?php

require "config/config.php";
// creo que funciona igual que include
require "config/database.php";
$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, descripcion, precio FROM productos WHERE activo=1");
$sql->execute();
// con esto llama a los productos de toda la tabla PDO::FETCH_ASSOC con esto
// estamos diciendo que me lo traiga por nombre de columnas
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

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
                                Carrito
                            </button></a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <section class="mb-12">
            <h2 class="text-3xl font-bold mb-6 text-center">Featured Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Product Card 1 -->
                <?php foreach ($resultado as $row) { ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <?php
                        $id = $row["id"];
                        $imagen = "img/producto/" . $id . "/image.png";

                        // hacemos una validacion si es que no existe la foto
                        if (!file_exists($imagen)) {
                            $imagen = "img/no-photo.jpg";
                        }

                        ?>
                        <img
                            src="<?= $imagen ?>"
                            alt="Product"
                            class="w-full object-cover" />
                        <div class="p-4">
                            <h3 class="text-xl font-semibold mb-2"><?= $row["nombre"] ?></h3>
                            <p class="text-gray-600 mb-4">
                                <?= $row["descripcion"] ?>
                            </p>
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold">$<?= number_format($row["precio"], 2, ".", ",") ?></span>
                                <div>
                                    <a href="details.php?id=<?= $row["id"] ?>&token=<?= hash_hmac("sha1", $row["id"], KEY_TOKEN); ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                        <!-- hash_hmac() lo que hace esto es cifrar informacion mediante una contraseña
                                             El key_token es lo que tenemos en config.php -->
                                        Detalles
                                    </a>
                                    <a
                                        class="bg-cyan-500 hover:bg-cyan-600  text-white px-4 py-2 rounded">
                                        Add to Cart
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <!-- Consejos para inicializar cuando cerremos:
                    cd c:/MAMP/htdocs/Ecoomerce
                    npm run dev
                    si no funciona esto:
                    cd c:/MAMP/htdocs/Ecoomerce
                    npx tailwindcss -i ./input.css -o ./output.css --watch 
                -->
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


</body>

</html>