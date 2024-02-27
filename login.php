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
                header("Refresh: 3; url=./user.php");
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
</head>

<body>
    <form method="post">
        <label for="username">Nombre de usuario:</label>
        <input type="text" name="username" id="username">
        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password">
        <button type="submit">Iniciar Sesión</button>
    </form>
    <a href="./index.php">Registrarse</a>
</body>

</html>