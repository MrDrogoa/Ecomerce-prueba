<?php

class Database
{
    // lo declaramos asi
    private $hostname = "database";  // Cambiado a "database" para usar el nombre del servicio en Docker
    private $database = "ecommerce_db";  // Usando el nombre de la base de datos definido en docker-compose
    private $username = "ecommerce_user";  // Usando el usuario definido en docker-compose
    private $password = "ecommerce_pass";  // Usando la contraseña definida en docker-compose
    private $port = 3306;  // Agregando la propiedad port que faltaba
    private $charset = "utf8";

    // para que funcione
    function conectar()
    {
        // esto se utiliza para manejar errores
        try {
            // agregamos el this para traer esas variables ya que no estan aqui en esta funcion
            // Corregimos la cadena de conexión eliminando espacios después de los punto y coma
            $conexion = "mysql:host=" . $this->hostname . ";port=" . $this->port . ";dbname=" . $this->database . ";charset=" . $this->charset;
            $options = [
                // maneja errores con esto
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            // conexion 
            $pdo = new PDO($conexion, $this->username, $this->password, $options);
            // retorna la conexion a la base de datos
            return $pdo;
        } catch (PDOException $e) {
            // con esto lo va atrapar los errores y los mostrara
            echo "Error conexion: " . $e->getMessage();
            exit;
        }
    }
}
