<?php

define("KEY_TOKEN", "APR.wqc-354*");
define("MONEDA", "$");

// inicia sesion cada vez que el usuario ingrese a nuestro portal
session_start();

$num_cart = 0;
if (isset($_SESSION['carrito']['productos'])) {
    $num_cart = count($_SESSION['carrito']['productos']);
}
