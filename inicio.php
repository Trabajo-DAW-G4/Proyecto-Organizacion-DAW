<?php
session_start();

if (!isset($_SESSION["id"]) || !isset($_SESSION["user"])) {
    header("Location: ./login.php"); // Redirigir a la página de inicio de sesión si no ha iniciado sesión
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
</head>

<body>
    <h1>Bienvenido, <?php echo $_SESSION["user"]; ?></h1>
    
    <button onclick="location.href='./user.php'">Todos los Productos</button>
    <button onclick="location.href='./camisetas.php'">Camisetas</button>
    <button onclick="location.href='./pantalones.php'">Pantalones</button>
    <button onclick="location.href='./sudaderas.php'">Sudaderas</button>

    <br>
    <a href="./logout.php">Cerrar Sesión</a>
</body>

</html>
