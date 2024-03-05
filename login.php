<?php
include("./connectDB.php");

session_start();

$message = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? $_POST["username"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;

    $conn = connectDB();

    if ($conn) {
        $query = "SELECT id, user, password, rol FROM usuarios WHERE user = :username";
        $statement = $conn->prepare($query);
        $statement->bindParam(":username", $username);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user && $user["password"]) {
            $_SESSION["id"] = $user["id"];
            $_SESSION["user"] = $username;

            if ($user["rol"] == 0) {
                $message = "Inicio de sesión exitoso";
                header("Refresh: 3; url=./admin.php");
            } else {
                $message = "Inicio de sesión exitoso";
                header("Refresh: 3; url=./inicio.php");
            }

            // No hay salida aquí
        }

        if (!$user) {
            $message = "Error al iniciar sesión";
            header("Refresh: 3; URL=" . $_SERVER['PHP_SELF']);
        }

        // No hay salida aquí
    }
}

// No hay salida antes de <!DOCTYPE html>...

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <script src="./scripts/darkmode.js"></script>
</head>
<body class="bg-gradient-to-r from-red-300 via-red-400 to-red-500">
    <header>
        <nav class="bg-gray-900 py-4 flex items-center justify-between">
            <img src="./assets/J3AT-removebg-preview.png" alt="Logo" class="h-16 ml-6">
            <ul class="flex m-5 gap-5">
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
    <form method="post" class="flex flex-col items-center justify-center gap-6 bg-red-200 p-10 max-w-lg mx-auto rounded m-6">
        <label for="username" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full">Nombre de usuario:</label>
        <input type="text" class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-red-500" name="username" id="username">
        <label for="password" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full">Contraseña:</label>
        <input type="password" class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-red-500" name="password" id="password">
        <button type="submit" class='bg-red-500 hover:bg-red-600 text-white font-bold p-3 text-center rounded w-full'>Iniciar Sesión</button>
        <a href="./index.php" class='bg-blue-500 hover:bg-blue-600 text-white text-center font-bold p-3 rounded w-full'>Registrarse</a>
    </form>
</body>
</html>