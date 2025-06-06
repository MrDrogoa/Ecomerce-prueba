<?php
// creo que funciona igual que include
require "config/database.php";
require "config/config.php";
$db = new Database();
$con = $db->conectar();

$id = isset($_GET["id"]) ? $_GET["id"] : "";
$token = isset($_GET["token"]) ? $_GET["token"] : "";

if ($id == "" || $token == "") {
  echo "error al procesar la peticion";
  exit;
} else {
  $token_tmp = hash_hmac("sha1", $id, KEY_TOKEN);

  if ($token == $token_tmp) {
    $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
    // con el ? se hace el filtrado por separacion con PDO
    $sql->execute([$id]);
    if ($sql->fetchColumn() > 0) {

      $sql = $con->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
      $sql->execute([$id]);
      $row = $sql->fetch(PDO::FETCH_ASSOC);
      $nombre = $row["nombre"];
      $descripcion = $row["descripcion"];
      $precio = $row["precio"];
      // descuento
      $descuento = $row["descuento"];
      $precio_desc = $precio - (($precio * $descuento) / 100);
      // traemos la imagen de forma dinamica
      $dir_images = "img/producto/" . $id . "/";

      // imagen principal 
      $rutaImg = $dir_images . "image.png";
      if (!file_exists($rutaImg)) {
        $rutaImg = "img/no-photo.jpg";
      }

      $imagenes = array();
      if (file_exists($dir_images)) {
        $dir = dir($dir_images);

        // con esto traemos las imagenes
        while (($archivo = $dir->read()) != false) {
          if ($archivo != "image.png" && (strpos($archivo, "png") || strpos($archivo, "jpg"))) {
            $imagenes[] = $dir_images . $archivo;
          }
        }
        $dir->close();
      }
    }
  } else {
    echo "error al procesar la peticion";
    exit;
  }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>E-commerce Website</title>
  <!-- tailwind -->
  <link href="./output.css" rel="stylesheet" />
  <!-- bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
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
    <div class="flex justify-center flex-col md:flex-row">
      <div class="w-full flex justify-center">

        <div id="carouselImages" class="carousel slide">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="<?= $rutaImg ?>" alt="image" class="w-full md:w-3/4">
            </div>
            <?php foreach ($imagenes as $img) { ?>
              <div class="carousel-item">
                <img src="<?= $img ?>" alt="image" class="w-full md:w-3/4">
              </div>
            <?php } ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>

      </div>
      <div class="w-full">
        <h2 class=""><?= $nombre ?></h2>
        <?php if ($descuento > 0) { ?>
          <p><del><?= MONEDA . number_format($precio, 2, ".", ",") ?></del></p>
          <h2>
            <?= MONEDA . number_format($precio_desc, 2, ".", ",") ?>
            <small class="text-success"><?= $descuento; ?>% descuento</small>
          </h2>
        <?php } else { ?>
          <h2><?= MONEDA . number_format($precio, 2, ".", ",") ?></h2>
        <?php } ?>
        <p><?= $descripcion ?></p>
        <div class="flex justify-center md:justify-start">
          <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded" type="button">Comprar ahora</button>
          <button class="bg-white hover:bg-slate-300 px-4 py-2 rounded" type="button" onclick="addProducto(<?= $id; ?>, '<?= $token_tmp; ?>')">Agregar al carrito ahora</button>
        </div>
      </div>
    </div>
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

  <!-- bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  <script src="./script.js"></script>
</body>

</html>