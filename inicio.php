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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="./scripts/darkmode.js"></script>
</head>

<body class="bg-gradient-to-r from-gray-100 to-gray-200">
    <header>
        <nav class="bg-gray-900 py-4 flex items-center justify-between">

            <img src="./assets/J3AT-removebg-preview.png" alt="Logo" class="h-16 ml-6">

            <ul class="flex m-5 gap-5">
                <li>
                    <a href="inicio.php" class="text-white hover:text-gray-300">Inicio</a>
                </li>
                <li>
                <button id="darkModeButton" class="flex items-center justify-center text-white rounded-md hover:text-gray-300">Dark Mode</button>
                </li>
                <li>
                    <a href="templates/sobreNosotros.html" class="text-white hover:text-gray-300">Sobre nosotros</a>
                </li>
                <li>
                    <a href="templates/terminos.html" class="text-white hover:text-gray-300">Términos y privacidad</a>
                </li>
            </ul>
        </nav>
    </header>
    <h1 class="text-center m-10 text-3xl font-bold mb-4">Bienvenido, <?php echo ucfirst($_SESSION["user"]); ?></h1>
    <div class="m-10 flex flex-col items-center gap-5">
        <button onclick="location.href='./user.php'" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded w-full mb-2">Todos los Productos</button>
        <button onclick="location.href='./camisetas.php'" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded w-full mb-2">Camisetas</button>
        <button onclick="location.href='./pantalones.php'" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded w-full mb-2">Pantalones</button>
        <button onclick="location.href='./sudaderas.php'" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded w-full mb-2">Sudaderas</button>
        <a href="./logout.php" class='bg-red-500 hover:bg-red-600 text-white font-bold py-4 px-6 text-center rounded w-full'>Cerrar Sesión</a>

</body>

</html>