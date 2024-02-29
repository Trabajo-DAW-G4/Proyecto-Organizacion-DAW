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
                echo $message;
                header("Refresh: 3; url=./admin.php");
            } else {
                $message = "Inicio de sesión exitoso";
                echo $message;
                header("Refresh: 3; url=./inicio.php");
            }
        }

        if (!$user) {
            $message = "Error al iniciar sesión";
            echo $message;
            header("Refresh: 3; URL=" . $_SERVER['PHP_SELF']);
        }
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>

<body  class="flex flex-col items-center justify-center gap-6 p-10 max-w-lg mx-auto rounded bg-gradient-to-r from-red-300 via-red-400 to-red-500">
    
    <form method="post" class="flex flex-col items-center justify-center gap-6 bg-red-200 p-10 max-w-lg mx-auto rounded">
        <label for="username" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full">Nombre de usuario:</label>
        <input type="text" name="username" id="username" class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-red-500">
        <label for="password" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full">Contraseña:</label>
        <input type="password" name="password" id="password" class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-red-500">
        <button type="submit" class='bg-red-500 hover:bg-red-600 text-white font-bold p-3 text-center rounded w-full'>Iniciar Sesión</button>
        <a href="./index.php" class='bg-blue-500 hover:bg-blue-600 text-white text-center font-bold p-3 rounded w-full'>Registrarse</a>
    </form>

    <script>
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./*.html'],
  theme: {
    container: {
      padding: '1.4rem'
    },
    extend: {
      spacing: {
        'quarter': '25%',
        'half': '50%',
      },
      colors: {
        "do-blue-dark": "#080C2D",
        "do-blue-medium": "rgb(0, 125, 255)",
        "do-blue-light": "rgb(0, 105, 255)"
      },
      boxShadow: {
        'input': '0 5px 1px 0 rgb(0 0 0 / 10%)',
        'input-focus': '0 2px 1px 0 rgb(0 0 0 / 10%)',
 
      },
      fontFamily: {
        sans: ["Poppins", "sans-serif"],
        cascadia: ["Cascadia Code", "monospace"]
      },
      backgroundImage: theme => ({
        'imagen-fondo': "url('./assets/fondo.avif')"
      })
    },
  },
  plugins: [],
}
    </script>


</body>

</html>