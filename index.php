<?php
include("./connectDB.php");
 
session_start();
 
$message = null;
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $rol = null;
 
    if ($username == "admin") {
        $rol = 0;
    } else {
        $rol = 1;
    }
 
    $name = isset($_POST["name"]) ? $_POST["name"] : "";
   
    $correo = isset($_POST["email"]) ? $_POST["email"] : "";
 
    $conn = connectDB();
 
    if ($conn) {
        $query = "INSERT INTO usuarios (user, name, password, rol, correo)
                  VALUES (:username, :name, :password, :rol, :correo)";
        $statement = $conn->prepare($query);
 
        $statement->bindParam(":username", $username);
        $statement->bindParam(":name", $name);
        $statement->bindParam(":password", $password);
        $statement->bindParam(":rol", $rol);
        $statement->bindParam(":correo", $correo);
 
        if ($statement->execute() && !empty($username) && !empty($password)) {
            $message = "Usuario registrado";
            echo $message;
            header("Refresh: 3; url=./login.php");
            exit();
        } else {
            $message = "Error al registrar al usuario";
            echo $message;
            header("Refresh: 3; url=" . $_SERVER['PHP_SELF']);
        }
    } else {
        echo "Error al conectar a la Base de Datos";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>
<body class="bg-gradient-to-r from-red-300 via-red-400 to-red-500">
    <header>
    <nav class="bg-gray-900 py-4 flex items-center justify-between">
    
        <img src="./assets/J3AT-removebg-preview.png" alt="Logo" class="h-16 ml-6">
    
    <ul class="flex m-5 gap-5">
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
        
    <label for="name" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full">Nombre:</label>
        <input type="text" class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-red-500" name="name" id="name">
        <label for="email" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full">Email:</label>
        <input type="email" class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-red-500" name="email" id="email">
        <label for="username" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full">Nombre de usuario:</label>
        <input type="text" class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-red-500" name="username" id="username">
        <label for="password" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full">Contraseña:</label>
        <input type="password" class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-red-500" name="password" id="password">
        <button class='bg-red-500 hover:bg-red-600 text-white font-bold p-3 text-center rounded w-full'>Registrarse</button>
        <a href="./login.php" class='bg-blue-500 hover:bg-blue-600 text-white text-center font-bold p-3 rounded w-full'>¿Ya tienes cuenta?</a>
    </form>
</body>
</html>
